<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use App\Enums\RequestOrderBy;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithCast;
use Illuminate\Contracts\Support\Arrayable;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class SearchData extends Data implements Arrayable
{
    public function __construct(
        #[WithCast(EnumCast::class)]
        public readonly ?RequestOrderBy $orderBy,
        public readonly ?int $perPage,
        public readonly ?int $page,
        public readonly ?string $search,
    ) {
        //
    }

    public function orderByValue(): string
    {
        $orderBy = $this->orderBy ?: RequestOrderBy::DESC;

        return $orderBy->value;
    }
}
