<?php
include "./langs/vars_esp.php";
session_start();

$user = '';
$passwd = '';

$userError = '';
$passwdError = '';
$sesionIniciada = '';
$userIniciado = false;
$passwdIniciado = false;

function sanitize($valor)
{
    $valor = htmlspecialchars($valor);
    $valor = stripcslashes($valor);
    return $valor;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = isset($_POST['user']) ? sanitize(trim($_POST['user'])) : '';
    $passwd = isset($_POST['passwd']) ? sanitize(trim($_POST['passwd'])) : '';

    $passwdError = comprobar_passwd($passwd);
    if ($passwdError !== '') {
        $passwd = '';
    } else {
        $userIniciado = true;
    }

    $userError = comprobar_user($user);
    if ($userError !== '') {
        $user = '';
    } else {
        $passwdIniciado = true;
    }

    if ($passwdIniciado == true && $userIniciado == true) {
        $sesionIniciada = 'Sesion Iniciada Correctamente';
        $_SESSION['usuario'] = $user;
    } else {
        $sesionIniciada = 'Fallo en la autentificación';
    }
}

function comprobar_user($user)
{
    $UserError = '';
    if (empty($user)) {
        return $UserError;
    } elseif (! filter_var($user, FILTER_VALIDATE_EMAIL)) {
        return "El usuario no está en el formato correcto";
    }
    return '';

    return $UserError;
}

function comprobar_passwd($passwd)
{
    $passwdError = '';
    if (empty($passwd)) {
        $passwdError = 'No puede estar vacio';
    } elseif (strlen($passwd) < 8) {
        $passwdError = 'No puede ser menor a 8 carácteres';
    }

    return $passwdError;
}

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

?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Landing Dídac</title>
<link rel="stylesheet" href="./estilos.css?v=1.0">
<link
	href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap"
	rel="stylesheet">
</head>

<body>
	<header id="header">
		<div class="logo-container">
			<img src="./media/logoWild.png" alt="Logo" />
		</div>
		<nav>
			<ul>
				<li><a href="home.php"><?php echo $tituloHome; ?></a></li>
				<li><a href="login.php"><?php echo $titulologin; ?></a></li>
				<li><?php echo $tituloIdioma; ?>
            <ul class="dropdown">
						<li><a href="login.php?idioma=es">Español</a></li>
						<li><a href="login.php?idioma=ch">Chino</a></li>
						<li><a href="login.php?idioma=jp">Japonés</a></li>
						<li><a href="login.php?idioma=ind">Indio</a></li>
						<li><a href="login.php?idioma=morse">Morse</a></li>
					</ul></li>
				<li><a href="studies.php"><?php echo $tituloStudies; ?></a></li>
				<li><a href="Inversions.php"><?php echo $tituloinversions; ?></a></li>
			</ul>
		</nav>
	</header>
	<main id="loginMain">
		<div>
			<form method="post" action="" class="login-form">
				<p><?php echo $usuario?></p>
				<input type="email" name="user"
					id="<?php echo $userError ? "red" : "normal"; ?>"
					placeholder="<?php echo $userError ?: $placeholderUser; ?>"
					value="<?php echo $user; ?>" required>
				<p><?php echo $password?></p>
				<input type="password" name="passwd"
					id="<?php echo $passwdError ? "red" : "normal"; ?>"
					placeholder="<?php echo $passwdError ?: $placeholderPasswd; ?>"
					value="<?php echo $passwd; ?>" required>
				<button type="submit"><?php echo $botonEnviar; ?></button>
				<button id="botonRegistrar" type="button" onclick="location.href='registro.php';"><?php echo $botonRegistrar; ?></button>
			</form>
			<br>
			<p id="validacion"><?php echo $sesionIniciada ?></p>
		</div>
	</main>
</body>

</html>