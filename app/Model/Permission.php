<?php

declare (strict_types=1);
namespace App\Model;

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
    public function roles(){
        return $this->belongsToMany(Role::class,'role_has_permissions','permission_id','role_id');
    }
}