<?php
namespace Xiringuito\Controller;

use Doctrine\ORM\EntityManager;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Xiringuito\Entity\Usuari;
use Xiringuito\Entity\Idioma;
use Xiringuito\Entity\Text;
use Xiringuito\Repository\UsuariRepository;
use Xiringuito\View\RegisterView;

class RegisterController
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

    public function register()
    {
        $errors = [
            'name' => '',
            'surname' => '',
            'email' => '',
            'password' => '',
            'password_repeat' => '',
            'general' => ''
        ];

        $name = '';
        $surname = '';
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = self::sanitize(trim($_POST['name']) ?? '');
            $surname = self::sanitize(trim($_POST['surname']) ?? '');
            $email = self::sanitize(trim($_POST['email']) ?? '');
            $password = self::sanitize($_POST['password']) ?? '';
            $passwordRepeat = self::sanitize($_POST['password_repeat']) ?? '';

            if ($name === '') {
                $errors['name'] = 'El nombre es obligatorio.';
            } elseif (! preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-']+$/u", $name)) {
                $errors['name'] = 'El nombre solo puede contener letras, espacios, guiones o apóstrofes.';
            }

            if ($surname !== '' && ! preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-']+$/u", $surname)) {
                $errors['surname'] = 'El apellido solo puede contener letras, espacios, guiones o apóstrofes.';
            }

            if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Introduce un email válido.';
            }

            if ($password === '') {
                $errors['password'] = 'La contraseña es obligatoria.';
            } elseif (! preg_match('/^(?=.*[a-zA-Z])(?=.*\d).{8,}$/', $password)) {
                $errors['password'] = 'La contraseña debe tener al menos 8 caracteres, incluir letras y números.';
            }

            if ($passwordRepeat === '') {
                $errors['password_repeat'] = 'Repetir contraseña es obligatorio.';
            } elseif ($password !== $passwordRepeat) {
                $errors['password_repeat'] = 'Las contraseñas no coinciden.';
            }
            
            $acceptTerms = $_POST['accept_terms'] ?? null;
            if (!$acceptTerms) {
                $errors['termsLabel'] = 'Debes aceptar los términos y condiciones.';
            }

            $repo = $this->em->getRepository(Usuari::class);
            if ($repo->findOneBy([
                'email' => $email
            ])) {
                $errors['email'] = 'Ya existe un usuario con este correo.';
            }

            if (empty($errors['name']) && empty($errors['email']) && empty($errors['password']) && empty($errors['password_repeat']) && empty($errors['termsLabel'])) {
                try {
                    $repo->createUser($name, $surname, $email, $password);
                    header('Location: index.php?/Login/show');

                    $mail = new PHPMailer(true);

                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'xiringuitoexpress@gmail.com';
                        $mail->Password = 'vssb fixl wzxk wmqk';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        $mail->setFrom('xiringuitoexpress@gmail.com', 'Xiringuito');
                        $mail->addAddress($email, "$name $surname");

                        $mail->isHTML(true);
                        $mail->Subject = 'Bienvenido a Xiringuito';

                        $mail->Body = "
                            <!DOCTYPE html>
                            <html lang='es'>
                            <head>
                                <meta charset='UTF-8'>
                                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                                <title>Bienvenid@ a Xiringuito_express</title>
                                <style>
                                    body {
                                        font-family: 'Helvetica', Arial, sans-serif;
                                        line-height: 1.6;
                                        color: #333333;
                                        margin: 0;
                                        padding: 0;
                                    }
                                    .container {
                                        max-width: 600px;
                                        margin: 0 auto;
                                        padding: 20px;
                                        background-color: #ffffff;
                                    }
                                    .header {
                                        text-align: center;
                                        padding: 20px 0;
                                        background-color: #FF9A3C;
                                        border-radius: 10px 10px 0 0;
                                    }
                                    .header h1 {
                                        color: #ffffff;
                                        margin: 0;
                                        font-size: 28px;
                                    }
                                    .content {
                                        padding: 30px 20px;
                                        background-color: #FFEFD5;
                                        border-left: 1px solid #FFD700;
                                        border-right: 1px solid #FFD700;
                                    }
                                    .footer {
                                        text-align: center;
                                        padding: 15px;
                                        background-color: #FF9A3C;
                                        color: #ffffff;
                                        font-size: 12px;
                                        border-radius: 0 0 10px 10px;
                                    }
                                    .button {
                                        display: inline-block;
                                        padding: 12px 20px;
                                        margin: 20px 0;
                                        background-color: #FF5733;
                                        color: #ffffff;
                                        text-decoration: none;
                                        border-radius: 5px;
                                        font-weight: bold;
                                    }
                                    .social-links {
                                        margin-top: 20px;
                                    }
                                    .social-links a {
                                        margin: 0 10px;
                                        color: #FF5733;
                                        text-decoration: none;
                                    }
                                    .icon {
                                        font-size: 22px;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class='container'>
                                    <div class='header'>
                                        <h1>¡Bienvenid@ a Xiringuito_express!</h1>
                                    </div>
                                    <div class='content'>
                                        <h2>¡Hola $name!</h2>
                                        <p>¡Estamos encantados de darte la bienvenida a la comunidad de <strong>Xiringuito_express</strong>! Tu aventura culinaria acaba de comenzar.</p>
                                        <p>En Xiringuito_express creemos que la cocina es el juego más divertido y delicioso. Prepárate para descubrir nuestro mundo de experiencias gastronómicas interactivas donde aprenderás, te divertirás y crearás platos increíbles.</p>
                                        <h3>¿Qué puedes esperar?</h3>
                                        <ul>
                                            <li>Juegos educativos que revolucionarán tu forma de cocinar</li>
                                            <li>Una comunidad apasionada por la gastronomía</li>
                                            <li>Acceso a recetas exclusivas y contenido premium</li>
                                        </ul>
                                        <p>¡Esperamos verte pronto en la cocina!</p>
                                        <p>Saludos gastronómicos,<br> El equipo de Xiringuito_express</p>
                                    </div>
                                    <div class='footer'>
                                        <p>© 2025 Xiringuito_express - Todos los derechos reservados</p>
                                    </div>
                                </div>
                            </body>
                            </html>
                        ";

                        $mail->send();
                    } catch (Exception $e) {
                        error_log("No se pudo enviar el correo: {$mail->ErrorInfo}");
                    }
                    exit();
                } catch (\Exception $e) {
                    $errors['general'] = 'Error al registrar: ' . $e->getMessage();
                }
            }
        }

        $this->show($name, $surname, $email, $errors);
    }

    public function show($name = '', $surname = '', $email = '', $errors = [])
    {
        if (is_array($name)) {
            $errors = $name;
            $name = '';
            $surname = '';
            $email = '';
        }

        $langCode = $_SESSION['lang'] ?? 'ca';
        $idiomaId = $this->getIdiomaIdPorCodi($langCode);

        $repo = $this->em->getRepository(Text::class);

        $titulo = $repo->findOneBy([
            'idioma' => $idiomaId,
            'seccion' => 'signup',
            'tipo' => 'titulo'
        ]);
        $labelNombre = $repo->findOneBy([
            'idioma' => $idiomaId,
            'seccion' => 'signup',
            'tipo' => 'label_name'
        ]);
        $labelApellido = $repo->findOneBy([
            'idioma' => $idiomaId,
            'seccion' => 'signup',
            'tipo' => 'label_surname'
        ]);
        $labelEmail = $repo->findOneBy([
            'idioma' => $idiomaId,
            'seccion' => 'signup',
            'tipo' => 'label_email'
        ]);
        $labelPassword = $repo->findOneBy([
            'idioma' => $idiomaId,
            'seccion' => 'signup',
            'tipo' => 'label_password'
        ]);
        $labelRepeatPassword = $repo->findOneBy([
            'idioma' => $idiomaId,
            'seccion' => 'signup',
            'tipo' => 'label_password_repeat'
        ]);
        $buttonText = $repo->findOneBy([
            'idioma' => $idiomaId,
            'seccion' => 'signup',
            'tipo' => 'button'
        ]);
        $terms = $repo->findOneBy([
            'idioma' => $idiomaId,
            'seccion' => 'termes',
            'tipo' => 'termes'
        ]);
        $termsTitol = $repo->findOneBy([
            'idioma' => $idiomaId,
            'seccion' => 'termes',
            'tipo' => 'titol'
        ]);
        $termsLabel = $repo->findOneBy([
            'idioma' => $idiomaId,
            'seccion' => 'termes',
            'tipo' => 'label'
        ]);
        $datos = [
            'titulo' => $titulo,
            'labelNombre' => $labelNombre,
            'labelEmail' => $labelEmail,
            'labelApellido' => $labelApellido,
            'labelPassword' => $labelPassword,
            'labelRepeatPassword' => $labelRepeatPassword,
            'buttonText' => $buttonText,
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'terms' => $terms,
            'termsTitle' => $termsTitol,
            'termsLabel' => $termsLabel
        ];

        RegisterView::show($datos, $errors);
    }

    public static function sanitize($a)
    {
        $a = stripslashes($a);
        $a = trim($a);
        $a = htmlspecialchars($a, ENT_QUOTES, 'UTF-8');
        return $a;
    }
}