<?php

declare (strict_types=1);
namespace App\Model\Blog;

use Hyperf\DbConnection\Model\Model;
use Xiaowei\ModelFilter\Filterable;

class User extends Model
{
    use Filterable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_admin',
        'name',
        'email',
        'password',
        'status',
        'last_login',
        'last_ip'
    ];


    public static $statusMapping = [
        0 => '正常',
        1 => '小黑屋',
        2 => '禁言',
        3 => '黑名单'
    ];

    public static $isAdminMapping = [
        0 => '否',
        1 => '是'
    ];

    public $appends = [
        'last_ip_address',
        'status_name',
        'is_admin_name'
    ];


    protected $hidden = [
        'password',
    ];

    public function getLastIpAddressAttribute()
    {
        return $this->last_ip ? long2ip($this->last_ip) : 0;
    }

    public function getStatusNameAttribute()
    {
        return self::$statusMapping[$this->status] ?? '';
    }

    public function getIsAdminNameAttribute()
    {
        return self::$isAdminMapping[$this->is_admin] ?? '';
    }
}
