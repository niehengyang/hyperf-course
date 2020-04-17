<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\Events\Retrieved;
use Hyperf\Database\Model\Events\Updating;

/**
 */
class User extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'name',
        'phone',
        'email',
        'create_by',
        'province',
        'city',
        'county',
        'status',
        'desc',
        'lastlogintime',
        'lastloginip',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer'];

    /**
     * The attributes that should be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'token_value',
        'token_exp'
    ];

    protected $with = ['roles'];

    /**
     * The table relations.
     *
     * @var array
     */
    public function roles(){
        return $this->belongsToMany(Role::class, 'user_has_roles',
            'user_id', 'role_id');
    }

    public function permissions(){
        return $this->belongsToMany(Role::class, 'user_has_permissions',
            'user_id', 'permission_id');
    }


        //查询事件
    public function retrieved(Retrieved $event)
    {
        $roleIds =  User2role::where('user_id',$this->id)->pluck('role_id')->toArray();

        $this['roleIds'] = $roleIds;
    }

    //更新事件
    public function updating(Updating $event)
    {
        unset($this['roleIds']);
    }

    /**
     *权限限制
     **/
    public function scopeLimitOnly($query,$user){
        return $query->where('create_by',$user->id)->orWhere('id',$user->id);
    }

    /**
     *建立关系
     *
     **/
    public function scopeAssignRole($query,array $roles){

        $permissionIds = [];
        foreach ($roles as $roleId){
            $pIds = Role2permission::where('role_id',$roleId)->pluck('permission_id')->toArray();
            $permissionIds = array_merge($permissionIds,$pIds);

        }

//        $this->roles()->attach($roles);
        $this->roles()->sync($roles);

        if (count($permissionIds)){
            $permissionIds = array_unique($permissionIds);
            $permissionIds = array_values($permissionIds);
//            $this->permissions()->attach($permissionIds);
            $this->permissions()->sync($permissionIds);
        }

        return true;
    }
}