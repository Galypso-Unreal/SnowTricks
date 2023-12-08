<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthorizedService extends AbstractController{

    public function isAuthorizedUserAndVerified($user){

        if($this->isGranted('ROLE_USER') === false){
                
            if(isset($user) && $user->isVerified() === false){
                return false;
            }
            
            return false;
            
        }
        else{
            return true;
        }
    }

    public function isUserConnected(): bool{
        if($this->isGranted('ROLE_USER') === true){
            return true;
        }
        else{
            return false;
        }
    }

    public function isUserVerified(UserInterface $user): bool{
        if($user->isVerified() === true){
            return true;
        }
        else{
            return false;
        }
    }
}

