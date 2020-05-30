<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exception\WrongRequestException;
use App\Services\MailService;
use Hyperf\Utils\Context;
use Hyperf\Utils\Str;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = Context::get(ResponseInterface::class);

        $whiteList = [
            'http://localhost:8080'
        ];

        $origin = $request->getHeader('origin');

        if (empty($origin)) {
            throw new WrongRequestException("非法来源请求!");
        }
        $origin = $origin[0];

        if (in_array($origin,$whiteList)) {
            $response = $response->withHeader('Access-Control-Allow-Origin',$origin)
                ->withHeader('Access-Control-Allow-Credentials', 'true')
                // Headers 可以根据实际情况进行改写。
                ->withHeader('Access-Control-Allow-Headers', 'DNT,Keep-Alive,User-Agent,Cache-Control,Content-Type,Authorization,token')
                ->withHeader('Allow', 'GET,HEAD,POST,PUT,DELETE,TRACE,OPTIONS,PATCH')
                ->withHeader('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,OPTIONS');
        }
        Context::set(ResponseInterface::class, $response);

        if ($request->getMethod() == 'OPTIONS') {
            return $response;
        }

        return $handler->handle($request);
    }
}
