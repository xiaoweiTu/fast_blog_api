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
            $table->integer('tag_id')->nullable(false)->default(0)->index();
            $table->tinyInteger('status')->unsigned()->nullable(false)->default(0)->comment(" -1 隐藏 0 正常");
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
