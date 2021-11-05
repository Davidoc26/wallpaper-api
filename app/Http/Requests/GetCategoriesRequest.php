<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class GetCategoriesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit' => ['min:1', 'max:15', 'integer'],
        ];
    }
}
