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
     * @Route("/api/v1/auth/user", name="user_register", methods={"POST"})
     */
    public function __invoke(Request $request): Response
    {
      
    
        $requestData = json_decode($request->getContent(), true);
        if ($requestData['password'] !== $requestData['password_confirmation']) {
            return new Response('Password confirmation does not match.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

            
        $formRequest = $this->formFactory->create(RegisterationFormType::class);

        $formRequest->submit(json_decode($request->getContent(), true));
     
        
        if ($formRequest->isSubmitted()) {
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

       
        return new Response('Invalid request data.', Response::HTTP_BAD_REQUEST);
        
    }
}
