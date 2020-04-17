<?php

declare (strict_types=1);
namespace App\Model;

use App\Service\RedisTree;
use Hyperf\Database\Model\Events\Retrieved;
use Hyperf\Database\Model\Events\Updating;

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
    protected $casts = ['id' => 'integer','parent_id'=> 'integer'];


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

    //更新事件
    public function updating(Updating $event)
    {
        unset($this['hasChildren']);
    }


    /**
     * The table relations.
     *
     * @var array
     */
    public function roles(){
        return $this->belongsToMany(Role::class,'role_has_permissions','permission_id','role_id',
            'permission_id','id','id');
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
     * 获取导航权限
     * @param $userId
     * @return  array
     **/
    public function scopeGetNavMenu($query ,$userId = false){

        if ($userId != 1){

            $permissionIds = User2permission::where('user_id',$userId)->pluck('permission_id')->toArray();
            return $query->whereIn('id',$permissionIds)->type('nav')->get();

        }else{

           return $query->type('nav')->get();
        }

    }

    /**
     * The cache backup in redis.
     * @param array $permissions
     * @param $tagId
     * @var array
     */
    public static function setRedisBak($permissions,$tagId = false){

        $redisTree = new RedisTree($tagId);

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
     * @param $tagId
     *
     * @return  bool
     */
    public static function cleanTree($tagId = false){

        $tree = new RedisTree($tagId);
        $tree->cleanTree();

        return true;
    }


    /**
     * 刷新菜单
     * @param $tagId
     * @return  bool
     */
    public static function refreshTree($tagId = false){

        $tree = new RedisTree($tagId);
        $tree->cleanTree();

        $permissions = self::getNavMenu($tagId);

        self::setRedisBak($permissions,$tagId);


        return true;
    }
}