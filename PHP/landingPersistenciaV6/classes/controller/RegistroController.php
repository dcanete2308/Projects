<?php

class RegistroController extends Controller
{

    public function __construct()
    {}

    public function show()
    {
        $correcto = 0;
        $rutaCompleta = '';
        $frm_user = isset($_POST['user']) ? parent::sanitize(trim($_POST['user'])) : '';
        $frm_passwd = isset($_POST['passwd']) ? parent::sanitize(trim($_POST['passwd'])) : '';
        $frm_repetirPasswd = isset($_POST['passwdRepeat']) ? parent::sanitize(trim($_POST['passwdRepeat'])) : '';
        $frm_dni = isset($_POST['dni_nif']) ? parent::sanitize(trim($_POST['dni_nif'])) : '';
        $frm_nombre = isset($_POST['nombre']) ? parent::sanitize(trim($_POST['nombre'])) : '';
        $frm_apellido = isset($_POST['apellido']) ? parent::sanitize(trim($_POST['apellido'])) : '';
        $frm_fecha = isset($_POST['fecha']) ? parent::sanitize(trim($_POST['fecha'])) : '';
        $frm_genero = isset($_POST['genero']) ? parent::sanitize(trim($_POST['genero'])) : '';
        $frm_direccion = isset($_POST['direccion']) ? parent::sanitize(trim($_POST['direccion'])) : '';
        $frm_cp = isset($_POST['cp']) ? parent::sanitize(trim($_POST['cp'])) : '';
        $frm_provincia = isset($_POST['provincia']) ? parent::sanitize(trim($_POST['provincia'])) : '';
        $frm_telefono = isset($_POST['telefono']) ? parent::sanitize(trim($_POST['telefono'])) : '';
        $frm_tipo_identificacion = isset($_POST['tipo_identificacion']) ? parent::sanitize(trim($_POST['tipo_identificacion'])) : '';
        $frm_fotoPerfil = isset($_POST['fotoPerfil']) ? parent::sanitize(trim($_POST['fotoPerfil'])) : '';

        $errors = [];

        

        // Validar cada campo
        $userError = $this->comprobar_user($frm_user ?? '');
        if ($userError !== '') {
            $errors['user'] = $userError;
        } else {
            $correcto ++;
            $frm_user = parent::sanitize($frm_user);
        }

        $passwdError = $this->comprobar_passwd($frm_passwd ?? '');
        if ($passwdError !== '') {
            $errors['passwd'] = $passwdError;
        } else {
            $correcto ++;
            $frm_passwd = parent::sanitize($frm_passwd);
        }

        $repPasswdError = $this->comprobar_passwdRepe($frm_repetirPasswd ?? '', $frm_passwd ?? '');
        if ($repPasswdError !== '') {
            $errors['repetirPasswd'] = $repPasswdError;
        } else {
            $correcto ++;
            $frm_repetirPasswd = parent::sanitize($frm_repetirPasswd);
        }

        $nombreError = $this->comprobar_nombre($frm_nombre ?? '');
        if ($nombreError !== '') {
            $errors['nombre'] = $nombreError;
        } else {
            $correcto ++;
            $frm_nombre = parent::sanitize($frm_nombre);
        }

        $apellidoError = $this->comprobar_apellido($frm_apellido ?? '');
        if ($apellidoError !== '') {
            $errors['apellido'] = $apellidoError;
        } else {
            $correcto ++;
            $frm_apellido = parent::sanitize($frm_apellido);
        }

        $fechaError = $this->comprobar_fecha($frm_fecha ?? '');
        if ($fechaError !== '') {
            $errors['fecha'] = $fechaError;
        } else {
            $correcto ++;
            $frm_fecha = parent::sanitize($frm_fecha);
        }

        $cpError = $this->comprobar_cp($frm_cp ?? '');
        if ($cpError !== '') {
            $errors['cp'] = $cpError;
        } else {
            $correcto ++;
            $frm_cp = parent::sanitize($frm_cp);
        }

        $provinciaError = $this->comprobar_provincia($frm_provincia ?? '');
        if ($provinciaError !== '') {
            $errors['provincia'] = $provinciaError;
        } else {
            $correcto ++;
            $frm_provincia = parent::sanitize($frm_provincia);
        }

        $telError = $this->comprobar_tel($frm_telefono ?? '');
        if ($telError !== '') {
            $errors['telefono'] = $telError;
        } else {
            $correcto ++;
            $frm_telefono = parent::sanitize($frm_telefono);
        }

        $generoError = $this->comprobar_genero($frm_genero ?? '');
        if ($generoError !== '') {
            $errors['genero'] = $generoError;
        } else {
            $correcto ++;
            $frm_genero = parent::sanitize($frm_genero);
        }

        $dniError = $this->comprobar_identificacion($frm_dni ?? '', $frm_tipo_identificacion ?? '');
        if ($dniError !== '') {
            $errors['dni'] = $dniError;
        } else {
            $correcto ++;
            $frm_dni = parent::sanitize($frm_dni);
            $frm_tipo_identificacion = parent::sanitize($frm_tipo_identificacion);
        }


        if (isset($_FILES['imatge']) && isset($_FILES['imatge']['name'])) {
            $directorio = '../imagenes/';
            $nombreOriginal = $_FILES['imatge']['name'];
            $rutaCompleta = $directorio . $nombreOriginal;

            if ($_FILES['imatge']['error'] !== UPLOAD_ERR_OK) {
                $errors['imatge'] = "Error al subir la imagen.";
            } elseif ($_FILES['imatge']['size'] > 2 * 1024 * 1024) {
                $errors['imatge'] = "El archivo es demasiado grande.";
            } else {
                if (! is_dir($directorio)) {
                    if (! mkdir($directorio, 0777, true)) {
                        $errors['imatge'] = "No se pudo crear el directorio de destino.";
                    }
                }

                if (empty($errors['imatge'])) {
                    if (! move_uploaded_file($_FILES['imatge']['tmp_name'], $rutaCompleta)) {
                        $errors['imatge'] = "Error al guardar el archivo.";
                    } else {
                        $_SESSION['nombreUser'] = $frm_user;
                        $nombreOriginal = $rutaCompleta;
                    }
                }
            }
        } else {
            $errors['imatge'] = "No se ha enviado ninguna img.";
        }

        $nuevoUsuario = new UsuariModel();

        $nuevoUsuario->__set('user', $frm_user);
        $nuevoUsuario->__set('passwd', $frm_passwd);
        $nuevoUsuario->__set('repetirPasswd', $frm_repetirPasswd);
        $nuevoUsuario->__set('dni', $frm_dni);
        $nuevoUsuario->__set('nombre', $frm_nombre);
        $nuevoUsuario->__set('apellido', $frm_apellido);
        $nuevoUsuario->__set('fecha', $frm_fecha);
        $nuevoUsuario->__set('genero', $frm_genero);
        $nuevoUsuario->__set('direccion', $frm_direccion);
        $nuevoUsuario->__set('cp', $frm_cp);
        $nuevoUsuario->__set('provincia', $frm_provincia);
        $nuevoUsuario->__set('telefono', $frm_telefono);
        $nuevoUsuario->__set('tipo_identificacion', $frm_tipo_identificacion);
        $nuevoUsuario->__set('fotoPerfil', $rutaCompleta);
        $nuevoUsuario->__set("errors", $errors);
        $nuevoUsuario->__set("autorizado", 0);

        
        $user_data = [
            'usuario' => $frm_user,
            'passwd' => $frm_passwd,
            'repetirPasswd' => $frm_repetirPasswd,
            'dni' => $frm_dni,
            'tipo_identificacion' => $frm_tipo_identificacion,
            'nombre' => $frm_nombre,
            'apellido' => $frm_apellido,
            'fecha' => $frm_fecha,
            'genero' => $frm_genero,
            'direccion' => $frm_direccion,
            'cp' => $frm_cp,
            'provincia' => $frm_provincia,
            'telefono' => $frm_telefono,
            'fotoPerfil' => $rutaCompleta,
            'autorizado' => 0
        ];

        if ($correcto == 9 || $correcto == 6 && empty($errors['imatge'])) {
            $nuevoUsuario -> set($user_data);
            $vMail = new MailView();
            $vMail->show($nuevoUsuario->user);
            exit();
        }
        
        $vContacta = new RegistroView();
        $vContacta->showRegistro($nuevoUsuario);
    }

    private function comprobar_user($user)
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

    private function comprobar_passwd($passwd)
    {
        $passwdError = '';
        if (empty($passwd)) {
            $passwdError = $passwdError;
        } elseif (strlen($passwd) < 8) {
            $passwdError = 'No puede ser menor a 8 carácteres';
        }

        return $passwdError;
    }

    private function comprobar_passwdRepe($passwdRepe, $passwd)
    {
        if ($passwdRepe !== $passwd) {
            return 'Las contraseñas no coinciden';
        }
        return '';
    }

    private function comprobar_nombre($nombre)
    {
        $errorNombre = '';
        if (empty($nombre)) {
            return $errorNombre;
        } elseif (! preg_match("/^[a-zA-Z\s]+$/", $nombre)) {
            return "El nombre no está en el formato correcto";
        }
        return '';
    }

    private function comprobar_apellido($apellido)
    {
        if (empty($apellido)) {
            return $apellido;
        } elseif (! preg_match("/^[a-zA-Z\s]+$/", $apellido)) {
            return "El apellido no está en el formato correcto";
        }
        return '';
    }

    private function comprobar_fecha($fecha)
    {
        $fechaSeparada = explode('/', $fecha);
        if (count($fechaSeparada) == 3 && ! checkdate($fechaSeparada[1], $fechaSeparada[0], $fechaSeparada[2])) {
            return "La fecha no esta en el formato correcto";
        } elseif ($fecha == '') {
            return $fecha;
        }
    }

    private function comprobar_cp($cp)
    {
        if (empty($cp)) {
            return '';
        }

        if (strlen($cp) !== 5) {
            return 'El código postal debe tener exactamente 5 dígitos';
        }

        if (! is_numeric($cp)) {
            return 'El código postal debe contener solo números';
        }

        return '';
    }

    private function comprobar_provincia($provincia)
    {
        // copiado de internet
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

        if (! in_array($provincia, $provinciasEspana)) {
            return 'La provincia no existe';
        }
        return '';
    }

    private function comprobar_tel($tel)
    {
        if (empty($tel)) {
            return '';
        }

        if (! ctype_digit($tel) || strlen($tel) != 9) {
            return "El teléfono no está en el formato correcto";
        }
        return '';
    }

    private function comprobar_genero($genero)
    {
        if (empty($genero)) {
            return 'El genero no puede estar vacio';
        }
    }

    // copiado de internet
    private function comprobar_dni($dni)
    {
        $dniPattern = '/^\d{8}[A-Z]$/';
        if (empty($dni)) {
            return 'El DNI no puede estar vacío';
        } elseif (! preg_match($dniPattern, $dni)) {
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
    private function comprobar_nif($nif)
    {
        $nifPattern = '/^\d{8}[A-Z]$/';

        if (! preg_match($nifPattern, $nif)) {
            return false;
        }
        $numeros = substr($nif, 0, 8);
        $letra = substr($nif, - 1);
        $letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        $residuo = $numeros % 23;
        $letra_correcta = $letras[$residuo];
        if ($letra === $letra_correcta) {
            return true;
        } else {
            return false;
        }
    }

    private function comprobar_identificacion($dni, $tipoIdentificacion)
    {
        if ($tipoIdentificacion == 'dni') {
            return $this->comprobar_dni($dni);
        } elseif ($tipoIdentificacion == 'nif') {
            return $this->comprobar_nif($dni);
        }
        return '';
    }
}



