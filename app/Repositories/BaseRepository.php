<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Traits\ForwardsCalls;
use App\Support\Repository\Exceptions\RepositoryException;

abstract class BaseRepository
{
    use ForwardsCalls;

    protected ?Model $entity;

    protected string $tableName;

    protected ?string $tablePrefix;

    public function __construct()
    {
        $this->entity = $this->resolveEntity();

        $this->tableName = $this->entity->getTable();

        $this->tablePrefix = DB::connection()->getTablePrefix() ?? '';
    }

    abstract public function modelClass(): string;

    protected function resolveEntity(): Model
    {
        $entity = app($this->modelClass());

        if (! $entity instanceof Model) {
            throw new RepositoryException(
                "Class {$this->modelClass()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        return $this->entity = $entity;
    }

    public function resetModel(): void
    {
        $this->resolveEntity();
    }

    /**
     * Recover all records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection
    {
        return $this->entity->latest()->get();
    }

    /**
     * Save the model to the database.
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $attributes): Model
    {
        $entity = $this->newInstance()->fill($attributes);

        $entity->save();

        return $entity;
    }

    /**
     * Update a entity in repository by id.
     *
     * @param array $attributes
     * @param \Illuminate\Database\Eloquent\Model|int $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(array $attributes, Model|int $id): Model
    {
        $entity = $id;

        if (! $id instanceof Model) {
            $entity = $this->newInstance()->lockForUpdate()->findOrFail($id);
        }

        $entity->fill($attributes)->save();

        return $entity;
    }

    /**
     * Delete the model from the database.
     *
     * @param \Illuminate\Database\Eloquent\Model|int $id
     *
     * @return \Illuminate\Database\Eloquent\Model|bool
     */
    public function delete(Model|int $id): Model|bool
    {
        $entity = $id;

        if (! $id instanceof Model) {
            $entity = $this->newInstance()->lockForUpdate()->findOrFail($id);
        }

        if (! $entity->delete()) {
            return false;
        }

        return $entity;
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        return $this->forwardCallTo($this->entity, $method, $arguments);
    }
}
