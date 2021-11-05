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

        return Candidateservice::indexCandidates($request);
    }
    public function store(CandidateStoreRequest $request)
    {
        
        return response()->json(CandidateService::storeCandidate($request),200);
    }
    public function update(CandidateUpdateRequest $request, $candidateId)
    {
        return CandidateService::updateCandidate($request, $candidateId);
    }
    public function search(CandidateSearchRequest $request)
    {   
        return CandidateService::searchCandidates($request);
    }
    public function filter(CandidateFilterRequest $request)
    {
        return CandidateService::filterCandidates($request); 
    }
    public function import(CandidateImportRequest $request)
    {
        return CandidateService::importCandidates($request); 
    }
    public function export(Request $request)
    {
        return CandidateService::exportCandidates($request);
    }
    public function exportCV(Request $request,$candidateId)
    {
        return CandidateService::exportCV($candidateId);
    }       
}
