<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Picture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TrickType;
use App\Entity\Trick;
use Doctrine\DBAL\Query;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $entityManager){
        $repository = $entityManager->getRepository(Trick::class);
        $page=1;
        $all_tricks_count = $repository->getAllTricksCount() / 15;
        $tricks = $repository->findByLimitTrick($page)->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        return $this->render('index.html.twig',array("tricks"=>$tricks, 'number_page'=>$all_tricks_count));
    }

    #[Route('/trick/{slug}', name: 'trick')]
    public function trick(EntityManagerInterface $entityManager, Request $request, string $slug){
        
        $slug = $request->attributes->get('slug');
        
        $repository = $entityManager->getRepository(Trick::class);
        $trick = $repository->findOneBySomeField($slug);

        $trickId = $trick->getId();

        $repositoryImage = $entityManager->getRepository((Picture::class));
        $images = $repositoryImage->findByTrickId($trickId);

        $repositoryComment = $entityManager->getRepository((Comment::class));

        $page=1;
        $all_comments_count = $repositoryComment->getAllCommentCount($trickId) / 10;
        $comments = $repositoryComment->findByLimitComment($page,$trickId)->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        
        

        return $this->render('trick/trick.html.twig',array("trick"=>$trick,'images'=>$images,'comments'=>$comments, 'number_page'=>$all_comments_count));
    }

    #[Route('/trick/page/{page}', name: 'getTricksPaged')]
    public function getTricksPaged(EntityManagerInterface $entityManager, Request $request){
        $repository = $entityManager->getRepository(Trick::class);
        $page=$request->attributes->get('page');
        $tricks = $repository->findByLimitTrick($page);
        $response = $tricks->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $exit = array();

        foreach ($response as $key) {
            $exit[] = '
            <div class="trick-teaser col-md-2 col-lg-2">
                <a class="name h3_style" href="'. $this->generateUrl('trick',array('slug'=>$key['slug'])) .'">
                    <h2>'. $key['name'] .'</h2>
                </a>
                <div>'.
                    $key['description'] .'
                </div>
            </div>
            ';
        }
        return new JsonResponse($exit);
    }
}