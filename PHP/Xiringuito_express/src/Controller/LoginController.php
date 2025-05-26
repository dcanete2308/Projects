<?php
namespace Xiringuito\Controller;

use Doctrine\ORM\EntityManager;
use Xiringuito\Entity\Idioma;
use Xiringuito\Entity\Text;
use Xiringuito\View\LoginView;

class LoginController
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

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        session_destroy();
        header("Location: index.php?/Home/show");
        exit;
    }
    
    public function login()
    {
        $errors = [
            'email' => '',
            'password' => '',
            'general' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = self::sanitize(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
            $password = self::sanitize(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

            if (! $email) {
                $errors['email'] = 'Introduce un email válido.';
            }
            if (! $password) {
                $errors['password'] = 'Introduce una contraseña.';
            }

            if (empty($errors['email']) && empty($errors['password'])) {
                $repo = $this->em->getRepository(\Xiringuito\Entity\Usuari::class);
                $user = $repo->findByEmailAndPassword($email, $password);

                if ($user) {
                    $_SESSION['user_id'] = $user->getId();
                    $_SESSION['user_name'] = $user->getNom();
                    header('Location: index.php?/Home/show');
                    exit();
                } else {
                    $errors['general'] = 'Email o contraseña incorrectos.';
                }
            }

            $this->show($email, $errors);
        } else {
            $this->show();
        }
    }

    public function show($email = '', array $errors = [])
    {
        if (! is_string($email)) {
            $email = '';
        }

        $langCode = $_SESSION['lang'] ?? 'ca';
        $idiomaId = $this->getIdiomaIdPorCodi($langCode);

        $titulo = $this->em->getRepository(Text::class)->findOneBy([
            'idioma' => $idiomaId,
            'seccion' => 'login',
            'tipo' => 'titulo'
        ]);

        $labelEmail = $this->em->getRepository(Text::class)->findOneBy([
            'idioma' => $idiomaId,
            'seccion' => 'login',
            'tipo' => 'label_email'
        ]);

        $labelPassword = $this->em->getRepository(Text::class)->findOneBy([
            'idioma' => $idiomaId,
            'seccion' => 'login',
            'tipo' => 'label_password'
        ]);

        $buttonText = $this->em->getRepository(Text::class)->findOneBy([
            'idioma' => $idiomaId,
            'seccion' => 'login',
            'tipo' => 'button'
        ]);

        $registroPregunta = $this->em->getRepository(Text::class)->findOneBy([
            'idioma' => $idiomaId,
            'seccion' => 'login',
            'tipo' => 'registro_pregunta'
        ]);

        $registroEnlace = $this->em->getRepository(Text::class)->findOneBy([
            'idioma' => $idiomaId,
            'seccion' => 'login',
            'tipo' => 'registro_enlace'
        ]);

        LoginView::show($titulo, $labelEmail, $labelPassword, $buttonText, $registroPregunta, $registroEnlace, $email, $errors);
    }

    public static function sanitize($a)
    {
        $a = stripslashes($a);
        $a = trim($a);
        $a = htmlspecialchars($a, ENT_QUOTES, 'UTF-8');
        return $a;
    }
}