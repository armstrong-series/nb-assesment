<?php

declare(strict_types=1);

namespace App\Infrastructure\Delivery\API\User\Handler;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Domain\Form\RegistrationFormType;
use App\Application\Command\RegisterUserCommand;
use App\Domain\User\Events\UserRegisteredEvent;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use App\Infrastructure\Message\SerializedEventMessage;



class UserRegistrationHandler
{
    private MessageBusInterface $messageBus;
    private FormFactoryInterface $formFactory;
    private SerializerInterface $serializer;

    public function __construct(MessageBusInterface $messageBus, FormFactoryInterface $formFactory, SerializerInterface $serializer)
    {
        $this->messageBus = $messageBus;
        $this->formFactory = $formFactory;
        $this->serializer  = $serializer;
    }

    public function handle(Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true) ?? [];
        
        if ($errorResponse = $this->validateRequest($requestData)) {
            return $errorResponse;
        }

        $form = $this->formFactory->create(RegistrationFormType::class);
        $form->submit($requestData);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->formatFormErrorsResponse($form);
        }

        $this->dispatchCommands($form->getData());
        return new Response('User registration initiated.', Response::HTTP_ACCEPTED);
    }

    private function validateRequest(array $requestData): ?Response
    {
        if (empty($requestData['password']) || empty($requestData['password_confirmation'])) {
            return new Response('Password and password confirmation are required.', Response::HTTP_BAD_REQUEST);
        }

        if ($requestData['password'] !== $requestData['password_confirmation']) {
            return new Response('Password confirmation does not match.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return null;
    }

    private function formatFormErrorsResponse($form): Response
    {
        $errors = [];
        foreach ($form->getErrors(true, true) as $error) {
            $errors[] = $error->getMessage();
        }
        $errorsString = implode(", ", $errors);
        return new Response("Invalid request data: " . $errorsString, Response::HTTP_BAD_REQUEST);
    }

    private function dispatchCommands($data): void
    {
        $command = new RegisterUserCommand($data['firstname'], $data['lastname'], $data['email'], $data['password'], $data['nationality']);
        $this->messageBus->dispatch($command);

        $event = new UserRegisteredEvent($data['email'], $data['firstname'], $data['lastname']);
        $jsonEventData = $this->serializer->serialize($event, 'json');

        $message = new SerializedEventMessage($jsonEventData);
        $this->messageBus->dispatch(new Envelope($message, [new AmqpStamp('user_registration')]));
        
    }
}