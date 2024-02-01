<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
#[ORM\Table(name: "st_picture")]
class Picture
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\ManyToOne(inversedBy: 'pictures')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Trick $trick = null;

  #[ORM\Column(length: 500)]
  private ?string $name = null;

  #[ORM\Column(type: Types::DATETIME_MUTABLE)]
  private ?\DateTimeInterface $created_at = null;

  #[ORM\Column(type: Types::DATETIME_MUTABLE)]
  private ?\DateTimeInterface $modified_at = null;

  public function __construct()
  {
    $utc_timezone = new \DateTimeZone("Europe/Paris");
    $date = new \DateTime("now", $utc_timezone);

    $this->setCreatedAt($date);
    $this->setModifiedAt($date);
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getTrick(): ?Trick
  {
    return $this->trick;
  }

  public function setTrick(?Trick $trick): static
  {
    $this->trick = $trick;

    return $this;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }

  public function getCreatedAt(): ?\DateTimeInterface
  {
    return $this->created_at;
  }

  public function setCreatedAt(\DateTimeInterface $created_at): static
  {
    $this->created_at = $created_at;

    return $this;
  }

  public function getModifiedAt(): ?\DateTimeInterface
  {
    return $this->modified_at;
  }

  public function setModifiedAt(\DateTimeInterface $modified_at): static
  {
    $this->modified_at = $modified_at;

    return $this;
  }
}
