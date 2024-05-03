<?php

namespace Tests\Feature;

use Exception;
use Illuminate\Support\Facades\Auth;
use Tests\FeatureTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;

#[Group('Feature')]
class LogoutTest extends FeatureTestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddlewareDependencies();
    }

    #[Test]
    #[TestDox('Logout - Deve ser possível sair / deslogar da API e não ser possível logar com o token anterior')]
    public function it_should_be_possible_to_logout(): void
    {
        $this->withToken($this->userTokenJWT())
             ->deleteJson(route('auth.logged.logout'))
             ->assertOk();
    }
}
