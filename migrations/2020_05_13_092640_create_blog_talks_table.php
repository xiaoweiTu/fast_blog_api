<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateBlogTalksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_talks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('article_id')->nullable(false)->default(0)->index();
            $table->unsignedInteger('user_id')->nullable(false)->default(0)->index();
            $table->string('content')->nullable(false)->default('');
            $table->unsignedInteger('to_user_id')->nullable(false)->default(0)->index();
            $table->unsignedTinyInteger('is_delete')->nullable(false)->default(0);
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
        Schema::dropIfExists('blog_talks');
    }
}
