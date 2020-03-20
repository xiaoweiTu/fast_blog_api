<?php
/**
 *
 * User: xiaowei<13177839316@163.com>
 * Date: 2020/3/20
 * Time: 15:07
 */

namespace App\Services;


use App\Model\Blog\Tag;

class TagService
{

    public function tagList()
    {
        return Tag::query()->where('status',0)
                            ->orderByDesc('level')
                            ->orderByDesc('id')
                            ->get();
    }
}
