<?php

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademyController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\EducationInstitutionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/candidates', [CandidateController::class, 'index']);
Route::post('/candidate', [CandidateController::class, 'store']);
Route::put('/candidate/{id}',[CandidateController::class, 'update']);
Route::post('/candidates/search',[CandidateController::class, 'search']);
Route::post('/candidates/filter',[CandidateController::class, 'filter']);
Route::post('/candidates/import',[CandidateController::class, 'import']);
Route::get('/candidate/exportCV/{id}',[CandidateController::class, 'exportCV']);
Route::get('/candidates/export',[CandidateController::class, 'export']);

Route::get('/academies',[AcademyController::class, 'index']);
Route::post('/academy',[AcademyController::class, 'store']);
Route::get('/academy_positions/{id}',[AcademyController::class, 'academyWithPositions']);

Route::get('/positions',[PositionController::class, 'index']);
Route::post('/position',[PositionController::class, 'store']);

Route::post('/education_institution',[EducationInstitutionController::class, 'store']);
Route::get('/education_institutions',[EducationInstitutionController::class, 'index']);

Route::post('/comment/{id}',[CommentController::class, 'store']);


