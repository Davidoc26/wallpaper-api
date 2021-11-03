<?php

namespace App\Http\Requests;

use App\Dto\Collections\ImageUploadCollection;
use App\Dto\ImageUploadDto;
use Illuminate\Foundation\Http\FormRequest;

final class ImagesUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'images.*.name' => ['required', 'string'],
            'images.*.image' => ['required', 'image'],
        ];
    }

    public function getDto(): ImageUploadCollection
    {
        $collection = new ImageUploadCollection();

        foreach ($this->validated()['images'] as $image) {
            $collection->add(new ImageUploadDto($this->user()->id, $image['name'], $image['image']));
        }

        return $collection;
    }
}
