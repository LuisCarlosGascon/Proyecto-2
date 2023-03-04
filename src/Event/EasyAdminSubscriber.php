<?php
namespace App\EventSubscriber;

use App\Entity\User;
use App\Service\Mailer;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $slugger;

    public function __construct($slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterEntityBuiltEvent::class => ['enviaGmailUser'],
        ];
    }

    public function enviaGmailUser(BeforeEntityPersistedEvent $event,Mailer $mailer)
    {
        $entity = $event->getEntityInstance();
        if (!($entity instanceof User)) {
            $mailer->sendEmail($entity);
        }
    }
}