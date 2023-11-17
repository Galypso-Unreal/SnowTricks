<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\TrickRepository;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
#[ORM\Table(name:"st_trick")]
class Trick
{   
    #[ORM\Id()]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column(type:"integer")]
    private int $id;

    #[ORM\Column(type:"string",unique:true)]
    #[Assert\NotBlank(message:"Le nom ne doit pas être vide")]
    private string $name;

    #[ORM\Column(type:"string")]
    #[Assert\NotBlank(message:"La description ne doit pas être vide")]
    private string $description;





    // #[ORM\Column(type:"string")]
    // private string $videos;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type:"datetime")]
    private datetime $created_at;

    #[ORM\Column(type:"datetime")]
    private datetime $modified_at;

    #[ORM\Column(type:"datetime",nullable:true)]
    private datetime $deleted_at;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Picture::class,cascade:['persist'])]
    private Collection $pictures;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Video::class)]
    private Collection $videos;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TrickGroup $trick_group = null;



    public function __construct()
    {
        
        $utc_timezone = new \DateTimeZone("Europe/Paris");
        $date = new \DateTime("now",$utc_timezone);

        $this->setCreatedAt($date);
        $this->setModifiedAt($date);
        $this->pictures = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->videos = new ArrayCollection();
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


    // public function getVideos(){
    //     return $this->videos;
    // }

    // public function setVideos($videos){
    //     $this->videos = $videos;
    // }

    public function getCreatedAt(){
        return $this->created_at;
    }

    public function setCreatedAt($created_at){
        $this->created_at = $created_at;
    }

    public function getModifiedAt(){
        return $this->modified_at;
    }

    public function setModifiedAt($modified_at){
        $this->modified_at = $modified_at;
    }

    public function getDeletedAt(){
        return $this->deleted_at;
    }

    public function setDeletedAt($deleted_at){
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): static
    {
        if (!$this->videos->contains($video)) {
            $this->videos->add($video);
            $video->setTrick($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): static
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

        return $this;
    }

    public function getTrickGroup(): ?TrickGroup
    {
        return $this->trick_group;
    }

    public function setTrickGroup(?TrickGroup $trick_group): static
    {
        $this->trick_group = $trick_group;

        return $this;
    }


}
