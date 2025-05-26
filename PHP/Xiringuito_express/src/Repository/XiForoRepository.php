<?php
namespace Xiringuito\Repository;

use Doctrine\ORM\EntityRepository;
use Xiringuito\Entity\Usuari;
use Xiringuito\Entity\XiForo;

class XiForoRepository extends EntityRepository
{
    public function findMensajesPaginados(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;
        
        return $this->createQueryBuilder('m')
        ->orderBy('m.createdAt', 'DESC')
        ->setFirstResult($offset)
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
    }
    
    public function contarMensajes(): int
    {
        return (int) $this->createQueryBuilder('m')
        ->select('COUNT(m.id)')
        ->getQuery()
        ->getSingleScalarResult();
    }
    
    public function guardarMensaje(Usuari $usuario, string $contenido, ?XiForo $padre = null): XiForo
    {
        $mensaje = new XiForo();
        $mensaje->setUsuari($usuario);
        $mensaje->setContent($contenido);
        $mensaje->setCreatedAt(new \DateTime());
        
        if ($padre !== null) {
            $mensaje->setPadre($padre);
        }       
        return $mensaje;
    }
}
