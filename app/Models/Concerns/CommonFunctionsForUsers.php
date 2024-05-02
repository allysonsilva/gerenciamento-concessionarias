<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait CommonFunctionsForUsers
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootCommonFunctionsForUsers()
    {
        self::addGlobalScopeWhereUserId();

        static::creating(function (self $entity) {
            $entity->user_id = auth()->id();
        });
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('id', $value)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();
    }

    /**
     * @see https://laravel.com/docs/11.x/eloquent#anonymous-global-scopes
     *
     * @return void
     */
    public static function addGlobalScopeWhereUserId(): void
    {
        static::addGlobalScope('where-logged-user', function (Builder $builder) {
            $builder->where('user_id', auth()->id());
        });
    }
}
