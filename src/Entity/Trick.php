<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;


class Trick
{
    private int $id;
    private string $name;
    private string $description;
    private string $trick_group;
    private string $illustrations;
    private string $videos;
    private datetime $create_at;
    private datetime $modified_at;
    private datetime $delete_at;

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function getTrickGroup(){
        return $this->trick_group;
    }

    public function setTrickGroup($trick_group){
        $this->trick_group = $trick_group;
    }

    public function getIllustrations(){
        return $this->illustrations;
    }

    public function setIllustrations($illustrations){
        $this->illustrations = $illustrations;
    }

    public function getVideos(){
        return $this->illustrations;
    }

    public function setVideos($videos){
        $this->videos = $videos;
    }

    public function getCreateAt(){
        return $this->create_at;
    }

    public function setCreateAt($create_at){
        $this->create_at = $create_at;
    }

    public function getModifiedAt(){
        return $this->modified_at;
    }

    public function setModifiedAt($modified_at){
        $this->modified_at = $modified_at;
    }

    public function getDeleteAt(){
        return $this->delete_at;
    }

    public function setDeleteAt($delete_at){
        $this->delete_at = $delete_at;
    }

}
