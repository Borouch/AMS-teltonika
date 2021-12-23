<?php


use App\Http\Controllers\PermissionController;
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

Route::middleware(['auth.jwt'])->group(function () {
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);

        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/roles/{id}', [RoleController::class, 'show']);;
        Route::put('/roles/assign/{user_id}', [RoleController::class, 'assign']);;
        Route::put('/roles/remove/{user_id}', [RoleController::class, 'remove']);;


        Route::get('/permissions', [PermissionController::class, 'index']);
        Route::get('/permissions/{id}', [PermissionController::class, 'show']);;
        Route::put('/permissions/assign/{user_id}', [PermissionController::class, 'assign']);;
        Route::put('/permissions/remove/{user_id}', [PermissionController::class, 'remove']);
    });
    Route::middleware(['role:read|admin'])->group(function () {
        Route::middleware(['permission:candidate'])->group(function () {
            Route::get('/candidates/export', [CandidateController::class, 'export']);
            Route::get('/candidates', [CandidateController::class, 'index']);
            Route::get('/candidates/{id}', [CandidateController::class, 'show']);
            Route::get('/candidate/{id}/export_cv', [CandidateController::class, 'exportCV']);
            Route::get('/candidates/{id}/comments', [CommentController::class, 'index']);
        });

        Route::middleware(['permission:position'])->group(function () {
            Route::get('/positions', [PositionController::class, 'index']);
            Route::get('/positions/{id}', [PositionController::class, 'show']);
        });

        Route::middleware(['permission:education_institution'])->group(function () {
            Route::get('/education_institutions', [EducationInstitutionController::class, 'index']);
            Route::get('/education_institutions/{id}', [EducationInstitutionController::class, 'show']);
        });

        Route::middleware(['permission:academy'])->group(function () {
            Route::get('/academies/{id}/positions', [AcademyController::class, 'showAcademyPositions']);
            Route::get('/academies', [AcademyController::class, 'index']);
            Route::get('/academies/{id}', [AcademyController::class, 'show']);
        });

        Route::middleware(['permission:statistic'])->group(function () {
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

            Route::get('/academies/statistic/application_date', [AcademyController::class, 'indexStatByApplicationDate']
            );
            Route::get(
                '/academies/{id}/statistic/application_date',
                [AcademyController::class, 'showStatByApplicationDate']
            );
            Route::get('/academies/statistic/month/{month_number}', [AcademyController::class, 'indexStatByMonth']);
            Route::get('/academies/{id}/statistic/month/{month_number}', [AcademyController::class, 'showStatByMonth']);
        });
    });
    Route::middleware(['role:write|admin'])->group(function () {
        Route::middleware(['permission:candidate'])->group(function () {
            Route::post('/candidate', [CandidateController::class, 'store']);
            Route::post('/candidates/import', [CandidateController::class, 'import']);
            Route::post('/candidate/{id}/comment', [CommentController::class, 'store']);
        });

        Route::post('/academy', [AcademyController::class, 'store'])->middleware(['permission:academy']);
        Route::post('/position', [PositionController::class, 'store'])->middleware(['permission:position']);;
        Route::post('/education_institution', [EducationInstitutionController::class, 'store'])->middleware(
            ['permission:education_institution']
        );
    });

    Route::middleware(['role:update|admin'])->group(function () {
        Route::middleware(['permission:candidate'])->group(function () {
            Route::put('/candidate/{id}', [CandidateController::class, 'update']);
            Route::put('/candidate/comment/{id}', [CommentController::class, 'update']);
        });

        Route::put('/academy/{id}', [AcademyController::class, 'update'])->middleware(['permission:academy']);;
        Route::put('/education_institution/{id}', [EducationInstitutionController::class, 'update'])->middleware(
            ['permission:education_institution']
        );
        Route::put('/position/{id}', [PositionController::class, 'update'])->middleware(['permission:position']);
    });
});
