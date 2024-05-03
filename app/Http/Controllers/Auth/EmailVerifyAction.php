<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * @codeCoverageIgnore
 * TODO:
 */
class EmailVerifyAction extends BaseController
{
    public function __construct()
    {
        $this->middleware('signed');
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->respond(__('messages.email already validated'), HttpResponse::HTTP_FORBIDDEN);
        }

        $request->fulfill();

        return $this->respond(__('messages.email successfully verified'));
    }
}
