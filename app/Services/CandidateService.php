<?php

namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Models\Academy;
use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Exports\CandidatesExport;
use App\Imports\CandidatesImport;
use App\Models\CandidatesPositions;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;

class CandidateService
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public static function indexCandidates(Request $request)
    {
        $groupByAcademy = $request->input('group_by_academy');

        $candidates = self::searchCandidates($request->only('search'));
        $candidates = self::filterCandidates($candidates, $request);
        if ($groupByAcademy == 1) {
            $groupedCandidates = [];
            $academies = Academy::all();
            foreach ($academies as $academy) {
                $group = ['academy' => $academy, 'candidates' => []];
                $groupedCandidates[] = $group;
            }
            foreach ($candidates as $candidate) {
                $groupedCandidates = self::addCandidateToGroup($groupedCandidates, $candidate);
            }
            return response()->json(['grouped candidates' => $groupedCandidates], 200);
        }

        return response()->json(['candidates' => $candidates], 200);
    }

    /**
     * @param int $candidateId
     * @return JsonResponse
     */
    public static function showCandidate(int $candidateId)
    {
        $candidate = Candidate::find($candidateId);
        return response()->json(['candidate' => $candidate], 200);
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
            if ($group['academy'] == $academy) {
                $group['candidates'][] = $candidate->makeHidden('academy');
                break;
            }
        }
        return $groupedCandidates;
    }


    /**
     * This method allows for storeCandidate() to store candidates from multiple data sources
     * Namely, from HTTP request and excel import
     * @param Request|array $dataSource
     * @param string $inputField
     * @return array|bool|mixed|string|null
     **/
    public static function getStoreFieldInput(Request|array $dataSource, string $inputField)
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
     * @param Request|array $dataSource
     * @return array
     */
    public static function storeCandidate(Request|array $dataSource)
    {
        $candidate = new Candidate();
        $canManageData = self::getStoreFieldInput($dataSource, 'can_manage_data');
        $candidate->name = self::getStoreFieldInput($dataSource, 'name');
        $candidate->surnname = self::getStoreFieldInput($dataSource, 'surnname');
        $candidate->email = self::getStoreFieldInput($dataSource, 'email');
        if ($canManageData != '1') {
            return [
                'message' => 'Candidate could not be saved as can_manage_data is false',
                'candidate' => $candidate
            ];
        }
        $candidate->gender = self::getStoreFieldInput($dataSource, 'gender');
        $candidate->application_date = self::getStoreFieldInput($dataSource, 'application_date');
        $eduId = self::getStoreFieldInput($dataSource, 'education_institution_id');
        $candidate->education_institution_id = $eduId;
        $candidate->city = self::getStoreFieldInput($dataSource, 'city');
        $candidate->course = self::getStoreFieldInput($dataSource, 'course');
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
        if ($comments != null) {
            foreach ($comments as $comment) {
                if ($comment != '') {
                    CommentService::saveComment($comment, $candidate->id);
                }
            }
        }
        $positions = self::getStoreFieldInput($dataSource, 'positions');
        self::storeCandidatePosition($positions, $candidate->id);
        $candidate = Candidate::find($candidate->id);
        return ['message' => 'Candidate saved successfully', 'candidate' => $candidate];
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public static function updateCandidate(Request $request, int $id)
    {
        $candidate = Candidate::find($id);

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
            $edu_id = $request->input('education_institution_id');
            $candidate->update(['education_institution_id' => $edu_id]);
        }
        if ($request->filled('academy_id')) {
            $hasValue = true;
            $newAcId = $request->input('academy_id');

            self::deleteCandidatePositionsThatNotInNewAc($request, $candidate, $newAcId);
            $candidate->update(['academy_id' => $newAcId]);
        }
        if ($request->filled('positions')) {
            $hasValue = true;

            self::deleteCandidateRelationItems($candidate, 'positions');
            self::storeCandidatePosition($request->get('positions'), $candidate->id);
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
            $hasValue = true;
            $path = $request->file('CV')->store('CVs');
            $candidate->update(['CV' => $path]);
        }
        if (!$hasValue) {
            throw new Exception('All valid input fields are empty', 406);
        }

        $candidate = Candidate::find($id);
        return response()->json([
            'message' => 'Candidate updated successfully',
            'candidate' => $candidate
        ], 200);
    }

    /**
     *  Candidate positions which do not belong to new academy will be deleted
     *  as candidate shouldn't be able to apply to positions which do not
     *  belong to academy to which he is applying
     * @param int $newAcId
     * @param Request $request
     * @param Candidate $candidate
     *
     * @return void
     */
    private static function deleteCandidatePositionsThatNotInNewAc(Request $request, Candidate $candidate, int $newAcId)
    {
        $currentAc = $candidate->academy()->get()->first();

        if ($newAcId != $currentAc->id && !$request->filled('positions')) {
            $newAc = Academy::find($newAcId);
            $currentPositions = $currentAc->positions()->get();
            $newAcPositions = $newAc->positions()->get();

            $currentPositionsId = $currentPositions->map(fn($pos) => $pos->id)->toArray();
            $newAcPositionsId = $newAcPositions->map(fn($pos) => $pos->id)->toArray();

            $positionsToDelete = array_diff($currentPositionsId, $newAcPositionsId);
            $candidatePositions = $candidate->positions()->get();
            foreach ($candidatePositions as $candidatePos) {
                if (in_array($candidatePos->id, $positionsToDelete)) {
                    $candidatePos->pivot->delete();
                }
            }
        }
    }

    /**
     * @param Collection $candidate
     * @param String $relation
     * @return void
     */
    private static function deleteCandidateRelationItems($candidate, $relation)
    {
        foreach ($candidate->$relation as $relationElement) {
            $relationElement->pivot->delete();
        }
    }


    public static function searchCandidates($searchQuery)
    {
        if (isset($searchQuery['search'])) {
            $searchQuery = $searchQuery['search'];
        }
        if ($searchQuery == null) {
            return Candidate::all();
        }
        $searchTerms = explode(' ', $searchQuery);
        $candidates = null;
        foreach ($searchTerms as $term) {
            $matchCandidates = Candidate::search($term)->get();
            if (!$candidates) {
                $candidates = $matchCandidates;
            } else {
                $uniqueMatches = $matchCandidates->diff($candidates);
                foreach ($uniqueMatches as $uniqueMatch) {
                    $candidates->push($uniqueMatch);
                }
            }
        }
        return $candidates;
    }


    /**
     * @param Collection $candidates
     * @param Request $request
     * @return mixed
     */
    public static function filterCandidates(Collection $candidates, Request $request)
    {
        if ($request->filled('date_from')) {
            $dateFrom = $request->input('date_from');
            $candidates = $candidates->where('application_date', '>=', "$dateFrom")->values();
        }

        if ($request->filled('date_to')) {
            $dateTo = $request->input('date_to');
            $candidates = $candidates->where('application_date', '<', "$dateTo")->values();
        }

        if ($request->filled('positions')) {
            $inputPositions = $request->input('positions');
            $candidates = $candidates->filter(function ($candidate) use ($inputPositions) {
                $candidatePositions = $candidate->positions()
                    ->get()
                    ->map(fn($pos) => $pos->id)->toArray();

                $count = count(array_intersect($candidatePositions, $inputPositions));
                return $count == count($inputPositions);
            });
        }

        if ($request->filled('academy')) {
            $academy = $request->input('academy');
            $candidates = $candidates->where('academy', '=', "$academy")->values();
        }

        if ($request->filled('course')) {
            $course = $request->input('course');
            $candidates = $candidates->where('course', '=', "$course")->values();
        }

        return $candidates;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public static function importCandidates(Request $request)
    {
        $path = $request->file('candidates_data')->store('temp');


        $candidates = Excel::toCollection(new CandidatesImport(), $path, null, \Maatwebsite\Excel\Excel::CSV);
        $candidates = CandidatesImport::processCandidates($candidates[0]);
        $responses = [];
        foreach ($candidates as $candidate) {
            $response = self::storeCandidate($candidate);
            $responses[] = $response;
        }
        return response()->json($responses, 200);
    }


    /**
     * @param int $id
     * @return BinaryFileResponse
     * @throws Exception
     */
    public static function exportCV(int $id)
    {
        $candidate = Candidate::find($id);
        if ($candidate->CV == null) {
            throw new Exception('Candidate does not have a CV', 404);
        }
        return response()->download(storage_path('app/' . $candidate->CV));
    }

    /**
     * @return BinaryFileResponse
     */
    public static function exportCandidates()
    {
        $fileName = now()->setTimezone('Europe/Vilnius')->format("Y-m-d H:i:s") . '.xlsx';
        return Excel::download(new CandidatesExport(), $fileName);
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
