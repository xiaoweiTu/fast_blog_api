<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\ArticleRequest;
use App\Services\ArticleService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;
/**
 * Class ArticleController
 * @AutoController()
 * @package App\Controller
 */
class ArticleController extends AbstractController
{

    /**
     * @Inject()
     * @var ArticleService
     */
    protected $articleService;


    /**
     * @param ArticleRequest $request
     * @Middleware(JwtAuthMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function pagination(ArticleRequest $request)
    {
        return $this->success($this->articleService->pagination($request->all()));
    }

    /**
     * @param ArticleRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list(ArticleRequest $request) {
        return $this->success($this->articleService->list($request->all()));
    }

    /**
     * @param ArticleRequest $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function row(ArticleRequest $request) {
        $request->validated();
        return $this->success($this->articleService->row($request->input('id')));
    }

    /**
     * @param ArticleRequest $request
     * @Middleware(JwtAuthMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(ArticleRequest $request) {
        $request->validated();
        return $this->success($this->articleService->delete($request->input('id')));
    }

    /**
     * @param ArticleRequest $request
     * @Middleware(JwtAuthMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function save(ArticleRequest $request) {
        $request->validated();
        return $this->success($this->articleService->save($request->all()));
    }

}
