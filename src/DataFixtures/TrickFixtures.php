<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;;

class TrickFixtures extends Fixture
{
    protected $slugger;
    protected $userPasswordHasher;

    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->slugger = $slugger;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('John');
        $user->setIsVerified('1');
        $user->setEmail("john@test.com");
        $user->setRoles(array('ROLE_USER'));
        $user->setPicture('default.webp');
        $user->setPassword($this->userPasswordHasher->hashPassword(
            $user,
            "john"
        ));

        $trickGroup = new TrickGroup();
        $trickGroup->setName('testinit');
        for ($count = 0; $count < 50; $count++) {
            $trick = new Trick();

            $name = "Figure " . $count + 1;
            $description = "La descritpion de la figure " . $count + 1 . " est ici. Elle va permettre de voir cette figure.";

            $trick->setName($name);
            $trick->setDescription($description);
            $trick->setSlug($this->slugger->slug(strtolower($trick->getName())));

            $utc_timezone = new \DateTimeZone("Europe/Paris");
            $date = new \DateTime(date('Y-m-d H:i:s', strtotime($count . 'seconds')), $utc_timezone);

            $trick->setCreatedAt($date);
            $trick->setModifiedAt($date);

            if ($count < 10) {

                $video = new Video();
                $video->setEmbed('<iframe width="1280" height="720" src="https://www.youtube.com/embed/mBB7CznvSPQ" title="Comment débuter le freestyle en snowboard (Les Basiques - snowsurf.com)" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>');
                $video->setTrick($trick);

                $manager->persist($video);

                $video = new Video();
                $video->setEmbed('<iframe style="width:100%;height:100%;position:absolute;left:0px;top:0px;overflow:hidden" frameborder="0" type="text/html" src="https://www.dailymotion.com/embed/video/x6gjpap" width="100%" height="100%" allowfullscreen title="Dailymotion Video Player" > </iframe>');
                $video->setTrick($trick);

                $manager->persist($video);

                $video = new Video();
                $video->setEmbed('<iframe src="https://player.vimeo.com/video/326852852?h=24a51c428a" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    <p><a href="https://vimeo.com/326852852">Snow Ghosts</a> from <a href="https://vimeo.com/diermajer">Jarrod Diermajer</a> on <a href="https://vimeo.com">Vimeo</a>.</p>');
                $video->setTrick($trick);

                $manager->persist($video);
            }



            for ($count2 = 0; $count2 < 30; $count2++) {

                $comment = new Comment();
                $comment->setContent('Je suis le commentaire numéro : ' . $count2);
                $comment->setIsValid(1);
                $comment->setTrick($trick);
                $comment->setUser($user);
                $comment->setCreatedAt($date);
                $manager->persist($comment);
            }

            $trick->setTrickGroup($trickGroup);
            $manager->persist($trick);
        }
        $manager->flush();
    }
}
