<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('唯一主键');
            $table->integer('parent_id')->comment('父级节点');
            $table->string('name',100)->comment('名称');
            $table->string('display_name', 50)->comment('显示名称');
            $table->string('url', 255)->comment('路由');
            $table->string('guard_name',50)->nullable()->comment('看守');
            $table->string('icon',50)->nullable()->comment('图标');
            $table->string('status',2)->default(1)->comment('状态,0禁用，1正常');
            $table->smallInteger('sort')->nullable()->comment('排序，数字越大越在前面');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
}
