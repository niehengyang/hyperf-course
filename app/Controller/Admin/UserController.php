<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use Phper666\JwtAuth\Jwt;
use Hyperf\Di\Annotation\Inject;

class UserController extends BaseController
{

    /**
     * @Inject()
     * @var Jwt
     */
    protected $jwt;

    /**
     * 获取用户信息
     * @return [type] [description]
     */
    public function info()
    {

        //获取用户数据
        $user = $this->request->getAttribute('user');
        return $this->success($user);

    }

    /**
     * 用户退出
     * @return [type] [description]
     */
    public function logout()
    {

        $user = $this->request->getAttribute('user');

        if  ($this->jwt->logout())  {

            $user->token_value = null;
            $user->token_exp = null;
            $user->save();

            return $this->success('','退出登录成功');
        };
        return $this->failed('退出登录失败');
    }
}
