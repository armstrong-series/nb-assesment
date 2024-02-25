<?php

declare(strict_types=1);

namespace App\Application\CommandHandler;

use App\Application\Command\RegisterUserCommand;
use App\Domain\Model\User\User;
use App\Domain\User\Interface\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegisterUserCommandHandler
{
    private UserInterface $userInterface;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserInterface $userInterface, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userInterface = $userInterface;
        $this->passwordHasher = $passwordHasher;
    }

    public function __invoke(RegisterUserCommand $command)
    {
        $user = new User();
        $user->setEmail($command->getEmail());
        $user->setFirstname($command->getFirstname());
        $user->setLastname($command->getLastName()); 
        $user->setNationality($command->getNationality());
        $hashedPassword = $this->passwordHasher->hashPassword($user, $command->getPassword());
        $user->setPassword($hashedPassword);

        $this->userInterface->create($user);
    }
}
