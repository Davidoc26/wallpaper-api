<?php

namespace App\Services;

use App\Dto\RegisteredUserDto;
use App\Exceptions\UserRegisterException;
use App\Models\User;
use Illuminate\Hashing\HashManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function count;

final class UserService
{
    private HashManager $hashManager;
    private ValidatorInterface $validator;

    public function __construct(HashManager $hashManager, ValidatorInterface $validator)
    {
        $this->hashManager = $hashManager;
        $this->validator = $validator;
    }

    /**
     * @param RegisteredUserDto $dto
     * @return User|false
     */
    public function register(RegisteredUserDto $dto): User|false
    {
        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            throw new UserRegisterException($violations);
        }

        $user = new User();
        $user->name = $dto->getName();
        $user->email = $dto->getEmail();
        $user->password = $this->hashManager->make($dto->getPassword());
        if ($user->save()) {
            return $user;
        }
        return false;
    }

}
