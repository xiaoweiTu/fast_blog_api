<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Blog\User;
use App\Request\UserRequest;
use App\Services\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\RateLimit\Annotation\RateLimit;
use Phper666\JwtAuth\Jwt;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;
use App\Middleware\AdminMiddleware;

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
        return $this->success($this->userService->login($request->input('email'),$request->input('password'),$request->getHeader('x-real-ip')[0],true));
    }

    /**
     * @param UserRequest $request
     * @Middleware(AdminMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list(UserRequest $request)
    {
        $request->validated();
        return $this->success($this->userService->list($request->all()));
    }

    /**
     * @param UserRequest $request
     * @Middleware(AdminMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function edit(UserRequest $request)
    {
        $request->validated();
        return $this->success($this->userService->edit($request->all()));
    }


    /**
     * @param UserRequest $request
     * @RateLimit(create=100,capacity=100,consume=1)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login(UserRequest $request)
    {
        $request->validated();
        return $this->success($this->userService->login($request->input('email'),$request->input('password'),$request->getHeader('x-real-ip')[0],false));
    }

    /**
     * @param UserRequest $request
     * @RateLimit(create=100,capacity=100,consume=1)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function register(UserRequest $request)
    {
        $request->validated();
        return $this->success($this->userService->register($request->all(),$request->getHeader('x-real-ip')[0]));
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
     * @param Jwt $jwt
     * @Middleware(JwtAuthMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function likeHistory(Jwt $jwt)
    {
        return $this->success($this->userService->likeHistory($jwt->getParserData()));
    }

    /**
     * @Middleware(JwtAuthMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function out() {
        return $this->success($this->userService->logout());
    }

    /**
     * @param UserRequest $request
     * @Middleware(JwtAuthMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function talk(UserRequest $request)
    {
        $request->validated();
        return $this->success($this->userService->talk($request->all()));
    }


    /**
     * @param UserRequest $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function deleteTalk(UserRequest $request)
    {
        $request->validated();
        return $this->success($this->userService->deleteTalk($request->input('id')));
    }


    /**
     * @param UserRequest $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function talkList(UserRequest $request)
    {
        $request->validated();
        return $this->success($this->userService->talkList($request->input('article_id')));
    }
}
