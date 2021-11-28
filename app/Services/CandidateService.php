<?php

namespace App\Services;

use Exception;
use Throwable;
use App\Models\Academy;
use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Exports\CandidatesExport;
use App\Imports\CandidatesImport;
use App\Models\CandidatesPositions;
use App\Models\EducationInstitution;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CandidateService
{
    /**
     * @param null|string $groupByAcademy
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function indexCandidates($id, $request)
    {
        $groupByAcademy = $request->get('group_by_academy');
        if ($id != null) {
            try {
                $candidate = Candidate::findOrFail($id);
            } catch (Throwable $e) {
                //Rethrown in order to be catched by handler
                throw new NotFoundHttpException(message: "User with such id does not exist", code: 404);
            }
            return response()->json(['candidate' => $candidate]);
            $academy = $candidate->academy()->get();
            return response()->json(['academy' => $academy, 'candidate' => $candidate]);
        } else {

            $candidates = self::searchCandidates($request);
            $candidates = self::filterCandidates($candidates,$request);
            if ($groupByAcademy == 1) {
                $groupedCandidates = [];
                $academies = Academy::all();
                foreach ($academies as $ac) {
                    $group = ['academy' => $ac->name, 'candidates' => []];
                    array_push($groupedCandidates, $group);
                }
                foreach ($candidates as $candidate) {
                    $groupedCandidates = self::addCandidateToGroup($groupedCandidates, $candidate);
                }
                return response()->json(['grouped candidates' => $groupedCandidates], 200);
            }

            return response()->json(['candidates' => $candidates], 200);
        }
    }

    /**
     * @param array $groupedCandidates
     * @param Collection $candidate
     *
     * @return array
     */
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
     * This method allows for @see self::storeCandidate() method to store data from multiple sources
     * Namely from HTTP request and excel import data array @see self::importCandidates()
     * @param Request|array $dataSource
     * @param string $inputField
     *
     * @return string|array
     */
    public static function getStoreFieldInput($dataSource, string $inputField)
    {
        if ($dataSource instanceof Request) { //Data from request
            if ($inputField == 'CV') {
                if ($dataSource->hasfile('CV') != null) {
                    return $dataSource->file('CV')->store('CVs');
                } else {
                    return null;
                }
            }
            if ($inputField == 'comments' && $dataSource->filled('comments')) {
                return [$dataSource->input($inputField)];
            } else {
                return $dataSource->input($inputField);
            }
        } else { //Data from candidates import
            return $dataSource[$inputField];
        }
    }
    /**
     * @param Request|null $request
     * @param array|null $candidateData
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function storeCandidate(Request $request = null, array $candidateData = null)
    {

        $dataSource = $candidateData != null ? $candidateData : $request;
        $candidate = new Candidate();
        $canManageData = self::getStoreFieldInput($dataSource, 'can_manage_data');
        $candidate->name = self::getStoreFieldInput($dataSource, 'name');
        $candidate->surnname =  self::getStoreFieldInput($dataSource, 'surnname');
        $candidate->email =  self::getStoreFieldInput($dataSource, 'email');
        if ($canManageData != '1') {
            return [
                'message' => 'Candidate could not be saved as can_manage_data is false', 'candidate' => $candidate
            ];
        }
        $candidate->gender =  self::getStoreFieldInput($dataSource, 'gender');
        $candidate->application_date =  self::getStoreFieldInput($dataSource, 'application_date');
        $eduId = self::getStoreFieldInput($dataSource, 'education_institution_id');
        $candidate->education_institution_id = $eduId;
        $candidate->city = self::getStoreFieldInput($dataSource, 'city');
        $candidate->course =  self::getStoreFieldInput($dataSource, 'course');
        $acId = self::getStoreFieldInput($dataSource, 'academy_id');
        $candidate->academy_id = $acId;
        $phone = self::getStoreFieldInput($dataSource, 'phone');
        $status = self::getStoreFieldInput($dataSource, 'status');
        $comments = self::getStoreFieldInput($dataSource, 'comments');
        $CV = self::getStoreFieldInput($dataSource, 'CV');
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
        if ($comments != null) {
            foreach ($comments as $comment) {
                if ($comment != '') {
                    CommentService::saveComment($comment, $candidateId);
                }
            }
        }
        $positions = self::getStoreFieldInput($dataSource, 'positions');
        self::storeCandidatePosition($positions, $candidateId);
        return ['message' => 'Candidate saved succesfully', 'candidate' => Candidate::all()->last()];
    }

    /**
     * @param Request $request
     * @param int $candidateId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function updateCandidate(Request $request, $candidateId)
    {
        try {
            $candidate = Candidate::findOrFail($candidateId);
        } catch (Throwable $e) {
            //Rethrown in order to be catched by handler
            throw new NotFoundHttpException(message: $e->getMessage(), code: 404);
        }

        $hasValue = false;
        if ($request->filled('name')) {
            $hasValue = true;
            $candidate->update(['name' => $request->input('name')]);
        }
        if ($request->filled('surnname')) {
            $hasValue = true;
            $candidate->update(['surnname' => $request->input('surnname')]);
        }
        if ($request->filled('gender')) {
            $hasValue = true;
            $candidate->update(['gender' => $request->input('gender')]);
        }
        if ($request->filled('phone')) {
            $hasValue = true;
            $candidate->update(['phone' => $request->input('phone')]);
        }
        if ($request->filled('status')) {
            $hasValue = true;
            $candidate->update(['status' => $request->input('status')]);
        }
        if ($request->filled('education_institution_id')) {
            $hasValue = true;
            $edu = $request->input('education_institution_id');
            $edu_id = EducationInstitution::where('name', '=', $edu)->first()->id;
            $candidate->update(['education_institution_id' => $edu_id]);
        }
        if ($request->filled('academy_id')) {
            $hasValue = true;
            $newAcName = $request->input('academy_id');

            $currentAc = $candidate->academy->get()->first();
            //When changing academies current positions that candidate applies are deleted
            if ($newAcName != $currentAc->name) {
                self::deleteCandidateRelationItems($candidate, 'positions');
            }
            $candidate->update(['academy_id' => Academy::where('name', '=', $newAcName)->first()->id]);

            #reassigned in order for newly assigned academy to be shown
            $candidate = Candidate::find($candidateId);
        }
        if ($request->filled('positions')) {
            $hasValue = true;

            self::deleteCandidateRelationItems($candidate, 'positions');
            self::storeCandidatePosition($request->get('positions'), $candidate->id);

            #reassigned in order for newly supplied positions to be shown
            $candidate = Candidate::find($candidateId);
        }
        if ($request->filled('email')) {
            $hasValue = true;
            $candidate->update(['email' => $request->input('email')]);
        }
        if ($request->filled('application_date')) {
            $hasValue = true;
            $candidate->update(['application_date' => $request->input('application_date')]);
        }
        if ($request->filled('city')) {
            $hasValue = true;
            $candidate->update(['city' => $request->input('city')]);
        }
        if ($request->filled('course')) {
            $hasValue = true;
            $candidate->update(['course' => $request->input('course')]);
        }
        if ($request->hasFile('CV')) {
            $path = $request->file('CV')->store('CVs');
            $candidate->update(['CV' => $path]);
        }
        if (!$hasValue) {
            throw new Exception('All valid input fields are empty', 406);
        }
        return response()->json([
            'message' => 'Candidate updated successfully',
            'candidate' => $candidate
        ], 200);
    }
    /**
     * @param Collection $candidate
     * @param String $relation
     * @return void
     */
    public static function deleteCandidateRelationItems($candidate, $relation)
    {
        foreach ($candidate->$relation as $relationElement) {
            $$relationElement->pivot->delete();
        }
    }
    
    /**
     * @param Request $request
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function searchCandidates(Request $request)
    {

        $name = $request->input('name');
        $surnname = $request->input('surnname');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $candidates = Candidate::when($name != null, function ($query) use ($name) {

            return $query->where('name', 'like', "%$name%");
        })
            ->when($surnname != null, function ($query) use ($surnname) {

                return $query->where('surnname', 'like', "%$surnname%");
            })

            ->when($email != null, function ($query) use ($email) {

                return $query->where('email', 'like', "%$email%");
            })

            ->when($phone != null, function ($query) use ($phone) {

                return $query->where('phone', 'like', "%$phone%");
            })
            ->get();

        return $candidates;
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function filterCandidates($candidates,Request $request)
    {
        if ($request->filled('date_from')) {
            $dateFrom = $request->input('date_from');
            $candidates = $candidates->where('application_date', '>=', "$dateFrom");
        }

        if ($request->filled('date_to')) {
            $dateTo = $request->input('date_to');
            $candidates = $candidates->where('application_date', '<', "$dateTo");
        }

        if ($request->filled('positions')) {
            $inputPositions = $request->input('positions');
            $candidates = $candidates->filter(function ($candidate) use ($inputPositions) {
                $candidatePositions = $candidate->positions()
                    ->get()
                    ->map(fn ($pos) => $pos->id)->toArray();
                $count = count(array_intersect($candidatePositions,$inputPositions));
                return $count == count($inputPositions);
            });
        }

        if ($request->filled('academy')) {
            $academy= $request->input('academy');
            $candidates = $candidates->where('academy', '=', $academy);
        }

        if ($request->filled('course')) {
            $course = $request->input('course');
            $candidates = $candidates->where('course', '=', "$course");
        }

        return $candidates;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function importCandidates(Request $request)
    {
        $path = $request->file('candidates_data')->store('temp');


        $candidates = Excel::toCollection(new CandidatesImport(), $path, null, \Maatwebsite\Excel\Excel::CSV);
        $candidates = CandidatesImport::processCandidates($candidates[0]);
        $responses = [];
        foreach ($candidates as $candidate) {
            $response = self::storeCandidate(candidateData: $candidate);
            array_push($responses, $response);
        }
        return response()->json($responses, 200);


        return response()->json(['candidates' => $candidates], 200);
    }

    /**
     * @param mixed $candidateId
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public static function exportCV($candidateId)
    {
        $candidate = Candidate::find($candidateId);
        if ($candidate == null) {
            throw new Exception('Candidate with such id does not exist', 404);
        }
        if ($candidate->CV == null) {
            throw new Exception('Candidate does not have a CV', 404);
        }
        return response()->download(storage_path('app/' . $candidate->CV));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public static function exportCandidates()
    {
        $fileName = now()->setTimezone('Europe/Vilnius')->format("Y-m-d H:i:s") . '.xlsx';
        $response = Excel::download(new CandidatesExport(), $fileName);
        return $response;
    }

    /**
     * @param array $positions
     * @param int $candidateId
     *
     * @return void
     */
    public static function storeCandidatePosition($positions, $candidateId)
    {
        foreach ($positions as $position) {
            $candidatePosition = new CandidatesPositions();
            $candidatePosition->candidate_id = $candidateId;
            $candidatePosition->position_id = $position;
            $candidatePosition->save();
        }
    }
}
