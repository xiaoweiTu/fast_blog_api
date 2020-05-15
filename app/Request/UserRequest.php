<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class UserRequest extends FormRequest
{
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
        $uri   = $this->path();

        switch ($uri) {
            case 'user/login':
            case 'user/admin_login':
                $rules = [
                    'email'    => 'required|exists:users',
                    'password' => 'required',
                ];
                break;
            case 'user/register':
                $rules = [
                    'email'            => 'required|unique:users',
                    'password'         => 'required',
                    'password_confirm' => 'same:password',
                    'name'             => 'required|unique:users'
                ];
                break;
            case 'user/edit':
                $rules = [
                    'email'  => 'required',
                    'name'   => 'required',
                    'status' => 'required',
                    'id'     => 'required|exists:users,id',
                ];
                break;
            case 'user/talk':
                $rules = [
                    'user_id'         => 'required|exists:users,id',
                    'to_user_id' => 'required',
                    'article_id' => 'required',
                    'content'    => 'required|max:80',
                    'to_id'      => 'required',
                ];
                break;

            case 'user/delete':
                $rules = [
                    'id' => 'required|exists:blog_talks,id',
                ];
                break;
            case 'user/talkList':
                $rules = [
                    'article_id' => 'required',
                ];
                break;

        }

        return $rules;
    }


    public function attributes(): array
    {
        return [
            'email'    => '邮箱',
            'password' => '密码',
            'name'     => '昵称',
        ];
    }

}
