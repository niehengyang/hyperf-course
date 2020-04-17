<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Model\Permission;
use App\Model\User;
use App\Service\CryptoDecrypt;

class AccountController extends BaseController
{

    public function index()
    {
        $currentUser =  $this->request->getAttribute('user');
        $pageSize = $this->request->input('pageSize',10);

        $users = User::limitOnly($currentUser)->paginate((integer)$pageSize);

        return $this->paginater($users);
    }


    //账号信息
    public function item($id){

        $account = User::findOrFail($id);

        return $this->success($account);
    }

    //创建
    public function create(){
        $currentUser =  $this->request->getAttribute('user');
        $password = $this->request->input('password',false);
        $adminPassword = CryptoDecrypt::cryptoJsAesDecrypt($password);
        $roles = $this->request->input('roles',false);

        $user = new User($this->request->all());
        $user->create_by = $currentUser['id'];
        $user->password = password_hash($adminPassword,PASSWORD_DEFAULT);
        $user->save();

        $user->assignRole($roles);

        //放入redis缓存
        Permission::refreshTree($user->id);

        return $this->success('','创建成功');
    }


    //编辑
    public function edit($id){

        $roles = $this->request->input('roleIds',false);

        $user = User::findOrFail($id);

        $user->update($this->request->all());

        $user->assignRole($roles);

        //放入redis缓存
        Permission::refreshTree($user->id);

        return $this->success('','修改成功');
    }

    //删除
    public function delete($id){

       $user = User::findOrFail($id);

        $user->roles()->sync([]);
        $user->permissions()->sync([]);

       if ($user->delete()){
           return $this->success('','删除成功');
       };
    }



}
