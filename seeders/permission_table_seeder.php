<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use App\Model\User;
use App\Model\Role;
use App\Model\Permission;
use Hyperf\DbConnection\Db as DB;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Permission::destroy([1, 2, 3, 4, 5, 6, 7, 8]);
        Role::destroy([1]);

        DB::table('permissions')->insert([
            [
                'id' => 1,
                'parent_id' => 0,
                'url' => '/home',
                'name' => '首页',
                'display_name' => '首页',
                'icon' => 'el-icon-s-home',
                'guard_name' => 'web'
            ],
            [
                'id' => 2,
                'parent_id' => 0,
                'url' => '/demo',
                'name' => 'demo',
                'display_name' => 'demo',
                'icon' => 'el-icon-star-off',
                'guard_name' => 'web'
            ],
            [
                'id' => 3,
                'parent_id' => 2,
                'url' => '/demo/charts',
                'name' => '图表',
                'display_name' => '图表',
                'icon' => 'el-icon-s-data',
                'guard_name' => 'web'
            ],
            [
                'id' => 4,
                'parent_id' => 0,
                'url' => '/system',
                'name' => '系统管理',
                'display_name' => '系统管理',
                'icon' => 'el-icon-setting',
                'guard_name' => 'web'
            ],
            [
                'id' => 5,
                'parent_id' => 4,
                'url' => '/user/list',
                'name' => '管理员列表',
                'display_name' => '管理员列表',
                'icon' => 'el-icon-user-solid',
                'guard_name' => 'web'
            ], [
                'id' => 6,
                'parent_id' => 4,
                'url' => '/role/list',
                'name' => '角色管理',
                'display_name' => '角色管理',
                'icon' => 'el-icon-s-custom',
                'guard_name' => 'web'
            ],
            [
                'id' => 7,
                'parent_id' => 4,
                'url' => '',
                'name' => '菜单管理',
                'display_name' => '菜单管理',
                'icon' => 'el-icon-connection',
                'guard_name' => 'web'
            ],
            [
                'id' => 8,
                'parent_id' => 0,
                'url' => '',
                'name' => '菜单四',
                'display_name' => '菜单四',
                'icon' => 'el-icon-s-grid',
                'guard_name' => 'web'
            ]
        ]);

        DB::table('roles')->insert([
            'id' => 1,
            'name' => '超级管理员',
            'guard_name' => 'web'
        ]);
        $role = Role::findOrFail(1);
        $role->permissions()->sync([1, 2, 3, 4, 5, 6, 7, 8]);
        $user = User::where('id', 1)->first();
        $user->assignRole([$role]);
    }
}
