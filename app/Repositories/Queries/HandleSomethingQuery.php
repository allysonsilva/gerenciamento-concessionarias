<?php

namespace App\Repositories\Queries;

use App\Support\Contracts\ShouldQueryInterface;

class HandleSomethingQuery implements ShouldQueryInterface
{
    /**
     * Execute the Query.
     *
     * @return mixed
     */
    public function execute(): mixed
    {
        // Do something
    }
}
