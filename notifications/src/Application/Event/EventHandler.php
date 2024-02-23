<?php 

declare(strict_types=1);

namespace App\Application\Event;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Domain\User\Events\UserRegisteredEvent;
use Psr\Log\LoggerInterface;

class EventHandler implements MessageHandlerInterface
{

    public function __construct(
        
        protected LoggerInterface $logger,
    ) {}

    public function __invoke(UserRegisteredEvent $event)
    { 
        $this->logger->info(sprintf('A user is initiated', $event->getFirstName()));
    }
}
