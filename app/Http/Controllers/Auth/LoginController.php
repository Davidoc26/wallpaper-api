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
use function __;
use function response;

final class LoginController extends Controller
{
    public function login(LoginRequest $request, AuthManager $authManager): AuthenticatedUserResource
    {
        if ($authManager->attempt($request->only(['email', 'password']))) {
            return new AuthenticatedUserResource($authManager->user());
        }

        return throw ValidationException::withMessages([__('auth.failed')]);
    }

    public function register(RegisterRequest $request,UserService $userService): RegisteredUserResource|JsonResponse
    {
        if ($user = $userService->register($request->getDto())) {
            return new RegisteredUserResource($user);
        }

        return response()->json(status: 500);
    }
}
