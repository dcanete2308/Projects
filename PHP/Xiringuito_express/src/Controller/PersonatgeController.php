<?php
namespace Xiringuito\Controller;

use Doctrine\ORM\EntityManager;
use Xiringuito\Entity\Idioma;
use Xiringuito\Entity\Nivell;
use Xiringuito\Entity\Personatge;
use Xiringuito\Entity\Text;
use Xiringuito\Entity\Usuari;
use Xiringuito\View\PersonatgeView;

class PersonatgeController
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
    
    private function subirImagen(array $errores): ?string
    {
        if (!isset($_FILES['img']) || $_FILES['img']['error'] !== UPLOAD_ERR_OK) {
            $errores['img'] = "No se ha subido ninguna imagen válida.";
            return null;
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['img']['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
            $errores['img'] = "Formato de imagen no permitido. Solo se permiten JPG, PNG o GIF.";
            return null;
        }
        
        $imgTmpPath = $_FILES['img']['tmp_name'];
        $imgExtension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $imgName = uniqid('personatge_') . '.' . $imgExtension;
        
        $uploadDir = './public/temp/';
        $destPath = $uploadDir . $imgName;
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        if (!move_uploaded_file($imgTmpPath, $destPath)) {
            $errores['img'] = "Error al guardar la imagen.";
            return null;
        }
        
        return $destPath;
    }

    private function cargarTextos(int $idiomaId): array
    {
        $textosRequeridos = [
            'mapas' => [
                'titulo',
                'boton_añadir'
            ],
            'personaje' => [
                'titulo',
                'modal_titulo',
                'placeholder_nombre',
                'placeholder_descripcion',
                'placeholder_local',
                'placeholder_vida',
                'placeholder_danyo',
                'seleccionar_idioma',
                'placeholder_img',
                'seleccionar_nivel',
                'boton_crear'
            ],
            'mapa' => [
                'modal_titulo',
                'placeholder_nombre',
                'seleccionar_idioma',
                'placeholder_img',
                'boton_crear'
            ]
        ];

        $textos = [];

        foreach ($textosRequeridos as $seccion => $tipos) {
            foreach ($tipos as $tipo) {
                $textEntity = $this->em->getRepository(Text::class)->findOneBy([
                    'idioma' => $idiomaId,
                    'seccion' => $seccion,
                    'tipo' => $tipo
                ]);
                $textos[$seccion][$tipo] = $textEntity ? $textEntity->getContenido() : '';
            }
        }

        return $textos;
    }

    private function renderVistaConErrores(array $errores = [])
    {
        $langCode = $_SESSION['lang'] ?? 'ca';
        $idiomaActual = $this->getIdiomaIdPorCodi($langCode);

        $mapas = $this->em->getRepository(Nivell::class)->getAllByIdioma($idiomaActual);
        $personatges = $this->em->getRepository(Personatge::class)->getAllByIdioma($idiomaActual);
        $idiomas = $this->em->getRepository(Idioma::class)->findAll();

        $sesionIniciada = isset($_SESSION['user_id']);
        $esAdmin = false;

        if ($sesionIniciada) {
            $usuari = $this->em->getRepository(Usuari::class)->find($_SESSION['user_id']);
            $esAdmin = $usuari && $usuari->getRol()->getNom() === 'admin';
        }

        $textos = $this->cargarTextos($idiomaActual);

        PersonatgeView::show($personatges, $mapas, $sesionIniciada, $esAdmin, $idiomas, $errores, [], $textos);
    }

    public function show()
    {
        $langCode = $_SESSION['lang'] ?? 'ca';
        $idiomaId = $this->getIdiomaIdPorCodi($langCode);

        $mapas = $this->em->getRepository(Nivell::class)->getAllByIdioma($idiomaId);
        $personatges = $this->em->getRepository(Personatge::class)->getAllByIdioma($idiomaId);

        $idiomas = $this->em->getRepository(Idioma::class)->findAll();

        $sesionIniciada = isset($_SESSION['user_id']);
        $esAdmin = false;

        if ($sesionIniciada) {
            $usuari = $this->em->getRepository(Usuari::class)->find($_SESSION['user_id']);
            $esAdmin = $usuari && $usuari->getRol()->getNom() === 'admin';
        }

        $textos = $this->cargarTextos($idiomaId);

        PersonatgeView::show($personatges, $mapas, $sesionIniciada, $esAdmin, $idiomas, $textos);
    }

    public function add()
    {
        $errores = []; 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $descripcio = $_POST['descripcio'] ?? '';
            $vida = (int) ($_POST['vida'] ?? 0);
            $dany = (int) ($_POST['dany'] ?? 0);
            $nivellId = (int) ($_POST['nivell_id'] ?? 0);
            $idiomaId = (int) ($_POST['idioma_id'] ?? 0);
            
            $imgName = $this->subirImagen($errores);
            if (!$imgName) {
                $this->renderVistaConErrores($errores);
                return;
            }           

            if (! $nom)
                $errores['nom'] = "El nombre es obligatorio";
            if (! $descripcio)
                $errores['descripcio'] = "La descripción es obligatoria";
            if (! $vida)
                $errores['vida'] = "La vida debe ser mayor que 0";
            if (! $dany)
                $errores['dany'] = "El daño debe ser mayor que 0";
            if (! $nivellId)
                $errores['nivell_id'] = "Debes seleccionar un nivel";
            if (! $idiomaId)
                $errores['idioma_id'] = "Debes seleccionar un idioma";

            if (! empty($errores)) {
                $this->renderVistaConErrores($errores);
                return;
            }

            $repo = $this->em->getRepository(Personatge::class);
            $personatge = $repo->crearPersonatge($nom, $descripcio, $vida, $dany, $nivellId, $idiomaId, $imgName);

            if (! $personatge) {
                $this->renderVistaConErrores([
                    'general' => "Nivel o idioma no encontrados"
                ]);
                return;
            }

            header('Location: index.php?/Personatge/show');
            exit();
        }
    }

    public function addNivell()
    {
        $errores = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $nom = $_POST['nom'] ?? '';
            $idiomaId = (int) ($_POST['idioma_id'] ?? 0);
            
            $sesionIniciada = isset($_SESSION['user_id']);
            $esAdmin = false;

            if ($sesionIniciada) {
                $usuari = $this->em->getRepository(Usuari::class)->find($_SESSION['user_id']);
                $esAdmin = $usuari && $usuari->getRol()->getNom() === 'admin';
            }
            
            $imgName = $this->subirImagen($errores);
            if (!$imgName) {
                $this->renderVistaConErrores($errores);
                return;
            } 
            
            if (! $nom)
                $errores['nom'] = "El nombre es obligatorio";
            if (! $idiomaId)
                $errores['idioma_id'] = "Debes seleccionar un idioma";

            if (! isset($_FILES['img']) || $_FILES['img']['error'] !== UPLOAD_ERR_OK) {
                $errores['img'] = "La imagen es obligatoria";
            }

            if (! empty($errores)) {
                $personatges = $this->em->getRepository(Personatge::class)->getAllByIdioma($idiomaId);
                $nivells = $this->em->getRepository(Nivell::class)->getAllByIdioma($idiomaId);
                $idiomaRepo = $this->em->getRepository(Idioma::class);
                $idiomas = $idiomaRepo->findAll();

                $textos = $this->cargarTextos($idiomaId);

                PersonatgeView::show($personatges, $nivells, $sesionIniciada, $esAdmin, $idiomas, $textos);
                return;
            }

            $nivellRepo = $this->em->getRepository(Nivell::class);
            $nivell = $nivellRepo->crearNivell($nom, $idiomaId, $imgName);

            if (! $nivell) {
                echo "Error al crear el nivel";
                return;
            }

            header('Location: index.php?/Personatge/show');
            exit();
        }
    }
    
    public static function sanitize($a) {
        $a = stripslashes($a);
        $a = trim($a);
        $a = htmlspecialchars($a, ENT_QUOTES, 'UTF-8');
        return $a;
    }
}
