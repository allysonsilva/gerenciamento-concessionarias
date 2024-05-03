<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;

#[Group('Feature')]
class ConcessionariaSearcheableTest extends FeatureTestCase
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
    #[TestDox('Search - Realizando algumas consultas de "Full-Text" no index das concessionárias')]
    public function it_search_and_get_predetermine(): void
    {
        // Arrange
        $structure = static::featureStructure('concessionarias-index');

        // Act
        $this->actingAs($this->userAuth())
             ->getJson(route('api.verified.concessionarias.index', ['search' => '36320010000151']))
        // Assert
             ->assertOk()
             ->assertJsonStructure($structure)
             ->assertJsonCount(3, 'data');

        // In implementing this feature, MySQL uses what is sometimes referred to as implied Boolean logic, in which:
        // + stands for AND
        // - stands for NOT
        // [no operator] implies OR

        $this->actingAs($this->userAuth())
            // MySQL Fulltext search
             ->getJson(route('api.verified.concessionarias.index', ['search' => '36320010000151 -AABBC']))
             ->assertOk()
             ->assertJsonCount(2, 'data');

        $this->actingAs($this->userAuth())
             ->getJson(route('api.verified.concessionarias.index', ['search' => '36320010000151 -AABBC -AABBD']))
             ->assertOk()
             ->assertJsonCount(1, 'data');

        $this->actingAs($this->userAuth())
             ->getJson(route('api.verified.concessionarias.index', ['search' => '36320010000151 -AABBC -AABBD']))
             ->assertOk()
             ->assertJsonCount(1, 'data');

        $this->actingAs($this->userAuth())
             ->getJson(route('api.verified.concessionarias.index', ['search' => '36320010000151 -AABBC -AABBD']))
             ->assertOk()
             ->assertJsonCount(1, 'data');

        $this->actingAs($this->userAuth())
             ->getJson(route('api.verified.concessionarias.index', ['search' => '+Concessionaria -36320010000151']))
             ->assertOk()
             ->assertJsonCount(2, 'data');

        $this->actingAs($this->userAuth())
             ->getJson(route('api.verified.concessionarias.index', ['search' => 'Silva']))
             ->assertOk()
             ->assertJsonCount(3, 'data');

        $this->actingAs($this->userAuth())
             ->getJson(route('api.verified.concessionarias.index', ['search' => 'AABBH AABBI']))
             ->assertOk()
             ->assertJsonCount(2, 'data');
    }
}
