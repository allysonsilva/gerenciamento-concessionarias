<?php

namespace App\Http\Controllers\Auth;

use Throwable;
use App\Enums\UserStatus;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Traits\Token;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Controllers\BaseController;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class LoginAction extends BaseController
{
    use Token;

    /**
     * Handle the incoming request.
     */
    public function __invoke(AuthLoginRequest $request): JsonResponse
    {
        try {
            $credentials = array_merge($request->validated(), ['status' => UserStatus::ENABLE]);

            if (! empty($token = auth()->attempt($credentials))) {
                return $this->respondWithToken($token);
            }

            return $this->respond(__('auth.failed'), HttpResponse::HTTP_UNAUTHORIZED);
        } catch (Throwable $e) {
            report($e);
        }

        return $this->respond(__('auth.login error'), HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
