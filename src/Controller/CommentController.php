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
    public function getTricksPaged(EntityManagerInterface $entityManager, Request $request, Packages $assetPackage)
    {
        $repository = $entityManager->getRepository(Comment::class);
        $page = $request->attributes->get('page');
        $trickId = $request->attributes->get('trickid');
        $tricks = $repository->findByLimitComment($page, $trickId);
        $response = $tricks->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $exit = array();

        foreach ($response as $key) {
            $datetime = $key['created_at']->format('d-M-Y');
            $exit[] = '
            <div class="comment-teaser col-md-12 col-lg-12">
                <div class="left">
                    <img src="' . $assetPackage->getUrl('assets/img/users/') . $key['picture'] . '">
                </div>

                <div class="right">
                    <div class="user-name">
                        ' . $key['username'] . '
                    </div>
                    <div class="created-at">
                        ' . $datetime . '
                    </div>
                    <div class="content">
                        ' . $key['content'] . '
                    </div>
                </div>
            </div>';
        }
        return new JsonResponse($exit);
    }
}
