<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\TagRequest;
use App\Services\TagService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\RateLimit\Annotation\RateLimit;

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
     * @GetMapping()
     * @RateLimit(create=2,capacity=2)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list()
    {
        return $this->success($this->tagService->tagList());
    }

    public function pagination()
    {

    }

    /**
     * @PostMapping()
     */
    public function save(TagRequest $tagRequest)
    {
        $tagRequest->validated();
        return $this->success([]);
    }

    /**
     * @PostMapping()
     */
    public function delete()
    {
    }

    /**
     * @GetMapping()
     */
    public function row()
    {

    }


}
