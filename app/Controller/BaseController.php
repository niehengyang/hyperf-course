<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface as Request;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Paginator\Paginator;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;
use Swoole\Coroutine\Http\Client\Exception;


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
     * @return []
     */
    public function success($data,$message = 'success')
    {
        return ['msg' => $message, 'code' => 200, 'data' => $data];
    }
    /**
     * 请求失败.
     *
     * @param string $message
     *
     * @return []
     */
    public function failed($message = 'Request format error!')
    {
        return ['msg' => $message, 'code' => 500, 'data' => ''];
    }


    /**
     * 分页器.
     *
     * @param  $data
     * @param  $pageSize
     *
     * @return $data
     */
    public function paginater($data)
    {

        $data = [
            'data' => $data->items(),
            'paginate' => [
                'current_page' => $data->currentPage(),
                'page_size' => $data->perPage(),
                'total' => $data->total(),
            ]
        ];

        return $data;
    }
}
