<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class RegisterService
{
    
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    
    public function handle(array $data): User
    {
        return $this->userRepository->create($data);
    }
}
