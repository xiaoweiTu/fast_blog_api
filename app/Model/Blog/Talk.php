<?php

declare (strict_types=1);
namespace App\Model\Blog;

use Hyperf\DbConnection\Model\Model;
/**
 */
class Talk extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog_talks';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected $guarded = [];


    public function user()
    {
        return $this->hasOne(User::class,'id','user_id')->select(['name','id']);
    }

    public function toUser()
    {
        return $this->hasOne(User::class,'id','to_user_id')->select(['name','id']);
    }
}
