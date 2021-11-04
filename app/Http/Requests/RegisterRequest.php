<?php

namespace App\Http\Requests;

use App\Dto\RegisteredUserDto;
use Illuminate\Foundation\Http\FormRequest;

final class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email'=>['required','email','unique:users'],
            'password' => ['required'],
        ];
    }

    public function getDto(): RegisteredUserDto
    {
        return new RegisteredUserDto(
            name: $this->get('name'),
            email: $this->get('email'),
            password: $this->get('password')
        );
    }
}
