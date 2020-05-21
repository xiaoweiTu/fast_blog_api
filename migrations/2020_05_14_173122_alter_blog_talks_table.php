<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class AlterBlogTalksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('blog_talks', function (Blueprint $table) {
            $table->unsignedBigInteger('to_id')->nullable(false)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_talks', function (Blueprint $table) {
            $table->dropColumn('to_id');
        });
    }
}
