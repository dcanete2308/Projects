<?php
namespace Xiringuito\Controller;

use Doctrine\ORM\EntityManager;
use Xiringuito\Entity\Idioma;
use Xiringuito\Entity\Text;
use Xiringuito\Entity\Usuari;
use Xiringuito\Entity\XiForo;
use Xiringuito\View\XiForoView;

class XiForoController
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
        $tipos = [
            'titulo',
            'descripcion',
            'tema',
            'responder_tema',
            'placeholder_respuesta',
            'boton_responder',
            'boton_enviar',
            'paginacion_primera',
            'paginacion_atras10',
            'paginacion_anterior',
            'paginacion_info',
            'paginacion_siguiente',
            'paginacion_adelante10',
            'paginacion_ultima',
            'modal_titulo',
            'publicado'
        ];
        
        $textos = [];
        
        foreach ($tipos as $tipo) {
            $textEntity = $this->em->getRepository(Text::class)->findOneBy([
                'idioma' => $idiomaId,
                'seccion' => 'foro',
                'tipo' => $tipo
            ]);
            $textos[$tipo] = $textEntity ? $textEntity->getContenido() : '';
        }
        
        return ['foro' => $textos];
    }
        
    public function post()
    {
        $langCode = $_SESSION['lang'] ?? 'ca';
        $idiomaActual = $this->getIdiomaIdPorCodi($langCode);
        
        $errors = [];
        $contenido = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contenido'])) {
            $contenido = trim($_POST['contenido']);
            $padreId = $_POST['padre_id'] ?? null;
            
            if (empty($contenido)) {
                $errors[] = "El contenido no puede estar vacío.";
            }
            
            if (!isset($_SESSION['user_id'])) {
                $errors[] = "Debes iniciar sesión para publicar.";
            }
            
            if (empty($errors)) {
                $usuario = $this->em->getRepository(Usuari::class)->find($_SESSION['user_id']);
                if (!$usuario) {
                    $errors[] = "Usuario no válido.";
                } else {
                    $padre = null;
                    if ($padreId) {
                        $padre = $this->em->getRepository(XiForo::class)->find($padreId);
                    }
                    
                    try {
                        $repo = $this->em->getRepository(XiForo::class);
                        $mensaje = $repo->guardarMensaje($usuario, $contenido, $padre);
                        
                        $this->em->persist($mensaje);
                        $this->em->flush();
                        
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        header("Location: index.php?XiForo/show&page=$page");
                        exit;
                    } catch (\Exception $e) {
                        $errors[] = "Error guardando mensaje: " . $e->getMessage();
                    }
                }
            }
        } else {
            $errors[] = "Método no permitido o contenido no recibido.";
        }
        
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 5;
        $repo = $this->em->getRepository(XiForo::class);
        $mensajes = $repo->findMensajesPaginados($page, $limit);
        $total = $repo->contarMensajes();
        $totalPaginas = ceil($total / $limit);
        $sesionIniciada = isset($_SESSION['user_id']);
        
        $textos = $this->cargarTextos($idiomaActual);
        
        XiForoView::show($sesionIniciada, $mensajes, $page, $totalPaginas, $errors, $contenido, $textos);
    }
    
    public function show()
    {
        $langCode = $_SESSION['lang'] ?? 'ca';
        $idiomaActual = $this->getIdiomaIdPorCodi($langCode);
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 4;
        
        $repo = $this->em->getRepository(XiForo::class);
        
        $mensajes = $repo->findMensajesPaginados($page, $limit);
        $total = $repo->contarMensajes();
        $totalPaginas = ceil($total / $limit);
        
        $sesionIniciada = isset($_SESSION['user_id']);
        
        $textos = $this->cargarTextos($idiomaActual);
        
        XiForoView::show($sesionIniciada, $mensajes, $page, $totalPaginas, [], [], $textos);
    }
}
