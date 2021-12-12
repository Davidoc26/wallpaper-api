<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      type="object",
 *      @OA\Property(
 *          property="authenticated",
 *          type="boolean",
 *      ),
 *      @OA\Property(
 *          property="user",
 *          type="object",
 *          @OA\Property(
 *              property="id",
 *              type="int64",
 *              example="1",
 *          ),
 *          @OA\Property(
 *              property="name",
 *              type="string",
 *              example="User",
 *          ),
 *          @OA\Property(
 *              property="email",
 *              type="string",
 *              example="user@mail.example",
 *          ),
 *      ),
 * ),
 */
final class AuthenticatedUserResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'authenticated' => true,
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
            ]
        ];
    }
}
