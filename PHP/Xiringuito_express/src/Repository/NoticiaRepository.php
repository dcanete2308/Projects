<?php
namespace Xiringuito\Repository;

use Doctrine\ORM\EntityRepository;
use Xiringuito\Entity\Idioma;
use Xiringuito\Entity\Noticia;

class NoticiaRepository extends EntityRepository
{
    public function getAllByIdioma(int $idiomaId): array {
        $idioma = $this->getEntityManager()->getReference(Idioma::class, $idiomaId);
        return $this->findBy(['idioma' => $idioma]);
    }
    
    public function getTreeByIdioma(int $idiomaId): array {
        $idioma = $this->getEntityManager()->getReference(Idioma::class, $idiomaId);
        return $this->findBy(['idioma' => $idioma], ['data' => 'DESC'], 3);
    }
    
    public function crearNoticia($titol, $desc, $idiomaId, $url, $data, $user) {
        $em = $this->getEntityManager();
        $idioma = $em->getRepository(Idioma::class)->find($idiomaId);
        
        $noticia = new Noticia();
        $noticia->setTitol($titol);
        $noticia->setData($data);
        $noticia->setDescripcio($desc);
        $noticia->setIdioma($idioma);
        $noticia->setImg($url);
        $noticia->setUsuari($user);
                
        $em->persist($noticia);
        $em->flush();
        return $noticia;
    }
}