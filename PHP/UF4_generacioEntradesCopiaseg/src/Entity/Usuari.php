<?php
namespace Entradas\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: "Entradas\Repository\UsuariRepository")]
#[ORM\Table(name: 'Usuari')]
class Usuari
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;
    
    #[ORM\Column(type: "string", length: 100)]
    private $name;
    
    #[ORM\Column(type: "string", length: 150)]
    private $email;
    
    #[ORM\Column(type: "string", length: 30)]
    private $phone;
    
    #[ORM\Column(type: 'datetime')]
    private $createdAt;
    
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Compra::class)]
    private Collection $compres;
    
    public function __construct()
    {
        $this->compres = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    
    public function getPhone()
    {
        return $this->phone;
    }
    
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }
    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    public function getCompres()
    {
        return $this->compres;
    }
    
    public function addCompra(Compra $compra)
    {
        if (!$this->compres->contains($compra)) {
            $this->compres[] = $compra;
        }
        return $this;
    }
    
    public function removeCompra(Compra $compra)
    {
        $this->compres->removeElement($compra);
        return $this;
    }
}


