<?php

declare (strict_types=1);
namespace App\Model\Blog;

use Hyperf\DbConnection\Model\Model;
use function Zipkin\Timestamp\now;

/**
 * @property int $id 
 * @property string $title 
 * @property string $content 
 * @property int $tag_id 
 * @property int $status 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Article extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'articles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected $guarded = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'tag_id' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];


    protected $appends = [
        'status_name',
        'minus_time'
    ];

    public static $statusMapping = [
        1 => '正常',
        0 => '隐藏',
    ];

    const NORMAL_STATUS = 1;
    const HIDE_STATUS   = 0;

    public function getStatusNameAttribute()
    {
        return self::$statusMapping[$this->status] ?? '';
    }

    /**
     * @param $time
     * 计算距离多长时间
     * @return string
     */
    function formatDate($time){
        $t=time()-$time;
        $f=array(
            '31536000'=>'年',
            '2592000'=>'个月',
            '604800'=>'星期',
            '86400'=>'天',
            '3600'=>'小时',
            '60'=>'分钟',
            '1'=>'秒'
        );
        foreach ($f as $k=>$v)    {
            if (0 != $c = floor($t/(int)$k)) {
                return $c.$v.'前';
            }
        }
    }

    public function getMinusTimeAttribute() {
        return $this->formatDate($this->created_at->getTimestamp());
    }


    public function tag() {
        return $this->hasOne(Tag::class,'id','tag_id');
    }



}