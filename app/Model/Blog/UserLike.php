<?php

declare (strict_types=1);
namespace App\Model\Blog;

use Hyperf\DbConnection\Model\Model;
/**
 */
class UserLike extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog_user_likes';
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

    public function article()
    {
        return $this->hasOne(Article::class,'id','article_id')->select(['title','id']);
    }

}
