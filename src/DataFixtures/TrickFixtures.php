<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($count = 0; $count < 50; $count++) {
            $trick = new Trick();

            $name = "Figure " . $count + 1;
            $description = "La descritpion de la figure " . $count + 1 . " est ici. Elle va permettre de voir cette figure.";

            $trick->setName($name);
            $trick->setDescription($description);

            $manager->persist($trick);
        }
        $manager->flush();
    }
}
