<?php

namespace App\Controller;

use App\Entity\Picture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TrickType;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;

class TrickController extends AbstractController
{   

    #[Route('/trick/new', name: 'trickform')]
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $trick = new Trick();
        

        $picture = new Picture();


        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            var_dump($trick);
            die();
            // $entityManager->persist($trick);
            // $entityManager->flush();
        }

        return $this->render('trick/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}