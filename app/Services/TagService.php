<?php
/**
 *
 * User: xiaowei<13177839316@163.com>
 * Date: 2020/3/20
 * Time: 15:07
 */
namespace App\Services;

use App\Exception\WrongRequestException;
use App\Model\Blog\Article;
use App\Model\Blog\Tag;
use Hyperf\Database\Query\Builder;

class TagService {
    /**
     * @return \Hyperf\Database\Model\Builder[]|\Hyperf\Database\Model\Collection
     */
    public function tagList() {
        return Tag::query()->with('articles')
            ->where('is_hide', Tag::NORMAL_STATUS)
            ->orderByDesc('order')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * @return \Hyperf\Database\Model\Builder[]|\Hyperf\Database\Model\Collection
     */
    public function all() {
        return Tag::query()->where('is_hide', Tag::NORMAL_STATUS)
            ->orderByDesc('order')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * @param $params
     *
     * @return \Hyperf\Contract\PaginatorInterface
     */
    public function pagination($params) {
        $build = Tag::query()->filter($params)->orderByDesc('id');
        return $build->paginate(10);
    }



    /**
     * @param $name
     *
     * @return int
     */
    protected function hasSameName($name) {
        return Tag::query()->where('name', $name)->count();
    }

    /**
     * @param $params
     *
     * @return bool
     */
    public function save($params) {
        if (isset($params['id'])) {
            $tag = Tag::query()->where('id', $params['id'])->first();
            if ($tag->name != $params['name'] && $this->hasSameName($params['name'])) {
                throw new WrongRequestException("存在相同名称的标签!");
            }
        } else {
            if ($this->hasSameName($params['name'])) {
                throw new WrongRequestException("存在相同名称的标签!");
            }
            $tag = new Tag();
        }
        $tag->name    = $params['name'];
        $tag->is_hide = $params['is_hide'];
        $tag->order   = $params['order'];

        return $tag->save();
    }

    /**
     * @param $id
     *
     * @return int|mixed
     */
    public function delete($id) {
        $count = Article::query()->where('tag_id', $id)->count();
        if ($count == 0) {
            return Tag::query()->where('id', $id)->delete();
        } else {
            throw new WrongRequestException("该标签下还有内容,无法删除!");
        }
    }

    /**
     * @param $id
     *
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    public function row($id) {
        return Tag::query()->where('id', $id)->first();
    }
}
