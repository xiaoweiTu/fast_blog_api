<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\WrongRequestException;
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


    public function status() {
        return $this->success(Tag::$statusMapping);
    }


    /**
     * @param RequestInterface $request
     * @Middleware(JwtAuthMiddleware::class)
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
     * @Middleware(JwtAuthMiddleware::class)
     */
    public function totalArticles() {
        return $this->success($this->articleService->count());
    }

    /**
     * @Middleware(JwtAuthMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function totalClicked() {
        return $this->success($this->articleService->totalClicked());
    }

    /**
     * @Middleware(JwtAuthMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function articlesInSeven() {
        return $this->success($this->articleService->articlesInSeven());
    }

    /**
     * @Middleware(JwtAuthMiddleware::class)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function clickedInSeven() {
        return $this->success($this->articleService->clickedInSeven());
    }

}
