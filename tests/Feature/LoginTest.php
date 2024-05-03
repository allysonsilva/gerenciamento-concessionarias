<?php

namespace Tests\Feature;

use Exception;
use Mockery\MockInterface;
use Tests\FeatureTestCase;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Requests\AuthLoginRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;

#[Group('Feature')]
class LoginTest extends FeatureTestCase
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
    #[TestDox('Login - Campos obrigatórios')]
    public function it_should_be_possible_to_validate(): void
    {
        $this->postJson(route('auth.guest.login'))
             ->assertUnprocessable()
             ->assertJsonValidationErrors(['email', 'password']);
    }

    #[Test]
    #[TestDox('Login - Deve ser possível se logar na API')]
    public function it_must_be_possible_to_login(): void
    {
        Event::fake();

        $this->postJson(route('auth.guest.login'), [
            'email' => static::EMAIL_USER_0,
            'password' => 'password'
        ])
        ->assertOk()
        ->assertJsonStructure(static::featureStructure('user-token'));

        Event::assertDispatched(Login::class);
    }

    #[Test]
    #[TestDox('Login - Não deve ser possível o Login de usuário desabilitados')]
    public function it_should_not_be_possible_to_disable_users(): void
    {
        Event::fake();

        $this->postJson(route('auth.guest.login'), [
            'email' => 'user_disabled@example.org',
            'password' => 'password'
        ])
        ->assertUnauthorized();

        Event::assertNotDispatched(Login::class);
    }

    #[Test]
    #[TestDox('Login - Deve ser possível tratar erros de try/catch')]
    public function it_must_be_possible_to_handle_trycatch_errors(): void
    {
        $this->partialMock(AuthLoginRequest::class, function (MockInterface $mock) {
            $mock->shouldReceive('validated')
                 ->once()
                 ->andThrow(new Exception('Test with exception'));
        });

        $this->postJson(route('auth.guest.login'), [
            'email' => 'X',
            'password' => 'Y'
        ])
        ->assertInternalServerError();
    }
}
