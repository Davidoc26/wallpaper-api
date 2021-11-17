<?php

namespace App\Http\Requests;

use App\Dto\DownloadImageDto;
use Illuminate\Foundation\Http\FormRequest;

final class DownloadImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'width' => ['required_with:height', 'integer', 'max:10000', 'min:10'],
            'height' => ['required_with:width', 'integer', 'max:10000', 'min:10'],
        ];
    }
}
