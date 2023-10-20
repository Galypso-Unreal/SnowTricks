<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Picture;
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

            if($count < 5){
                $image = new Picture();
                $image->setUrl('firstpicture.jpg');
                $image->setTrick($trick);
                
            }

            

            for ($count2 = 0; $count2 < 30; $count2++) {

                $comment = new Comment();
                $comment->setContent('Je suis le commentaire numéro : ' . $count2);
                $comment->setIsValid(1);
                $comment->setTrick($trick);
                $manager->persist($comment);
            }
            

            $manager->persist($trick);
            $manager->persist($image);
            
        }
        $manager->flush();
    }
}
