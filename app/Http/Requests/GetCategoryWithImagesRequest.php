<?php

namespace App\Http\Requests;

use App\Models\Image;
use Illuminate\Foundation\Http\FormRequest;

final class GetCategoryWithImagesRequest extends FormRequest
{
    public function rules(): array
    {
        $max = Image::PAGINATION_LIMIT;

        return [
            'limit' => ['min:1', "max:$max", 'integer'],
        ];
    }
}
