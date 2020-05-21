<?php
namespace App\Services;

use App\Exception\WrongRequestException;
use App\Model\Blog\Article;
use App\Model\Blog\User;
use App\Model\Blog\UserLike;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Redis\Redis;

class ArticleService {

    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;


    const LIKED_KEY = "article:like:";

    /**
     * @param $params
     *
     * @return \Hyperf\Contract\LengthAwarePaginatorInterface
     */
    public function pagination($params) {
        return Article::query()->filter($params)->with('tag')
            ->where('is_hide', Article::NORMAL_STATUS)
            ->orderByDesc('order')
            ->orderByDesc('id')
            ->paginate(10);
    }



    /**
     * @param $params
     *
     * @return \Hyperf\Contract\LengthAwarePaginatorInterface
     */
    public function list($params) {
        return Article::query()->filter($params)->with(['tag','talks'])
            ->where('is_hide', Article::NORMAL_STATUS)
            ->orderByDesc('order')
            ->orderByDesc('likes')
            ->orderByDesc('id')->paginate(10);
    }

    /**
     * @param $id
     *
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    public function row($id, $addClick) {
        $article          = Article::query()->with(['tag','talks'])->where('id', $id)->first();
        if ($addClick) {
            $article->clicked += 1;
        }
        $article->save();
        return $article;
    }

    /**
     * @param $id
     *
     * @return int|mixed
     */
    public function delete($id) {
        return Article::query()->with('tag')->where('id', $id)->delete();
    }

    /**
     * @param $name
     *
     * @return int
     */
    protected function hasSameName($title) {
        return Article::query()->where('title', $title)->count();
    }

    /**
     * @param $params
     *
     * @return bool
     */
    public function save($params) {
        if (isset($params['id'])) {
            $article = Article::query()->where('id', $params['id'])->first();
            if ($article->title != $params['title'] && $this->hasSameName($params['title'])) {
                throw new WrongRequestException("存在相同名称的文章!");
            }
        } else {
            if ($this->hasSameName($params['title'])) {
                throw new WrongRequestException("存在相同名称的文章!");
            }
            $article = new Article();
        }
        $article->title       = $params['title'];
        $article->content     = $params['content'];
        $article->order       = $params['order'];
        $article->tag_id      = $params['tag_id'];
        $article->is_hide     = $params['is_hide'];
        $article->icon        = $params['icon'];
        $article->description = $params['description'];
        $article->editor_type = $params['editor_type'];
        $article->type        = $params['type'];
        return $article->save();
    }

    /**
     * @return int
     */
    public function count() {
        return Article::query()->count();
    }

    /**
     * @return int
     */
    public function totalUsers() {
        return User::query()->count();
    }


    /**
     * @return int
     */
    public function totalLikes()
    {
        return UserLike::query()->count();
    }

    /**
     *
     * @return Builder[]|\Hyperf\Database\Model\Collection|\Hyperf\Database\Query\Builder[]|\Hyperf\Utils\Collection
     */
    public function articlesSta() {
        return Article::query()
                               ->orderBy(Db::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"))
                               ->selectRaw("count(1) total,DATE_FORMAT(created_at,'%Y-%m-%d') created_at")
                               ->groupBy(Db::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"))
                               ->get(['total','created_at']);
    }

    /**
     * @return Builder[]|\Hyperf\Database\Model\Collection|\Hyperf\Database\Query\Builder[]|\Hyperf\Utils\Collection
     */
    public function userSta()
    {
        return User::query()
            ->orderBy(Db::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"))
            ->selectRaw("count(1) total,DATE_FORMAT(created_at,'%Y-%m-%d') created_at")
            ->groupBy(Db::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"))
            ->get(['total','created_at']);
    }



    /**
     * @return Builder[]|\Hyperf\Database\Model\Collection|\Hyperf\Database\Query\Builder[]|\Hyperf\Utils\Collection
     */
    public function likesSta()
    {
        return UserLike::query()
            ->selectRaw("count(1) total, DATE_FORMAT(created_at,'%Y-%m-%d') created_at")
            ->groupBy(Db::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"))
            ->get(['total','created_at']);
    }


    /**
     * @param $id
     * @param $ip
     *
     * @return string
     */
    protected function getLikedKey($id, $ip) {
        return self::LIKED_KEY.$ip.':'.$id;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function like($userId, $id)
    {
        if ( UserLike::query()->where('user_id',$userId)->where('article_id',$id)->count() > 0 ) {
            throw new WrongRequestException("您已经点过赞了!");
        }

        $article = Article::query()->where('id',$id)->first();
        if (empty($article)) {
            throw new WrongRequestException("无此文章!");
        }

        $article->likes += 1;
        $article->save();

        UserLike::query()->create([
            'user_id' => $userId,
            'article_id' => $id
        ]);

        return true;
    }
}
