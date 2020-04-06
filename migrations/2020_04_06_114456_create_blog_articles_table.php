<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateBlogArticlesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',120)->nullable(false)->default('')->unique();
            $table->text('content')->nullable(false);
            $table->string('description')->nullable(false)->default('');
            $table->string('icon')->nullable(false)->default('');
            $table->integer('clicked')->nullable(false)->default(0);
            $table->integer('likes')->nullable(false)->default(0);
            $table->integer('tag_id')->nullable(false)->default(0)->index();
            $table->tinyInteger('is_hide')->unsigned()->nullable(false)->default(0);
            $table->tinyInteger('order')->unsigned()->nullable(false)->default(0);
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
        Schema::dropIfExists('blog_articles');
    }
}
