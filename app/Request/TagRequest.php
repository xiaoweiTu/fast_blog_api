<?php
declare(strict_types=1);
namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class TagRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array {
        $rules = [];
        $uri   = $this->path();
        switch ($uri) {
            case 'tag/save':
                $rules = [
                    'id'      => 'sometimes|exists:blog_tags',
                    'name'    => 'required',
                    'is_hide' => 'required',
                    'order'   => 'required',
                ];
                break;
            case 'tag/delete':
            case 'tag/row':
                $rules = [
                    'id' => 'required',
                ];
                break;
        }
        return $rules;
    }

    public function attributes(): array {
        return [
            'id'      => '标签ID',
            'name'    => '标签名称',
            'is_hide' => '是否隐藏',
            'order'   => '标签排序',
        ];
    }
}
