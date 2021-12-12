<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     type="object",
 *     @OA\Property(
 *          property="id",
 *          type="int64",
 *          example="1",
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          example="User",
 *     ),
 *     @OA\Property(
 *          property="email",
 *          type="string",
 *          example="user@mail.example",
 *     ),
 *     @OA\Property(
 *          property="created_at",
 *          type="string",
 *          example="2021-12-03T00:00:00.000000Z",
 *     ),
 * )
 */
final class RegisteredUserResource extends JsonResource
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
            'email' => $this->email,
            'created_at' => $this->created_at
        ];
    }
}
