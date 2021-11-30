<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use ErrorException;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Filesystem\Exception\InvalidArgumentException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [];
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if($e instanceof UnauthorizedException) 
        {
             $roles = Role::all()->map(fn($r)=>$r->name)->toArray();
             $e->forRoles($roles);
             return Response::json(['error' => $e->getMessage()], 401);
        }
        return Parent::render($request,$e);
    }
    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {

            $this->renderable(function (ValidationException $e, $request) {
                return Response::json(['error' => $e->getMessage(), 'details' => $e->errors()], 422);
            });
            $this->renderable(function (NotFoundHttpException $e, $request) {
                return Response::json(['error' => $e->getMessage()], $e->getCode());
            });
            $this->renderable(function (ErrorException $e, $request) {
                return Response::json(['error' => $e->getMessage()], $e->getCode());
            });
            $this->renderable(function (Exception $e, $request) {
                return Response::json(['error' => $e->getMessage()], $e->getCode());
            });
            var_dump(get_class($e));
            var_dump($e->getMessage());
        });
    }
}
