<?php
namespace App\EventListener;

use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\ORM\Event\PreUpdateEventArgs;


class TrickListener
{
    public function preUpdate(Trick $trick, PreUpdateEventArgs $eventArgs): void
    {
        if (!$eventArgs->getObject() instanceof $trick || !$eventArgs->getObject() instanceof Video) {
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