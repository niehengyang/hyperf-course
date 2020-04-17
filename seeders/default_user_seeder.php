<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use \Hyperf\DbConnection\Db as DB;
use \Hyperf\Utils\Str;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $result = \App\Model\User::destroy([1]);

        DB::table('users')->insert([

            'id' => 1,
            'username' => '13999999999',
            'password' => password_hash('123456',PASSWORD_DEFAULT),
            'name' => 'admin',
            'email' => '790227542@qq.com',
            'phone' => '17387916289',
            'desc' => 'Root账号',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
