<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request   $request   Request
     * @param Throwable $exception Throwable
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (DB::transactionLevel()) {
            DB::rollBack();
        }

        if ($exception instanceof ForbiddenException) {
            return response()->json([
                'status_code' => Response::HTTP_FORBIDDEN,
                'message' => $exception->getMessage()
                    ? $exception->getMessage()
                    : trans('message.forbidden'),
                'errors' => []
            ], Response::HTTP_FORBIDDEN);
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'status_code' => Response::HTTP_NOT_FOUND,
                'message' => $exception->getModel() ?
                    trans(sprintf('message.%s.not_found', $exception->getModel())) :
                    trans('message.model_not_found'),
                'errors' => []
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => trans('message.validation'),
                'errors' => $exception->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($exception instanceof NotFoundHttpException || $exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'status_code' => Response::HTTP_NOT_FOUND,
                'message' => empty($exception->getMessage())
                    ? trans('message.route_not_found')
                    : $exception->getMessage(),
                'errors' => []
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof BadRequestException) {
            return response()->json([
                'status_code' => Response::HTTP_BAD_REQUEST,
                'message' => empty($exception->getMessage())
                    ? trans('message.bad_request')
                    : $exception->getMessage(),
                'errors' => []
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($exception instanceof AuthorizationException || $exception instanceof AuthenticationException) {
            return response()->json([
                'status_code' => Response::HTTP_UNAUTHORIZED,
                'message' => empty($exception->getMessage())
                    ? trans('message.unauthorized')
                    : $exception->getMessage(),
                'errors' => []
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => trans('message.internal_server'),
            'errors' => []
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
