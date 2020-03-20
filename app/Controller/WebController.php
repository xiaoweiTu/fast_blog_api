<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Config\Annotation\Value;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\GetMapping;


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
     * @GetMapping()
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function settings()
    {

        return $this->success([$this->webConfig]);
    }
}