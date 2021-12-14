<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidateShowRequest;
use App\Http\Requests\ExportCVRequest;
use Exception;
use App\Models\Candidate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\CandidateService;
use App\Http\Requests\CandidateIndexRequest;
use App\Http\Requests\CandidateStoreRequest;
use App\Http\Requests\CandidateFilterRequest;
use App\Http\Requests\CandidateImportRequest;
use App\Http\Requests\CandidateSearchRequest;
use App\Http\Requests\CandidateUpdateRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CandidateController extends Controller
{

    /**
     * @param CandidateIndexRequest $request
     * @return JsonResponse
     */
    public function index(CandidateIndexRequest $request)
    {

        return Candidateservice::indexCandidates($request);
    }

    /**
     * @param CandidateShowRequest $request
     * @param int $candidateId
     * @return JsonResponse
     */
    public function show(CandidateShowRequest $request, int $candidateId)
    {

        return Candidateservice::showCandidate($candidateId);
    }

    /**
     * @param CandidateStoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(CandidateStoreRequest $request)
    {

        return response()->json(CandidateService::storeCandidate($request), 200);
    }


    /**
     * @param CandidateUpdateRequest $request
     * @param int $candidateId
     * @return JsonResponse
     * @throws Exception
     */
    public function update(CandidateUpdateRequest $request, int $candidateId)
    {
        return CandidateService::updateCandidate($request, $candidateId);
    }


    /**
     * @param CandidateImportRequest $request
     *
     * @return JsonResponse
     */
    public function import(CandidateImportRequest $request)
    {
        return CandidateService::importCandidates($request);
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function export(Request $request)
    {
        return CandidateService::exportCandidates($request);
    }

    /**
     * @param ExportCVRequest $request
     * @param int $candidateId
     * @return BinaryFileResponse
     * @throws Exception
     */
    public function exportCV(ExportCVRequest $request, int $candidateId)
    {
        return CandidateService::exportCV($candidateId);
    }
}
