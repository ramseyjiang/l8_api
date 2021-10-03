<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Contracts\Repositories\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
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
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Find an user entity by email
     *
     * @param string $email
     * @return mixed
     */
    public function findByEmail(string $email)
    {
        return $this->model
            ->where('email', $email)
            ->where('status', User::STATUS_ACTIVE)
            ->first();
    }

    /**
     * Find an register user's id by email
     *
     * @param string $email
     * @return id
     */
    public function findIdByEmail(string $email)
    {
        return $this->model
            ->where('email', $email)
            ->pluck('id')
            ->first();
    }

    /**
     * Disable a user login.
     *
     * @param integer $id
     * @return void
     */
    public function disable(int $id)
    {
        $user = $this->model->find($id);
        $user->status = User::STATUS_DISABLED;

        return $user->save();
    }

    /**
     * Active a user after first login.
     * The user status should be changed from disabled and pending to active.
     *
     * @param integer $id
     * @return void
     */
    public function active(int $id)
    {
        $user = $this->model->whereIn('status', [User::STATUS_PENDING, User::STATUS_DISABLED, User::STATUS_ACTIVE])->find($id);
        $user->status = User::STATUS_ACTIVE;

        return $user->save();
    }
}
