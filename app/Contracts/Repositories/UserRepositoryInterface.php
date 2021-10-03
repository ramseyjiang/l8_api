<?php

namespace App\Contracts\Repositories;

use App\Contracts\EloquentRepositoryInterface;

interface UserRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Find an entity by email
     *
     * @param string $email
     * @return Model
     */
    public function findByEmail(string $email);

    /**
     * Find an register user's id by email
     *
     * @param string $email
     * @return id
     */
    public function findIdByEmail(string $email);

    /**
     * Disable a user login portal.
     *
     * @param integer $id
     * @return void
     */
    public function disable(int $id);

    /**
     * Active a user after first login portal.
     * The user status should be changed from approved to active.
     *
     * @param integer $id
     * @return void
     */
    public function active(int $id);
}
