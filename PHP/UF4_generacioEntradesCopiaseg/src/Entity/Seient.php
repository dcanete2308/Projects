<?php
namespace Entradas\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: "Entradas\Repository\SeientRepository")]
#[ORM\Table(name: 'Seient')]
class Seient
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;
    
    #[ORM\Column(type: "string", length: 10)]
    private $row;
    
    #[ORM\Column(type: 'integer')]
    private $number;
    
    #[ORM\Column(type: "string", length: 20)]
    private $type;
    
    #[ORM\ManyToOne(targetEntity: Localitzacio::class, inversedBy: 'seients')]
    #[ORM\JoinColumn(name: 'venue_id', referencedColumnName: 'id')]
    private Localitzacio $venue;

    public function getId()
    {
        return $this->id;
    }
    
    public function getRow()
    {
        return $this->row;
    }
    
    public function setRow($row)
    {
        $this->row = $row;
        return $this;
    }
    
    public function getNumber()
    {
        return $this->number;
    }
    
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    
    public function getVenue()
    {
        return $this->venue;
    }
    
    public function setVenue(Localitzacio $venue)
    {
        $this->venue = $venue;
        return $this;
    }
}

