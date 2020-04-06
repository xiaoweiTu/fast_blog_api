<?php

declare (strict_types=1);
namespace App\Model\Blog;

use Hyperf\DbConnection\Model\Model;

class Tag extends Model
{
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
    ];

    public static $statusMapping = [
        1 => '隐藏',
        0 => '正常',
    ];

    const NORMAL_STATUS = 0;
    const HIDE_STATUS   = 1;



    public function getIsHideNameAttribute()
    {
        return self::$statusMapping[$this->is_hide] ?? '';
    }



    public function articles() {
        return $this->hasMany(Article::class,'tag_id','id')->where('is_hide',Article::NORMAL_STATUS)->select(['title','id','tag_id','created_at']);
    }

}
