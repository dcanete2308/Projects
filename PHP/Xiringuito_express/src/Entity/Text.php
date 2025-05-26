<?php
namespace Xiringuito\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "Xiringuito\Repository\TextRepository")]
#[ORM\Table(name: "text")]
class Text
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    
    #[ORM\ManyToOne(targetEntity: Idioma::class)]
    #[ORM\JoinColumn(name: 'idioma_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private $idioma;
    
    #[ORM\Column(type: 'string', length: 255)]
    private $tipo;
    
    #[ORM\Column(type: 'string', length: 255)]
    private $seccion;
    
    #[ORM\Column(type: 'text')]
    private $contenido;
    
    public function getId(): ?int
    {
        return $this->id;
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
    
    public function getTipo(): ?string
    {
        return $this->tipo;
    }
    
    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;
        return $this;
    }
    
    public function getSeccion(): ?string
    {
        return $this->seccion;
    }
    
    public function setSeccion(string $seccion): self
    {
        $this->seccion = $seccion;
        return $this;
    }
    
    public function getContenido(): ?string
    {
        return $this->contenido;
    }
    
    public function setContenido(string $contenido): self
    {
        $this->contenido = $contenido;
        return $this;
    }
}
