<?php
namespace Xiringuito\Controller;

use Doctrine\ORM\EntityManager;
use Xiringuito\Entity\Idioma;
use Xiringuito\Entity\Noticia;
use Xiringuito\Entity\Personatge;
use Xiringuito\Entity\Text;
use Xiringuito\Entity\Usuari;
use Xiringuito\View\NoticiaView;

class NoticiaController
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

    private function subirImagen(array &$errores): ?string
    {
        if (! isset($_FILES['img']) || $_FILES['img']['error'] !== UPLOAD_ERR_OK) {
            $errores['img'] = "No se ha subido ninguna imagen válida.";
            return null;
        }

        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/gif'
        ];
        $fileType = $_FILES['img']['type'];

        if (! in_array($fileType, $allowedTypes)) {
            $errores['img'] = "Formato de imagen no permitido. Solo se permiten JPG, PNG o GIF.";
            return null;
        }

        $imgTmpPath = $_FILES['img']['tmp_name'];
        $imgExtension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $imgName = uniqid('personatge_') . '.' . $imgExtension;

        $uploadDir = './public/temp/';
        $destPath = $uploadDir . $imgName;

        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (! move_uploaded_file($imgTmpPath, $destPath)) {
            $errores['img'] = "Error al guardar la imagen.";
            return null;
        }

        return $destPath;
    }

    private function cargarTextos(int $idiomaId): array
    {
        $textosRequeridos = [
            'form_noticias' => [
                'titol',
                'seleccionar_idioma',
                'placeholder_img',
                'placeholder_titol',
                'placeholder_descripcio',
                'btn_publicar',
                'btn_create'
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

    public function show($errores = array())
    {
        $langCode = $_SESSION['lang'] ?? 'ca';
        $idiomaId = $this->getIdiomaIdPorCodi($langCode);
        $sesionIniciada = isset($_SESSION['user_id']);
        $esAdmin = false;
        $idiomas = $this->em->getRepository(Idioma::class)->findAll();

        if ($sesionIniciada) {
            $usuari = $this->em->getRepository(Usuari::class)->find($_SESSION['user_id']);
            $esAdmin = $usuari && $usuari->getRol()->getNom() === 'admin';
        }

        $noticies = $this->em->getRepository(Noticia::class)->getAllByIdioma($idiomaId);
        $textos = $this->cargarTextos($idiomaId);
        NoticiaView::show($noticies, $sesionIniciada, $esAdmin, $textos, $idiomas, $errores);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = self::sanitize($_POST['titol']) ?? '';
            $descripcio = self::sanitize($_POST['descripcio']) ?? '';
            $idiomaId = (int) (self::sanitize($_POST['idioma_id']) ?? 0);
            $errores = [];

            if (! $nom)
                $errores['titol'] = "El título es obligatorio";
            if (! $descripcio)
                $errores['descripcio'] = "La descripción es obligatoria";
            if (! $idiomaId)
                $errores['idioma_id'] = "Debes seleccionar un idioma";

            if (! empty($errores)) {
                $this->show($errores);
                return;
            }

            if (! isset($_SESSION['user_id'])) {
                $this->show([
                    'general' => "Hay que iniciar sesión"
                ]);
                return;
            }

            $usuari = $this->em->getRepository(Usuari::class)->find($_SESSION['user_id']);
            if (! $usuari) {
                $this->show([
                    'general' => "Usuario no válido"
                ]);
                return;
            }

            $imgPath = $this->subirImagen($errores);
            if (! $imgPath) {
                $this->show($errores);
                return;
            }

            $repo = $this->em->getRepository(Noticia::class);
            $noticia = $repo->crearNoticia($nom, $descripcio, $idiomaId, $imgPath, new \DateTime(), $usuari);
            if (! $noticia) {
                $this->show([
                    'general' => "Error al crear la noticia"
                ]);
                return;
            }

            header('Location: index.php?/Noticia/show');
        }
    }
    
    public static function sanitize($a) {
        $a = stripslashes($a);
        $a = trim($a);
        $a = htmlspecialchars($a, ENT_QUOTES, 'UTF-8');
        return $a;
    }
}
