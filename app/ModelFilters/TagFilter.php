<?php
/**
 *
 * User: xiaowei<13177839316@163.com>
 * Date: 2020/4/24
 * Time: 17:16
 */

namespace App\ModelFilters;


use Xiaowei\ModelFilter\ModelFilter;

class TagFilter extends ModelFilter
{
    public function tagId($value)
    {
        $this->where('id',$value);
    }

    public function name($value)
    {
        $this->where('name','like',$value.'%');
    }

    public function order($value)
    {
        $this->where('order','>=',$value);
    }

    public function isHide($value)
    {
        if (is_array($value)) {
            $this->whereIn('is_hide',$value);
        } else {
            $this->where('is_hide',$value);
        }
    }

    public function type($value)
    {
        if (is_array($value)) {
            $this->whereIn('type',$value);
        } else {
            $this->where('type',$value);
        }
    }
}
