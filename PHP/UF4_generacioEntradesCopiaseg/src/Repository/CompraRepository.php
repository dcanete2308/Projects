<?php
namespace Entradas\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Entradas\Entity\Compra;

class CompraRepository extends EntityRepository
{

    private EntityManager $entityManager;

    public function __construct(EntityManager $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->entityManager = $em;
    }

    public function add($compra)
    {
        try {
            $this->entityManager->persist($compra);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            echo "Error al añadir la compra: " . $e->getMessage();
        }
    }

    public function delete($compra)
    {
        try {
            $this->entityManager->remove($compra);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            echo "Error al eliminar la compra: " . $e->getMessage();
        }
    }

    public function actualizarCompra(Compra $compra)
    {
        try {
            $compraOriginal = $this->findCompra($compra->getId());
            
            if (!$compraOriginal) {
                echo "Compra no encontrada con ID: " . $compra->getId();
                return;
            }
            
            if ($compra->getUser()) {
                $compraOriginal->setUser($compra->getUser());
            }
            
            if ($compra->getPurchaseDate()) {
                $compraOriginal->setPurchaseDate($compra->getPurchaseDate());
            }
            
            if ($compra->getPaymentMethod()) {
                $compraOriginal->setPaymentMethod($compra->getPaymentMethod());
            }
            
            if ($compra->getTotalAmount()) {
                $compraOriginal->setTotalAmount($compra->getTotalAmount());
            }
            
            $this->entityManager->flush();
        } catch (\Exception $e) {
            echo "Error al actualizar la compra: " . $e->getMessage();
        }
    }
    

    public function findCompra($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $e) {
            echo "Error al buscar la compra: " . $e->getMessage();
            return null;
        }
    }
}
