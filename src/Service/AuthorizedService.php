<?php

namespace App\Service;

class AuthorizedService{

    public function AuthorizedUser($grantedRole, $errorVerified, $errorVerifiedRoute, $errorVerifiedRouteParams = null, $errorGranted, $errorGrantedRoute, $errorGrantedRouteParams = null){

        if($this->isGranted($grantedRole) == false){
                
            $user = $this->getUser();
            if($user->isVerified() == false){
                $this->addFlash('warning',$errorVerified);
                return $this->redirectToRoute($errorVerifiedRoute,$errorVerifiedRouteParams);
            }
            $this->addFlash('warning',$errorGranted);
            return $this->redirectToRoute($errorGrantedRoute,$errorGrantedRouteParams);
        }
    }
}

