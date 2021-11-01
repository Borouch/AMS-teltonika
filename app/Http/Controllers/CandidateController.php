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
    public function index(CandidateIndexRequest $request)
    {
        $request->validated();
        return Candidateservice::indexCandidates($request);
    }
    public function store(CandidateStoreRequest $request)
    {
        $request->validated();
        return response()->json(CandidateService::storeCandidate($request),200);
    }
    public function update(CandidateUpdateRequest $request, $candidateId)
    {
        $request->validated();
        return CandidateService::updateCandidate($request, $candidateId);
    }
    public function search(CandidateSearchRequest $request)
    {   
        $request->validated();
        return CandidateService::searchCandidates($request);
    }
    public function filter(CandidateFilterRequest $request)
    {
        $request->validated();
        return CandidateService::filterCandidates($request); 
    }
    public function import(CandidateImportRequest $request)
    {
        $request->validated();
        return CandidateService::importCandidates($request); 
    }
}
