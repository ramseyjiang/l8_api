<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use App\Models\User;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Login the given user or create the first if none supplied.
     *
     * @param $user
     *
     * @return User
     */
    public function loginAsUser()
    {
        $user = User::first();
        $this->actingAs($user, 'sanctum');

        return $user;
    }
}
