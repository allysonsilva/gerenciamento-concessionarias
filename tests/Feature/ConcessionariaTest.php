<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use App\Models\Concessionaria;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use App\Http\Resources\ConcessionariaResource;

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

        $this->withoutMiddlewareDependencies();
    }

    #[Test]
    #[TestDox('Deve ser possível listar as concessionárias do usuário logado')]
    public function it_get_concessionarias_listing(): void
    {
        // Arrange
        $structure = static::featureStructure('concessionarias-index');

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
                         ->getJson(route('api.verified.concessionarias.show', $resourceCreated->getKey()));

        // Assert
        $response->assertOk()
                 ->assertJsonStructure(['data' => $structure['data']['*']]);
    }

    #[Test]
    #[TestDox('Deve ser possível atualizar os dados de uma concessionária')]
    public function it_update_concessionaria(): void
    {
        // Arrange
        $response = $this->actingAs($this->userAuth())
                         ->withoutExceptionHandling()
                         ->postJson(route('api.verified.concessionarias.store'), Concessionaria::factory()->make()->toArray())
                         ->assertCreated();

        /** @var \App\Models\Concessionaria */
        $resourceCreated = $response->getOriginalContent();

        $newData = Concessionaria::factory()->make()->toArray();

        $resourceUpdated = tap(clone $resourceCreated, fn (Model $resource) => $resource->fill($newData));

        // Act
        $response = $this->actingAs($this->userAuth())
                         ->withoutExceptionHandling()
                         ->putJson(route('api.verified.concessionarias.update', $resourceCreated->getKey()), $newData);

        // Assert
        $response->assertOk()
                 ->assertResource(new ConcessionariaResource($resourceUpdated));
    }

    #[Test]
    #[TestDox('Deve ser remover uma concessionária')]
    public function it_destroy_concessionaria(): void
    {
        // $this->withoutExceptionHandling();

        // Arrange
        $response = $this->actingAs($this->userAuth())
                         ->postJson(route('api.verified.concessionarias.store'), Concessionaria::factory()->make()->toArray())
                         ->assertCreated();

        /** @var \App\Models\Concessionaria */
        $resourceCreated = $response->getOriginalContent();

        // Act
        $response = $this->actingAs($this->userAuth())
                         ->deleteJson(route('api.verified.concessionarias.destroy', $resourceCreated->getKey()));

        // Assert
        $response->assertNoContent();

        $this->actingAs($this->userAuth())
             ->getJson(route('api.verified.concessionarias.show', $resourceCreated->getKey()))
             ->assertNotFound();
    }
}
