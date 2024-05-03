<?php

namespace Tests\Feature;

use Exception;
use App\Models\User;
use Tests\FeatureTestCase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use App\Notifications\UserVerifyEmail;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Auth\Events\Registered;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use Illuminate\Support\Facades\Notification;

#[Group('Feature')]
class RegisterTest extends FeatureTestCase
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
    #[TestDox('Register - Campos obrigatórios')]
    public function it_should_be_possible_to_validate(): void
    {
        $this->postJson(route('auth.guest.signup'))
             ->assertUnprocessable()
             ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    #[Test]
    #[TestDox('Register - Email deve ser único')]
    public function it_should_be_possible_to_validate_unique_email(): void
    {
        $this->postJson(route('auth.guest.signup'), ['email' => parent::EMAIL_USER_0])
             ->assertUnprocessable()
             ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    #[TestDox('Register - Registrando novo usuário')]
    public function it_should_be_possible_to_register_a_new_user(): void
    {
        Notification::fake();

        $response = $this->postJson(route('auth.guest.signup'), $this->newUser())
                         ->assertCreated()
                         ->assertJsonStructure(static::featureStructure('user-token'));

        Notification::assertSentTo(
            [User::findOrFail(data_get($response->getOriginalContent(), 'user.id'))],
            UserVerifyEmail::class
        );
    }

    #[Test]
    #[TestDox('Register - Registrando novo usuário com asserção do evento')]
    public function it_should_be_possible_to_register_a_new_user_assert_event(): void
    {
        Event::fake();

        $this->postJson(route('auth.guest.signup'), $this->newUser())
             ->assertCreated()
             ->assertJsonStructure(static::featureStructure('user-token'));

        Event::assertDispatched(Registered::class);
    }

    #[Test]
    #[TestDox('Register - Deve ser possível manipular o caso de error')]
    public function it_must_be_possible_to_manipulate_the_errors(): void
    {
        Event::fake();

        $this->partialMock(User::class)
             ->shouldReceive('create')
             ->withAnyArgs()
             ->andThrow(new Exception('Test with exception'));

        $this->postJson(route('auth.guest.signup'), $this->newUser())
             ->assertInternalServerError();

        Event::assertNotDispatched(Registered::class);
    }

    private function newUser()
    {
        return Arr::only(array_merge(User::factory()->make()->toArray(), [
            'password' => 'password',
            'password_confirmation' => 'password',
        ]), ['name', 'email', 'password', 'password_confirmation']);
    }
}
