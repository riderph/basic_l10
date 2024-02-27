<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Return a new JSON response from the application.
     *
     * @param string       $message Message
     * @param string|array $data    Data
     * @param int          $status  Status code
     * @param array        $headers Headers
     *
     * @return \Illuminate\Http\JsonResponse;
     */
    public function responseSuccess($message, $data = [], $status = Response::HTTP_OK, array $headers = [])
    {
        return response()->json([
            'status_code' => $status,
            'message' => $message,
            'data' => $data
        ], $status, $headers);
    }

    /**
     * Return a new JSON response from the application.
     *
     * @param string       $message Message
     * @param string|array $error   Error response
     * @param int          $status  Status code
     * @param array        $headers Headers
     *
     * @return \Illuminate\Http\JsonResponse;
     */
    public function responseError(
        $message,
        $error = [],
        $status = Response::HTTP_INTERNAL_SERVER_ERROR,
        array $headers = []
    ) {
        if ($message instanceof ModelNotFoundException) {
            return $this->responseNotFound($message);
        }

        return response()->json([
            'status_code' => $status,
            'message' => $message,
            'errors' => $error
        ], $status, $headers);
    }

    /**
     * Response not found exception
     *
     * @param ModelNotFoundException $exception ModelNotFoundException
     *
     * @return \Illuminate\Http\JsonResponse;
     */
    protected function responseNotFound($exception)
    {
        return response()->json([
            'status_code' => Response::HTTP_NOT_FOUND,
            'message' => 'messages.' . $exception->getModel() . '.not_found',
            'errors' => []
        ], Response::HTTP_NOT_FOUND);
    }
}
