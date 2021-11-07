<?php

namespace App\Repositories;

use App\Models\User;
use App\Contracts\UserContract;

/**
 * It is a tricky for observer.
 * If uses this way $this->model->where('email', $email)->update(['status'=>'xxx']); to update, it will update table.
 * But, it won't trigger the observer.
 * Only uses "$user->save()", it will trigger the observer.
 */
class UserRepository extends BaseRepository implements UserContract
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
    public function findUserByEmail(string $email)
    {
        return $this->model
            ->where('email', $email)
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
     * It is only used by the nova admin.
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
     * Make a status which is disabled to active.
     * The user status should be changed from disabled to active.
     *
     * @param integer $id
     * @return void
     */
    public function active(int $id)
    {
        $user = $this->model->where('status', User::STATUS_DISABLED)->find($id);
        $user->status = User::STATUS_ACTIVE;

        return $user->save();
    }

    /**
     * Record a user last login date.
     *
     * @param integer $id
     * @return void
     */
    public function recordLastLoginDate(int $id)
    {
        $user = $this->model->find($id);
        $user->last_login_date = now();

        return $user->save();
    }

    /**
     * Record a user last login date.
     *
     * @param integer $id
     * @return void
     */
    public function recordLastSessionDate(int $id)
    {
        $user = $this->model->find($id);
        $user->last_session_date = now();

        return $user->save();
    }
}
