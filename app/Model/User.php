<?php

declare (strict_types=1);
namespace App\Model;

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


    /**
     * The table relations.
     *
     * @var array
     */
    public function roles(){
        return $this->belongsToMany(Role::class, 'user_has_roles', 'user_id', 'role_id');
    }

    public function permissions(){
        return $this->belongsToMany(Role::class, 'user_has_permissions', 'user_id', 'permission_id');
    }

    /**
     *建立关系
     *
     **/
    public function scopeAssignRole($query,array $roles){

        $roleIds = [];
        $permissionIds = [];
        foreach ($roles as $role){
            array_push($roleIds,$role['id']);
            $pIds = Role2permission::where('role_id',$role['id'])->pluck('permission_id')->toArray();
            $permissionIds = array_merge($permissionIds,$pIds);

        }

        $this->roles()->attach($roleIds);

        if (count($permissionIds)){
            $permissionIds = array_unique($permissionIds);
            $permissionIds = array_values($permissionIds);
            $this->permissions()->attach($permissionIds);
        }

        return true;
    }
}