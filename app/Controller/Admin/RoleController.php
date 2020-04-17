<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Model\Permission;
use App\Model\Role;

class RoleController extends BaseController
{
    //分页列表
    public function index()
    {
        $pageSize = $this->request->input('pageSize',10);

        $roles = Role::limitOnly($this->currentUser)->paginate((integer)$pageSize);

        return $this->paginater($roles);
    }


    /**
     * 获取单个角色
     * @param int $id
     *
     * @return $permission
     **/
    public function item($id){

        $role = Role::findOrFail($id);

        return $this->success($role);
    }

    //创建
    public function create(){

        $permissions = $this->request->input('permissions',false);

        if (!$permissions){
            return $this->failed("缺少参数（permissions）");
        }

        $role = new Role($this->request->all());
        $role->create_by = $this->currentUser['id'];
        $role->save();

        $role->assignPermissions($permissions);

        return $this->success('','创建成功');
    }

     //编辑
    public function edit($id){

        $permissions = $this->request->input('permissions',false);

        if (!$permissions){
            return $this->failed("缺少参数（permissions）");
        }

        $role = Role::findOrFail($id);

        $role->update($this->request->all());

        $role->assignPermissions($permissions);

        return $this->success('','编辑成功');
    }

    //删除
    public function delete($id){

        $role = Role::findOrFail($id);

        $role->users()->sync([]);
        $role->permissions()->sync([]);

        if ($role->delete()){
            return $this->success('','删除成功');
        };
    }
}
