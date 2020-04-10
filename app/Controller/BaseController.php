<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface as Request;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;


class BaseController
{
    /**
     * @Inject
     *
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @Inject
     *
     * @var Request
     */
    protected $request;
    /**
     * @Inject
     *
     * @var ResponseInterface
     */
    protected $response;
    /**
     * @Inject
     *
     * @var ServerRequestInterface
     */
    protected $serverResponse;

    /**
     * 请求成功
     *
     * @param        $data
     * @param string $message
     *
     * @return array
     */
    public function success($data, $message = 'success')
    {
        $code = $this->response->getStatusCode();
        return ['msg' => $message, 'code' => $code, 'data' => $data];
    }
    /**
     * 请求失败.
     *
     * @param string $message
     *
     * @return array
     */
    public function failed($message = 'Request format error!')
    {
        return ['msg' => $message, 'code' => 500, 'data' => ''];
    }
}
