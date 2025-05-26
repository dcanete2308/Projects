<?php
namespace Entradas\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "Entradas\Repository\EventRepository")]
#[ORM\Table(name: 'Esdeveniment')]
class Esdeveniment
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;
    
    #[ORM\Column(type: "string",length: 255)]
    private $title;
    
    #[ORM\Column(type: 'text')]
    private $description;
    
    #[ORM\Column(type: 'datetime')]
    private \DateTime $start_time;
    
    #[ORM\Column(type: 'datetime')]
    private \DateTime $end_time;
    
    #[ORM\Column(type: "string", length: 50)]
    private $type;
    
    #[ORM\ManyToOne(targetEntity: Localitzacio::class)]
    #[ORM\JoinColumn(name: 'venue_id', referencedColumnName: 'id')]
    private Localitzacio $venue;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    public function getStartTime()
    {
        return $this->start_time;
    }
    
    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;
        return $this;
    }
    
    public function getEndTime()
    {
        return $this->end_time;
    }
    
    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;
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


