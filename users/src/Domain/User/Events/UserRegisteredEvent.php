<?php

declare(strict_types=1);

namespace App\Domain\User\Events;

use Symfony\Contracts\EventDispatcher\Event;

class UserRegisteredEvent extends Event
{
  
    private $email;
    private $firstName;
    private $lastName;

    public function __construct(string $email, string $firstName, string $lastName)
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    { {
            return $this->lastName;
        }
    }
}
