<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class TagRequest extends FormRequest
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
        $uri = $this->path();

        switch ($uri) {
            case 'tag/save':
                $rules = [
                    'id' => 'required',
                ];
                break;
        }

        return $rules;
    }
    public function attributes():array
    {
        return [
            'id'    => 'ID',
        ];
    }

}
