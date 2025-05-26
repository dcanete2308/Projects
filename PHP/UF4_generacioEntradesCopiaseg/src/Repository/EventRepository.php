<?php
namespace Entradas\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Entradas\Entity\Esdeveniment;

class EventRepository extends EntityRepository
{
    private EntityManager $entityManager;
    
    public function __construct(EntityManager $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->entityManager = $em;
    }
    
    public function add($evento)
    {
        try {
            $this->entityManager->persist($evento);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            echo "Error al añadir el evento: " . $e->getMessage();
        }
    }
    
    public function updateLugar($evento)
    {
        try {
            $eventoOriginal = $this->findEvento($evento->getTitle()); 
            
            if ($eventoOriginal) {
                $eventoOriginal->setVenue($evento->getVenue()); 
                $this->entityManager->flush();
            } else {
                echo "Evento no encontrado con título: " . $evento->getTitle();
            }
        } catch (\Exception $e) {
            echo "Error al actualizar el lugar del evento: " . $e->getMessage();
        }
    }
    
    public function delete($evento)
    {
        try {
            $this->entityManager->remove($evento);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            echo "Error al eliminar el evento: " . $e->getMessage();
        }
    }
    
    public function findEventosPorFecha(string $fecha): array
    {
        try {
            $fechaInicio = new \DateTime($fecha . ' 00:00:00');
            $fechaFin = new \DateTime($fecha . ' 23:59:59');
            
            $qb = $this->createQueryBuilder('e');
            $qb->where('e.start_time BETWEEN :fechaInicio AND :fechaFin')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFin', $fechaFin);
            
            return $qb->getQuery()->getResult();
        } catch (\Exception $e) {
            echo "Error al buscar eventos por fecha: " . $e->getMessage();
            return [];
        }
    }
    
    
    public function findEvento($title)
    {
        try {
            return $this->findOneBy(['title' => $title]);
        } catch (\Exception $e) {
            echo "Error al buscar el evento: " . $e->getMessage();
            return null;
        }
    }
    
    public function findEventoById($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $e) {
            echo "Error al buscar el evento: " . $e->getMessage();
            return null;
        }
    }
    
   
}
