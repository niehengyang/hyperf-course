<?php

declare (strict_types=1);
namespace App\Model;
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
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];


    /**
     * The table relations.
     *
     * @var array
     */
    public function permissions(){
        return $this->belongsToMany(Permission::class,'role_has_permissions','role_id','permission_id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'user_has_roles', 'role_id', 'user_id');
    }

}