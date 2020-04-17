<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

$middleware = [
    App\Middleware\JwtAuthMiddleware::class,
//    App\Middleware\PermissionMiddleware::class,
];


/**登录**/
Router::post('/user/login', 'App\Controller\Auth\AuthController@login');
//Router::post('/user/register', 'App\Controller\Auth\RegisterController@register');
Router::get('/user/captcha', 'App\Controller\Auth\AuthController@getCaptcha');


//个人资料
Router::addGroup('/user/', function () {
    Router::get('info','App\Controller\Admin\UserController@info');
    Router::post('logout', 'App\Controller\Admin\UserController@logout');
//    Router::get('elasticsearch', 'App\Controller\UserController@elasticsearch');

}, [
    'middleware' => $middleware
]);

//菜单权限
Router::addGroup('/permission/', function () {
    Router::get('list','App\Controller\Admin\PermissionController@list');
    Router::get('all','App\Controller\Admin\PermissionController@all');
    Router::post('create','App\Controller\Admin\PermissionController@create');
    Router::get('menu','App\Controller\Admin\PermissionController@getMenuTree');
    Router::post('clean','App\Controller\Admin\PermissionController@cleanTree');
    Router::post('refresh','App\Controller\Admin\PermissionController@refreshTree');
    Router::get('nodes','App\Controller\Admin\PermissionController@getPermissionNode');
    Router::delete('delete/{id}','App\Controller\Admin\PermissionController@deletePermissionNode');
    Router::get('item/{id}','App\Controller\Admin\PermissionController@item');
    Router::put('edit/{id}','App\Controller\Admin\PermissionController@edit');

}, [
    'middleware' => $middleware
]);

//账号
Router::addGroup('/account/', function () {

    Router::get('list','App\Controller\Admin\AccountController@index');
    Router::post('create','App\Controller\Admin\AccountController@create');
    Router::delete('delete/{id}','App\Controller\Admin\AccountController@delete');
    Router::get('initdata','App\Controller\Admin\UserController@portalInitData');
    Router::get('item/{id}','App\Controller\Admin\AccountController@item');
    Router::put('edit/{id}','App\Controller\Admin\AccountController@edit');
}, [
    'middleware' => $middleware
]);

//角色
Router::addGroup('/role/', function () {

    Router::get('list','App\Controller\Admin\RoleController@index');
    Router::get('item/{id}','App\Controller\Admin\RoleController@item');
    Router::post('create','App\Controller\Admin\RoleController@create');
    Router::delete('delete/{id}','App\Controller\Admin\RoleController@delete');
    Router::put('edit/{id}','App\Controller\Admin\RoleController@edit');

}, [
    'middleware' => $middleware
]);



Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');
