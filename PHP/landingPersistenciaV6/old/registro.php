<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "./langs/vars_esp.php";
session_start();


function sanitize($valor)
{
    $valor = htmlspecialchars($valor);
    $valor = stripcslashes($valor);
    return $valor;
}

$user = '';
$passwd = '';
$repetirPasswd = '';
$dni = '';
$nombre = '';
$apellido = '';
$fecha = '';
$genero = '';
$direccion = '';
$cp = '';
$provincia = '';
$telefono = '';
$tipo_identificacion = '';

$userError = '';
$passwdError = '';
$repPasswdError = '';
$dniError = '';
$nombreError = '';
$apellidoError = '';
$fechaError = '';
$generoError = '';
$direccionError = '';
$cpError = '';
$provinciaError = '';
$telefonoError = '';
$imagenError = '';

$correcto = 0;
$todoCorrecto = false;
$registrado = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = isset($_POST['user']) ? sanitize(trim($_POST['user'])) : '';
    $passwd = isset($_POST['passwd']) ? sanitize(trim($_POST['passwd'])) : '';
    $repetirPasswd = isset($_POST['passwdRepeat']) ? sanitize(trim($_POST['passwdRepeat'])) : '';
    $dni = isset($_POST['dni_nif']) ? sanitize(trim($_POST['dni_nif'])) : '';
    $tipo_identificacion = isset($_POST['tipo_identificacion']) ? $_POST['tipo_identificacion'] : 'dni';
    $nombre = isset($_POST['nombre']) ? sanitize(trim($_POST['nombre'])) : '';
    $apellido = isset($_POST['apellido']) ? sanitize(trim($_POST['apellido'])) : '';
    $fecha = isset($_POST['fecha']) ? sanitize(trim($_POST['fecha'])) : '';
    $genero = isset($_POST['genero']) ? sanitize(trim($_POST['genero'])) : '';
    $direccion = isset($_POST['direccion']) ? sanitize(trim($_POST['direccion'])) : '';
    $cp = isset($_POST['cp']) ? sanitize(trim($_POST['cp'])) : '';
    $provincia = isset($_POST['provincia']) ? sanitize(trim($_POST['provincia'])) : '';
    $telefono = isset($_POST['telefono']) ? sanitize(trim($_POST['telefono'])) : '';

    $userError = comprobar_user($user);
    if ($userError !== '') {
        $user = '';
    } else {
        $correcto ++;
    }

    $passwdError = comprobar_passwd($passwd);
    if ($passwdError !== '') {
        $passwd = '';
    } else {
        $correcto ++;
    }

    $repPasswdError = comprobar_passwdRepe($repetirPasswd, $passwd);
    if ($repPasswdError !== '') {
        $repetirPasswd = ''; 
    } else {
        $correcto++;
    }
    
    $dniError = comprobar_identificacion($dni, $tipo_identificacion);
    if ($dniError !== '') {
        $dni = '';
    } else {
        $correcto++;
    }

    $nombreError = comprobar_nombre($nombre);
    if ($nombreError !== '') {
        $nombre = '';
    } else {
        $correcto ++;
    }

    $apellidoError = comprobar_apellido($apellido);
    if ($apellidoError !== '') {
        $apellido = '';
    } else {
        $correcto ++;
    }

    $fechaError = comprobar_fecha($fecha);
    if ($fechaError !== '') {
        $fecha = '';
    } else {
        $correcto ++;
    }

    $generoError = comprobar_genero($genero);
    if ($generoError !== '') {
        $genero = '';
    } else {
        $correcto ++;
    }

    if (!empty($cp)) {
        $cpError = comprobar_cp($cp);
    }
    
    if (!empty($provincia)) {
        $provinciaError = comprobar_provincia($provincia);
    }
    
    if (!empty($telefono)) {
        $telefonoError = comprobar_tel($telefono);
    }
    
    
        $directorio='./imagenes/';
        
        if (!is_dir($directorio)) {
            if (!mkdir($directorio, 0777, true)) {
                $imagenError = "No se pudo crear el directorio de destino.";
            }
        }
        
        $nombreOriginal=$_FILES["imatge"]["name"];
        
        if ($_FILES["imatge"]["error"] !== UPLOAD_ERR_OK) {
            $imagenError = "Error al subir la imagen.";
        } elseif ($_FILES['imatge']['size'] > 2 * 1024 * 1024) {
            $imagenError = "El archivo es demasiado grande.";
        } else {
            if (!is_dir($directorio)) {
                if (!mkdir($directorio, 0777, true)) {
                    $imagenError = "No se pudo crear el directorio de destino.";
                }
            }
            if (empty($imagenError)) {
                if (!move_uploaded_file($_FILES["imatge"]["tmp_name"], $directorio.$nombreOriginal)) {
                    $imagenError = "Error al guardar el archivo.";
            }
        }
    }
    
   
    if ($correcto == 6 || $correcto==9 && empty($imagenError)) {
        $todoCorrecto = true;
    }

    if ($todoCorrecto) {
        $registrado="Te has registrado bien";
        $_SESSION['registro']==$user;
        header('Location: home.php');
        exit(); 
    } else {
        $registrado="No te has registrado bien";
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

function comprobar_passwdRepe($passwdRepe, $passwd)
{
    if ($passwdRepe !== $passwd) {
        return 'Las contraseñas no coinciden'; 
    }
    return '';
}

function comprobar_nombre($nombre)
{
    $errorNombre = '';
    if (empty($nombre)) {
        return $errorNombre;
    } elseif (!ctype_alpha($nombre)) {
        return "El nombre no está en el formato correcto";
    }
    return '';
}


function comprobar_apellido($apellido)
{
    if (!ctype_alpha($apellido)) {
        return "El apellido no está en el formato correcto";
    } else {
        return '';
    }
}

function comprobar_fecha($fecha)
{
    $fechaSeparada = explode('/', $fecha);
    if (count($fechaSeparada) == 3 && ! checkdate($fechaSeparada[1], $fechaSeparada[0], $fechaSeparada[2])) {
        return "La fecha no esta en el formato correcto";
    } elseif ($fecha == '') {
        return "La fecha no puede estar vacia";
    }
}

function comprobar_cp($cp)
{
    //copiado de internet
    $codigosPostalesEspana = [
        '01001',
        '01002',
        '01003',
        '02001',
        '02002',
        '02003',
        '03001',
        '03002',
        '03003',
        '04001',
        '04002',
        '04003',
        '33001',
        '33002',
        '33003',
        '05001',
        '05002',
        '05003',
        '06001',
        '06002',
        '06003',
        '08001',
        '08002',
        '08003',
        '09001',
        '09002',
        '09003',
        '10001',
        '10002',
        '10003',
        '11001',
        '11002',
        '11003',
        '39001',
        '39002',
        '39003',
        '12001',
        '12002',
        '12003',
        '13001',
        '13002',
        '13003',
        '14001',
        '14002',
        '14003',
        '15001',
        '15002',
        '15003',
        '16001',
        '16002',
        '16003',
        '17001',
        '17002',
        '17003',
        '18001',
        '18002',
        '18003',
        '19001',
        '19002',
        '19003',
        '20001',
        '20002',
        '20003',
        '21001',
        '21002',
        '21003',
        '22001',
        '22002',
        '22003',
        '23001',
        '23002',
        '23003',
        '24001',
        '24002',
        '24003',
        '25001',
        '25002',
        '25003',
        '27001',
        '27002',
        '27003',
        '28001',
        '28002',
        '28003',
        '29001',
        '29002',
        '29003',
        '30001',
        '30002',
        '30003',
        '31001',
        '31002',
        '31003',
        '32001',
        '32002',
        '32003',
        '34001',
        '34002',
        '34003',
        '35001',
        '35002',
        '35003',
        '36001',
        '36002',
        '36003',
        '37001',
        '37002',
        '37003',
        '38001',
        '38002',
        '38003',
        '40001',
        '40002',
        '40003',
        '41001',
        '41002',
        '41003',
        '42001',
        '42002',
        '42003',
        '43001',
        '43002',
        '43003',
        '44001',
        '44002',
        '44003',
        '45001',
        '45002',
        '45003',
        '46001',
        '46002',
        '46003',
        '47001',
        '47002',
        '47003',
        '48001',
        '48002',
        '48003',
        '49001',
        '49002',
        '49003',
        '50001',
        '50002',
        '50003',
        '51001',
        '52001',
        '08304',
        '08303',
        '08302'
    ];
    
    if (empty($cp)) {
        return ''; 
    }
    
    if (!in_array($cp, $codigosPostalesEspana)) {
       return "No existe el cp";
    } 
    
    return '';
}

function comprobar_provincia($provincia)
{
    //copiado de internet
    $provinciasEspana = [
        'Álava',
        'Albacete',
        'Alicante',
        'Almería',
        'Asturias',
        'Ávila',
        'Badajoz',
        'Barcelona',
        'Burgos',
        'Cáceres',
        'Cádiz',
        'Cantabria',
        'Castellón',
        'Ciudad Real',
        'Córdoba',
        'La Coruña',
        'Cuenca',
        'Gerona',
        'Granada',
        'Guadalajara',
        'Guipúzcoa',
        'Huelva',
        'Huesca',
        'Jaén',
        'León',
        'Lérida',
        'Lugo',
        'Madrid',
        'Málaga',
        'Murcia',
        'Navarra',
        'Orense',
        'Palencia',
        'Las Palmas',
        'Pontevedra',
        'La Rioja',
        'Salamanca',
        'Segovia',
        'Sevilla',
        'Soria',
        'Tarragona',
        'Santa Cruz de Tenerife',
        'Teruel',
        'Toledo',
        'Valencia',
        'Valladolid',
        'Vizcaya',
        'Zamora',
        'Zaragoza',
        'Ceuta',
        'Melilla'
    ];

    if (empty($provincia)) {
        return ''; 
    }
    
    
    if (!in_array($provincia, $provinciasEspana)) {
        return 'La provincia no existe';
    }   
    return '';
}

function comprobar_tel($tel)
{
    if (empty($tel)) {
        return ''; 
    }
    
   if (! ctype_digit($tel) || strlen($tel) != 9) {
        return "El teléfono no está en el formato correcto";
    }
    return '';
}

function comprobar_genero($genero){
    if (empty($genero)) {
        return 'El genero no puede estar vacio';
    }
}

// copiado de internet
function comprobar_dni($dni) {
    $dniPattern = '/^\d{8}[A-Z]$/';
    if (empty($dni)) {
        return 'El DNI no puede estar vacío';
    } elseif (!preg_match($dniPattern, $dni)) {
        return 'El formato del DNI es incorrecto';
    } else {
        $digits = substr($dni, 0, 8);
        $letter = strtoupper(substr($dni, 8, 1));
        $letters = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $calculatedLetter = $letters[$digits % 23];
        
        if ($letter !== $calculatedLetter) {
            return 'La letra del DNI no coincide';
        }
    }
    return '';
}

// copiado de internet
function comprobar_nif($nif) {
    $nifPattern = '/^\d{8}[A-Z]$/';

    if (!preg_match($nifPattern, $nif)) {
        return false; 
    }
    $numeros = substr($nif, 0, 8);
    $letra = substr($nif, -1);
    $letras = "TRWAGMYFPDXBNJZSQVHLCKE";
    $residuo = $numeros % 23;
    $letra_correcta = $letras[$residuo];
    if ($letra === $letra_correcta) {
        return true; 
    } else {
        return false; 
    }
}


function comprobar_identificacion($dni, $tipoIdentificacion) {
    if ($tipoIdentificacion == 'dni') {
        return comprobar_dni($dni);
    } elseif ($tipoIdentificacion == 'nif') {
        return comprobar_nif($dni);
    }
    return '';
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
<script>
        document.addEventListener("DOMContentLoaded", () => {
        
            const sections = document.querySelectorAll(".form-section");
            const nextButtons = document.querySelectorAll(".next-button");
            const prevButtons = document.querySelectorAll(".prev-button");
            let currentSection = 0;

            function showSection(index) {
                sections.forEach((section, i) => {
                    section.style.display = i === index ? "block" : "none";
                });
            }

            nextButtons.forEach((button, index) => {
                button.addEventListener("click", () => {
                    if (currentSection < sections.length - 1) {
                        currentSection++;
                        showSection(currentSection);
                    }
                });
            });

            prevButtons.forEach((button, index) => {
                button.addEventListener("click", () => {
                    if (currentSection > 0) {
                        currentSection--;
                        showSection(currentSection);
                    }
                });
            });

            showSection(currentSection);
        });
</script>
</head>

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
					<li><a href="registro.php?idioma=es">Español</a></li>
					<li><a href="registro.php?idioma=ch">Chino</a></li>
					<li><a href="registro.php?idioma=jp">Japonés</a></li>
					<li><a href="registro.php?idioma=ind">Indio</a></li>
					<li><a href="registro.php?idioma=morse">Morse</a></li>
				</ul></li>
			<li><a href="studies.php"><?php echo $tituloStudies; ?></a></li>
			<li><a href="Inversions.php"><?php echo $tituloinversions; ?></a></li>
		</ul>
	</nav>
</header>
<body id="resgistro">
	<form method="post" action="" class="login-form"
		enctype="multipart/form-data" id="registration-form">

		<div id="section-1" class="form-section">
			<label for="user-email"><?php echo $usuario?></label> 
			<input type="email" id="<?php echo $userError ? "red" : "normal"; ?>" name="user" placeholder="<?php echo $userError?: $placeholderUser?>" value="<?php echo $user ?>" required > 
				
			<label for="user-password"><?php echo $password?></label>
			<input type="password" id="<?php echo $passwdError ? "red" : "normal"; ?>" name="passwd" value="<?php echo $passwd?>" placeholder="<?php echo $passwdError?: $placeholderContra?>" required > 
			
			<label for="user-repeat-password"><?php echo $repetirContra?></label> 
			<input type="password" id="<?php echo $repPasswdError ? "red" : "normal"; ?>" name="passwdRepeat" value="<?php echo $repetirPasswd?>" placeholder="<?php echo $repPasswdError?: $placeholderRepetirContra?>" required >
				
			<div class="botones">
				<button type="button" id="next-1" class="next-button"><?php echo $continuar?></button>
			</div>
			
		</div>


		<div id="section-2" class="form-section">
		
				<label for="tipo_identificacion"><?php echo $tipoIdentificacionMensaje ?></label>
    			<select id="tipo_identificacion" name="tipo_identificacion" required>
        			<option value="dni" <?php echo ($tipo_identificacion == 'dni') ? 'selected' : ''; ?>>DNI</option>
        			<option value="nif" <?php echo ($tipo_identificacion == 'nif') ? 'selected' : ''; ?>>NIF</option>
    			</select>
    
    			<input type="text" id="<?php echo $dniError ? "red" : "normal"; ?>" name="dni_nif" value="<?php echo $dni ?>" placeholder="Introduce tu DNI o NIF" required> 
			
				<label for="name"><?php echo $nombreMensaje ?></label> 
				<input type="text" id="<?php echo $nombreError ? "red" : "normal"; ?>" name="nombre" value="<?php echo $nombre?>" placeholder="<?php echo $nombreError ?: $placeholderNombre?>" required > 
			
				<label for="apellido"><?php echo $apellidoMensaje ?></label> 
				<input type="text" id="<?php echo $apellidoError ? "red" : "normal"; ?>" name="apellido" value="<?php echo $apellido?>" placeholder="<?php echo $apellidoError?: $placeholderApellido?>" required > 
			
				<label for="fecha"><?php echo $fechaMensaje ?></label> 
				<input type="date" id="<?php echo $fechaError ? "red" : "normal"; ?>" value="<?php echo isset($_POST['fecha']) ? $_POST['fecha'] : ''; ?>"  placeholder="<?php echo $fechaError?: $placeholderFecha?>" name="fecha" required > 
						
				<div id='genero'>
					<div id='options'>
						<label for="masculino"><?php echo $generoMasculino ?></label>
						<input type="radio" id="<?php echo $generoError ? "red" : "normal"; ?>" name="genero" value="masculino" <?php echo (isset($_POST['genero']) && $_POST['genero'] == 'masculino') ? 'checked' : ''; ?> required>
	
						<label for="femenino"><?php echo $generoFemenino ?></label>
						<input type="radio" id="<?php echo $generoError ? "red" : "normal"; ?>" name="genero" value="femenino" <?php echo (isset($_POST['genero']) && $_POST['genero'] == 'femenino') ? 'checked' : ''; ?> required>
					</div>
				</div>
			
			<div class="botones">
				<button type="button" id="prev-1" class="prev-button"><?php echo $back?></button>
				<button type="button" id="next-1" class="next-button"><?php echo $continuar?></button>
			</div>
		</div>


		<div id="section-3" class="form-section">
			
			<label for="direccion"><?php echo $dir ?></label> 
			<input type="text" id="<?php echo $direccionError ? "red" : "normal"; ?>" name="direccion" value="<?php echo $direccion?>" placeholder="<?php echo $direccionError?: $placeholderDireccion?>"> 
			
			<label for="cp"><?php echo $codigoPostal ?></label>
			<input type="text" id="<?php echo $cpError ? "red" : "normal"; ?>" name="cp" value="<?php echo $cp?>" placeholder="<?php echo $cpError?: $placeholderCp?>">

			<label for="provincia"><?php echo $provinciaMensaje ?></label> 
			<input type="text"  id="<?php echo $provinciaError ? "red" : "normal"; ?>" name="provincia" value="<?php echo $provincia?>" placeholder="<?php echo $provinciaError?: $placeholderProvincia?>"> 
			
			<label for="telefono"><?php echo $tel ?></label> 
			<input type="tel"  id="<?php echo $telefonoError ? "red" : "normal"; ?>" name="telefono" value="<?php echo $telefono?>" placeholder="<?php echo $telefonoError?: $placeholderTelefono?>">

			<div class="botones">
				<button type="button" id="prev-1" class="prev-button"><?php echo $back?></button>
				<button type="button" id="next-1" class="next-button"><?php echo $continuar?></button>
			</div>
		</div>


		<div id="section-4" class="form-section">
			<label for="user-image"><?php echo $imagenUser ?></label> 
			<input type="file" id="user-image" name="imatge" accept="image/*" required class="form-file">


			<button type="button" id="prev-1" class="prev-button"><?php echo $back?></button>
			<button type="submit" id="submit-button" class="submit-button"><?php echo $send?></button>
		</div>
			<p id="validacion"><?php echo $registrado ?></p>		
	</form>
</body>
</html>