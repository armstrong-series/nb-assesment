<?php 

declare(strict_types=1);

namespace App\Application\Event;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Domain\User\Events\UserRegisteredEvent;
use Psr\Log\LoggerInterface;

class EventHandler implements MessageHandlerInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(UserRegisteredEvent $event)
    { 
        $this->logger->info(sprintf('User %s registered.', $event->getUserId()));
    }
}
