<?php
namespace Xiringuito\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "Xiringuito\Repository\NivellRepository")]
#[ORM\Table(name: "nivell")]
class Nivell
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    
    #[ORM\Column(type: 'string', length: 255)]
    private $img;
    
    #[ORM\ManyToOne(targetEntity: Idioma::class)]
    #[ORM\JoinColumn(name: 'idioma_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private $idioma;
    
    #[ORM\Column(type: 'string', length: 50)]
    private $nom;
    
    #[ORM\Column(type: 'text', nullable: true)]
    private $descripcio;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getImg(): ?string
    {
        return $this->img;
    }
    
    public function setImg(string $img): self
    {
        $this->img = $img;
        return $this;
    }
    
    public function getIdioma(): ?Idioma
    {
        return $this->idioma;
    }
    
    public function setIdioma(?Idioma $idioma): self
    {
        $this->idioma = $idioma;
        return $this;
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
    
    public function getDescripcio(): ?string
    {
        return $this->descripcio;
    }
    
    public function setDescripcio(?string $descripcio): self
    {
        $this->descripcio = $descripcio;
        return $this;
    }
}