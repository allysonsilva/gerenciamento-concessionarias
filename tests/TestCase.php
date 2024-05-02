<?php

namespace Tests;

use Tests\Support\Concerns\CustomMacros;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use WithFaker;
    use CustomMacros;

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();

        if (isset($uses[CustomMacros::class])) {
            $this->testResponseMacros();
        }

        return $uses;
    }
}
