<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Picture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TrickType;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\CommentFormType;
use App\Repository\TrickRepository;
use App\Service\PictureService;
use Doctrine\DBAL\Query;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{   

    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    #[Route('/trick/new', name: 'trickform')]
    public function new(Request $request, EntityManagerInterface $entityManager, PictureService $pictureService)
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            /* Get all input pictures */
            $images = $form->get('images')->getData();
            
            foreach ($images as $image) {
                /* Destination folder */
                $folder = 'tricks';

                $fichier = $pictureService->add($image,$folder,300,300);

                $picture = new Picture();
                $picture->setTrick($trick);
                $picture->setName($fichier);
                $trick->addPicture($picture);
                

            }
            $trick->setSlug($this->slugger->slug(strtolower($trick->getName())));
            $entityManager->persist($trick);
            $entityManager->flush();
            $this->addFlash('success','the new trick has been correctly added');
            return $this->redirectToRoute('trick',array('slug'=>$trick->getSlug()));
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
    public function trick(EntityManagerInterface $entityManager, Request $request, string $slug, TrickRepository $trickRepository){
        
        $slug = $request->attributes->get('slug');
        
        $repository = $entityManager->getRepository(Trick::class);
        $trick = $repository->findOneBySomeField($slug);

        $trickId = $trick->getId();

        $repositoryImage = $entityManager->getRepository((Picture::class));
        $images = $repositoryImage->findByTrickId($trickId);

        $repositoryVideo = $entityManager->getRepository((Video::class));
        $videos = $repositoryVideo->findByTrickId($trickId);

        $repositoryComment = $entityManager->getRepository((Comment::class));

        $page=1;
        $all_comments_count = $repositoryComment->getAllCommentCount($trickId) / 10;
        $comments = $repositoryComment->findByLimitComment($page,$trickId)->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        

        // $trick = $trickRepository->findOneById($request->attributes->get('trick_id'));

        
        $form = $this->createForm(CommentFormType::class);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            if($this->isGranted('ROLE_USER') == false){
                
                $user = $this->getUser();
                if($user->isVerified() == false){
                    $this->addFlash('warning','You need to be verified to leave a comment');
                    return $this->redirectToRoute('trick',array('slug'=>$trick->getSlug()));
                }
                $this->addFlash('warning','You need to be connected to leave a comment');
                return $this->redirectToRoute('app_login');
            }
            $comment = new Comment();
            $comment->setContent($form->get('content')->getData());
            $comment->setTrick($trick);
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success','the new comment has been correctly added');
            return $this->redirectToRoute('trick',array('slug'=>$trick->getSlug()));
        }

        return $this->render('trick/trick.html.twig',array("trick"=>$trick,'images'=>$images,'videos'=>$videos,'comments'=>$comments, 'number_page'=>$all_comments_count,'form'=>$form));
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