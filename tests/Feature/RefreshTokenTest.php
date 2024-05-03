<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;

#[Group('Feature')]
class RefreshTokenTest extends FeatureTestCase
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
    #[TestDox('Refresh Token - Campos obrigatórios')]
    public function it_should_be_possible_to_validate(): void
    {
        $this->putJson(route('auth.logged.refresh'))->assertInternalServerError();
    }

    #[Test]
    #[TestDox('Refresh Token - Campos obrigatórios')]
    public function it_must_be_possible_to_update_the_access_token(): void
    {
        $this->withToken($this->userTokenJWT())
             ->putJson(route('auth.logged.refresh'))
             ->assertCreated()
             ->assertJsonStructure(static::featureStructure('user-token'));
    }
}
