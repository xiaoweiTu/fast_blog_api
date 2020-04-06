<?php
namespace App\Services;

use App\Exception\WrongRequestException;
use App\Model\Blog\Article;
use Hyperf\Database\Model\Builder;

class ArticleService {
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
    public function row($id) {
        $article          = Article::query()->with('tag')->where('id', $id)->first();
        $article->clicked += 1;
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
}