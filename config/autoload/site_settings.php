<?php
/**
 *
 * User: xiaowei<13177839316@163.com>
 * Date: 2020/3/19
 * Time: 9:59
 */

return [
    'web_name'    => env('WEB_NAME','FastBlog'),  //网站名称 , 页面左上角展示和title中展示
    'web_author'  => env('WEB_AUTHOR','FastBlog'),  //文章中展示
    'web_desc'    => env('WEB_DESC','基于Hyperf搭建高性能博客'), // 网站描述
    'web_keyword' => env('WEB_KEYWORD','hyperf laravel'),  // 关键词
    'web_icon'    => env('WEB_ICON',''),  // icon地址
    'web_record'  => env('WEB_RECORD',''),    // 备案号
    'web_who'     => env('WEB_WHO'), // 站长身份
];
