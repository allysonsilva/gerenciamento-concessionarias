<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Illuminate\Support\Traits\ForwardsCalls;

abstract class BaseService
{
    use ForwardsCalls;

    public function __construct(protected BaseRepository $repository)
    {
    }

    /**
     * Handle dynamic method calls into the repository.
     *
     * @param  string  $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        return $this->forwardCallTo($this->repository, $method, $arguments);
    }
}
