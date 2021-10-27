<?php

namespace App\Exceptions;

use Exception;

use Throwable;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            $this->renderable(function (ModelNotFoundException $e, $request) {
                return Response::json(['error' => $e->getMessage()]);
            });
            $this->renderable(function (Exception $e, $request) {
                return Response::json(['error' => $e->getMessage()],$e->getCode());
            });
            //
        });
        
    }
}
