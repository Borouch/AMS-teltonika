<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
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
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

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
     * @param Request $request
     * @param Throwable $e
     * @return HttpResponse
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof UnauthorizedException) {
            $roles = Role::all()->map(fn($r) => $r->name)->toArray();
            $e->forRoles($roles);
            return Response::json(['error' => $e->getMessage()], 401);
        }
        if($e instanceof \TypeError)
        {
            $e = new \TypeError();
            return Response::json(['error' => $e->getMessage()], 500);
        }
        return Parent::render($request, $e);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            $this->renderable(function (TokenInvalidException $e, $request) {
                return Response::json(['error' => 'Invalid token'], 401);
            });
            $this->renderable(function (TokenExpiredException $e, $request) {
                return Response::json(['error' => 'Token has Expired'], 401);
            });

            $this->renderable(function (JWTException $e, $request) {
                return Response::json(['error' => 'Token not parsed'], 401);
            });
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
