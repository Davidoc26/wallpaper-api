<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use function asset;
use function storage_path;
use function url;

final class ImageResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'path' => asset('storage/' . $this->path),
            'created_at' => $this->created_at,
            'categories' => $this->whenLoaded('categories', CategoryResource::collection($this->categories), null),
        ];

    }
}
