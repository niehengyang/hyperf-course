<?php
/**
 * Created by PhpStorm.
 * User: NHY
 * Date: 2020/4/13
 * Time: 10:26
 */

namespace App\Service;
use Swoole\Coroutine\Redis;

class RedisTree {


    /**
     *$var Redis
     */
    private $redis;

    protected $rootKeyPre;
    protected $nodeKeyPre;

    public function __construct($tagId = false)
    {
        if ($tagId){
            $this->rootKeyPre = "menuTreeRoot{$tagId}:";
            $this->nodeKeyPre = "menuTreeNode{$tagId}:";
        }else{
            $this->rootKeyPre = "menuTreeRoot";
            $this->nodeKeyPre = "menuTreeNode";
        }

        $this->redis = new Redis();
        $this->redis->connect(env('REDIS_HOST'), env('REDIS_PORT'));
    }

    /**
     *@param int $id //ID
     * @param array $data //数据
     *
     * @return bool
     **/
    public function setRootTree(int $id,array $data){

        if(is_null($data)){
            return true;
        }

        $this->redis->set( $this->rootKeyPre.$id,json_encode($data,true));

        return true;
    }


    /**
     *@param integer $perId //父ID
     *@param integer $id //节点ID
     *@param array $data //数据
     *
     * @return bool
     **/
    public function addNode(int $perId ,int $id,array $data){

        //节点当前数据
        $value = $this->redis->get($this->nodeKeyPre.$perId);

        if (!$value){
            $value = [];
        }else{
            $value = json_decode($value,true);
        }

        //增加
        $value[$id] = $data;

        //保存
        $this->redis->set($this->nodeKeyPre.$perId,json_encode($value,true));

        return true;
    }


    /**
     *@param integer $perId //父ID
     *@param integer $id //节点ID
     * @param array $data //数据
     *
     * @return bool
     **/
    public function updateNode(int $perId ,int $id,array $data){

        //节点当前数据
        $value = $this->redis->get($this->nodeKeyPre.$perId);

        if (!$value){
            $value = [];
        }else{
            $value = json_decode($value,true);
        }

        //修改
        $value[$id] = $data;

        //保存
        $this->redis->set($this->nodeKeyPre.$perId,json_encode($value,true));

        return true;
    }


    /**
     *@param integer $perId //父ID
     *
     * @return $result
     **/
    public function getNodes(int $perId){

        //节点当前数据
        $nodes = $this->redis->get($this->nodeKeyPre.$perId);
        if (is_null($nodes)) return [];
        $nodes = json_decode($nodes,true);

        //排序
        $result = $this->sortValue($nodes);

        return $result;
    }


    /**
     * @param integer $id //ID
     * @return $arrayData
     **/
    public function getTree(int $id){

        //节点当前数据
        $nodes = $this->redis->get($this->nodeKeyPre.$id);

        if (is_null($nodes)) return [];
        $nodes = json_decode($nodes,true);

        $arrayData = $this->sortValue($nodes);

        foreach ($arrayData as $key => $node){

            $children = $this->getTree($node['id']);
            if (count($children) == 0) continue;

            $node['children'] = $children;
            $arrayData[$key] = $node;
        }

        return $arrayData;
    }

    /**
     * 排序
     * @param array $data
     **/
    public function sortValue(array $data){
        $arrayData = array_values($data);
        return $arrayData;
    }

    /**
     *@param integer $id //ID
     *
     * @return bool
     **/
    public function deleteNodes(int $id){

        //节点当前数据
        $key = $this->rootKeyPre.$id;

        $value = $this->redis->exists($key);
        if (!$value) return true;

        $this->redis->del($key);

        return true;
    }


    /**
     * @return bool
     **/
    public function cleanTree(){

        $rootKeys = $this->redis->getKeys($this->rootKeyPre.'*');
        $nodeKeys = $this->redis->getKeys($this->nodeKeyPre.'*');
        $keys = array_merge($rootKeys,$nodeKeys);

         $this->redis->del($keys);

         return true;
    }

}