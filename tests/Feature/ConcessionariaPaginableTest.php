<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use Illuminate\Testing\Fluent\AssertableJson;

#[Group('Feature')]
class ConcessionariaPaginableTest extends FeatureTestCase
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
    #[TestDox('Paginate - Deve ser possível trabalhar com paginação')]
    public function it_should_be_possible_to_paginate(): void
    {
        // Arrange
        $structure = static::featureStructure('concessionarias-index');

        $this->actingAs($this->userAuth())
             ->getJson(route('api.verified.concessionarias.index', ['per_page' => 5, 'page' => 2]))
             ->assertOk()
             ->assertJsonStructure($structure)
             ->assertJsonCount(5, 'data');

        $this->actingAs($this->userAuth())
             ->getJson(route('api.verified.concessionarias.index', ['per_page' => 5, 'page' => 3]))
             ->assertOk()
             ->assertJsonStructure($structure)
             ->assertJsonCount(0, 'data');

        $this->actingAs($this->userAuth())
             ->getJson(route('api.verified.concessionarias.index', ['per_page' => 2, 'page' => 2]))
             ->assertOk()
             ->assertJsonStructure($structure)
             ->assertJsonCount(2, 'data');

        $this->actingAs($this->userAuth())
             ->getJson(route('api.verified.concessionarias.index', ['per_page' => 2, 'order_by' => 'desc']))
             ->assertOk()
             ->assertJsonStructure($structure)
             ->assertJson(fn (AssertableJson $json) =>
                $json->has('links')
                     ->has('meta')
                     ->has('data', 2, fn (AssertableJson $json) =>
                         $json->where('name', 'Concessionaria 10')
                              ->etc()
                     )
             );

        $this->actingAs($this->userAuth())
             ->getJson(route('api.verified.concessionarias.index', ['per_page' => 1, 'order_by' => 'asc']))
             ->assertOk()
             ->assertJsonStructure($structure)
             ->assertJson(fn (AssertableJson $json) =>
                $json->has('links')
                     ->has('meta')
                     ->has('data', 1, fn (AssertableJson $json) =>
                         $json->where('name', 'Concessionaria 1')
                              ->etc()
                     )
             );
    }
}
