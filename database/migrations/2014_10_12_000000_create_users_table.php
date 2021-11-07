<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone_number')->nullable();
            $table->text('postal_address')->nullable();
            $table->string('role')->default('Customer')->comment('Customer, Admin');
            $table->string('status')->default('Active')->comment('Disable, Active');
            $table->boolean('subscribe')->default(0);
            $table->timestamp('last_login_date')->nullable();
            $table->timestamp('last_session_date')->nullable();
            $table->boolean('flag_force_reset_password')->default(0);
            $table->boolean('two_factor_auth_enabled')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
