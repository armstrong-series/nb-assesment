<?php

declare(strict_types=1);

namespace App\Infrastructure\Delivery\API\User;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Infrastructure\Delivery\API\User\Handler\UserRegistrationHandler;

final class RegisterUserAction
{
   
    private $userRegistrationHandler;

    public function __construct(UserRegistrationHandler $userRegistrationHandler)
    {
        $this->userRegistrationHandler = $userRegistrationHandler;
    }

    /**
     * @Route("/api/v1/auth/user", name="user_register", methods={"POST"})
     */
    public function __invoke(Request $request): Response
    {
        return $this->userRegistrationHandler->handle($request);
    }
    
}

