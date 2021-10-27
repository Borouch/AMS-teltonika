<?php

namespace App\Services;

use Exception;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidateService
{
    public static function storeCandidate(Request $request)
    {
        $candidate = new Candidate();
        $candidate->name = $request->input('name');
        $candidate->surnname = $request->input('surnname');
        $candidate->gender = $request->input('gender');
        if ($request->filled('phone')) {

            $candidate->phone = $request->input('phone');
        }
        $candidate->email = $request->input('email');
        $candidate->application_date = $request->input('application_date');
        $candidate->education_institution = $request->input('education_institution');
        $candidate->city = $request->input('city');
        $candidate->course = $request->input('course');
        $candidate->academy = $request->input('academy');
        if ($request->filled('comment')) {

            $candidate->comment = $request->input('comment');
        }
        if ($request->hasFile('CV')) {
            $path = $request->file('CV')->store('CVs');
            $candidate->CV = $path;
        }

        $candidate->save();
        return response()->json(['message' => 'Candidate created succesfully', 'candidate' => $candidate], 200);
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
        return response()->json(['message' => " $count candidates found that match search query fields ", 'candidates' => $candidates], 200);
    }
}
