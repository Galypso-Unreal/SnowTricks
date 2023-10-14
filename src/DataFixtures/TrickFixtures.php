<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

;

class TrickFixtures extends Fixture
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        for ($count = 0; $count < 50; $count++) {
            $trick = new Trick();

            $name = "Figure " . $count + 1;
            $description = "La descritpion de la figure " . $count + 1 . " est ici. Elle va permettre de voir cette figure.";

            $trick->setName($name);
            $trick->setDescription($description);
            $trick->setSlug($this->slugger->slug(strtolower($trick->getName())));

            $manager->persist($trick);
        }
        $manager->flush();
    }
}
