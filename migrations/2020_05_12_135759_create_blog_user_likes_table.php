<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateBlogUserLikesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_user_likes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index()->nullable(false)->default(0);
            $table->unsignedInteger('article_id')->index()->nullable(false)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_user_likes');
    }
}
