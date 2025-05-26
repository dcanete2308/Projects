<?php
namespace Entradas\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Entradas\Entity\Compra;
use Entradas\Entity\Esdeveniment;
use Entradas\Entity\Seient;

class TicketRepository extends EntityRepository
{

    private EntityManager $entityManager;

    public function __construct(EntityManager $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->entityManager = $em;
    }

    public function add($ticket)
    {
        try {
            $this->entityManager->persist($ticket);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            echo "Error al crear el ticket: " . $e->getMessage();
        }
    }

    public function delete($ticket)
    {
        try {
            $this->entityManager->remove($ticket);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            echo "Error al eliminar el ticket: " . $e->getMessage();
        }
    }

    public function update($ticket)
    {
        try {
            $ticketOriginal = $this->find($ticket->getId());

            if (! $ticketOriginal) {
                throw new \Exception("Ticket no encontrado con ID: " . $ticket->getId());
            }

            if ($ticket->getCode() !== null) {
                $ticketOriginal->setCode($ticket->getCode());
            }
            if ($ticket->getStatus() !== null) {
                $ticketOriginal->setStatus($ticket->getStatus());
            }
            if ($ticket->getPrice() !== null) {
                $ticketOriginal->setPrice($ticket->getPrice());
            }
            if ($ticket->getImg() !== null) {
                $ticketOriginal->setImg($ticket->getImg());
            }
            if ($ticket->getEvent() !== null) {
                $ticketOriginal->setEvent($ticket->getEvent());
            }
            if ($ticket->getSeat() !== null) {
                $ticketOriginal->setSeat($ticket->getSeat());
            }
            if ($ticket->getPurchase() !== null) {
                $ticketOriginal->setPurchase($ticket->getPurchase());
            }

            $this->entityManager->flush();

            return $ticketOriginal;
        } catch (\Exception $e) {
            throw new \Exception("Error al actualizar ticket: " . $e->getMessage());
        }
    }

    public function findByReferencia($code)
    {
        try {
            return $this->findOneBy([
                'code' => $code
            ]);
        } catch (\Exception $e) {
            echo "Error al buscar el código: " . $e->getMessage();
            return null;
        }
    }

    public function findEventsByDate($date)
    {
        $start = new \DateTime($date . ' 00:00:00');
        $end = new \DateTime($date . ' 23:59:59');

        return $this->entityManager->getRepository(\Entradas\Entity\Esdeveniment::class)
            ->createQueryBuilder('e')
            ->where('e.start_time BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();
    }
    
    public function findAsiento(Seient $seat, Esdeveniment $event)
    {
        return $this->findOneBy([
            'seat' => $seat,
            'event' => $event
        ]);   
    }
}
