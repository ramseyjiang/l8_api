<?php

namespace App\Repositories;

use App\Contracts\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Find all entities.
     *
     * @param array $attributes
     *
     * @return \Illuminate\Support\Collection
     */
    public function findAll(): Collection
    {
        return $this->model->get();
    }

    /**
     * Find an entity by its primary key.
     *
     * @param int   $id
     * @param array $attributes
     *
     * @return mixed
     */
    public function findById(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new entity with the given attributes.
     *
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes = []): ?Model
    {
        $model = $this->model->create($attributes);

        return $model->fresh;
    }

    /**
     * Update an entity with the given attributes.
     *
     * @param mixed $id
     * @param array $attributes
     *
     * @return bool
     */
    public function update(int $id, array $attributes = []): bool
    {
        $model = $this->findById($id);

        return $model->update($attributes);
    }

    /**
     * Delete an entity with the given id.
     *
     * @param mixed $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->findById($id)->delete();
    }

    /**
     * Delete all entities.
     *
     * @return bool
     */
    public function deleteAll(): bool
    {
        return $this->model->delete();
    }
}
