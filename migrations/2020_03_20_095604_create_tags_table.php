<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',30)->unique()->nullable(false)->default('');
            $table->tinyInteger('type')->unsigned()->nullable(false)->default(0)->comment("类型 0 普通 1 系列");
            $table->tinyInteger('status')->unsigned()->nullable(false)->default(0)->comment("类型 0 正常 -1 隐藏 ");
            $table->tinyInteger('level')->unsigned()->nullable(false)->default(0);
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
        Schema::dropIfExists('tags');
    }
}
