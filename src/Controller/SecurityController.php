<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

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
    MailerInterface $mailer
    ): Response
    {
        $form = $this->createForm(ResetPasswordFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //Cheking user by this username to get email
            $user = $userRepository->findOneByUsername($form->get('username')->getData());
            // dd($user);
            
            /* if an user is find */
            if($user){
                $token = $tokenGeneratorInterface->generateToken();
                $user->setResetToken($token);
                $entityManagerInterface->persist($user);
                $entityManagerInterface->flush();

                /* Generate a link restPassword for user */
                $url = $this->generateUrl('app_forget_password_reset',['token'=>$token]);

                /* Create all data for email send to user */
                $data = compact('url','user');

                /* Send mail with mailer interface */
                $email = (new Email())
                    ->from('no-reply@snowtricks.com')
                    ->to($user->getEmail())
                    ->subject('Your password reset !')
                    ->html('test')
                ;
                $mailer->send($email);
                $this->addFlash('success','Reset email has been send');
                return $this->redirectToRoute('app_login');
            }

            /* Add flash message to user when no username is found, return to login */

            $this->addFlash('danger','Error');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/reset_password.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    #[Route(path: '/forget-password/{token}', name: 'app_forget_password_reset')]
    public function forgetPasswordReset(){

    }

}
