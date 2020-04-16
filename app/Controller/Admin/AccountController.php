<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Model\User;
use App\Service\CryptoDecrypt;

class AccountController extends BaseController
{

    public function index()
    {

        $pageSize = $this->request->input('pageSize',10);

        $users = User::limitOnly($this->currentUser)->paginate((integer)$pageSize);

        return $this->paginater($users);
    }


    //创建
    public function create(){

        $password = $this->request->input('password',false);
        $adminPassword = CryptoDecrypt::cryptoJsAesDecrypt($password);
        $roles = $this->request->input('roles',false);

        $user = new User($this->request->all());
        $user->create_by = $this->currentUser['id'];
        $user->password = password_hash($adminPassword,PASSWORD_DEFAULT);
        $user->save();

        $user->assignRole($roles);

        return $this->success('','创建成功');
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