<?php

namespace App\Services;

use Exception;
use Throwable;
use App\Models\Academy;
use App\Models\Position;
use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Imports\CandidatesImport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\CandidatesPositions;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use Symfony\Component\String\Exception\InvalidArgumentException;

class CandidateService
{
    public static function indexCandidates(Request $request)
    {
        $candidates = Candidate::all();
        $academy = null;
        if ($request->filled('academy_name') && $request->filled('academy_name_abbreviation')) {
            throw new Exception('Both fields cannot be filled', 406);
        }
        if ($request->filled('academy_name')) {
            $academy = $request->input('academy_name');
            $candidates = $candidates->where('academy', '=', $request->input('academy_name'));
        }

        if ($request->filled('academy_name_abbreviation')) {
            $academy = $request->input('academy_name_abbreviation');
            $academyName = Academy::where('abbreviation', '=', $academy)->first()->name;
            $candidates = $candidates->where('academy', '=', $academyName);
        }

        if ($academy == null) {

            return response()->json(['candidates' => $candidates], 200);
        } else {
            return response()->json(['academy' => $academy, 'candidates' => $candidates], 200);
        }
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
        $canManageData = filter_var($canManageData, FILTER_VALIDATE_BOOLEAN);

        $candidate->name = CandidateService::getStoreFieldInput($dataSource, 'name');
        $candidate->surnname =  CandidateService::getStoreFieldInput($dataSource, 'surnname');
        $candidate->email =  CandidateService::getStoreFieldInput($dataSource, 'email');
        if (!$canManageData) {
            return ['message' => 'Candidate could not be saved as can_manage_data is false'
            ,'candidate'=>$candidate];
        }
        $candidate->gender =  CandidateService::getStoreFieldInput($dataSource, 'gender');
        $candidate->application_date =  CandidateService::getStoreFieldInput($dataSource, 'application_date');
        $candidate->education_institution =  CandidateService::getStoreFieldInput($dataSource, 'education_institution');
        $candidate->city = CandidateService::getStoreFieldInput($dataSource, 'city');
        $candidate->course =  CandidateService::getStoreFieldInput($dataSource, 'course');
        $candidate->academy =  CandidateService::getStoreFieldInput($dataSource, 'academy');
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
        if ($comment != null) {
            
            $candidate->comment = $comment;
        }
        if ($CV != null) {
            $candidate->CV = $CV;
        }
        
        $candidate->save();
        $positions = CandidateService::getStoreFieldInput($dataSource, 'positions');
        CandidateService::storeCandidatePosition($positions, Candidate::all()->last()->id);
        return ['message' => 'Candidate saved succesfully', 'candidate' => Candidate::all()->last()];
    }

    public static function updateCandidate(Request $request, $candidateId)
    {
        $candidate = Candidate::findOrFail($candidateId);
        $hasValue = false;
        if ($request->filled('name')) {
            $hasValue = true;
            $candidate->name = $request->input('name');
        }
        if ($request->filled('surnname')) {
            $hasValue = true;
            $candidate->surnname = $request->input('surnname');
        }
        if ($request->filled('gender')) {
            $hasValue = true;
            $candidate->gender = $request->input('gender');
        }
        if ($request->filled('phone')) {
            $hasValue = true;
            $candidate->phone = $request->input('phone');
        }
        if ($request->filled('positions')) {
            $hasValue = true;
            foreach ($candidate->positions as $candidatePosition) {
                $candidatePosition->pivot->delete();
            }
            CandidateService::storeCandidatePosition($request, $candidate->id);
        }
        if ($request->filled('email')) {
            $hasValue = true;
            $candidate->email = $request->input('email');
        }
        if ($request->filled('application_date')) {
            $hasValue = true;
            $candidate->application_date = $request->input('application_date');
        }
        if ($request->filled('education_institution')) {
            $hasValue = true;
            $candidate->education_institution = $request->input('education_institution');
        }
        if ($request->filled('city')) {
            $hasValue = true;
            $candidate->city = $request->input('city');
        }
        if ($request->filled('course')) {
            $hasValue = true;
            $candidate->course = $request->input('course');
        }
        if ($request->filled('academy')) {
            $hasValue = true;
            $candidate->academy = $request->input('academy');
        }
        if ($request->filled('comment')) {
            $hasValue = true;
            $candidate->comment = $request->input('comment');
        }
        if ($request->hasFile('CV')) {
            $path = $request->file('CV')->store('CVs');
            $candidate->CV = $path;
        }
        if (!$hasValue) {
            throw new Exception('All valid input fields are empty', 406);
        }
        $candidate->save();
        return response()->json([
            'message' => 'Candidate updated successfully',
            'candidate' => $candidate
        ], 200);
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
            $academy = $request->input('academy');
            $candidates = $candidates->where('academy', '=', "$academy");
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

        try {

            $candidates = Excel::toCollection(new CandidatesImport(), $path, null, \Maatwebsite\Excel\Excel::CSV);
            $candidates = CandidatesImport::validateCandidates($candidates[0]);
            $responses = [];
            foreach ($candidates as $candidate) {
                $response = CandidateService::storeCandidate(candidateData: $candidate);
                array_push($responses, $response);
            }
            return response()->json($responses, 200);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            var_dump($e->getMessage());
            var_dump(get_class($e));
        }

        return response()->json(['candidates' => $candidates], 200);
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
