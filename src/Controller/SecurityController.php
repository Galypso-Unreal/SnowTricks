<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordUserFormType;
use App\Repository\UserRepository;
use App\Service\SendMailService;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Service\AuthorizedService;

class SecurityController extends AbstractController
{
  #[Route(path: '/login', name: 'app_login')]
  public function login(AuthenticationUtils $authenticationUtils, AuthorizedService $authorizedService): Response
  {
    // if ($this->getUser()) {
    //     return $this->redirectToRoute('target_path');
    // }

    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();
    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    // if($this->isGranted('ROLE_USER')){
    //     return $this->redirectToRoute('index');
    // }
    if ($authorizedService->isUserConnected() === true) {
      return $this->redirectToRoute('index');
    }
    return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
  }

  #[Route(path: '/logout', name: 'app_logout')]
  public function logout(): void
  {
    throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
  }

  #[Route(path: '/forget-password', name: 'app_forget_password')]
  public function forgetPassword(
    Request $request,
    UserRepository $userRepository,
    TokenGeneratorInterface $tokenGeneratorInterface,
    EntityManagerInterface $entityManagerInterface,
    SendMailService $mailService
  ): Response {
    $form = $this->createForm(ResetPasswordFormType::class);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      //Cheking user by this username to get email
      $user = $userRepository->findOneByUsername($form->get('username')->getData());
      // dd($user);

      /* if an user is find */
      if ($user) {
        $token = $tokenGeneratorInterface->generateToken();
        $user->setResetToken($token);
        $user->setResetTokenDate((new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'))));
        $entityManagerInterface->persist($user);
        $entityManagerInterface->flush();

        /* Generate a link restPassword for user */
        $url = $this->generateUrl('app_forget_password_reset', ['token' => $token]);

        /* Create all data for email send to user */
        $context = compact('url', 'user');

        /* Send mail with mailService */

        $mailService->send(
          'no-reply@snowtricks.com',
          $user->getEmail(),
          'SnowTricks Password Reset',
          'password_reset',
          $context
        );
        $this->addFlash('success', 'Reset email has been send');
        return $this->redirectToRoute('app_login');
      }

      /* Add flash message to user when no username is found, return to login */

      $this->addFlash('danger', "The username doesn't exist");
      return $this->redirectToRoute('app_login');
    }
    return $this->render('security/reset_password.html.twig', [
      'form' => $form->createView()
    ]);
  }

  #[Route(path: '/forget-password/{token}', name: 'app_forget_password_reset')]
  public function forgetPasswordReset(
    string $token,
    Request $request,
    UserRepository $userRepository,
    UserPasswordHasherInterface $passwordHasher,
    EntityManagerInterface $entityManagerInterface
  ): Response {

    /* Checking if token is correct in the database*/
    $user = $userRepository->findOneByResetToken($token);
    if ($user) {
      $form = $this->createForm(ResetPasswordUserFormType::class);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        if ($user->getUsername() === $form->get('username')->getData()) {
          $date = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
          $datemax = (new DateTimeImmutable($user->getResetTokenDate()->format('Y-m-d H:i:s'), new DateTimeZone('Europe/Paris')))->add(new DateInterval('PT15M'));

          if ($date->getTimestamp() > $datemax->getTimestamp()) {
            $this->addFlash('warning', 'Token is expired');
            $user->setResetToken(null);
            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_login');
          }
          $user->setResetToken(null);
          $user->setPassword(
            $passwordHasher->hashPassword($user, $form->get('password')->getData())
          );
          $entityManagerInterface->persist($user);
          $entityManagerInterface->flush();
          $this->addFlash('success', 'Your password has been changed');
          return $this->redirectToRoute('index');
        }
        $this->addFlash('danger', 'Username is not correct');


        // return $this->redirectToRoute('app_forget_password_reset',['token'=>$token]);
      }

      return $this->render('security/reset_password_user.html.twig', [
        'form' => $form->createView()
      ]);
    }
    $this->addFlash('danger', 'Invalid Token');
    return $this->redirectToRoute('app_login');
  }
}
