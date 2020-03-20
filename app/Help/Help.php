<?php

use Hyperf\Utils\ApplicationContext;

/**
 *
 * User: xiaowei<13177839316@163.com>
 * Date: 2020/3/19
 * Time: 15:49
 */
if (!function_exists('container')) {
    function container()
    {
        return ApplicationContext::getContainer();
    }
}


if (!function_exists('logger')) {
    function logger() {
        return container()->get(\Hyperf\Logger\LoggerFactory::class)->make('log','default');
    }
}
