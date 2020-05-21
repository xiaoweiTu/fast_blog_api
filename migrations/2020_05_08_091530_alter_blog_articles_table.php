<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class AlterBlogArticlesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('blog_articles', function (Blueprint $table) {
            $table->unsignedTinyInteger('type')->nullable(false)->default(0)->comment("0 普通 1 需登录");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_articles', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
