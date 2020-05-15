<?php
/**
 * Created by PhpStorm.
 * User: liyuzhao
 * Date: 2019-08-01
 * Time: 22:32
 */
namespace App\Middleware;

use App\Exception\WrongRequestException;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Phper666\JwtAuth\Jwt;
use Phper666\JwtAuth\Exception\TokenValidException;

class AdminMiddleware implements MiddlewareInterface
{
    /**
     * @var HttpResponse
     */
    protected $response;

    protected $prefix = 'Bearer';

    protected $jwt;

    public function __construct(HttpResponse $response, Jwt $jwt)
    {
        $this->response = $response;
        $this->jwt = $jwt;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $isValidToken = false;
        // 根据具体业务判断逻辑走向，这里假设用户携带的token有效
        $token = $request->getHeader('Authorization')[0] ?? '';
        if (strlen($token) > 0) {
            $token = ucfirst($token);
            $arr = explode($this->prefix . ' ', $token);
            $token = $arr[1] ?? '';
            if (strlen($token) > 0 && $this->jwt->checkToken()) {
                $isValidToken = true;
            }
        }

        if ($isValidToken) {
            $admin = $this->jwt->getParserData($token);
            if ($admin['is_admin'] != 1 ) {
                throw new WrongRequestException("非管理员账号!");
            }
            return $handler->handle($request);
        }

        throw new TokenValidException('Token authentication does not pass', 401);
    }
}
