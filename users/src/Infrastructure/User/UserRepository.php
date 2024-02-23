<?php

namespace App\Infrastructure\User;

use App\Domain\User\Interface\UserInterface;
use App\Domain\Model\User\User;

class UserRepository implements UserInterface
{
    public function find(string $userId): ?User
    {
        
    }

    public function getByEmail(string $email): ?User
    {
       
    }

    public function create(User $user): void
    {
        
    }

    public function delete(User $user): void
    {
       
    }
}
