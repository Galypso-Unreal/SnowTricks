<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService{
    
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250){
        /* Give name to the picture */

        $fichier = md5(uniqid(rand(),true)) . '.webp';

        /* Get informations about the picture */

        $picture_infos = getimagesize($picture);

        if($picture_infos === false){
            throw new Exception('Incorrect image format');
        }

        /* Check image format */

        
    }
}