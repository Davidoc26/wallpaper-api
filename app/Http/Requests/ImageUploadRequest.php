<?php

namespace App\Http\Requests;

use App\Dto\ImageUploadDto;
use Illuminate\Foundation\Http\FormRequest;

final class ImageUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'image' => ['required', 'image'],
        ];
    }

    public function getDto(): ImageUploadDto
    {
        return new ImageUploadDto($this->user()->id, $this->input('name'), $this->file('image'));
    }
}
