<?php

namespace App\Http\Controllers\Auth;

use Throwable;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Traits\Token;
use App\Http\Controllers\BaseController;
use App\Http\Requests\AuthSignupRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class RegisterAction extends BaseController
{
    use Token;

    /**
     * Handle the incoming request.
     */
    public function __invoke(AuthSignupRequest $request, User $repository): JsonResponse
    {
        DB::beginTransaction();

        try {
            /** @var \App\Models\User */
            $newUser = $repository->create($request->safe()->toArray());

            event(new Registered($newUser));

            /** @var string */
            $token = auth()->login($newUser);

            DB::commit();

            return $this->respondWithToken($token, HttpResponse::HTTP_CREATED);
        } catch (Throwable $e) {
            DB::rollBack();

            report($e);
        }

        return $this->respond(__('messages.error when registering user'));
    }
}
