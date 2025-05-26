<?php
namespace Xiringuito\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "Xiringuito\Repository\PersonatgeRepository")]
#[ORM\Table(name: "personatge")]
class Personatge
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private $id;
    
    #[ORM\Column(type: 'integer')]
    private $vida;
    
    #[ORM\Column(type: 'integer')]
    private $dany;
    
    #[ORM\ManyToOne(targetEntity: Nivell::class)]
    #[ORM\JoinColumn(name: 'nivell_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private $nivell;
    
    #[ORM\Column(type: 'string', length: 255)]
    private $img;
    
    #[ORM\ManyToOne(targetEntity: Idioma::class)]
    #[ORM\JoinColumn(name: 'idioma_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private $idioma;
    
    #[ORM\Column(type: 'string', length: 100)]
    private $nom;
    
    #[ORM\Column(type: 'text')]
    private $descripcio;
        
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getVida(): ?int
    {
        return $this->vida;
    }
    
    public function setVida(int $vida): self
    {
        $this->vida = $vida;
        return $this;
    }
    
    public function getDany(): ?int
    {
        return $this->dany;
    }
    
    public function setDany(int $dany): self
    {
        $this->dany = $dany;
        return $this;
    }
    
    public function getNivell(): ?Nivell
    {
        return $this->nivell;
    }
    
    public function setNivell(?Nivell $nivell): self
    {
        $this->nivell = $nivell;
        return $this;
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
    
    public function setDescripcio(string $descripcio): self
    {
        $this->descripcio = $descripcio;
        return $this;
    }
}

