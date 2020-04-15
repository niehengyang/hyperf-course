<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Model\Permission;
use App\Service\RedisTree;

class PermissionController extends BaseController
{
    //分页列表
    public function list()
    {
        $pageSize = $this->request->input('pageSize',10);

        $permissions = Permission::rootNode()->paginate((integer)$pageSize);

        return $this->paginater($permissions);
    }

    //所有
    public function all(){

        $type = $this->request->input('type',false);
        if (false == $type){
            return $this->failed('参数不足（type）');
        }
        $permissions = Permission::type($type)->get();

        return $this->success($permissions);

    }

    //获取菜单权限
    public function getMenuTree(){
        $tree = new RedisTree();

        $permnissions = $tree->getTree(0);

        return $this->success($permnissions);
    }

    //删除菜单树
    public function cleanTree(){
        Permission::cleanTree();
        return $this->success('');
    }

    //刷新菜单树
    public function refreshTree(){
        Permission::refreshTree();
        return $this->success('');
    }

    //获取权限节点
    public function getPermissionNode(){

        $parentId = $this->request->input('parentId',false);

        if (false == $parentId){
            return $this->failed('参数不足（parent_id）');
        }

        $permissionNodes = Permission::parentId($parentId)->get();

        return $this->success($permissionNodes);
    }

    //创建权限
    public function create(){

        $type = $this->request->input('type',false);

        Permission::query()->create($this->request->all());

        if ($type == 'nav'){
            Permission::refreshTree();
        }

        return $this->success('','创建成功');

    }

    //删除节点
    public function deletePermissionNode($id){

        $permission = Permission::find($id);
        if (is_null($permission)) return true;

        if ($permission->hasChildren){

            $permissionNodes = Permission::parentId($id)->get();
            foreach ($permissionNodes as $node){
                $this->deletePermissionNode($node['id']);
            }
        }
        $permission->delete();
    }
}
