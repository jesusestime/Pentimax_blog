<?php
namespace App\EventSubscriber;

use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use App\Entity\Traits\Timestampable;
use App\Model\TimestampedInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminSubscriber implements EventSubscriberInterface
{
   public static function getSubscribedEvents():array
   {
       return [
        BeforeEntityPersistedEvent::class =>["setEntityCreatedAt"],
        BeforeEntityUpdatedEvent::class => ["setEntityUpdatedAt"]
       ];
   }

   public function setEntityCreatedAt(BeforeEntityPersistedEvent $event):void
   {
       $entity=$event->getEntityInstance();

       if(!($entity instanceof TimestampedInterface)){
            return;
        }
 
        $entity->setCreatedAt(new \DateTimeImmutable());
   }

   public function setEntityUpdatedAt(BeforeEntityUpdatedEvent $event):void
   {
       $entity=$event->getEntityInstance();

       if(!($entity instanceof TimestampedInterface)){
            return;
        }
 
        $entity->setUpdatedAt(new \DateTimeImmutable());
   }
}



?>