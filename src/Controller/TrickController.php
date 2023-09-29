<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TrickType;
use App\Entity\Trick;

class TrickController extends AbstractController
{   
    #[Route('/trick/new', name: 'trickform')]
    public function new(Request $request)
    {
        $trick = new Trick();
        // $trick->setName('name');
        // $trick->setDescription('Description');
        // $trick->setFigureGroup('Figure');
        // $trick->setIllustrations('Illustrations');
        // $trick->setVideos('videos');

        $form = $this->createForm(TrickType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            var_dump($trick);
        }

        return $this->render('trick/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}