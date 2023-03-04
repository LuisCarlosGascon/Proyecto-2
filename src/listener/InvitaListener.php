<?php

namespace App\EventListener;

use App\Event\InvitaEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserDeactivateSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            InvitaEvent::NAME => 'enviaTelegram'
        ];
    }

    public function enviaTelegram(InvitaEvent $event){
    }
}

