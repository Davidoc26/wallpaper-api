<?php

namespace App\Http\Requests;

use App\Dto\ImageUploadDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

final class ImageUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'image' => ['required', 'image'],
            'category_id' => ['integer', 'min:1']
        ];
    }

    public function getDto(): ImageUploadDto
    {
        return new ImageUploadDto(
            userId: $this->user()->id,
            name: $this->input('name'),
            uploadedFile: $this->file('image'),
            categoryId: $this->input('category_id')
        );
    }
}
