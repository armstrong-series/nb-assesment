<?php

declare(strict_types=1);

namespace App\Infrastructure\Delivery\API\User\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Domain\Form\RegistrationFormType;
use App\Application\Command\RegisterUserCommand;
use App\Domain\User\Events\UserRegisteredEvent;

class UserRegistrationHandler
{

    private $messageBus;
    private $formFactory;

    public function __construct(MessageBusInterface $messageBus, FormFactoryInterface $formFactory)
    {
        $this->messageBus = $messageBus;
        $this->formFactory = $formFactory;
    }


    public function handle(Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        
        if (!isset($requestData['password']) || !isset($requestData['password_confirmation'])) {
            return new Response('Password and password confirmation are required.', Response::HTTP_BAD_REQUEST);
        }

        if ($requestData['password'] !== $requestData['password_confirmation']) {
            return new Response('Password confirmation does not match.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $form = $this->formFactory->create(RegistrationFormType::class);
        $form->submit($requestData);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $command = new RegisterUserCommand(
                $data['firstname'],
                $data['lastname'],
                $data['email'],
                $data['password'],
                $data['nationality']
            );

            $this->messageBus->dispatch($command);

            $event = new UserRegisteredEvent($data['email'], $data['firstname'], $data['lastname']);
            $this->messageBus->dispatch($event);

            return new Response('User registration initiated.', Response::HTTP_ACCEPTED);
        }

        $errors = [];
        foreach ($form->getErrors(true, true) as $error) {
            $errors[] = $error->getMessage();
        }
        $errorsString = implode(", ", $errors);
        return new Response("Invalid request data: " . $errorsString, Response::HTTP_BAD_REQUEST);
    }

}
