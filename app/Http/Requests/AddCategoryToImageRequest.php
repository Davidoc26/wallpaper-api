<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class AddCategoryToImageRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required','integer','min:1'],
        ];
    }
}
