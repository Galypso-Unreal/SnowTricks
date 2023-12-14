<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Service\AuthorizedService;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    #[Route(path: '/profile', name: 'app_profile')]
    public function profile(AuthorizedService $authorizedService, Request $request, PictureService $pictureService, EntityManagerInterface $entityManager){
        if($authorizedService->isUserConnected($this->getUser()) === true){

            $form = $this->createForm(UserFormType::class);
            $form->handleRequest($request);
            
            
            if ($form->isSubmitted() && $form->isValid()) {
                
                /* Get all input pictures */
                $image = $form->get('picture')->getData();
                
                /* Destination folder */
                $folder = 'users';

                $fichier = $pictureService->add($image,$folder,300,300);
                
                $user = $this->getUser();
                $user->setPicture($fichier);
                    
                $entityManager->flush();

                $this->addFlash('success','Update picture has been done');
                return $this->redirectToRoute('app_profile');
            }

            return $this->render('user/profile.html.twig',array(
                "user"=>$this->getUser(),
                "form"=>$form->createView()
            ));
        }
        $this->addFlash('danger','you are not connected to see your profile');
        return $this->redirectToRoute(('app_login'));
    }
}
