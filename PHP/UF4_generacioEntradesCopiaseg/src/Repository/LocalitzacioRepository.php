<?php
namespace Entradas\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Entradas\Entity\Localitzacio;

class LocalitzacioRepository extends EntityRepository
{
    private EntityManager $entityManager;
    
    public function __construct($em, $class)
    {
        parent::__construct($em, $class);
        $this->entityManager = $em;
    }
    
    public function add($localitzacio)
    {
        try {
            $this->entityManager->persist($localitzacio);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            echo "Error al añadir la localización: " . $e->getMessage();
        }
    }
    
    public function update($localitzacio)
    {
        try {
            $localitzacioOriginal = $this->find($localitzacio->getId());
            if ($localitzacioOriginal) {
                $localitzacioOriginal->setName($localitzacio->getName());
                $localitzacioOriginal->setAddress($localitzacio->getAddress());
                $localitzacioOriginal->setCity($localitzacio->getCity());
                $localitzacioOriginal->setCapacity($localitzacio->getCapacity());
                $this->entityManager->flush();
            } else {
                echo "Localización no encontrada con ID: " . $localitzacio->getId();
            }
        } catch (\Exception $e) {
            echo "Error al actualizar la localización: " . $e->getMessage();
        }
    }
    
    public function delete($localitzacio)
    {
        try {
            $this->entityManager->remove($localitzacio);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            echo "Error al eliminar la localización: " . $e->getMessage();
        }
    }
    
    public function findLocalitzacioById($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $e) {
            echo "Error al buscar la localización: " . $e->getMessage();
            return null;
        }
    }
    
    public function findLocalitzacioByName($name)
    {
        try {
            return $this->findOneBy(['name' => $name]);
        } catch (\Exception $e) {
            echo "Error al buscar la localización: " . $e->getMessage();
            return null;
        }
    }
}
