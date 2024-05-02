<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Database\Factories\ConcessionariaFactory;
use App\Models\Concerns\CommonFunctionsForUsers;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Concessionaria extends Model
{
    use HasFactory, CommonFunctionsForUsers;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'symbol',
        'cnpj',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        ConcessionariaFactory::class;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function cnpj(): Attribute
    {
        return new Attribute(
            get: function (string $value, array $attributes) {
                return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $value);
            },
        );
    }

    public static function columnsToFullTextSearch(): array
    {
        return ['name', 'symbol', 'cnpj'];
    }

    public function scopeSearcheable(Builder $query, string $search): void
    {
        $query->whereFullText(static::columnsToFullTextSearch(), $search, ['mode' => 'boolean']);
    }
}
