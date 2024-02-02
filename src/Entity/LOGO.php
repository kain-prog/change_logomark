<?php

namespace App\Entity;

use App\Repository\LOGORepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LOGORepository::class)]
class LOGO
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private string $path = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId( $id ): LOGO
    {
        $this->id = $id;
        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath( $path ): LOGO
    {
        $this->path = $path;
        return $this;
    }
}
