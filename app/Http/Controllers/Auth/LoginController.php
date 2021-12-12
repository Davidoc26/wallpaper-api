<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthenticatedUserResource;
use App\Http\Resources\RegisteredUserResource;
use App\Services\UserService;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;
use function __;
use function response;

final class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      example="user@mail.example",
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      example="123456",
     *                  ),
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Successful authentication",
     *          @OA\JsonContent(ref="#/components/schemas/AuthenticatedUserResource"),
     *     ),
     *     @OA\Response(response="422", description="validation failed", @OA\JsonContent()),
     * )
     */
    public function login(LoginRequest $request, AuthManager $authManager): AuthenticatedUserResource
    {
        if ($authManager->attempt($request->only(['email', 'password']))) {
            return new AuthenticatedUserResource($authManager->user());
        }

        return throw ValidationException::withMessages([__('auth.failed')]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      example="User",
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      example="user@mail.example",
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      example="123456",
     *                  ),
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *          response="201",
     *          description="Successful authentication",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/RegisteredUserResource",
     *              ),
     *          ),
     *     ),
     *     @OA\Response(response="422", description="validation failed", @OA\JsonContent()),
     * )
     */
    public function register(RegisterRequest $request, UserService $userService): RegisteredUserResource|JsonResponse
    {
        if ($user = $userService->register($request->getDto())) {
            return new RegisteredUserResource($user);
        }

        return response()->json(status: 500);
    }
}
