<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Exception\Handler;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpServer\Response;
use Hyperf\Validation\ValidationException;
use Phper666\JwtAuth\Exception\TokenValidException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{


    /**
     * @Inject()
     * @var Response
     */
    protected $response;



    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $data = [
            'code' => 400,
            'msg'  => $throwable->getMessage()
        ];

        if ( $throwable instanceof ValidationException ) {
            $data['msg'] = $throwable->errors();
        }

        logger()->info('请求异常',$data);


        if ( $throwable instanceof TokenValidException ) {
            $data['code'] = 302;
        }

        $this->stopPropagation();

        return $this->response->json($data);

    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
