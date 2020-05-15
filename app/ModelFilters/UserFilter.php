<?php
/**
 *
 * User: xiaowei<13177839316@163.com>
 * Date: 2020/5/12
 * Time: 9:10
 */

namespace App\ModelFilters;


use Xiaowei\ModelFilter\ModelFilter;

class UserFilter extends ModelFilter
{
    public function name($value)
    {
        $this->where('name','like',$value.'%');
    }

    public function email($value)
    {
        $this->where('email','like',$value.'%');
    }

    public function isAdmin($value)
    {
        $this->where('is_admin',$value);
    }

    public function status($value)
    {
        $this->where('status', $value);
    }

    public function lastLogin($value)
    {
        $this->whereBetween('last_login', $value);
    }
}
