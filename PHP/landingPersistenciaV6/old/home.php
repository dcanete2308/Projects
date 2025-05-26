<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "./langs/vars_esp.php";

if (isset($_SERVER["REQUEST_METHOD"])) {
    $idioma = $_GET['idioma'] ?? 'es';
    setcookie("idioma", $idioma, time() + 31536000);
    switch ($idioma) {
        case 'es':
            include "./langs/vars_esp.php";
            break;
        case 'ch':
            include "./langs/vars_ch.php";
            break;
        case 'jp':
            include "./langs/vars_jp.php";
            break;
        case 'ind':
            include "./langs/vars_ind.php";
            break;
        case 'morse':
            include "./langs/vars_morse.php";
            break;
    }
} else {
    echo "No se ha podido traducir";
}

$fechaActual = date('Y-m-d H:i:s');
$correcto = 0;
$nombre = '';
$correo = '';
$telefono = '';
$mensaje = '';

$nombre_error = '';
$correo_error = '';
$telefono_error = '';
$mensaje_error = '';

function sanitize($valor)
{
    $valor = htmlspecialchars($valor);
    $valor = stripcslashes($valor);
    return $valor;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre']) ? sanitize(trim($_POST['nombre'])) : '';
    $correo = isset($_POST['correo']) ? sanitize(trim($_POST['correo'])) : '';
    $telefono = isset($_POST['telefono']) ? sanitize(trim($_POST['telefono'])) : '';
    $mensaje = isset($_POST['mensaje']) ? sanitize(trim($_POST['mensaje'])) : '';

    $nombre_error = comprobar_nombre($nombre);
    if ($nombre_error !== '') {
        $nombre = '';
    } else {
        $correcto ++;
    }

    $correo_error = comprobar_correo($correo);
    if ($correo_error !== '') {
        $correo = '';
    } else {
        $correcto ++;
    }

    $telefono_error = comprobar_tel($telefono);
    if ($telefono_error !== '') {
        $telefono = '';
    } else {
        $correcto ++;
    }

    $mensaje_error = comprobar_msg($mensaje);
    if ($mensaje_error !== '') {
        $mensaje = '';
    } else {
        $correcto ++;
    }

    if ($correcto == 4) {
        $file = "formDatos.xml";
        $contenido = file_get_contents($file);

        $contactador = "\n<contact>\n";
        $contactador .= "   <name>{$nombre}</name>\n";
        $contactador .= "   <email>{$correo}</email>\n";
        $contactador .= "   <telefono>{$telefono}</telefono>\n";
        $contactador .= "   <mensaje>{$mensaje}</mensaje>\n";
        $contactador .= "   <date>{$fechaActual}</date>\n";
        $contactador .= "</contact>";

        $contenido = str_replace("</contactos>", $contactador . "\n</contactos>", $contenido);

        file_put_contents($file, $contenido);

        $nombre = '';
        $correo = '';
        $telefono = '';
        $mensaje = '';
    }
}

function comprobar_nombre($nombre)
{
    global $errorNombre;
    if (empty($nombre)) {
        return $errorNombre;
    } elseif (! ctype_alpha($nombre)) {
        return "El nombre no está en el formato correcto";
    }
    return '';
}

function comprobar_correo($correo)
{
    global $errorCorreo;
    if (empty($correo)) {
        return $errorCorreo;
    } elseif (! filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        return "El correo no está en el formato correcto";
    }
    return '';
}

function comprobar_tel($tel)
{
    global $errorTelefono;
    if (empty($tel)) {
        return $errorTelefono;
    } elseif (! ctype_digit($tel) || strlen($tel) != 9) {
        return "El teléfono no está en el formato correcto";
    }
    return '';
}

function comprobar_msg($msg)
{
    global $errorMensaje;
    if (empty($msg)) {
        return $errorMensaje;
    }
    return '';
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $tituloPagina; ?></title>
<link rel="stylesheet" href="./estilos.css">
<link
	href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap"
	rel="stylesheet">
</head>

<body>
	<header id="header">
		<div class="logo-container">
			<img src="./media/logoWild.png" alt="Logo" />
			<img src="./media/usuarioNoRegistrado.jpg" alt="Logo" />
		</div>
		<nav>
			<ul>
				<li><a href="home.php"><?php echo $tituloHome; ?></a></li>
				<li><a href="login.php"><?php echo $titulologin; ?></a></li>
				<li><?php echo $tituloIdioma; ?>
            <ul class="dropdown">
						<li><a href="home.php?idioma=es">Español</a></li>
						<li><a href="home.php?idioma=ch">Chino</a></li>
						<li><a href="home.php?idioma=jp">Japonés</a></li>
						<li><a href="home.php?idioma=ind">Indio</a></li>
						<li><a href="home.php?idioma=morse">Morse</a></li>
					</ul></li>
				<li><a href="studies.php"><?php echo $tituloStudies; ?></a></li>
				<li><a href="Inversions.php"><?php echo $tituloinversions; ?></a></li>
			</ul>
		</nav>
	</header>
	<main>
		<section id="about">
			<div class="content">
				<div class="text-content">
					<h3><?php echo $descripcion; ?></h3>
					<hr>
					<h4><?php echo $subtitulo; ?></h4>
					<p><?php echo $textoSobreMi; ?></p>
					<p><?php echo $textoFinal; ?></p>
					<div class="form-container">
						<h2><?php echo $formTitulo; ?></h2>
						<form method="post" action="" class="contact-form">
							<input type="text" name="nombre"
								id="<?php echo $nombre_error ? "red" : "normal"; ?>"
								placeholder="<?php echo $nombre_error ?: $placeholderNombre; ?>"
								value="<?php echo $nombre; ?>" required> <input type="email"
								name="correo"
								id="<?php echo $correo_error ? "red" : "normal"; ?>"
								placeholder="<?php echo $correo_error ?: $placeholderCorreo; ?>"
								value="<?php echo $correo; ?>" required> <input type="tel"
								name="telefono"
								id="<?php echo $telefono_error ? "red" : "normal"; ?>"
								placeholder="<?php echo $telefono_error ?: $placeholderTelefono; ?>"
								value="<?php echo $telefono; ?>" required>
							<textarea id="<?php echo $mensaje_error ? "red" : "normal"; ?>"
								name="mensaje" rows="4" cols="50"
								placeholder="<?php echo $mensaje_error ?: $placeholderMensaje; ?>"
								required><?php echo $mensaje; ?></textarea>
							<button type="submit"><?php echo $botonEnviar; ?></button>
						</form>
					</div>
				</div>
				<div class="image-container">
					<img id="imgDidac" src="./media/fotoDidac.JPG" alt="Foto de Dídac" />
				</div>
			</div>
		</section>
	</main>

	<footer>
		<div class="social-icons">
			<a href="#"><img src="./media/instagram.png" alt="Instagram" /></a> <a
				href="#"><img src="./media/linkedin.png" alt="LinkedIn" /></a> <a
				href="#"><img src="./media/facebook-f.png" alt="Facebook" /></a> <a
				href="#"><img src="./media/X_logo.png" alt="X" /></a>
		</div>
	</footer>
</body>

</html>
