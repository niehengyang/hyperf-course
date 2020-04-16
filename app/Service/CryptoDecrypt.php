<?php
/**
 * Created by PhpStorm.
 * User: NHY
 * Date: 2020/4/16
 * Time: 14:59
 */

namespace App\Service;

class CryptoDecrypt{


    /**向量
     * @var string
     */
    const IV = "1234567890123456";//16位
    const KEY = "1234567890654321";//16位

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
}