<?php

declare (strict_types=1);
namespace App\Model\Blog;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id
 * @property string $name
 * @property int $type
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Tag extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected $guarded = [];

    protected $appends = [
        'status_name',
        'type_name'
    ];

    public static $statusMapping = [
        '-1' => '隐藏',
        '0'  => '正常'
    ];
    public static $typeMapping   = [
        0 => "普通",
        1 => '系列'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'type' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function getStatusNameAttribute()
    {
        return self::$statusMapping[$this->status] ?? '';
    }

    public function getTypeNameAttribute()
    {
        return self::$typeMapping[$this->type] ?? '';
    }
}
