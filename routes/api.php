<?php

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademyController;
use App\Http\Controllers\CommentController;
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

Route::get('/candidates/export',[CandidateController::class, 'export']);
Route::get('/candidates/{should_group_by_academy?}', [CandidateController::class, 'index']);
Route::post('/candidate', [CandidateController::class, 'store']);
Route::put('/candidate/{id}',[CandidateController::class, 'update']);
Route::post('/candidates/search',[CandidateController::class, 'search']);
Route::post('/candidates/filter',[CandidateController::class, 'filter']);
Route::post('/candidates/import',[CandidateController::class, 'import']);
Route::get('/candidate/exportCV/{id}',[CandidateController::class, 'exportCV']);

Route::get('/academies',[AcademyController::class, 'index']);
Route::post('/academy',[AcademyController::class, 'store']);
Route::get('/academy_positions/{id}',[AcademyController::class, 'academyWithPositions']);

Route::get('/positions',[PositionController::class, 'index']);
Route::post('/position',[PositionController::class, 'store']);

Route::post('/education_institution',[EducationInstitutionController::class, 'store']);
Route::get('/education_institutions',[EducationInstitutionController::class, 'index']);

Route::post('/comment/{candidate_id}',[CommentController::class, 'store']);
Route::put('/comment/{comment_id}',[CommentController::class, 'update']);

Route::get('/academy/statistics/position/{academy_id?}',[AcademyController::class, 'statByPositions']);
Route::get('/academy/statistics/education_institution/{academy_id?}',[AcademyController::class, 'statByEducationInstitutions']);
Route::get('/academy/statistics/course/{academy_id?}',[AcademyController::class, 'statByCourses']);
Route::get('/academy/statistics/gender/{academy_id?}',[AcademyController::class, 'statByGenders']);
Route::get('/academy/statistics/status/{academy_id?}',[AcademyController::class, 'statByStatuses']);
Route::get('/academy/statistics/application_date/{academy_id?}',[AcademyController::class, 'statByApplicationDate']);
Route::get('/academy/statistics/month/{month_number}/{academy_id?}',[AcademyController::class, 'statByMonth']);
