<?php
namespace Entradas\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: "Entradas\Repository\TicketRepository")]
#[ORM\Table(name: 'Ticket')]
class Ticket
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;
    
    #[ORM\Column(type: "string",length: 50)]
    private $code;
    
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $price;
    
    #[ORM\Column(type: "string", length: 20)]
    private $status;
    
    #[ORM\Column(type: "string", length: 100)]
    private $img;
    
    #[ORM\ManyToOne(targetEntity: Esdeveniment::class)]
    #[ORM\JoinColumn(name: 'event_id', referencedColumnName: 'id')]
    private Esdeveniment $event;
    
    #[ORM\ManyToOne(targetEntity: Seient::class)]
    #[ORM\JoinColumn(name: 'seat_id', referencedColumnName: 'id')]
    private Seient $seat;
    
    #[ORM\ManyToOne(targetEntity: Compra::class, inversedBy: 'tickets')]
    #[ORM\JoinColumn(name: 'purchase_id', referencedColumnName: 'id')]
    private Compra $purchase;

    public function getId()
    {
        return $this->id;
    }
    
    public function getCode()
    {
        return $this->code;
    }
    
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
    
    public function getPrice()
    {
        return $this->price;
    }
    
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    
    public function getImg()
    {
        return $this->img;
    }
    
    public function setImg($img)
    {
        $this->img = $img;
        return $this;
    }
    
    public function getEvent()
    {
        return $this->event;
    }
    
    public function setEvent(Esdeveniment $event)
    {
        $this->event = $event;
        return $this;
    }
    
    public function getSeat(): Seient
    {
        return $this->seat;
    }
    
    public function setSeat(Seient $seat)
    {
        $this->seat = $seat;
        return $this;
    }
    
    public function getPurchase()
    {
        return $this->purchase;
    }
    
    public function setPurchase(Compra $purchase)
    {
        $this->purchase = $purchase;
        return $this;
    }
}

    