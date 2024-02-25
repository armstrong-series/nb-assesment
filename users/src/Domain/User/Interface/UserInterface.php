<?php

declare(strict_types=1); 

namespace App\Domain\User\Interface;

use App\Domain\Model\User\User;

interface UserInterface
{
    /**
     * Finds a user by its identifier.
     *
     * @param string $userId
     * @return User|null
     */
    public function find(string $userId): ?User;

    /**
     * Finds a user by email.
     *
     * @param string $email
     * @return User|null
     */
    public function getByEmail(string $email): ?User;

    /**
     * Saves/Create a User entity.
     *
     * @param User $user
     * @return void
     */
    public function create(User $user): void;

    /**
     * Deletes a User entity.
     *
     * @param User $user
     * @return void
     */
    public function delete(User $user): void;
}
