<?php

namespace App\Domain\User;

class User
{
    private $id;
    private $email;
    private $firstName;
    private $lastName;

    public function __construct(string $email, string $firstName, string $lastName)
    {
        
        $this->id = uniqid();
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
    {
        return $this->lastName;
    }

}
