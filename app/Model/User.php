<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
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
}