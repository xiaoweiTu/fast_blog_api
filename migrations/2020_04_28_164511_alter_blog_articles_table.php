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
            $table->unsignedTinyInteger('editor_type')->nullable(false)->default(0)->comment('编辑器类型  0:markdown,1:富文本');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_articles', function (Blueprint $table) {
            //
            $table->dropColumn('editor_type');
        });
    }
}
