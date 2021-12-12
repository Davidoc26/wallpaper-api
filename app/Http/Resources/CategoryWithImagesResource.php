<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      description="Category with images",
 *      @OA\Property(property="id",type="int64",example="1"),
 *      @OA\Property(property="name",type="string",example="Simple Category"),
 *      @OA\Property(property="slug",type="string",example="simple-category"),
 *      @OA\Property(
 *          property="images",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/ImageResource"),
 *      ),
 * ),
 */
final class CategoryWithImagesResource extends JsonResource
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
            'slug' => $this->slug,
            'images' => ImageResource::collection($this->images),
        ];
    }
}
