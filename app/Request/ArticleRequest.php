<?php
declare(strict_types=1);

namespace App\Request;
use Hyperf\Validation\Request\FormRequest;

class ArticleRequest extends FormRequest{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [];
        $uri = $this->path();

        switch ($uri) {
            case 'article/save':
                $rules = [
                    'id'          => 'sometimes|exists:articles',
                    'title'       => 'required',
                    'content'     => 'required',
                    'tag_id'      => 'required',
                    'status'      => 'required',
                    'level'       => 'required',
                    'icon'        => 'required',
                    'description' => 'required',
                ];
                break;
            case 'article/delete':
            case 'article/row':
                $rules = [
                    'id' => 'required',
                ];
                break;
            case 'article/list':
                $rules = [
                    'tag_id' => 'required',
                ];
                break;
        }

        return $rules;
    }


    public function attributes():array
    {
        return [
            'id'      => '文章ID',
            'title'   => '文章标题',
            'content' => '内容',
            'tag_id'  => '标签ID',
            'status'  => '状态',
            'level'   => '排序',
        ];
    }
}
