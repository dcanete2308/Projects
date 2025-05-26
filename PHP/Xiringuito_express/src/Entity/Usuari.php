<?php
namespace Xiringuito\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "Xiringuito\Repository\UsuariRepository")]
#[ORM\Table(name: "usuaris")]
class Usuari
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;
    
    #[ORM\Column(type: "string", length: 50)]
    private string $nom;
    
    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $cognom = null;
    
    #[ORM\Column(type: "string", length: 255)]
    private string $contrasenya;
    
    #[ORM\Column(type: "string", length: 100)]
    private string $email;
    
    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTimeInterface $data = null;
    
    #[ORM\ManyToOne(targetEntity: Rol::class)]
    #[ORM\JoinColumn(name: "rol_id", referencedColumnName: "id", nullable: false)]
    private Rol $rol;
    
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
    
    public function getCognom(): ?string
    {
        return $this->cognom;
    }
    
    public function setCognom(?string $cognom): self
    {
        $this->cognom = $cognom;
        return $this;
    }
    
    public function getContrasenya(): ?string
    {
        return $this->contrasenya;
    }
    
    public function setContrasenya(string $contrasenya): self
    {
        $this->contrasenya = $contrasenya;
        return $this;
    }
    
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    
    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }
    
    public function setData(?\DateTimeInterface $data): self
    {
        $this->data = $data;
        return $this;
    }
    
    public function getRol(): ?Rol
    {
        return $this->rol;
    }
    
    public function setRol(?Rol $rol): self
    {
        $this->rol = $rol;
        return $this;
    }
    
}

