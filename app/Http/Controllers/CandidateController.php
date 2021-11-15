<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Services\CandidateService;
use App\Http\Requests\CandidateIndexRequest;
use App\Http\Requests\CandidateStoreRequest;
use App\Http\Requests\CandidateFilterRequest;
use App\Http\Requests\CandidateImportRequest;
use App\Http\Requests\CandidateSearchRequest;
use App\Http\Requests\CandidateUpdateRequest;

class CandidateController extends Controller
{
    /**
     * @param CandidateIndexRequest $request
     * @param null|string $shouldGroupByAcademy=null
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(CandidateIndexRequest $request, $shouldGroupByAcademy = null)
    {

        return Candidateservice::indexCandidates($shouldGroupByAcademy);
    }
    /**
     * @param CandidateStoreRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CandidateStoreRequest $request)
    {

        return response()->json(CandidateService::storeCandidate($request), 200);
    }
    /**
     * @param CandidateUpdateRequest $request
     * @param int $candidateId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CandidateUpdateRequest $request, $candidateId)
    {
        return CandidateService::updateCandidate($request, $candidateId);
    }
    /**
     * @param CandidateSearchRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(CandidateSearchRequest $request)
    {
        return CandidateService::searchCandidates($request);
    }
    /**
     * @param CandidateFilterRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(CandidateFilterRequest $request)
    {
        return CandidateService::filterCandidates($request);
    }
    /**
     * @param CandidateImportRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(CandidateImportRequest $request)
    {
        return CandidateService::importCandidates($request);
    }

    /**
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(Request $request)
    {

        return CandidateService::exportCandidates($request);
    }

    /**
     * @param Request $request
     * @param int $candidateId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportCV(Request $request, $candidateId)
    {
        return CandidateService::exportCV($candidateId);
    }
}
