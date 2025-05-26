<?php

namespace Xiringuito\Entity;

use Doctrine\ORM\Mapping as ORM;
use Xiringuito\Entity\Idioma;

#[ORM\Entity(repositoryClass: 'Xiringuito\Repository\NoticiaRepository')]
class Noticia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;
    
    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $data;
    
    #[ORM\Column(type: 'string', length: 255)]
    private string $img;
    
    #[ORM\ManyToOne(targetEntity: Usuari::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Usuari $usuari;
    
    #[ORM\ManyToOne(targetEntity: Idioma::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Idioma $idioma;
    
    #[ORM\Column(type: 'string', length: 100)]
    private string $titol;
    
    #[ORM\Column(type: 'text')]
    private string $descripcio;
        
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getData(): \DateTimeInterface
    {
        return $this->data;
    }
    
    public function setData(\DateTimeInterface $data): self
    {
        $this->data = $data;
        return $this;
    }
    
    public function getImg(): string
    {
        return $this->img;
    }
    
    public function setImg(string $img): self
    {
        $this->img = $img;
        return $this;
    }
    
    public function getUsuari(): Usuari
    {
        return $this->usuari;
    }
    
    public function setUsuari(Usuari $usuari): self
    {
        $this->usuari = $usuari;
        return $this;
    }
    
    public function getIdioma(): Idioma
    {
        return $this->idioma;
    }
    
    public function setIdioma(Idioma $idioma): self
    {
        $this->idioma = $idioma;
        return $this;
    }
    
    public function getTitol(): string
    {
        return $this->titol;
    }
    
    public function setTitol(string $titol): self
    {
        $this->titol = $titol;
        return $this;
    }
    
    public function getDescripcio(): string
    {
        return $this->descripcio;
    }
    
    public function setDescripcio(string $descripcio): self
    {
        $this->descripcio = $descripcio;
        return $this;
    }
}
