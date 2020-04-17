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

    /**
     * 获取单个菜单
     * @param int $id
     *
     * @return $permission
     **/
    public function item(int $id){

        $permission = Permission::findOrFail($id);

        return $this->success($permission);
    }


    /**
     * 编辑菜单
     * @param int $id
     *
     * @return {*}
     **/
    public function edit(int $id){

        $permission = Permission::findOrFail($id);

        $permission->update($this->request->all());

        if ($permission->type == 'nav'){
            Permission::refreshTree();
        }

        return $this->success('','编辑成功');
    }


    /**
     * 获取菜单权限树
     *
     **/
    public function getMenuTree(){

        $tree = new RedisTree($this->currentUser->id);

        $permnissions = $tree->getTree(0);

        return $this->success($permnissions);

    }

    /**
     * 删除菜单树
     * @param $tagId
     * @return {*}
     **/
    public function cleanTree($tagId = false){

        Permission::cleanTree($tagId);

        return $this->success('','清除成功');

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

        Permission::query()->create($this->request->all());


        return $this->success('','创建成功');

    }

    /**
     * 删除节点
     * @param int $id
     * @return {*}
     **/
    public function deletePermissionNode(int $id){

        $permission = Permission::find($id);
        if (is_null($permission)) return true;

        $permission->delete();
        if ($permission->hasChildren){
            $permissionNodes = Permission::parentId($id)->get();
            foreach ($permissionNodes as $node){

                $this->deletePermissionNode($node['id']);
            }
        }

        //重建导航
        if ($permission->type == 'nav'){
            Permission::refreshTree($this->currentUser->id);
        }

        return $this->success('','删除成功');
    }
}
