<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\TrickRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Asset\Packages;

class CommentController extends AbstractController
{
    #[Route('/comment/{trickid}/page/{page}', name: 'getCommentsPaged')]
    public function getTricksPaged(EntityManagerInterface $entityManager, Request $request, Packages $assetPackage){
        $repository = $entityManager->getRepository(Comment::class);
        $page=$request->attributes->get('page');
        $trickId=$request->attributes->get('trickid');
        $tricks = $repository->findByLimitComment($page,$trickId);
        $response = $tricks->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $exit = array();

        foreach ($response as $key) {
        $datetime = $key['createdAt']->format('d-M-Y');
            $exit[] = '
            <div class="comment-teaser col-md-12 col-lg-12">
                <div class="left">
                    <img src="'. $assetPackage->getUrl('assets/img/default.jpg'). '">
                </div>

                <div class="right">
                    <div class="user-name">
                        USERNAME
                    </div>
                    <div class="created-at">
                        '. $datetime . '
                    </div>
                    <div class="content">
                        ' . $key['content'] . '
                    </div>
                </div>
            </div>';
        }
        return new JsonResponse($exit);
    }
    #[Route('/comment/add/{trick_id}', name: 'createComment')]
    public function createComment(Request $request, EntityManagerInterface $entityManager, TrickRepository $trickRepository){

        $comment = new Comment();

        $trick = $trickRepository->findOneById($request->attributes->get('trick_id'));

        dd($trick);

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $comment->setContent($form->get('content')->getData());
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success','the new comment has been correctly added');
            return $this->redirectToRoute('trick',array('slug'=>$trick->getSlug()));
        }

        
    }
}
