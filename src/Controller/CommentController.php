<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/comment/page/{page}', name: 'getCommentsPaged')]
    public function getTricksPaged(EntityManagerInterface $entityManager, Request $request){
        $repository = $entityManager->getRepository(Trick::class);
        $page=$request->attributes->get('page');
        $tricks = $repository->findByLimitTrick($page);
        $response = $tricks->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $exit = array();

        foreach ($response as $key) {
            $exit[] = '
            <div class="comment-teaser col-md-2 col-lg-2">
                '. $key['content']. '
            </div>
            ';
        }
        return new JsonResponse($exit);
    }
}
