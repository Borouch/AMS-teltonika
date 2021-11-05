<?php

namespace App\Services;

use Exception;
use Throwable;
use App\Models\Academy;
use App\Models\Comment;
use App\Models\Position;
use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Models\CandidateComment;
use App\Exports\CandidatesExport;
use App\Imports\CandidatesImport;
use App\Models\CandidateComments;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\CandidatesPositions;
use App\Models\EducationInstitution;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use Symfony\Component\String\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CandidateService
{
    public static function indexCandidates(Request $request)
    {

        $candidates = Candidate::all();
        $shoudGroupByAcademy = $request->input('should_group_by_academy');

        if ($shoudGroupByAcademy) {
            $groupedCandidates = [];
            $academies = Academy::all();
            foreach ($academies as $ac) {
                $group = ['academy' => $ac->name, 'candidates' => []];
                array_push($groupedCandidates, $group);
            }
            foreach ($candidates as $candidate) {
                $groupedCandidates = CandidateService::addCandidateToGroup($groupedCandidates, $candidate);
            }
            return response()->json(['grouped candidates' => $groupedCandidates], 200);
        }

        return response()->json(['candidates' => $candidates], 200);
    }

    public static function addCandidateToGroup($groupedCandidates, $candidate)
    {
        $academy = Academy::find($candidate->academy_id);
        foreach ($groupedCandidates as &$group) {
            if ($group['academy'] == $academy->name) {
                array_push($group['candidates'], $candidate);
                break;
            }
        }
        return $groupedCandidates;
    }
    /**
     * @param Request|array $dataSource
     * @param string $inputField
     * 
     * @return string
     */
    public static function getStoreFieldInput($dataSource, string $inputField)
    {
        if ($dataSource instanceof Request) {
            if ($inputField == 'CV') {
                if ($dataSource->hasfile('CV') != null) {
                    return $dataSource->file('CV')->store('CVs');
                } else return null;
            }
            return $dataSource->input($inputField);
        } else {
            return $dataSource[$inputField];
        }
    }
    public static function storeCandidate(Request $request = null, array $candidateData = null)
    {

        $dataSource = $candidateData != null ? $candidateData : $request;
        $candidate = new Candidate();
        $canManageData = CandidateService::getStoreFieldInput($dataSource, 'can_manage_data');
        $candidate->name = CandidateService::getStoreFieldInput($dataSource, 'name');
        $candidate->surnname =  CandidateService::getStoreFieldInput($dataSource, 'surnname');
        $candidate->email =  CandidateService::getStoreFieldInput($dataSource, 'email');
        if (!$canManageData) {
            return [
                'message' => 'Candidate could not be saved as can_manage_data is false', 'candidate' => $candidate
            ];
        }
        $candidate->gender =  CandidateService::getStoreFieldInput($dataSource, 'gender');
        $candidate->application_date =  CandidateService::getStoreFieldInput($dataSource, 'application_date');
        $education_institution =  CandidateService::getStoreFieldInput($dataSource, 'education_institution');
        $eduId=EducationInstitution::where('name','=',$education_institution)->first()->id;
        $candidate->education_institution_id=$eduId;
        $candidate->city = CandidateService::getStoreFieldInput($dataSource, 'city');
        $candidate->course =  CandidateService::getStoreFieldInput($dataSource, 'course');
        $academyName =  CandidateService::getStoreFieldInput($dataSource, 'academy');
        $acId = Academy::where('name', '=', $academyName)->first()->id;
        $candidate->academy_id=$acId;
        $phone = CandidateService::getStoreFieldInput($dataSource, 'phone');
        $status = CandidateService::getStoreFieldInput($dataSource, 'status');
        $comment = CandidateService::getStoreFieldInput($dataSource, 'comment');
        $CV = CandidateService::getStoreFieldInput($dataSource, 'CV');
        if ($phone != null) {

            $candidate->phone = $phone;
        }
        if ($status != null) {

            $candidate->status = $status;
        }
        if ($CV != null) {
            $candidate->CV = $CV;
        }
        $candidate->save();
        $candidateId = Candidate::all()->last()->id;
        if ($comment != null) {
            $comment = new Comment();
            $comment->content=$comment;
            $comment->candidate_id=$candidateId;
            $comment->save();
        }
        $positions = CandidateService::getStoreFieldInput($dataSource, 'positions');
        CandidateService::storeCandidatePosition($positions, $candidateId);
        return ['message' => 'Candidate saved succesfully', 'candidate' => Candidate::all()->last()];
    }

    public static function updateCandidate(Request $request, $candidateId)
    {
        try
        {
            $candidate = Candidate::findOrFail($candidateId);
        }catch(Throwable $e)
        {
            //Rethrown in order to be catched by handler
            throw new NotFoundHttpException(message: $e->getMessage(),code: 404);
        }
    
        $hasValue = false;
        if ($request->filled('name')) {
            $hasValue = true;
            $candidate->update(['name'=>$request->input('name')]);
        }
        if ($request->filled('surnname')) {
            $hasValue = true;
            $candidate->update(['surnname'=>$request->input('surnname')]);
        }
        if ($request->filled('gender')) {
            $hasValue = true;
            $candidate->update(['gender'=>$request->input('gender')]);
        }
        if ($request->filled('phone')) {
            $hasValue = true;
            $candidate->update(['phone'=>$request->input('phone')]);
        }
        if ($request->filled('education_institution')) {
            $hasValue = true;
            $edu = $request->input('education_institution');
            $edu_id = EducationInstitution::where('name','=',$edu)->first()->id;
            $candidate->update(['education_institution_id'=>$edu_id]);

        }
        if ($request->filled('academy')) {
            $hasValue = true;
            $newAcName = $request->input('academy');

            $currentAc = $candidate->academy->get()->first();
            //When changing academies current positions that candidate applies are deleted
            if($newAcName != $currentAc->name) 
            {   
                CandidateService::deleteCandidatePositions($candidate);
            }   
            $candidate->update(['academy_id'=> Academy::where('name','=',$newAcName)->first()->id]);
            
            #reassigned in order to newly assigned academy to be shown
            $candidate =Candidate::find($candidateId);
        }
        if ($request->filled('positions')) {
            $hasValue = true;
            
            CandidateService::deleteCandidatePositions($candidate);
            CandidateService::storeCandidatePosition($request->get('positions'), $candidate->id);

            #reassigned in order to newly supplied positions to be shown
            $candidate =Candidate::find($candidateId);
        }
        if ($request->filled('email')) {
            $hasValue = true;
            $candidate->update(['email'=>$request->input('email')]);
        }
        if ($request->filled('application_date')) {
            $hasValue = true;
            $candidate->update(['application_date'=>$request->input('application_date')]);
        }
        if ($request->filled('city')) {
            $hasValue = true;
            $candidate->update(['city'=>$request->input('city')]);
        }
        if ($request->filled('course')) {
            $hasValue = true;
            $candidate->update(['course'=>$request->input('course')]);
        }
        if ($request->filled('comment')) {
            $hasValue = true;
            $candidate->update(['comment'=>$request->input('comment')]);
        }
        if ($request->hasFile('CV')) {
            $path = $request->file('CV')->store('CVs');
            $candidate->update(['CV'=>$path]);
        }
        if (!$hasValue) {
            throw new Exception('All valid input fields are empty', 406);
        }
        return response()->json([
            'message' => 'Candidate updated successfully',
            'candidate' => $candidate
        ], 200);
    }
    public static function deleteCandidatePositions($candidate)
    {
        foreach ($candidate->positions as $candidatePosition) {
            $candidatePosition->pivot->delete();
        }
    }
    public static function searchCandidates(Request $request)
    {
        $hasValue = false;
        if ($request->filled('name')) {
            $hasValue = true;
            $name = $request->input('name');
            $candidates = DB::table('candidates')->where('name', 'like', "%$name%")->get();
        }
        if ($request->filled('surnname')) {
            $hasValue = true;
            $surnname = $request->input('surnname');
            $candidates = DB::table('candidates')->where('surnname', 'like', "%$surnname%")->get();
        }
        if ($request->filled('phone')) {
            $hasValue = true;
            $phone = $request->input('phone');
            $candidates = DB::table('candidates')->where('phone', 'like', "%$phone%")->get();
        }
        if ($request->filled('email')) {
            $hasValue = true;
            $email = $request->input('email');
            $candidates = DB::table('candidates')->where('email', 'like', "%$email%")->get();
        }
        if (!$hasValue) {
            throw new Exception('All valid search fields are empty', 406);
        }
        $count = $candidates->count();
        return response()->json(['message' => " $count candidates found that match search query fields ", 'candidates' => $candidates, $candidates->position()->get()], 200);
    }

    public static function filterCandidates(Request $request)
    {
        $hasValue = false;
        $candidates = Candidate::all();
        if ($request->filled('date_from')) {
            $hasValue = true;
            $dateFrom = $request->input('date_from');
            $candidates = $candidates->where('application_date', '>=', "$dateFrom");
        }
        if ($request->filled('date_to')) {
            $hasValue = true;
            $dateTo = $request->input('date_to');
            $candidates = $candidates->where('application_date', '<', "$dateTo");
        }
        if ($request->filled('positions')) {
            $hasValue = true;
            $positions = $request->input('positions');
            $candidates = $candidates->filter(function ($candidate) use ($positions) {
                $candidatePositions = $candidate->positions()
                    ->get()
                    ->map(fn ($pos) => $pos->name);
                $count = $candidatePositions->intersect($positions)->count();
                return $count != 0;
            });
        }
        if ($request->filled('academy')) {
            $hasValue = true;
            $academyName = $request->input('academy');
            $academyId = Academy::where('name','=',$academyName);
            $candidates = $candidates->where('academy', '=', $academyId);
        }
        if ($request->filled('course')) {
            $hasValue = true;
            $course = $request->input('course');

            $candidates = $candidates->where('course', '=', "$course");
        }
        if (!$hasValue) {
            throw new Exception('All valid filter fields are empty', 406);
        }
        $count = $candidates->count();
        return response()->json(['message' => " $count candidates found that match search query fields ", 'candidates' => $candidates], 200);
    }
    public static function importCandidates(Request $request)
    {
        $path = $request->file('candidates_data')->store('temp');


        $candidates = Excel::toCollection(new CandidatesImport(), $path, null, \Maatwebsite\Excel\Excel::CSV);
        $candidates = CandidatesImport::validateCandidates($candidates[0]);
        $responses = [];
        foreach ($candidates as $candidate) {
            $response = CandidateService::storeCandidate(candidateData: $candidate);
            array_push($responses, $response);
        }
        return response()->json($responses, 200);


        return response()->json(['candidates' => $candidates], 200);
    }
    public static function exportCV($candidateId)
    {
        $candidate = Candidate::find($candidateId);
        if($candidate==null)
        {
            throw new Exception('Candidate with such id does not exist',404);
        }
        if($candidate->CV==null)
        {
            throw new Exception('Candidate does not have a CV',404);
        }
        return response()->download(storage_path('app/'.$candidate->CV));
        
    }
    public static function exportCandidates(Request $request)
    {

        $fileName = date("Y-m-d H:i:s") . '.xlsx';
        return Excel::download(new CandidatesExport(), $fileName);
    }
    public static function storeCandidatePosition($positions, $candidateId)
    {
        foreach ($positions as $position) {

            $positionId = Position::all()->where('name', '=', $position)->first()->id;
            $candidatePosition = new CandidatesPositions();
            $candidatePosition->candidate_id = $candidateId;
            $candidatePosition->position_id = $positionId;
            $candidatePosition->save();
        }
    }

}
