<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use App\Http\Requests\ConcessionariaStoreRequest;

#[Group('Feature')]
class ConcessionariaValidatableTest extends FeatureTestCase
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
    #[TestDox('Validatable - Realizando algumas validações no cadastro de concessionárias')]
    public function it_should_be_possible_to_validate(): void
    {
        $this->actingAs($this->userAuth())
             ->postJson(route('api.verified.concessionarias.store'))
             ->assertUnprocessable()
             ->assertJsonValidationErrors(['name', 'symbol', 'cnpj']);
    }

    #[Test]
    #[TestDox('Validatable - Não deve ser possível usar o FormRequest se o usuário não tiver logado')]
    public function it_should_not_authorize_the_request_if_the_user_is_not_logged_in(): void
    {
        Auth::shouldReceive('check')
            ->once()
            ->andReturn(false);

        $this->assertFalse((new ConcessionariaStoreRequest())->authorize());
    }

    #[Test]
    #[TestDox('Validatable - DEVE ser possível usar o FormRequest se o usuário não tiver logado')]
    public function it_should_authorize_the_request_if_the_user_is_logged_in(): void
    {
        Auth::shouldReceive('check')
            ->once()
            ->andReturn(true);

        $this->assertTrue((new ConcessionariaStoreRequest)->authorize());
    }
}
