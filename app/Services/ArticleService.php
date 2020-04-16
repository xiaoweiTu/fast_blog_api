<?php
namespace App\Services;

use App\Exception\WrongRequestException;
use App\Model\Blog\Article;
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
        $build = Article::query()->with('tag')
            ->where('is_hide', Article::NORMAL_STATUS)
            ->orderByDesc('order')
            ->orderByDesc('id');
        $build = $this->buildWhere($build, $params);
        return $build->paginate(10);
    }

    /**
     * @param Builder $build
     * @param array   $params
     *
     * @return Builder
     */
    protected function buildWhere(Builder $build, array $params) {
        if (!empty($params['created_at'])) {
            $build->whereBetween('created_at', $params['created_at']);
        }
        if (!empty($params['title'])) {
            $build->where('title', $params['title']);
        }
        if (!empty($params['tag_id'])) {
            $build->whereIn('tag_id', $params['tag_id']);
        }
        if (!empty($params['is_hide'])) {
            $build->whereIn('is_hide', $params['is_hide']);
        }
        return $build;
    }

    /**
     * @param $params
     *
     * @return \Hyperf\Contract\LengthAwarePaginatorInterface
     */
    public function list($params) {
        $build = Article::query()->with('tag')
            ->where('is_hide', Article::NORMAL_STATUS)
            ->orderByDesc('order')
            ->orderByDesc('likes')
            ->orderByDesc('id');
        if (!empty($params['tag_id'])) {
            $build->where('tag_id', $params['tag_id']);
        }
        return $build->paginate(10);
    }

    /**
     * @param $id
     *
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    public function row($id, $addClick) {
        $article          = Article::query()->with('tag')->where('id', $id)->first();
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
    public function totalClicked() {
        return Article::query()->sum('clicked');
    }


    /**
     * @return int
     */
    public function totalLikes()
    {
        return Article::query()->sum('likes');
    }

    /**
     *
     * @return Builder[]|\Hyperf\Database\Model\Collection|\Hyperf\Database\Query\Builder[]|\Hyperf\Utils\Collection
     */
    public function articlesInSeven() {
        $begin = date('Y-m-d 00:00:00',strtotime('-7 days'));
        return Article::query()->where('created_at','>=', $begin)
                               ->orderBy(Db::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"))
                               ->selectRaw("count(1) total,DATE_FORMAT(created_at,'%Y-%m-%d') created_at")
                               ->groupBy(Db::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"))
                               ->get(['total','created_at']);
    }

    /**
     * @return Builder[]|\Hyperf\Database\Model\Collection|\Hyperf\Database\Query\Builder[]|\Hyperf\Utils\Collection
     */
    public function clickedInSeven() {
        $begin = date('Y-m-d 00:00:00',strtotime('-7 days'));
        return Article::query()->where('created_at','>=', $begin)
                               ->selectRaw("sum(clicked) total, DATE_FORMAT(created_at,'%Y-%m-%d') created_at")
                               ->groupBy(Db::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"))
                               ->get(['total','created_at']);
    }

    /**
     * @return Builder[]|\Hyperf\Database\Model\Collection|\Hyperf\Database\Query\Builder[]|\Hyperf\Utils\Collection
     */
    public function likesInSeven()
    {
        $begin = date('Y-m-d 00:00:00',strtotime('-7 days'));
        return Article::query()->where('created_at','>=', $begin)
            ->selectRaw("sum(likes) total, DATE_FORMAT(created_at,'%Y-%m-%d') created_at")
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
    public function like(RequestInterface $request)
    {
        $ip = $request->getHeader('x-real-ip');

        if (!empty($ip)) {
            $ip = array_shift($ip);
            $key = $this->getLikedKey($request->input('id'),$ip);
            $liked = $this->redis->get($key);
            if (empty($liked)) {
                $exTime = strtotime(date('Y-m-d 23:59:59')) - time();
                $this->redis->setex($key,$exTime,1);
            } else if($liked > 5 ) {
                throw new WrongRequestException("每天仅可点赞5次哟");
            } else if ($liked <= 5 ) {
                $this->redis->incr($key);
            }
        }

        $article = Article::query()->where('id',$request->input('id'))->first();
        if (empty($article)) {
            throw new WrongRequestException("无此文章!");
        }

        $article->likes += 1;
        return $article->save();
    }
}
