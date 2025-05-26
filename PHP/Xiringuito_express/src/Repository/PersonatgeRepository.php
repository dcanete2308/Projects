<?php
namespace Xiringuito\Repository;

use Doctrine\ORM\EntityRepository;
use Xiringuito\Entity\Idioma;
use Xiringuito\Entity\Nivell;
use Xiringuito\Entity\Personatge;

class PersonatgeRepository extends EntityRepository
{

    public function getAllByIdioma(int $idiomaId): array
    {
        $idioma = $this->getEntityManager()->getReference(Idioma::class, $idiomaId);
        return $this->findBy([
            'idioma' => $idioma
        ]);
    }

    public function crearPersonatge(string $nom, string $descripcio, int $vida, int $dany, int $nivellId, int $idiomaId, string $img)
    {
        $em = $this->getEntityManager();

        $nivell = $em->getRepository(Nivell::class)->find($nivellId);
        $idioma = $em->getRepository(Idioma::class)->find($idiomaId);

        if (! $nivell || ! $idioma) {
            return null;
        }

        $personatge = new Personatge();
        $personatge->setNom($nom);
        $personatge->setDescripcio($descripcio);
        $personatge->setVida($vida);
        $personatge->setDany($dany);
        $personatge->setNivell($nivell);
        $personatge->setIdioma($idioma);
        $personatge->setImg($img);

        $em->persist($personatge);
        $em->flush();

        return $personatge;
    }
    
}
