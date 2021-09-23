<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{
    /**
     * Find all entities.
     *
     * @param array $attributes
     *
     * @return \Illuminate\Support\Collection
     */
    public function findAll(): Collection;

    /**
     * Find an entity by its primary key.
     *
     * @param int   $id
     * @param array $attributes
     *
     * @return Model
     */
    public function findById(int $id): ?Model;

    /**
     * Create a new entity with the given attributes.
     *
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes = []): ?Model;

    /**
     * Update an entity with the given attributes.
     *
     * @param mixed $id
     * @param array $attributes
     *
     * @return bool
     */
    public function update(int $id, array $attributes = []): bool;

    /**
     * Delete an entity with the given id.
     *
     * @param mixed $id
     *
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Delete all entities.
     *
     * @return bool
     */
    public function deleteAll(): bool;

    /**
     * Delete all entities left current.
     *
     * @return bool
     */
    public function delAllLeftCurrent(array $ids): bool;
}
