<?php
namespace Entradas\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Entradas\Entity\Localitzacio;

class SeientRepository extends EntityRepository
{
    private EntityManager $entityManager;
    
    public function __construct($em, $class)
    {
        parent::__construct($em, $class);
        $this->entityManager = $em;
    }
    
    public function add($seient)
    {
        try {
            $this->entityManager->persist($seient);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            echo "Error al añadir la localización: " . $e->getMessage();
        }
    }
    
    public function update($seient)
    {
        try {
            $seientOriginal = $this->find($seient->getId());
            if ($seientOriginal) {
                $seientOriginal->setRow($seient->getRow());
                $seientOriginal->setNumber($seient->getNumber());
                $seientOriginal->setType($seient->getType());
                $seientOriginal->setVenue($seient->getVenue());
                $this->entityManager->flush();
            } else {
                echo "Seient no encontrado con ID: " . $seient->getId();
            }
        } catch (\Exception $e) {
            echo "Error al actualizar el seient: " . $e->getMessage();
        }
    }
        
    public function delete($seient)
    {
        try {
            $this->entityManager->remove($seient);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            echo "Error al eliminar el seient: " . $e->getMessage();
        }
    }
    
    public function get($id)
    {
        try {
            $seient = $this->find($id);
            if ($seient) {
                return $seient;
            } else {
                echo "Seient no encontrado con ID: " . $id;
                return null;
            }
        } catch (\Exception $e) {
            echo "Error al obtener el seient: " . $e->getMessage();
            return null;
        }
    }
    
}
