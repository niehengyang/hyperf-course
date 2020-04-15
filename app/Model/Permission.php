<?php

declare (strict_types=1);
namespace App\Model;

use App\Service\RedisTree;
use Hyperf\Database\Model\Events\Retrieved;

/**
 */
class Permission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'index_value',
        'parent_id',
        'name',
        'type',
        'level',
        'display_name',
        'route',
        'guard_name',
        'icon',
        'status',
        'sort'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    //查询事件
    public function retrieved(Retrieved $event)
    {
        $childCount = self::where('parent_id',$this->id)->count();
        if ($childCount){
            $this['hasChildren'] = true;
        }else{
            $this['hasChildren'] = false;
        }
    }

    /**
     * The table relations.
     *
     * @var array
     */
    public function roles(){
        return $this->belongsToMany(Role::class,'role_has_permissions','permission_id','role_id');
    }


    public function scopeRootNode($query){
        return $query->where('parent_id',0);
    }


    //筛选菜单类型权限
    public function scopeType($query,$type = false){
        if (!$type) return $query;
        return $query->where('type',$type);
    }

    //从父ID筛选
    public function scopeParentId($query,$id){
        return $query->where('parent_id',$id);
    }

    /**
     * The cache backup in redis.
     *
     * @var array
     */
    public static function setRedisBak(){

        $permissions = self::type('nav')->get();
        $redisTree = new RedisTree();
        foreach ($permissions as $permission){
            if ($permission->parent_id == 0){
                $redisTree->setRootTree($permission->id,$permission->toArray());
                $redisTree->addNode($permission->parent_id,$permission->id,$permission->toArray());
            }else{
                $redisTree->addNode($permission->parent_id,$permission->id,$permission->toArray());
            }
        }
    }


    /**
     * 删除所有缓存菜单
     *
     * @return  bool
     */
    public static function cleanTree(){
        $tree = new RedisTree();
        $tree->cleanTree();

        return true;
    }


    /**
     * 刷新所有菜单
     *
     * @return  bool
     */
    public static function refreshTree(){

        $tree = new RedisTree();
        $tree->cleanTree();
        Permission::setRedisBak();

        return true;
    }
}