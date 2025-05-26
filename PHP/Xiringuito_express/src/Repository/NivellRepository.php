<?php
namespace Xiringuito\Repository;

use Doctrine\ORM\EntityRepository;
use Xiringuito\Entity\Idioma;
use Xiringuito\Entity\Nivell;

class NivellRepository extends EntityRepository
{
    public function getAllByIdioma(int $idiomaId): array {
        $idioma = $this->getEntityManager()->getReference(Idioma::class, $idiomaId);
        return $this->findBy(['idioma' => $idioma]);
    }
    
    public function crearNivell(string $nom, int $idiomaId, string $img)
    {
        $em = $this->getEntityManager();
        
        $idioma = $em->getRepository(Idioma::class)->find($idiomaId);
        if (!$idioma) {
            return null;
        }
        
        $nivell = new Nivell();
        $nivell->setNom($nom);
        $nivell->setIdioma($idioma);
        $nivell->setImg($img);
        
        $em->persist($nivell);
        $em->flush();
        
        return $nivell;
    }
}
