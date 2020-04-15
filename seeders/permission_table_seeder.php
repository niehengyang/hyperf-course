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
                'index_value' => 'home',
                'parent_id' => 0,
                'type' => 'nav',
                'route' => '/home',
                'name' => '首页',
                'display_name' => '首页',
                'icon' => 'el-icon-s-home',
                'sort' => 2,
                'guard_name' => 'web'
            ],
            [
                'id' => 2,
                'index_value' => 'demo',
                'parent_id' => 0,
                'type' => 'nav',
                'route' => '/demo',
                'name' => 'demo',
                'display_name' => 'demo',
                'icon' => 'el-icon-star-off',
                'sort' => 1,
                'guard_name' => 'web'
            ],
            [
                'id' => 3,
                'index_value' => 'demoCharts',
                'parent_id' => 2,
                'type' => 'nav',
                'route' => '/demo/charts',
                'name' => '图表',
                'display_name' => '图表',
                'icon' => 'el-icon-s-data',
                'sort' => 1,
                'guard_name' => 'web'
            ],
            [
                'id' => 4,
                'index_value' => 'system',
                'parent_id' => 0,
                'type' => 'nav',
                'route' => '/system',
                'name' => '系统管理',
                'display_name' => '系统管理',
                'icon' => 'el-icon-setting',
                'sort' => 1,
                'guard_name' => 'web'
            ],
            [
                'id' => 5,
                'index_value' => 'userList',
                'parent_id' => 4,
                'type' => 'nav',
                'route' => '/user/list',
                'name' => '管理员列表',
                'display_name' => '管理员列表',
                'icon' => 'el-icon-user-solid',
                'sort' => 10,
                'guard_name' => 'web'
            ], [
                'id' => 6,
                'index_value' => 'roleList',
                'parent_id' => 4,
                'type' => 'nav',
                'route' => '/role/list',
                'name' => '角色管理',
                'display_name' => '角色管理',
                'icon' => 'el-icon-s-custom',
                'sort' => 1,
                'guard_name' => 'web'
            ],
            [
                'id' => 7,
                'index_value' => 'menuList',
                'parent_id' => 4,
                'type' => 'nav',
                'route' => '/menu/list',
                'name' => '菜单管理',
                'display_name' => '菜单管理',
                'icon' => 'el-icon-connection',
                'sort' => 5,
                'guard_name' => 'web'
            ],
            [
                'id' => 8,
                'index_value' => 'menu4',
                'parent_id' => 0,
                'type' => 'nav',
                'route' => 'menu4',
                'name' => '菜单四',
                'display_name' => '菜单四',
                'icon' => 'el-icon-s-grid',
                'sort' => 1,
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

        //放入redis缓存
        Permission::refreshTree();
    }
}
