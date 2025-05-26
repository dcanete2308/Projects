<?php

class RegistroView
{

    public function showRegistro(UsuariModel $user)

    {
        
        $userError = isset($user->errors['usuario']) ? $user->errors['usuario'] : '';
        $passwdError = isset($user->errors['passwd']) ? $user->errors['passwd'] : '';
        $repetirPasswdError = isset($user->errors['repetirPasswd']) ? $user->errors['repetirPasswd'] : '';
        $dniError = isset($user->errors['dni']) ? $user->errors['dni'] : '';
        $nombreError = isset($user->errors['nombre']) ? $user->errors['nombre'] : '';
        $apellidoError = isset($user->errors['apellido']) ? $user->errors['apellido'] : '';
        $fechaError = isset($user->errors['fecha']) ? $user->errors['fecha'] : '';
        $generoError = isset($user->errors['genero']) ? $user->errors['genero'] : '';
        $direccionError = isset($user->errors['direccion']) ? $user->errors['direccion'] : '';
        $cpError = isset($user->errors['cp']) ? $user->errors['cp'] : '';
        $provinciaError = isset($user->errors['provincia']) ? $user->errors['provincia'] : '';
        $telefonoError = isset($user->errors['telefono']) ? $user->errors['telefono'] : '';
        
        include "../langs/vars_esp.php";

        echo '<!DOCTYPE html>';
        echo '<html lang="es">';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<title>Landing Dídac</title>';
        echo '<link rel="stylesheet" href="../css/estilos.css?v=1.0">';
        echo '<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">';
        echo '<script src="../js/main.js"></script>';
        echo '</head>';

        echo '<header id="header">';
        echo '<div class="logo-container">';
        echo '<img src="../media/logoWild.png" alt="Logo" />';
        echo '</div>';
        echo '<nav>';
        echo '<ul>';
        echo "<li><a href='index.php?/Home/show'>$tituloHome</a></li>";
        echo "<li><a href='index.php?Login/show'>$titulologin</a></li>";
        echo "<li><a href='index.php?Estudios/show'>$tituloStudies</a></li>";
        echo "<li><a href='index.php?Inversiones/show'>$tituloinversions</a></li>";
        echo '</ul>';
        echo '</nav>';
        echo '</header>';

        echo '<body id="resgistro">';
        echo '<form method="POST" action="" enctype="multipart/form-data" class="login-form" id="registration-form">';

        echo '<div id="section-1" class="form-section">';
        echo '<label for="user-email">' . $usuario . '</label>';
        echo '<input type="email" id="' . ($userError ? "red" : "normal") . '" name="user" placeholder="' . ($userError ?: $placeholderUser) . '" value="' . $user->__get('user') . '" required>';

        echo '<label for="user-password">' . $password . '</label>';
        echo '<input type="password" id="' . ($passwdError ? "red" : "normal") . '" name="passwd" value="' . $user->__get('passwd') . '" placeholder="' . ($passwdError ?: $placeholderContra) . '" required>';

        echo '<label for="user-repeat-password">' . $repetirContra . '</label>';
        echo '<input type="password" id="' . ($repetirPasswdError ? "red" : "normal") . '" name="passwdRepeat" value="' . $user->__get('repetirPasswd') . '" placeholder="' . ($repetirPasswdError ?: $placeholderRepetirContra) . '" required>';

        echo '<div class="botones">';
        echo '<button type="button" id="next-1" class="next-button">' . $continuar . '</button>';
        echo '</div>';
        echo '</div>';

        echo '<div id="section-2" class="form-section">';
        echo '<label for="tipo_identificacion">' . $tipoIdentificacionMensaje . '</label>';
        echo '<select id="tipo_identificacion" name="tipo_identificacion" required>';
        echo '<option value="dni" ' . ($tipo_identificacion == "dni" ? "selected" : "") . '>DNI</option>';
        echo '<option value="nif" ' . ($tipo_identificacion == "nif" ? "selected" : "") . '>NIF</option>';
        echo '</select>';

        echo '<input type="text" id="' . ($dniError ? "red" : "normal") . '" name="dni_nif" value="' . $user->__get('dni') . '" placeholder="Introduce tu DNI o NIF" required>';

        echo '<label for="name">' . $nombreMensaje . '</label>';
        echo '<input type="text" id="' . ($nombreError ? "red" : "normal") . '" name="nombre" value="' . $user->__get('nombre') . '" placeholder="' . ($nombreError ?: $placeholderNombre) . '" required>';

        echo '<label for="apellido">' . $apellidoMensaje . '</label>';
        echo '<input type="text" id="' . ($apellidoError ? "red" : "normal") . '" name="apellido" value="' . $user->__get('apellido') . '" placeholder="' . ($apellidoError ?: $placeholderApellido) . '" required>';

        echo '<label for="fecha">' . $fechaMensaje . '</label>';
        echo '<input type="date" id="' . ($fechaError ? "red" : "normal") . '" value="' . (isset($_POST['fecha']) ? $_POST['fecha'] : '') . '" placeholder="' . ($fechaError ?: $placeholderFecha) . '" name="fecha" required>';

        echo '<div id="genero">';
        echo '<div id="options">';
        echo '<label for="masculino">' . $generoMasculino . '</label>';
        echo '<input type="radio" id="' . ($generoError ? "red" : "normal") . '" name="genero" value="masculino" ' . (isset($_POST['genero']) && $_POST['genero'] == 'masculino' ? 'checked' : '') . ' required>';

        echo '<label for="femenino">' . $generoFemenino . '</label>';
        echo '<input type="radio" id="' . ($generoError ? "red" : "normal") . '" name="genero" value="femenino" ' . (isset($_POST['genero']) && $_POST['genero'] == 'femenino' ? 'checked' : '') . ' required>';
        echo '</div>';
        echo '</div>';

        echo '<div class="botones">';
        echo '<button type="button" id="prev-1" class="prev-button">' . $back . '</button>';
        echo '<button type="button" id="next-1" class="next-button">' . $continuar . '</button>';
        echo '</div>';
        echo '</div>';

        echo '<div id="section-3" class="form-section">';
        echo '<label for="direccion">' . $dir . '</label>';
        echo '<input type="text" id="' . ($direccionError ? "red" : "normal") . '" name="direccion" value="' . $user->__get('direccion') . '" placeholder="' . ($direccionError ?: $placeholderDireccion) . '">';

        echo '<label for="cp">' . $codigoPostal . '</label>';
        echo '<input type="text" id="' . ($cpError ? "red" : "normal") . '" name="cp" value="' . $user->__get('cp') . '" placeholder="' . ($cpError ?: $placeholderCp) . '">';

        echo '<label for="provincia">' . $provinciaMensaje . '</label>';
        echo '<input type="text" id="' . ($provinciaError ? "red" : "normal") . '" name="provincia" value="' . $user->__get('provincia') . '" placeholder="' . ($provinciaError ?: $placeholderProvincia) . '">';

        echo '<label for="telefono">' . $tel . '</label>';
        echo '<input type="tel" id="' . ($telefonoError ? "red" : "normal") . '" name="telefono" value="' . $user->__get('telefono') . '" placeholder="' . ($telefonoError ?: $placeholderTelefono) . '">';

        echo '<div class="botones">';
        echo '<button type="button" id="prev-1" class="prev-button">' . $back . '</button>';
        echo '<button type="button" id="next-1" class="next-button">' . $continuar . '</button>';
        echo '</div>';
        echo '</div>';

        echo '<div id="section-4" class="form-section">';
        echo '<label for="user-image">' . $imagenUser . '</label>';
        echo '<input type="file" id="user-image" name="imatge" accept="image/*" required class="form-file">';

        echo '<button type="button" id="prev-1" class="prev-button">' . $back . '</button>';
        echo '<button type="submit" id="submit-button" class="submit-button">' . $send . '</button>';
        echo '</div>';

        echo '</form>';
        echo '</body>';
        echo '</html>';
    }
}

