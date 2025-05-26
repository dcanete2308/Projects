<?php
namespace Xiringuito\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "Xiringuito\Repository\ProblemaRepository")]
#[ORM\Table(name: "problema")]
class Problema
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;
    
    #[ORM\Column(type: "datetime")]
    private \DateTime $data;
    
    #[ORM\Column(type: "string", length: 50)]
    private string $titol;
    
    #[ORM\Column(type: "string", length: 50)]
    private string $descripcio;
        
    #[ORM\ManyToOne(targetEntity: Usuari::class)]
    #[ORM\JoinColumn(name: "usuari_id", referencedColumnName: "id", nullable: false)]
    private Usuari $usuari;
    
    public function __construct()
    {
        $this->data = new \DateTime();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }
    
    public function setData(\DateTimeInterface $data): self
    {
        $this->data = $data;
        return $this;
    }
    
    public function getTitol(): ?string
    {
        return $this->titol;
    }
    
    public function setTitol(string $titol): self
    {
        $this->titol = $titol;
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
    
    public function getUsuari(): ?Usuari
    {
        return $this->usuari;
    }
    
    public function setUsuari(?Usuari $usuari): self
    {
        $this->usuari = $usuari;
        return $this;
    }
    
}

