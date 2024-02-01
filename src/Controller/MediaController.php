<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AuthorizedService;
use App\Entity\Picture;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Video;
use App\Repository\PictureRepository;
use App\Repository\TrickRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class MediaController extends AbstractController
{
    #[Route('/trick/delete/image/{id}', name: 'deleteTrickImage', methods: ['DELETE'])]
    public function deleteImage(Picture $image, Request $request, EntityManagerInterface $entityManager, PictureService $pictureService, AuthorizedService $authorizedService)
    {
        /**
         * Get data from request
         */

        if ($authorizedService->isAuthorizedUserAndVerified($this->getUser()) === true) {

            $data = json_decode($request->getContent(), true);

            if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
                /**
                 * Token is valid, get name of picture
                 */
                $name = $image->getName();

                if ($pictureService->delete($name, 'tricks', 300, 300)) {
                    /**
                     * Delete picture from database
                     */

                    $entityManager->remove($image);
                    $entityManager->flush();
                    return new JsonResponse(['success' => true], 200);
                }
                /**
                 * Delete failed
                 */
                return new JsonResponse(['error' => 'Failed to delete picture'], 400);
            }
            return new JsonResponse(['error' => 'Invalid Token'], 400);
        }
        $this->addFlash('danger', 'You need to be connected and verified user to remove a picture trick');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/trick/delete/video/{id}', name: 'deleteTrickVideo')]
    public function deleteVideo(Video $video, Request $request, EntityManagerInterface $entityManager, AuthorizedService $authorizedService)
    {
        /**
         * Get data from request
         */
        if ($authorizedService->isAuthorizedUserAndVerified($this->getUser()) === true) {
            $data = json_decode($request->getContent(), true);

            if($this->isCsrfTokenValid('delete' . $video->getId(), $data['_token'])){

            /**
             * Delete video from database
             */
            $utc_timezone = new \DateTimeZone("Europe/Paris");
            $date = new \DateTime("now", $utc_timezone);

            $video->setDeletedAt($date);
            $entityManager->flush();
            return new JsonResponse(['success' => true], 200);
            /**
             * Delete failed
             */
            }
            return new JsonResponse(['error' => 'Invalid Token'], 400);
        }
        $this->addFlash('danger', 'You need to be connected and verified user to remove a video trick');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/trick/modify/image/{id}', name: 'modifyTrickImage')]
    public function modifyImage(PictureRepository $pictureRepository,Picture $picture, Request $request, EntityManagerInterface $entityManager, AuthorizedService $authorizedService, PictureService $pictureService)
    {
        if ($authorizedService->isAuthorizedUserAndVerified($this->getUser()) === true) {

            $image = $request->files->get('picture');
            $trickImageId = $request->get('trickImageId');

            $picture = $pictureRepository->find($trickImageId);

            

            $pictureService->delete($picture->getName(), 'tricks', 300, 300);

            $fichier = $pictureService->add($image, 'tricks', 300, 300);

            $picture->setName($fichier);

            $utc_timezone = new \DateTimeZone("Europe/Paris");
            $date = new \DateTime("now", $utc_timezone);

            $picture->setModifiedAt($date);
            $picture->getTrick()->setModifiedAt($date);

            $entityManager->flush();

            return new JsonResponse(['success' => '200', 'url'=>$picture->getName(), 'id'=>$picture->getId()], 200);

        }
        $this->addFlash('danger', 'You need to be connected and verified user to modify an image trick');
        return $this->redirectToRoute('app_login');
       
    }
}
