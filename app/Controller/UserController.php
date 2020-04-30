<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\UserRequest;
use App\Services\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\RateLimit\Annotation\RateLimit;
use Phper666\JwtAuth\Jwt;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;

/**
 * Class UserController
 * @AutoController()
 * @package App\Controller
 */
class UserController extends AbstractController
{

    /**
     * @Inject()
     * @var UserService
     */
    protected $userService;

    /**
     * @param UserRequest $request
     * @RateLimit(create=1,capacity=1,consume=1)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function admin_login(UserRequest $request) {
        $request->validated();
        return $this->success($this->userService->login($request->input('email'),$request->input('password'),true));
    }


    /**
     * @param UserRequest $request
     * @RateLimit(create=100,capacity=100,consume=1)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login(UserRequest $request)
    {
        $request->validated();
        return $this->success($this->userService->login($request->input('email'),$request->input('password'),false));
    }

    /**
     * @param UserRequest $request
     * @RateLimit(create=100,capacity=100,consume=1)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function register(UserRequest $request)
    {
        $request->validated();
        return $this->success($this->userService->register($request->all()));
    }

    /**
     * @param Jwt $jwt
     * @Middleware(JwtAuthMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function info(Jwt $jwt) {
        return $this->success($jwt->getParserData());
    }

    /**
     * @Middleware(JwtAuthMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function out() {
        return $this->success($this->userService->logout());
    }
}
