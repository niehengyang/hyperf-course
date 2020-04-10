<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreatePermissionRelationTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('user_has_permissions', function (Blueprint $table){
            $table->bigIncrements('id')->comment('唯一主键');
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'user_id'], 'user_has_permissions_permission_id_user_id_primary');
        });


        Schema::create('user_has_roles', function (Blueprint $table){
            $table->bigIncrements('id')->comment('唯一主键');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->primary(['role_id', 'user_id'], 'user_has_roles_role_id_user_id_primary');
        });

        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('唯一主键');
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('user_has_permissions');
        Schema::dropIfExists('user_has_roles');
    }
}
