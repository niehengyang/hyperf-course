<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Controller\BaseController;
use App\Model\User;
use App\Service\CryptoDecrypt;
use Hyperf\Config\Config;
use Hyperf\Redis\Redis;
use Phper666\JwtAuth\Jwt;
use Hyperf\Di\Annotation\Inject;

class AuthController extends BaseController
{

    /**
     * @Inject
     *
     * @var Jwt
     */
    protected $jwt;

    /**
     * 用户登录.
     *
     * @return array
     */
    public function login()
    {

        $username = $this->request->input('username',false);
        $password = $this->request->input('password',false);

        $user = User::query()->where('username', $username)->first();

        $adminPassword = CryptoDecrypt::cryptoJsAesDecrypt($password);


        //验证用户账户密码
        if  (!empty($user->password) && password_verify($adminPassword, $user->password))  {
            $userData = [
                'uid'       => $user->id,
                'account'  => $user->username,
            ];

            $service = $this->serverResponse->getServerParams();

            $token = $this->jwt->getToken($userData);
            $token_exp = $this->jwt->getTTL();
            $data  = [
                'uid'       => $user->id,
                'username'  => $user->username,
                'token' => (string) $token,
                'exp'   => $token_exp,
                'service' => $service['remote_addr']
            ];

            $user->lastloginip = $service['remote_addr'];
            $user->lastlogintime = date('Y-m-d H:i:s', $service['request_time']);
            $user->token_value = (string) $token;
            $user->token_exp = $token_exp;

            $user->save();

            return $this->success($data);
        }

        return $this->failed('用户名或密码错误');
    }

    /**
     *验证码获取
     *
     **/
    public function getCaptcha(){


//        $sessionId = $this->request->cookie('_session');
//
//        Redis::setEx("captcha{$sessionId}",5*60,$capture['key']);


        $data =  '';
//            [
//
//            'url' => $capture['img']
//        ];
        return $this->success($data);
    }
}
