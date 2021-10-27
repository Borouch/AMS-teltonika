<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Services\CandidateService;
use App\Http\Requests\CandidateStoreRequest;
use App\Http\Requests\CandidateSearchRequest;
use App\Http\Requests\CandidateUpdateRequest;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $candidates = Candidate::all();
        return response()->json(['candidates' => $candidates], 200);
    }
    public function store(CandidateStoreRequest $request)
    {
        $request->validated();
        return CandidateService::storeCandidate($request);
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
}
