<?php
namespace Entradas\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Entradas\Entity\Usuari;

class UsuariRepository extends EntityRepository
{
    private EntityManager $entityManager;
    
    public function __construct($em, $class)
    {
        parent::__construct($em, $class);
        $this->entityManager = $em;
    }
    
    public function add($usuari)
    {
        try {
            $this->entityManager->persist($usuari);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            echo "Error al añadir el usuario: " . $e->getMessage();
        }
    }
    
    public function update($usuari)
    {
        try {
            $usuariOriginal = $this->find($usuari->getId());
            if ($usuariOriginal) {
                $usuariOriginal->setName($usuari->getName());
                $usuariOriginal->setEmail($usuari->getEmail());
                $usuariOriginal->setPhone($usuari->getPhone());
                $usuariOriginal->setCreatedAt($usuari->getCreatedAt());
                $this->entityManager->flush();
            } else {
                echo "Usuario no encontrado con ID: " . $usuari->getId();
            }
        } catch (\Exception $e) {
            echo "Error al actualizar el usuario: " . $e->getMessage();
        }
    }
    
    public function delete($usuari)
    {
        try {
            $this->entityManager->remove($usuari);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            echo "Error al eliminar el usuario: " . $e->getMessage();
        }
    }
    
    public function findUsuariById($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $e) {
            echo "Error al buscar el usuario: " . $e->getMessage();
            return null;
        }
    }
    
    public function findUsuariByEmail($email)
    {
        try {
            return $this->findOneBy(['email' => $email]);
        } catch (\Exception $e) {
            echo "Error al buscar el usuario: " . $e->getMessage();
            return null;
        }
    }
}
