<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\RegisterUserRequest;
use App\Services\User\Actions\RegisterAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{

    /**
     * Create user
     *
     * @param RegisterUserRequest $request Request
     *
     * @return JsonResponse
     */
    public function store(RegisterUserRequest $request)
    {
        $credentials = $request->only([
            'name',
            'email',
            'password',
            'password_confirm',
        ]);
        $response = resolve(RegisterAction::class)->run($credentials);

        return $this->responseSuccess(
            trans('message.user.register_success'),
            $response,
            Response::HTTP_CREATED
        );
    }
}
