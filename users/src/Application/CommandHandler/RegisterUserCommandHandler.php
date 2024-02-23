<?php

declare(strict_types=1);

namespace App\Application\CommandHandler;

use App\Domain\Model\User\User;
use App\Domain\User\Interface\UserInterface;
use App\Application\Command\RegisterUserCommand;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegisterUserCommandHandler
{
    private $userInterface;
    private $passwordHash;

    public function __construct(UserInterface $userInterface, UserPasswordHasherInterface $passwordHash)
    {
        $this->$userInterface = $$userInterface;
        $this->passwordHash = $passwordHash;
    }

    public function __invoke(RegisterUserCommand $command)
    {
        $user = new User();
        $user->setEmail($command->getEmail());
        $user->setFirstname($command->getFirstname());
        $user->setLastName($command->getLastName());
        $user->setNationality($command->getNationality());
        $user->setPassword($this->passwordHash->hashPassword($user, $command->getPassword()));


        $this->userInterface->save($user);

    
    }
}
