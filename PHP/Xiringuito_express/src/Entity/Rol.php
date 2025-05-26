<?php
namespace Xiringuito\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "Xiringuito\Repository\RolRepository")]
#[ORM\Table(name: "rol")]
class Rol {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;
    
    #[ORM\Column(type: "string", length: 50)]
    private string $nom;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getNom(): ?string
    {
        return $this->nom;
    }
    
    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }
    
}
