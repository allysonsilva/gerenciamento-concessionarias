<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Traits\Token;
use App\Http\Controllers\BaseController;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class RefreshTokenAction extends BaseController
{
    use Token;

    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
       try {
            return $this->respondWithToken(auth()->refresh(true), HttpResponse::HTTP_CREATED);
        } catch (Exception $e) {
            report($e);
        }

        return $this->respond(__('auth.error validating authentication'), HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
