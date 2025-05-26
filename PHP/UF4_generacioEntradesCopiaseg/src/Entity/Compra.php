<?php
namespace Entradas\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: "Entradas\Repository\CompraRepository")]
#[ORM\Table(name: 'Compra')]
class Compra
{

    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $purchaseDate;

    #[ORM\Column(type: "string", length: 30)]
    private $paymentMethod;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $totalAmount;

    #[ORM\ManyToOne(targetEntity: Usuari::class, inversedBy: 'compres')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private Usuari $user;

    #[ORM\OneToMany(mappedBy: 'purchase', targetEntity: Ticket::class)]
    private Collection $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPurchaseDate()
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate($purchaseDate)
    {
        $this->purchaseDate = $purchaseDate;
        return $this;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getTickets()
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket)
    {
        if (! $this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
        }
        return $this;
    }

    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
        return $this;
    }
}



