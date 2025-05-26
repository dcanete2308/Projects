<?php
namespace Xiringuito\Repository;

use Doctrine\ORM\EntityRepository;

class IdiomaRepository extends EntityRepository
{
    private function getIdiomaIdPorCodi(string $codi): int
    {
        $idioma = $this->findOneBy(['codi' => $codi]);
        return $idioma ? $idioma->getId() : 2;  
    }
}

