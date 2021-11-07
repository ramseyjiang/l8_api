<?php

namespace App\Contracts;

use App\Contracts\EloquentContract;

interface UserContract extends EloquentContract
{
    /**
     * Find an entity by email
     *
     * @param string $email
     * @return Model
     */
    public function findUserByEmail(string $email);

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

    /**
     * Record a user last login date.
     *
     * @param integer $id
     * @return void
     */
    public function recordLastLoginDate(int $id);

    /**
     * Record a user last session date.
     *
     * @param integer $id
     * @return void
     */
    public function recordLastSessionDate(int $id);
}
