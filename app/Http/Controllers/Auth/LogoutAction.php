<?php

namespace App\Http\Controllers\Auth;

use Throwable;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class LogoutAction extends BaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
        try {
            auth()->logout(true);
        } catch (Throwable $e) {
            report($e);

            return $this->respond(__('auth.error validating authentication'), HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->respond(__('auth.successfully logged out'));
    }
}
