<?php

namespace Tests;

use App\Models\User;
use Illuminate\Support\Facades\URL;
use App\Http\Middleware\JwtAuthenticate;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Routing\Middleware\ThrottleRequestsWithRedis;
use Illuminate\Auth\Middleware\Authorize as AuthorizeMiddleware;

abstract class FeatureTestCase extends TestCase
{
    use DatabaseTransactions;

    protected const EMAIL_USER_0 = 'user_0@example.org';

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        URL::forceRootUrl('http://localhost');
    }

    protected function userTokenJWT(): string
    {
        return JWTAuth::fromUser($this->userAuth());
    }

    protected function userAuth(): User
    {
        return User::where('email', static::EMAIL_USER_0)->firstOrFail();
    }

    protected function withoutMiddlewareDependencies(): static
    {
        $this->withoutMiddleware([
            JwtAuthenticate::class,
            AuthorizeMiddleware::class,
            ThrottleRequests::class,
            ThrottleRequestsWithRedis::class
        ]);

        return $this;
    }

    protected static function featureStructure(string $filename): mixed
    {
        return json_decode(file_get_contents(__DIR__ . "/Feature/Structures/{$filename}.json") ?: '', true);
    }
}
