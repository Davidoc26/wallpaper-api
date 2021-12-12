<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     description="Category resource",
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          example="1",
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          example="Simple category",
 *     ),
 *     @OA\Property(
 *          property="slug",
 *          type="",
 *          example="simple-category",
 *     ),
 * )
 */
final class CategoryResource extends JsonResource
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
        ];
    }
}
