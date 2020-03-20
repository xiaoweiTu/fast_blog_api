<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 30)->nullable(false)->unique()->default('');
            $table->string('email')->nullable(false)->unique()->default('');
            $table->string('password')->nullable(false)->default('');
            $table->tinyInteger('is_admin')->nullable(false)->default(0);
            $table->tinyInteger('status')->unsigned()->nullable(false)->default(0);
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
