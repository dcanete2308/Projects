<?php

namespace Xiringuito\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'idioma')]
#[ORM\Entity(repositoryClass: 'Xiringuito\Repository\IdiomaRepository')]

class Idioma
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;
    
    #[ORM\Column(type: 'string', length: 5, unique: true)]
    private string $codi;
    
    #[ORM\Column(type: 'string', length: 50)]
    private string $nom;
        
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getCodi(): string
    {
        return $this->codi;
    }
    
    public function setCodi(string $codi): self
    {
        $this->codi = $codi;
        return $this;
    }
    
    public function getNom(): string
    {
        return $this->nom;
    }
    
    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }
}

