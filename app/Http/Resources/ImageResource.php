<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;
use function asset;

/**
 * @OA\Schema(
 *      description="Image resource",
 *      @OA\Property(
 *          property="id",
 *          format="int64",
 *          example="1",
 *      ),
 *      @OA\Property(
 *          format="string",
 *          property="name",
 *          example="My Image",
 *      ),
 *      @OA\Property(
 *          format="string",
 *          property="path",
 *          example="https://site.com/storage/wallpapers/01_12_21/image.png",
 *      ),
 *      @OA\Property(
 *          format="string",
 *          property="created_at",
 *          example="2021-12-03T00:00:00.000000Z",
 *      ),
 *      @OA\Property(
 *          type="array",
 *          property="categories",
 *          @OA\Items(
 *              ref="#/components/schemas/CategoryResource",
 *          ),
 *      )
 * )
 */
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
