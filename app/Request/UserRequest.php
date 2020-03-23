<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class UserRequest extends FormRequest{
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
                case 'user/login':
                    $rules = [
                        'email'    => 'required|exists:users',
                        'password' => 'required',
                    ];
                    break;
            }

            return $rules;
        }


        public function attributes():array
        {
            return [
                'email'     => '邮箱',
                'password'  => '密码',
            ];
        }

}
