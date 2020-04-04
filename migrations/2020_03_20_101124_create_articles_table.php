<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',120)->nullable(false)->default('')->unique();
            $table->text('content')->nullable(false);
            $table->string('description')->nullable(false)->default('');
            $table->string('icon')->nullable(false)->default('');
            $table->integer('clicked')->nullable(false)->default(0);
            $table->integer('likes')->nullable(false)->default(0);
            $table->integer('dislikes')->nullable(false)->default(0);
            $table->integer('tag_id')->nullable(false)->default(0)->index();
            $table->tinyInteger('status')->unsigned()->nullable(false)->default(1)->comment(" 1 正常 0  隐藏");
            $table->tinyInteger('level')->unsigned()->nullable(false)->default(0);
            $table->charset   = 'utf8mb4'; //4个字节
            $table->collation = 'utf8mb4_general_ci';
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
}
