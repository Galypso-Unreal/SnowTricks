<?php

namespace App\DataFixtures;

use App\Entity\TrickGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;;

class GroupTrickFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $grabs = new TrickGroup;
        $grabs->setName('Grabs');
        $manager->persist($grabs);

        $rotations = new TrickGroup;
        $rotations->setName('Rotations');
        $manager->persist($rotations);

        $flips = new TrickGroup;
        $flips->setName('Flips');
        $manager->persist($flips);

        $off = new TrickGroup;
        $off->setName('Off-axis rotations');
        $manager->persist($off);

        $slides = new TrickGroup;
        $slides->setName('Slides');
        $manager->persist($slides);

        $one = new TrickGroup;
        $one->setName('One foot tricks');
        $manager->persist($one);

        $old = new TrickGroup;
        $old->setName('Old school');
        $manager->persist($old);

        $jump = new TrickGroup;
        $jump->setName('Jumps');
        $manager->persist($jump);

        $slidebar = new TrickGroup;
        $slidebar->setName('Slide bar');
        $manager->persist($slidebar);

        $manager->flush();
    }
}
