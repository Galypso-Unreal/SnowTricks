<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
#[ORM\Table(name:"st_trick")]
class Trick
{   
    #[ORM\Id()]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column(type:"integer")]
    private int $id;

    #[ORM\Column(type:"string")]
    #[Assert\NotBlank(message:"Le nom ne doit pas être vide")]
    private string $name;

    #[ORM\Column(type:"string")]
    #[Assert\NotBlank(message:"La description ne doit pas être vide")]
    private string $description;

    // #[ORM\Column(type:"string")]
    // #[Assert\NotBlank(message:"Le group de figure ne doit pas être vide")]
    // private string $trick_group;



    // #[ORM\Column(type:"string")]
    // private string $videos;

    #[ORM\Column(type:"datetime")]
    private datetime $created_at;

    #[ORM\Column(type:"datetime")]
    private datetime $modified_at;

    #[ORM\Column(type:"datetime",nullable:true)]
    private datetime $deleted_at;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Picture::class)]
    private Collection $pictures;

    public function __construct()
    {
        
        $utc_timezone = new \DateTimeZone("Europe/Paris");
        $date = new \DateTime("now",$utc_timezone);

        $this->setCreateAt($date);
        $this->setModifiedAt($date);
        $this->pictures = new ArrayCollection();
    }

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

    // public function getTrickGroup(){
    //     return $this->trick_group;
    // }

    // public function setTrickGroup($trick_group){
    //     $this->trick_group = $trick_group;
    // }


    // public function setPicture(Picture $picture):self{
    //     if(!$this->pictures->contains($picture)){
    //         $this->pictures[] = $picture;
    //     }
    //     return $this;
    // }

    // public function setIllustrations($illustrations){
    //     $this->illustrations = $illustrations;
    // }

    // public function getVideos(){
    //     return $this->videos;
    // }

    // public function setVideos($videos){
    //     $this->videos = $videos;
    // }

    public function getCreateAt(){
        return $this->created_at;
    }

    public function setCreateAt($created_at){
        $this->created_at = $created_at;
    }

    public function getModifiedAt(){
        return $this->modified_at;
    }

    public function setModifiedAt($modified_at){
        $this->modified_at = $modified_at;
    }

    public function getDeleteAt(){
        return $this->deleted_at;
    }

    public function setDeleteAt($deleted_at){
        $this->deleted_at = $deleted_at;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): static
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setTrick($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): static
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getTrick() === $this) {
                $picture->setTrick(null);
            }
        }

        return $this;
    }


}
