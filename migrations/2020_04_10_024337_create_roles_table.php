<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('唯一主键');
            $table->string('name')->comment('角色名称');
            $table->string('status',2)->default(1)->comment('角色的状态，0：禁用，1：正常，默认为1，正常');
            $table->string('description', 200)->nullable()->comment('描述');
            $table->string('guard_name',50)->nullable()->comment('警卫');
            $table->integer('create_by')->default(0)->comment('创建人');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
}
