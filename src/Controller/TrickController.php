<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Service\AuthorizedService;
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
use Doctrine\ORM\Event\PreUpdateEventArgs;
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

    #[Route('/trick/new', name: 'createTrick')]
    public function new(Request $request, EntityManagerInterface $entityManager, PictureService $pictureService, AuthorizedService $authorizedService)
    {
        if ($authorizedService->isAuthorizedUserAndVerified($this->getUser()) === true) {

            $trick = new Trick();

            
            $form = $this->createForm(TrickType::class, $trick);
            $form->remove('image');
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {

                /* Get all input pictures */
                $images = $form->get('images')->getData();

                foreach ($images as $image) {
                    /* Destination folder */
                    $folder = 'tricks';

                    $fichier = $pictureService->add($image, $folder, 300, 300);

                    $picture = new Picture();
                    $picture->setTrick($trick);
                    $picture->setName($fichier);
                    $trick->addPicture($picture);
                }
                $trick->setSlug($this->slugger->slug(strtolower($trick->getName())));
                $entityManager->persist($trick);
                $entityManager->flush();
                $this->addFlash('success', 'the new trick has been correctly added');
                return $this->redirect($this->generateUrl('index') . '#tricks-homepage');
            }

            

            return $this->render('trick/new.html.twig', array(
                'form' => $form->createView(),
            ));
        }
        $this->addFlash('danger', 'You need to be connected and verified user to add a new tricks');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Trick::class);
        $repositoryPicture = $entityManager->getRepository(Picture::class);
        $page = 1;
        $all_tricks_count = intval(ceil($repository->getAllTricksCount() / 15));
        $tricks = $repository->findByLimitTrick($page)->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        foreach ($tricks as $id => $trick) {
            $image = $repositoryPicture->onePictureByTrickId($trick['id']);
            if ($image) {
                $trick['image'] = $image[0]['name'];
                $tricks[$id] = $trick;
            }
        }
        return $this->render('index.html.twig', array("tricks" => $tricks, 'number_page' => $all_tricks_count));
    }

    #[Route('/trick/{slug}', name: 'trick')]
    public function trick(EntityManagerInterface $entityManager, Request $request, string $slug, TrickRepository $trickRepository, AuthorizedService $authorizedService)
    {

        $slug = $request->attributes->get('slug');

        $repository = $entityManager->getRepository(Trick::class);
        $trick = $repository->findOneBySomeField($slug);

        $trickId = $trick->getId();

        $repositoryImage = $entityManager->getRepository((Picture::class));
        $images = $repositoryImage->findByTrickId($trickId);

        $repositoryVideo = $entityManager->getRepository((Video::class));
        $videos = $repositoryVideo->findByTrickId($trickId);

        $repositoryComment = $entityManager->getRepository((Comment::class));

        $page = 1;
        $all_comments_count = $repositoryComment->getAllCommentCount($trickId) / 10;
        $comments = $repositoryComment->findByLimitComment($page, $trickId)->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);


        $form = $this->createForm(CommentFormType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            if ($authorizedService->isAuthorizedUserAndVerified($this->getUser()) === false) {
                $this->addFlash('warning', 'You need to be connected and have a verified account to leave a comment');
                return $this->redirectToRoute('trick', array('slug' => $trick->getSlug()));
            }

            $comment = new Comment();
            $comment->setUser($this->getUser());
            $comment->setContent($form->get('content')->getData());
            $comment->setTrick($trick);
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'the new comment has been correctly added');
            return $this->redirectToRoute('trick', array('slug' => $trick->getSlug()));
        }

        return $this->render('trick/trick.html.twig', array("trick" => $trick, 'images' => $images, 'videos' => $videos, 'comments' => $comments, 'number_page' => $all_comments_count, 'form' => $form));
    }
    

    #[Route('/trick/page/{page}', name: 'getTricksPaged')]
    public function getTricksPaged(EntityManagerInterface $entityManager, Request $request, AuthorizedService $authorizedService)
    {
        $repository = $entityManager->getRepository(Trick::class);
        $page = $request->attributes->get('page');
        $repositoryPicture = $entityManager->getRepository(Picture::class);
        $tricks = $repository->findByLimitTrick($page);
        $response = $tricks->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $exitall = array();

        foreach ($response as $key) {
            
            if ($key && is_array($key) === true) {
                $image = $repositoryPicture->onePictureByTrickId($key['id']);
                $exit = "
            <div class='trick-teaser col-12 col-sm-6 col-md-6'>";
                if ($image) {
                    $exit .= '<img src="assets/img/tricks/mini/300x300-' . $image[0]["name"] . '>';
                } else {
                    $exit .= '<img src="assets/img/tricks/default.webp">';
                }
                $exit .= "<a class='name h3_style' href='" . $this->generateUrl('trick', array('slug' => $key['slug'])) . "'>
                    <h2>" . $key['name'] . "</h2>
                </a>";
                if ($authorizedService->isAuthorizedUserAndVerified($this->getUser()) === true) {
                    $exit .=
                        "<div class='modify-panel'>
                    <a href='" . $this->generateUrl('modifyTrick', array('slug' => $key['slug'])) . "'>
                        <img class='icon' src='/assets/img/svg/crayon.svg'>
                    </a>

                    <span class='open-modal' data-url='" . $this->generateUrl('deleteTrick', array('slug' => $key['slug']))  . "'>
                        <img class='icon' src='/assets/img/svg/trash.svg'>
                    </span>
                    
                </div>";
                }
                $exit .= "</div>";
                $exitall[] = $exit;
            }
        }

        return new JsonResponse($exitall);
    }

    #[Route('/trick/modify/{slug}', name: 'modifyTrick')]
    public function modifyTrick(EntityManagerInterface $entityManager, Request $request, string $slug, PictureService $pictureService, AuthorizedService $authorizedService)
    {

        if ($authorizedService->isAuthorizedUserAndVerified($this->getUser()) === true) {
            $slug = $request->attributes->get('slug');

            $repository = $entityManager->getRepository(Trick::class);

            $trick = $repository->findOneBySomeField($slug);

            $trickId = $trick->getId();

            $repositoryImage = $entityManager->getRepository((Picture::class));
            $images = $repositoryImage->findByTrickId($trickId);

            $repositoryVideo = $entityManager->getRepository((Video::class));
            $videos = $repositoryVideo->findByTrickId($trickId);

            $form = $this->createForm(TrickType::class, $trick);
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {

                $utc_timezone = new \DateTimeZone("Europe/Paris");
                $date = new \DateTime("now", $utc_timezone);

                /* Get all input pictures */
                $images = $form->get('images')->getData();

                foreach ($images as $image) {
                    /* Destination folder */
                    $folder = 'tricks';

                    $fichier = $pictureService->add($image, $folder, 300, 300);

                    $picture = new Picture();
                    $picture->setTrick($trick);
                    $picture->setName($fichier);
                    $trick->addPicture($picture);
                }
                $trick->setSlug($this->slugger->slug(strtolower($trick->getName())));
                $entityManager->flush();
                $this->addFlash('success', 'Trick has been correctly modified');
                return $this->redirectToRoute('trick', array('slug' => $trick->getSlug()));
            }

            return $this->render(
                'trick/modify.html.twig',
                array(
                    "trick" => $trick,
                    'form' => $form,
                    "images" => $images,
                    "videos" => $videos,
                )
            );
        }
        $this->addFlash('danger', 'You need to be connected and verified user to modify a trick');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/trick/delete/{slug}', name: 'deleteTrick')]
    public function deleteTrick(EntityManagerInterface $entityManager, Request $request, string $slug, AuthorizedService $authorizedService)
    {
        if ($authorizedService->isAuthorizedUserAndVerified($this->getUser()) === true) {
            $slug = $request->attributes->get('slug');

            $repository = $entityManager->getRepository(Trick::class);
            $trick = $repository->findOneBySomeField($slug);

            $utc_timezone = new \DateTimeZone("Europe/Paris");
            $date = new \DateTime("now", $utc_timezone);

            $trick->setDeletedAt($date);

            $entityManager->flush();

            return $this->redirectToRoute('index');
        }
        $this->addFlash('danger', 'You need to be connected and verified user to remove a trick');
        return $this->redirectToRoute('app_login');
    }
}
