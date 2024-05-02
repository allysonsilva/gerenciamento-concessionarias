<?php

namespace App\Http\Controllers\Traits;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

trait Token
{
    /**
     * Get the token array structure.
     *
     * @param string $token
     * @param int|null $statusCode
     * @param array|null $optionalData
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token, int $statusCode = null, ...$optionalData): JsonResponse
    {
        /** @var \PHPOpenSourceSaver\JWTAuth\JWTGuard */
        $newToken = auth()->setToken($token);

        /** @var \PHPOpenSourceSaver\JWTAuth\Payload */
        $payload = $newToken->payload();

        // /** @var \App\Models\User */
        // $user = $newToken->user();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'created_at' => Carbon::createFromTimestamp($payload->get('iat'))->toDateTimeString(),
            'not_before_at' => Carbon::createFromTimestamp($payload->get('nbf'))->toDateTimeString(),
            'expires_at' => Carbon::createFromTimestamp($payload->get('exp'))->toDateTimeString(),
            'user' => $payload->get('commonUserData'),
            'data' => $optionalData ?? null
        ], $statusCode ?? HttpResponse::HTTP_OK);
    }
}
