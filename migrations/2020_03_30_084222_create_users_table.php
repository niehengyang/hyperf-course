<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('唯一主键');
            $table->string('username',20)->unique()->comment('用户名');
            $table->string('password',120)->comment('密码');
            $table->string('name',20)->comment('姓名');
            $table->string('phone',12)->comment('电话');
            $table->string('email',100)->nullable()->comment('用户的电子邮箱地址');
            $table->integer('province')->nullable()->comment('省级区域码');
            $table->integer('city')->nullable()->comment('市级区域码');
            $table->integer('county')->nullable()->comment('区县级区域码');
            $table->string('status',2)->default(1)->comment('用户账号的状态，0：禁用，1：正常，默认为1，正常');

            $table->string('desc',500)->nullable()->comment('用户账号的描述信息');
            $table->text('token_value')->nullable()->comment('用户登录维护token');
            $table->timestamp('lastlogintime')->nullable()->comment('最后登录时间戳');
            $table->string('lastloginip',50)->nullable()->comment('最后登录处的ip地址');
            $table->string('token_exp',50)->nullable()->comment('用户token过期时间(秒)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
