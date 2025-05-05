<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\User\UserRepository;

class LoginService
{
    public function __construct(private UserRepository $userRepository) {}

    public function authenticate(string $username, string $password): ?array
    {
        $user = $this->userRepository->findByUsername($username);

        if (!$user || !password_verify($password, $user->password)) {
            return null;
        }

        return [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
        ];
    }
}
