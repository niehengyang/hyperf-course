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


/**登录**/
Router::post('/user/login', 'App\Controller\Auth\AuthController@login');
//Router::post('/user/register', 'App\Controller\Auth\RegisterController@register');


//个人资料
Router::addGroup('/user/', function () {
    Router::get('info','App\Controller\Admin\UserController@info');
    Router::post('logout', 'App\Controller\Admin\UserController@logout');
//    Router::get('elasticsearch', 'App\Controller\UserController@elasticsearch');
}, [
    'middleware' => [App\Middleware\JwtAuthMiddleware::class]
]);

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');
