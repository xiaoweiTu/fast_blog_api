<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\TagRequest;
use App\Services\TagService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\RateLimit\Annotation\RateLimit;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;

/**
 * Class TagController
 * @AutoController()
 * @package App\Controller
 *
 * User: xiaowei<13177839316@163.com>
 * Date: 2020/3/20
 * Time: 15:06
 */
class TagController extends AbstractController
{

    /**
     * @Inject()
     * @var TagService
     */
    protected $tagService;


    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list()
    {
        return $this->success($this->tagService->tagList());
    }



    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function all() {
        return $this->success($this->tagService->all());
    }

    /**
     * @param TagRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function pagination(TagRequest $request)
    {
        return $this->success($this->tagService->pagination($request->all()));
    }


    /**
     * @param TagRequest $request
     * @Middleware(JwtAuthMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function save(TagRequest $request)
    {
        $request->validated();
        return $this->success($this->tagService->save($request->all()));
    }

    /**
     * @param TagRequest $request
     * @Middleware(JwtAuthMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(TagRequest $request)
    {
        $request->validated();
        return $this->success($this->tagService->delete($request->input('id')));
    }

    /**
     * @param TagRequest $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function row(TagRequest $request)
    {
        $request->validated();
        return $this->success($this->tagService->row($request->input('id')));
    }

}
