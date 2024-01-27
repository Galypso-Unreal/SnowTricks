<?php
namespace App\EventListener;

use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;


class VideoListener
{
    public function preUpdate(Video $video, PreUpdateEventArgs $eventArgs): void
    {
        if (!$eventArgs->getObject() instanceof $video) {
            return;
        }

        $changeArray = $eventArgs->getEntityChangeSet();

        if(isset($changeArray) && !empty($changeArray)){
            $utc_timezone = new \DateTimeZone("Europe/Paris");
            $date = new \DateTime("now", $utc_timezone);
            $video->setModifiedAt($date);
        }
        else{
            return;
        }
    }
}