<?php
namespace Xiringuito\Entity;

use Doctrine\ORM\Mapping as ORM;
use Xiringuito\Entity\Usuari;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: "Xiringuito\Repository\XiForoRepository")]
#[ORM\Table(name: 'xiforo')]
class XiForo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;
    
    #[ORM\ManyToOne(targetEntity: Usuari::class)]
    #[ORM\JoinColumn(name: 'usuari_id', referencedColumnName: 'id', nullable: false)]
    private Usuari $usuari;
    
    #[ORM\Column(type: 'text')]
    private string $content;
    
    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;
    
    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'respuestas')]
    #[ORM\JoinColumn(name: 'padre_id', referencedColumnName: 'id', nullable: true, onDelete: "CASCADE")]
    private ?XiForo $padre = null;
    
    #[ORM\OneToMany(mappedBy: 'padre', targetEntity: self::class, cascade: ['persist', 'remove'])]
    private Collection $respuestas;
    
    public function __construct()
    {
        $this->respuestas = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }
        
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getUsuari(): Usuari
    {
        return $this->usuari;
    }
    
    public function setUsuari(Usuari $usuari): void
    {
        $this->usuari = $usuari;
    }
    
    public function getContent(): string
    {
        return $this->content;
    }
    
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
    
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
    
    public function getPadre(): ?XiForo
    {
        return $this->padre;
    }
    
    public function setPadre(?XiForo $padre): void
    {
        $this->padre = $padre;
    }
    
    public function getRespuestas(): Collection
    {
        return $this->respuestas;
    }
    
    public function addRespuesta(XiForo $respuesta): void
    {
        if (!$this->respuestas->contains($respuesta)) {
            $this->respuestas[] = $respuesta;
            $respuesta->setPadre($this);
        }
    }
}

