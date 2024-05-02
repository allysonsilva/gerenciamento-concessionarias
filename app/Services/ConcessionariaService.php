<?php

namespace App\Services;

use App\Repositories\ConcessionariaRepository;

final class ConcessionariaService extends BaseService
{
    public function __construct(ConcessionariaRepository $repository)
    {
        $this->repository = $repository;
    }
}
