<?php

declare (strict_types=1);

namespace App\Model\Blog;

use Hyperf\DbConnection\Model\Model;
use Xiaowei\ModelFilter\Filterable;

/**
 * @property int            $id
 * @property string         $title
 * @property string         $content
 * @property int            $tag_id
 * @property int            $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Article extends Model
{
    use Filterable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog_articles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected $guarded = [];

    protected $appends = [
        'is_hide_name',
        'minus_time',
        'type_name'
    ];

    public static $statusMapping = [
        self::HIDE_STATUS   => '隐藏',
        self::NORMAL_STATUS => '正常',
    ];

    const NORMAL_STATUS = 0;
    const HIDE_STATUS   = 1;

    public static $typeMapping = [
        self::NORMAL_STATUS => '普通',
        self::LOGIN_TYPE    => '需登录'
    ];

    const NORMAL_TYPE = 0;
    const LOGIN_TYPE  = 1;


    public function getIsHideNameAttribute()
    {
        return self::$statusMapping[$this->is_hide] ?? '';
    }

    public function getTypeNameAttribute()
    {
        return self::$typeMapping[$this->type] ?? '';
    }

    /**
     * @param $time
     * 计算距离多长时间
     *
     * @return string
     */
    function formatDate($time)
    {
        $t = time() - $time;
        $f = [
            '31536000' => '年',
            '2592000'  => '个月',
            '604800'   => '星期',
            '86400'    => '天',
            '3600'     => '小时',
            '60'       => '分钟',
            '1'        => '秒'
        ];
        foreach ($f as $k => $v) {
            if (0 != $c = floor($t / (int)$k)) {
                return $c . $v . '前';
            }
        }
    }

    public function getMinusTimeAttribute()
    {
        if ($this->created_at) {
            return $this->formatDate($this->created_at->getTimestamp());
        }
        return '';
    }


    public function tag()
    {
        return $this->hasOne(Tag::class, 'id', 'tag_id');
    }

    public function talks()
    {
        return $this->hasMany(Talk::class,'article_id','id');
    }


}
