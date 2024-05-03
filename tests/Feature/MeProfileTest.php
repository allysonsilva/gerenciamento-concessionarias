<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use Illuminate\Testing\Fluent\AssertableJson;

#[Group('Feature')]
class MeProfileTest extends FeatureTestCase
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
    #[TestDox('Deve ser possível recuperar os dados do usuário logado - profile')]
    public function it_get_me_profile(): void
    {
        // Arrange
        $structure = static::featureStructure('profile-show');

        // Act
        $this->actingAs($this->userAuth())
             ->withoutExceptionHandling()
             ->getJson(route('api.me.profile.show'))
        // Assert
             ->assertOk()
             ->assertJsonStructure($structure)
             ->assertJson(fn (AssertableJson $json) =>
                    $json->has('user', fn ($json) =>
                        $json->where('has_email_verified', true)
                             ->where('is_enabled', true)
                             ->where('email', parent::EMAIL_USER_0)
                             ->etc()
                    )
                 );
    }
}
