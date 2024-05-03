<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * @codeCoverageIgnore
 * TODO:
 */
class JwtAuthenticate
{
    /**
     * The JWT Authenticator.
     *
     * @var \PHPOpenSourceSaver\JWTAuth\JWTAuth
     */
    private JWTAuth $auth;

    /**
     * Create a new middleware instance.
     *
     * @param \PHPOpenSourceSaver\JWTAuth\JWTAuth $auth
     *
     * @return void
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->checkForToken($request);

        try {

            /** @var \App\Models\User */
            $user = $this->auth->parseToken()->authenticate();

            if (! $user->isEnabled()) {
                throw new HttpException(Response::HTTP_UNAUTHORIZED, __('auth.inactive user'));
            }

            if (empty($user)) {
                throw new NotFoundHttpException(__('auth.user not found'));
            }

            auth()->shouldUse('jwt');

        } catch (TokenBlacklistedException) {
            return response()->json(__('jwt.token blacklisted'), JsonResponse::HTTP_UNAUTHORIZED);
        } catch (TokenExpiredException) {
            return response()->json(__('jwt.token expired'), JsonResponse::HTTP_UNAUTHORIZED);
        } catch (TokenInvalidException) {
            return response()->json(__('jwt.token invalid'), JsonResponse::HTTP_UNAUTHORIZED);
        } catch (JWTException) {
            return response()->json(__('jwt.token absent'), JsonResponse::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }

    /**
     * Check the request for the presence of a token.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     *
     * @return void
     */
    public function checkForToken(Request $request)
    {
        if (! $this->auth->parser()->setRequest($request)->hasToken()) {
            throw new UnauthorizedHttpException('auth.jwt', __('jwt.token not provided'));
        }
    }
}
