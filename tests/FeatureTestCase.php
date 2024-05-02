<?php

namespace Tests;

use App\Models\User;
use Illuminate\Support\Facades\URL;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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

    protected static function featureStructure(string $filename): mixed
    {
        return json_decode(file_get_contents(__DIR__ . "/Feature/Structures/{$filename}.json") ?: '', true);
    }
}
