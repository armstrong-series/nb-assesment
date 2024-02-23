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
    private $passwordHasher;

    public function __construct(UserInterface $userInterface, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userInterface = $userInterface; 
        $this->passwordHasher =  $passwordHasher;
    }

    public function __invoke(RegisterUserCommand $command)
    {
        $user = new User();
        $user->setEmail($command->getEmail());
        $user->setFirstname($command->getFirstname());
        $user->setLastName($command->getLastName());
        $user->setNationality($command->getNationality());
        $user->setPassword($this->passwordHasher->hashPassword($user, $command->getPassword()));


        $this->userInterface->create($user);

    
    }
}
