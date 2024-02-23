<?php

namespace App\Infrastructure\Delivery\API\User;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Form\FormFactoryInterface;
use App\Application\Command\RegisterUserCommand;
use App\Domain\Form\RegisterationFormType;
use App\Domain\User\Events\UserRegisteredEvent;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Messenger\Envelope;



final class RegisterUserAction
{
    private $messageBus;
    public $formFactory;
    


   
    public function __construct(MessageBusInterface $messageBus, FormFactoryInterface $formFactory)
    {
        $this->messageBus  = $messageBus;
        $this->formFactory = $formFactory;
    
    }

     /**
     * @Route("/api/v1/auth/user", name="api_v1_auth_register", methods={"POST"})
     */
    public function __invoke(Request $request): Response
    {
        
        $formRequest = $this->formFactory->create(RegisterationFormType::class);
        $formRequest->submit(json_decode($request->getContent(), true));
        
        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $data = $formRequest->getData();
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
        
    }
}
