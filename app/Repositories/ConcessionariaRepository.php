<?php

namespace App\Repositories;

use App\Data\SearchData;
use App\Models\Concessionaria;
use Illuminate\Contracts\Pagination\Paginator;

final class ConcessionariaRepository extends BaseRepository
{
    public function modelClass(): string
    {
        return Concessionaria::class;
    }

    public function index(SearchData $data): Paginator
    {
        $query = $this->entity->query()->with('user');

        if (! empty($search = $data->search)) {
            $query->searcheable($search);
        }

        return $query->orderBy('id', $data->orderByValue())
                     ->simplePaginate(perPage: $data->perPage, page: $data->page)
                     ->withQueryString();
    }
}
