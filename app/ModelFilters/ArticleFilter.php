<?php
/**
 *
 * User: xiaowei<13177839316@163.com>
 * Date: 2020/4/28
 * Time: 16:23
 */

namespace App\ModelFilters;


use Xiaowei\ModelFilter\ModelFilter;

class ArticleFilter extends ModelFilter
{

    public function createdAt(array $value)
    {
        $this->whereBetween('created_at', $value);
    }

    public function title($value)
    {
        $this->where('title', 'like',$value.'%');
    }

    public function tagId( $value)
    {
        if (is_array($value)) {
            $this->whereIn('tag_id', $value);
        } else {
            $this->where('tag_id',$value);
        }
    }

    public function isHide(array $value)
    {
        $this->whereIn('is_hide', $value);
    }
}
