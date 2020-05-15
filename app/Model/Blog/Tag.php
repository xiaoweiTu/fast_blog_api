<?php

declare (strict_types=1);
namespace App\Model\Blog;

use Hyperf\DbConnection\Model\Model;
use Xiaowei\ModelFilter\Filterable;


class Tag extends Model
{
    use Filterable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected $guarded = [];

    protected $appends = [
        'is_hide_name',
        'type_name'
    ];

    public static $statusMapping = [
        self::HIDE_STATUS => '隐藏',
        self::NORMAL_STATUS => '正常',
    ];


    public static $typeMapping = [
        self::NORMAL_STATUS => '普通',
        self::SERIES_TYPE   => '系列',
        self::LOGIN_TYPE    => '需登录'
    ];

    const NORMAL_TYPE = 0;
    const SERIES_TYPE = 1;
    const LOGIN_TYPE  = 2;

    const NORMAL_STATUS = 0;
    const HIDE_STATUS   = 1;


    public function getIsHideNameAttribute()
    {
        return self::$statusMapping[$this->is_hide] ?? '';
    }

    public function getTypeNameAttribute()
    {
        return self::$typeMapping[$this->type] ?? '';
    }



    public function articles() {
        return $this->hasMany(Article::class,'tag_id','id')->where('is_hide',Article::NORMAL_STATUS)->select(['title','id','tag_id','created_at']);
    }

}
