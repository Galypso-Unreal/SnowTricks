<?php
namespace App\EventListener;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;


class TrickChangedNotifier
{
    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function preUpdate(Trick $trick, PreUpdateEventArgs $eventArgs): void
    {
        if (!$eventArgs->getObject() instanceof $trick) {
            return;
        }

        $changeArray = $eventArgs->getEntityChangeSet();

        if(isset($changeArray) && !empty($changeArray)){
            $utc_timezone = new \DateTimeZone("Europe/Paris");
            $date = new \DateTime("now", $utc_timezone);
            $trick->setModifiedAt($date);
        }
        else{
            return;
        }
    }
}