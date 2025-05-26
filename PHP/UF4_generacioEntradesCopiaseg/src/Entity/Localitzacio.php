<?php
namespace Entradas\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: "Entradas\Repository\LocalitzacioRepository")]
#[ORM\Table(name: 'Localitzacio')]
class Localitzacio
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;
    
    #[ORM\Column(type: "string",length: 255)]
    private $name;
    
    #[ORM\Column(type: "string",length: 255)]
    private $address;
    
    #[ORM\Column(type: "string",length: 100)]
    private $city;
    
    #[ORM\Column(type: 'integer')]
    private $capacity;
    
    #[ORM\OneToMany(mappedBy: 'venue', targetEntity: Esdeveniment::class)]
    private Collection $events;
    
    #[ORM\OneToMany(mappedBy: 'venue', targetEntity: Seient::class)]
    private Collection $seients;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->seients = new ArrayCollection();
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
    
    public function getAddress()
    {
        return $this->address;
    }
    
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }
    
    public function getCity()
    {
        return $this->city;
    }
    
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }
    
    public function getCapacity()
    {
        return $this->capacity;
    }
    
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
        return $this;
    }
    
    public function getEvents()
    {
        return $this->events;
    }
    
    public function addEvent(Esdeveniment $event)
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
        }
        return $this;
    }
    
    public function removeEvent(Esdeveniment $event)
    {
        $this->events->removeElement($event);
        return $this;
    }
    
    public function getSeients()
    {
        return $this->seients;
    }
    
    public function addSeient(Seient $seient)
    {
        if (!$this->seients->contains($seient)) {
            $this->seients[] = $seient;
        }
        return $this;
    }
    
    public function removeSeient(Seient $seient)
    {
        $this->seients->removeElement($seient);
        return $this;
    }
}


