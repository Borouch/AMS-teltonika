<?php

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AcademyController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\newPasswordController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post("/reset_password", [NewPasswordController::class, 'reset']);
Route::get("/send_reset_link", [NewPasswordController::class, 'sendResetLink']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/users/{user_id?}', [UserController::class, 'index']);
        Route::post('/user/{user_id?}/assign_roles', [UserController::class, 'assignRoles']);
        Route::post('/user/{user_id?}/remove_roles', [UserController::class, 'removeRoles']);
        Route::post('/role', [RoleController::class, 'store']);
        Route::get('/roles/{roleId?}', [RoleController::class, 'index']);
        Route::put('/role/{roleId}', [RoleController::class, 'update']);
    });
    Route::middleware(['role:read|admin'])->group(function () {
        Route::get('/candidates/export', [CandidateController::class, 'export']);
        Route::get('/candidates/{id?}', [CandidateController::class, 'index']);
        Route::get('/candidate/{candidate_id}/exportCV', [CandidateController::class, 'exportCV']);

        
        Route::get('/positions/{id?}', [PositionController::class, 'index']);
        
        Route::get('/education_institutions/{id?}', [EducationInstitutionController::class, 'index']);
        
        Route::get('/comments/{id?}', [CommentController::class, 'index']);
        
        Route::get('/academy/{id}/positions', [AcademyController::class, 'academyWithPositions']);
        Route::get('/academies/{id?}', [AcademyController::class, 'index']);
        
        Route::get('/academy/{academy_id?}/statistics/position', [AcademyController::class, 'statByPositions']);
        Route::get('/academy/{academy_id?}/statistics/education_institution', [AcademyController::class, 'statByEducationInstitutions']);
        Route::get('/academy/{academy_id?}/statistics/course', [AcademyController::class, 'statByCourses']);
        Route::get('/academy/{academy_id?}/statistics/gender', [AcademyController::class, 'statByGenders']);
        Route::get('/academy/{academy_id?}/statistics/status', [AcademyController::class, 'statByStatuses']);
        Route::get('/academy/{academy_id?}/statistics/application_date', [AcademyController::class, 'statByApplicationDate']);
        Route::get('/academy/{academy_id?}/statistics/month/{month_number}', [AcademyController::class, 'statByMonth']);
    });
    Route::middleware(['role:write|admin'])->group(function () {
        Route::post('/candidate', [CandidateController::class, 'store']);
        Route::post('/candidates/import', [CandidateController::class, 'import']);
        Route::post('/academy', [AcademyController::class, 'store']);
        Route::post('/position', [PositionController::class, 'store']);
        Route::post('/education_institution', [EducationInstitutionController::class, 'store']);
        Route::post('/comment/{candidate_id}', [CommentController::class, 'store']);
        
    });

    Route::middleware(['role:update|admin'])->group(function () {
        Route::put('/candidate/{candidate_id}', [CandidateController::class, 'update']);
        Route::put('/comment/{comment_id}', [CommentController::class, 'update']);

    });







});
