<?php
namespace Xiringuito\Controller;

use Doctrine\ORM\EntityManager;
use Xiringuito\Entity\Idioma;
use Xiringuito\Entity\Nivell;
use Xiringuito\Entity\Personatge;
use Xiringuito\Entity\Text;
use Xiringuito\Entity\Usuari;
use Xiringuito\View\DescargaView;
use Xiringuito\View\PersonatgeView;

class DescargaController
{

    private EntityManager $em;

    public function __construct()
    {
        global $entityManager;
        $this->em = $entityManager;
    }

    public function getIdiomaIdPorCodi(string $codi): int
    {
        $idioma = $this->em->getRepository(Idioma::class)->findOneBy([
            'codi' => $codi
        ]);
        return $idioma ? $idioma->getId() : 2;
    }

    private function cargarTextos(int $idiomaId): array
    {
        $textosDescargas = $this->em->getRepository(Text::class)->findBy([
            'idioma' => $idiomaId,
            'seccion' => 'descargas'
        ]);
        
        $textos = [];
        
        foreach ($textosDescargas as $texto) {
            $textos[$texto->getTipo()] = $texto->getContenido();
        }
        
        return $textos;
    }
    

    public function show()
    {
        $langCode = $_SESSION['lang'] ?? 'ca';
        $idiomaActual = $this->getIdiomaIdPorCodi($langCode);
        
        
        
        $textos = $this->cargarTextos($idiomaActual);
        
        DescargaView::show($textos);
    }
}
