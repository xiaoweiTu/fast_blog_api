<?php
namespace App\Services;


use App\Exception\WrongRequestException;
use App\Model\Blog\Article;

class ArticleService {

    public function pagination($params) {
        return Article::query()->with('tag')
                               ->where('status',Article::NORMAL_STATUS)
                               ->orderByDesc('level')
                               ->orderByDesc('id')
                               ->paginate(10);
    }

    public function list($params) {
        return Article::query()->with('tag')
                               ->orderByDesc('id')
                               ->get();
    }

    public function row($id) {
        return Article::query()->with('tag')->where('id',$id)->first();
    }


    public function delete($id) {
        return Article::query()->with('tag')->where('id',$id)->delete();
    }

    /**
     * @param $name
     *
     * @return int
     */
    protected function hasSameName($title) {
        return Article::query()->where('title',$title)->count();
    }

    /**
     * @param $params
     *
     * @return bool
     */
    public function save($params) {
        if (isset($params['id'])) {
            $article = Article::query()->where('id',$params['id'])->first();
            if ($article->title != $params['title'] && $this->hasSameName($params['title']) ) {
                throw new WrongRequestException("存在相同名称的文章!");
            }
        } else {
            if ( $this->hasSameName($params['title']) ){
                throw new WrongRequestException("存在相同名称的文章!");
            }
            $article = new Article();
        }

        $article->title   = $params['title'];
        $article->content = $params['content'];
        $article->level   = $params['level'];
        $article->tag_id  = $params['tag_id'];
        $article->status  = $params['status'];

        return $article->save();
    }


}