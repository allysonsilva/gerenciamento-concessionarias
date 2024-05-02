<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use App\Models\Concessionaria;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use App\Http\Middleware\JwtAuthenticate;
use PHPUnit\Framework\Attributes\TestDox;
use Illuminate\Auth\Middleware\Authorize as AuthorizeMiddleware;

#[Group('Feature')]
class ConcessionariaTest extends FeatureTestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([JwtAuthenticate::class, AuthorizeMiddleware::class]);
    }

    #[Test]
    #[TestDox('Deve ser possível listar as concessionárias do usuário logado')]
    public function it_get_concessionarias_listing(): void
    {
        // Arrange
        $structure = static::featureStructure('concessionarias-index');

        // foreach (range(1, 10) as $index) {
        //     $payload = Concessionaria::factory()->make()->toArray();

        //     $this->actingAs($this->userAuth())
        //          ->withoutExceptionHandling()
        //          ->postJson(route('api.verified.concessionarias.store'), $payload)
        //          ->assertCreated();
        // }

        // Act
        $this->actingAs($this->userAuth())
             ->withoutExceptionHandling()
             ->getJson(route('api.verified.concessionarias.index'))
        // Assert
             ->assertOk()
             ->assertJsonStructure($structure)
             ->assertJsonCount(10, 'data');
    }

    #[Test]
    #[TestDox('Deve ser possível recuperar / mostrar os dados de uma concessionária')]
    public function it_show_concessionaria(): void
    {
        // Arrange
        $structure = static::featureStructure('concessionarias-index');

        $response = $this->actingAs($this->userAuth())
                         ->withoutExceptionHandling()
                         ->postJson(route('api.verified.concessionarias.store'), Concessionaria::factory()->make()->toArray())
                         ->assertCreated();

        $resourceCreated = $response->getOriginalContent();

        // Act
        $response = $this->actingAs($this->userAuth())
                         ->withoutExceptionHandling()
                         ->getJson(route('api.verified.concessionarias.show', [
                            'concessionaria' => $resourceCreated->getKey(),
                         ]));

        $response->assertOk()
                 ->assertJsonStructure(['data' => $structure['data']['*']]);
    }
}
