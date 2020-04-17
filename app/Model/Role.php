<?php

declare (strict_types=1);
namespace App\Model;
use Hyperf\Database\Model\Events\Retrieved;
use Hyperf\Database\Model\Events\Updating;

/**
 */
class Role extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
        'description',
        'guard_name',
        'create_by'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer'];


    protected $with = ['admin'];


//    //查询事件
//    public function retrieved(Retrieved $event)
//    {
//        $permissionIds =  Role2permission::where('role_id',$this->id)->pluck('permission_id')->toArray();
//
//        $this['permissions'] = $permissionIds;
//    }
//
//    //更新事件
//    public function updating(Updating $event)
//    {
//        unset($this['permissions']);
//    }


    /**
     * The table relations.
     *
     * @var array
     */
    public function permissions(){
        return $this->belongsToMany(Permission::class,'role_has_permissions', 'role_id',
            'permission_id','id','id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'user_has_roles',
            'role_id', 'user_id','id','id');
    }

    public function admin(){
        return $this->belongsTo(User::class,'create_by','id');
    }


    /**
     * 权限限制
     * @param $query
     * @param $user
     * @return {*}
     **/
    public function scopeLimitOnly($query,$user){
        return $query->where('create_by',$user->id);
    }


    /**
     * 权限关联
     * @param array $permissions
     * @param $query
     * @return bool
     **/
    public function scopeAssignPermissions($query,array $permissions){

        $this->permissions()->attach($permissions);
//        $this->permissions()->sync($permissions);

        return true;
    }

}