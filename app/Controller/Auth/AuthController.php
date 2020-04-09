<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Controller\BaseController;
use App\Model\User;
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


    /**向量
     * @var string
     */
    const IV = "1234567890123456";//16位
    const KEY = "1234567890654321";//16位

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

        $adminPassword = self::cryptoJsAesDecrypt($password);


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
     * 解密字符串
     * @param string $str 字符串
     * @return string
     */
    public static function cryptoJsAesDecrypt($str){
        $str = str_replace(' ','+',$str);

        $jsondata = openssl_decrypt($str, 'aes-128-cbc', self::KEY, OPENSSL_ZERO_PADDING , self::IV);
        return trim($jsondata);
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
