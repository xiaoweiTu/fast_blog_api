<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\WrongRequestException;
use App\Model\Blog\Article;
use App\Model\Blog\Tag;
use App\Services\ArticleService;
use App\Services\UploadService;
use App\Services\UserService;
use Hyperf\Config\Annotation\Value;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;
use App\Middleware\AdminMiddleware;

/**
 * Class WebSettingController
 * @AutoController()
 * @package App\Controller
 *
 * User: xiaowei<13177839316@163.com>
 * Date: 2020/3/19
 * Time: 11:04
 */
class WebController extends AbstractController
{

    /**
     * @Value("site_settings")
     */
    private $webConfig;

    /**
     * @Inject()
     * @var UserService
     */
    protected $webService;
    /**
     * @Inject()
     * @var UploadService
     */
    protected $uploadService;

    /**
     * @Inject()
     * @var ArticleService
     */
    protected $articleService;


    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function settings()
    {
        return $this->success($this->webConfig);
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function status() {
        return $this->success(Tag::$statusMapping);
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function tagTypeMapping()
    {
        return $this->success(Tag::$typeMapping);
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function articleTypeMapping()
    {
        return $this->success(Article::$typeMapping);
    }


    /**
     * @param RequestInterface $request
     * @Middleware(AdminMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function upload(RequestInterface $request) {
        if (!$request->hasFile('image') ) {
            throw new WrongRequestException("请传入合法的图片资源!");
        }
        return $this->success($this->uploadService->image($request->file('image')));
    }

    /**
     * @Middleware(AdminMiddleware::class)
     */
    public function totalArticles() {
        return $this->success($this->articleService->count());
    }

    /**
     * @Middleware(AdminMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function totalUsers() {
        return $this->success($this->articleService->totalUsers());
    }

    /**
     * @Middleware(JwtAuthMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function totalLikes() {
        return $this->success($this->articleService->totalLikes());
    }

    /**
     * @Middleware(AdminMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function articlesSta() {
        return $this->success($this->articleService->articlesSta());
    }

    /**
     * @Middleware(AdminMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function userSta() {
        return $this->success($this->articleService->userSta());
    }

    /**
     * @Middleware(AdminMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function likesSta() {
        return $this->success($this->articleService->likesSta());
    }

}
