<?php
namespace Xiringuito\Controller;

use Doctrine\ORM\EntityManager;
use Xiringuito\Entity\Idioma;
use Xiringuito\Entity\Noticia;
use Xiringuito\Entity\Text;
use Xiringuito\View\HomeView;

class HomeController {
    
    private EntityManager $em;
    
    public function __construct() {
        global $entityManager;
        $this->em = $entityManager;
    }
    
    public function getIdiomaIdPorCodi(string $codi): int
    {
        $idioma = $this->em->getRepository(Idioma::class)->findOneBy(['codi' => $codi]);
        return $idioma ? $idioma->getId() : 2;
    }
        
    public function show() {
        
        $langCode = $_SESSION['lang'] ?? 'ca';
        $idiomaId = $this->getIdiomaIdPorCodi($langCode);
        $sesionIniciada = isset($_SESSION['user_id'], $_SESSION['user_name']) && !empty($_SESSION['user_id']) && !empty($_SESSION['user_name']);
                
        $datos = array();
        
        $datos['noticias'] = $this->em->getRepository(Noticia::class)->getTreeByIdioma($idiomaId);
        
        $datos['heroTitulo'] = $this->em->getRepository(Text::class)->findOneBy(['idioma' => $idiomaId ,'seccion' => 'hero', 'tipo' => 'titulo']);
        $datos['heroContenido'] = $this->em->getRepository(Text::class)->findOneBy(['idioma' => $idiomaId ,'seccion' => 'hero', 'tipo' => 'contenido']);
        $datos['download'] = $this->em->getRepository(Text::class)->findOneBy(['idioma' => $idiomaId ,'seccion' => 'hero', 'tipo' => 'contenido2']);
        
        $datos['gameHistorySubtitulo'] = $this->em->getRepository(Text::class)->findOneBy(['idioma' => $idiomaId ,'seccion' => 'game-history', 'tipo' => 'subtitulo']);
        $datos['gameHistoryContenido'] = $this->em->getRepository(Text::class)->findOneBy(['idioma' => $idiomaId , 'seccion' => 'game-history', 'tipo' => 'contenido']);
        $datos['gameHistoryContenido2'] = $this->em->getRepository(Text::class)->findOneBy(['idioma' => $idiomaId ,'seccion' => 'game-history', 'tipo' => 'contenido2']);
        $datos['gameHistoryContenido3'] = $this->em->getRepository(Text::class)->findOneBy(['idioma' => $idiomaId ,'seccion' => 'game-history', 'tipo' => 'contenido3']);
        $datos['gameHistoryContenido4'] = $this->em->getRepository(Text::class)->findOneBy(['idioma' => $idiomaId ,'seccion' => 'game-history', 'tipo' => 'contenido4']);
        
        $datos['developerDidac'] = $this->em->getRepository(Text::class)->findOneBy(['idioma' => $idiomaId ,'seccion' => 'developer-items', 'tipo' => 'subtitulo']);
        $datos['developerClaudia'] = $this->em->getRepository(Text::class)->findOneBy(['idioma' => $idiomaId ,'seccion' => 'developer-items', 'tipo' => 'subtitulo1']);
        $datos['developerDidacContent'] = $this->em->getRepository(Text::class)->findOneBy(['idioma' => $idiomaId ,'seccion' => 'developer-items', 'tipo' => 'contenido5']);
        $datos['developerClaudiaContent'] = $this->em->getRepository(Text::class)->findOneBy(['idioma' => $idiomaId ,'seccion' => 'developer-items', 'tipo' => 'contenido6']);
        
        $datos['developerSectionTitle'] = $this->em->getRepository(Text::class)->findOneBy(['idioma' => $idiomaId, 'seccion' => 'developer-items', 'tipo' => 'subtitulo2']);
        
        $datos['noticiasSectionTitle'] = $this->em->getRepository(Text::class)->findOneBy(['idioma' => $idiomaId, 'seccion' => 'noticias', 'tipo' => 'subtitulo']);
        $datos['verTodasNoticiasTexto'] = $this->em->getRepository(Text::class)->findOneBy(['idioma' => $idiomaId, 'seccion' => 'noticias', 'tipo' => 'button']);
        
        HomeView::show($datos, $sesionIniciada);
               
    }
}
