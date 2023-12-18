<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\SecurityAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Service\AuthorizedService;

class RegistrationController extends AbstractController
{
    // private EmailVerifier $emailVerifier;

    // public function __construct(EmailVerifier $emailVerifier)
    // {
    //     $this->emailVerifier = $emailVerifier;
    // }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, SecurityAuthenticator $authenticator, EntityManagerInterface $entityManager, SendMailService $sendMailService, JWTService $jwtService, AuthorizedService $authorizedService): Response
    {
        if($authorizedService->isUserConnected() === true){
            return $this->redirectToRoute('index');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setPicture('default.webp');
            $entityManager->persist($user);
            $entityManager->flush();

            /* Send mail to register user */
            /* Create Header for JWT doc : https://jwt.io/ */
            $header = [
                'alg'=>'HS256',
                'typ'=>'JWT'
            ];

            /* Create Payload */
            $payload = [
                'user_id' => $user->getId(),
            ];

            /* Generate Token */
            $token = $jwtService->generate($header,$payload,$this->getParameter('app.jwtsecret'));

            /* Generate JWT user token */



            $sendMailService->send(
                'no-reply@snowtricks.com',
                $user->getEmail(),
                'SnowTricks Account Confirmation',
                'register',
                compact('user','token')
            );
            $this->addFlash('success','Your account has been created ! Check your e-mail address to confirm your account');
            return $this->redirectToRoute('index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email/{token}', name: 'app_verify_email')]
    public function verifyUserEmail(JWTService $jwtService, string $token, UserRepository $userRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        /* Check if token is not modified, expired and invalid  */
        if($jwtService->isValid($token) && !$jwtService->isExpired($token) && $jwtService->check($token, $this->getParameter('app.jwtsecret'))){
            /* Get payload */
            $payload = $jwtService->getPayload($token);

            /* Get user token */
            $user = $userRepository->find($payload['user_id']);

            /* Check if user exist and doesn't confirm account */
            if($user && !$user->isVerified()){
                $user->setIsVerified(true);
                $user->setRoles(['ROLE_USER']);
                $entityManagerInterface->flush($user);
                $this->addFlash('success', 'Your account has been activated!');
                return $this->redirectToRoute('index');
            }
        }
        /* trigger problem in token */
        $this->addFlash('danger', 'Token is invalid or expired');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/sendEmailVerif', name: 'app_send_email_verif')]
    public function sendEmailVerif(UserRepository $userRepository, SendMailService $sendMailService, JWTService $jwtService): Response
    {
        $user = $this->getUser();

        if(!$user){
            $this->addFlash('danger','You need to be connected!');
            return $this->redirectToRoute('app_login');
        }

        if($user->isVerified()){
            $this->addFlash('warning','This user is already activated');
            return $this->redirectToRoute('index');
        }

        /* Send mail to register user */
        /* Create Header for JWT doc : https://jwt.io/ */
            $header = [
                'alg'=>'HS256',
                'typ'=>'JWT'
            ];

            /* Create Payload */
            $payload = [
                'user_id' => $user->getId(),
            ];

            /* Generate Token */
            $token = $jwtService->generate($header,$payload,$this->getParameter('app.jwtsecret'));

            /* Generate JWT user token */



            $sendMailService->send(
                'no-reply@snowtricks.com',
                $user->getEmail(),
                'SnowTricks Account Confirmation',
                'register',
                compact('user','token')
            );
        $this->addFlash('success','Email has been send');
        return $this->redirectToRoute('index');
    }
    #[Route('/forget-password', name: 'app_forget_password')]
    public function forgetPassword(): Response{

        return $this->render('security/reset_password_request.html.twig');
    }
    
}
