<?php


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



Route::post('/login', [AuthController::class, 'login']);
Route::post('/reset_password', [NewPasswordController::class, 'reset']);
Route::get('/reset_link', [NewPasswordController::class, 'resetLink']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/user/{id}/assign_roles', [UserController::class, 'assignRoles']);
        Route::post('/user/{id}/remove_roles', [UserController::class, 'removeRoles']);

        Route::post('/role', [RoleController::class, 'store']);
        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/roles/{id}', [RoleController::class, 'show']);
        Route::put('/role/{id}', [RoleController::class, 'update']);
    });
    Route::middleware(['role:read|admin'])->group(function () {
        Route::get('/candidates/export', [CandidateController::class, 'export']);
        Route::get('/candidates', [CandidateController::class, 'index']);
        Route::get('/candidates/{id}', [CandidateController::class, 'show']);
        Route::get('/candidate/{id}/exportCV', [CandidateController::class, 'exportCV']);

        Route::get('/positions', [PositionController::class, 'index']);
        Route::get('/positions/{id}', [PositionController::class, 'show']);

        Route::get('/education_institutions', [EducationInstitutionController::class, 'index']);
        Route::get('/education_institutions/{id}', [EducationInstitutionController::class, 'show']);

        Route::get('/candidates/{id}/comments', [CommentController::class, 'index']);

        Route::get('/academies/{id}/positions', [AcademyController::class, 'showAcademyPositions']);
        Route::get('/academies', [AcademyController::class, 'index']);
        Route::get('/academies/{id}', [AcademyController::class, 'show']);


        Route::get('/academies/statistic/position', [AcademyController::class, 'indexStatByPosition']);
        Route::get('/academies/{id}/statistic/position', [AcademyController::class, 'showStatByPosition']);

        Route::get(
            '/academies/statistic/education_institution',
            [AcademyController::class, 'indexStatByEducationInstitution']
        );
        Route::get(
            '/academies/{id}/statistic/education_institution',
            [AcademyController::class, 'showStatByEducationInstitution']
        );

        Route::get('/academies/statistic/course', [AcademyController::class, 'indexStatByCourse']);
        Route::get('/academies/{id}/statistic/course', [AcademyController::class, 'showStatByCourse']);

        Route::get('/academies/statistic/gender', [AcademyController::class, 'indexStatByGender']);
        Route::get('/academies/{id}/statistic/gender', [AcademyController::class, 'showStatByGender']);

        Route::get('/academies/statistic/status', [AcademyController::class, 'indexStatByStatus']);
        Route::get('/academies/{id}/statistic/status', [AcademyController::class, 'showStatByStatus']);

        Route::get('/academies/statistic/application_date', [AcademyController::class, 'indexStatByApplicationDate']);
        Route::get('/academies/{id}/statistic/application_date', [AcademyController::class, 'showStatByApplicationDate']
        );

        Route::get('/academies/statistic/month/{month_number}', [AcademyController::class, 'indexStatByMonth']);
        Route::get('/academies/{id}/statistic/month/{month_number}', [AcademyController::class, 'showStatByMonth']);
    });
    Route::middleware(['role:write|admin'])->group(function () {
        Route::post('/candidate', [CandidateController::class, 'store']);
        Route::post('/candidates/import', [CandidateController::class, 'import']);
        Route::post('/academy', [AcademyController::class, 'store']);
        Route::post('/position', [PositionController::class, 'store']);
        Route::post('/education_institution', [EducationInstitutionController::class, 'store']);
        Route::post('/candidate/{id}/comment', [CommentController::class, 'store']);
    });

    Route::middleware(['role:update|admin'])->group(function () {
        Route::put('/candidate/{id}', [CandidateController::class, 'update']);
        Route::put('/candidate/comment/{id}', [CommentController::class, 'update']);
        Route::put('/academy/{id}', [AcademyController::class, 'update']);
        Route::put('/education_institution/{id}', [EducationInstitutionController::class, 'update']);
        Route::put('/position/{id}', [PositionController::class, 'update']);

    });
});
