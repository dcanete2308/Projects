<?php

class LoginView2
{
    
    public function showLogin(UsuariModel $user)
    {
        include "../langs/vars_esp.php";
        $userError = isset($user->errors['usuario']) ? $user->errors['usuario'] : '';
        $passwdError = isset($user->errors['passwd']) ? $user->errors['passwd'] : '';
               
        echo '<!DOCTYPE html>';
        echo '<html lang="es">';
        echo '<head>';
        echo '    <meta charset="UTF-8">';
        echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '    <title>Landing Dídac</title>';
        echo '    <link rel="stylesheet" href="../css/estilos.css?v=1.0">';
        echo '    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">';
        echo '</head>';
        
        echo '<body>';
        
        echo '<header id="header">';
        echo '        <div class="logo-container">';
        echo '            <img src="../media/logoWild.png" alt="Logo" />';
        echo '        </div>';
        echo '        <nav>';
        echo '            <ul>';
        echo "                <li><a href='index.php?/Home/show'>$tituloHome</a></li>";
        echo "                <li><a href='index.php?Login/show'>$titulologin</a></li>";
        echo "                <li><a href='index.php?Estudios/show'>$tituloStudies</a></li>";
        echo "                <li><a href='index.php?Inversiones/show'>$tituloinversions</a></li>";
        echo "                <li><a href='index.php?Autor/show'>Frases</a></li>";
        echo '            </ul>';
        echo '        </nav>';
        echo '    </header>';
        
        echo '<main id="loginMain">';
        echo '    <section id="newsSection">';
        echo '        <div class="news-container">';
        echo '            <div class="news-text">';
        echo '                <h2>Últimas Noticias</h2>';
        echo '            <p><strong>Guía para el registro:</strong></p>';
        echo '            <ol>';
        echo '                <li>Le damos permisos a la carpeta de la actividad porque se ha de guardar la foto de perfil en una carpeta y si no damos permisos no dejará hacerlo.</li>';
        echo '                <li>Le damos a Registrarse.</li>';
        echo '                <li>Introducimos el nombre que ha de ser un correo como "phpUser@gmail.com".</li>';
        echo '                <li>Introducimos la contraseña, yo recomiendo "asdfghjkl".</li>';
        echo '                <li>Introducimos el DNI (ha de ser real porque si no da error).</li>';
        echo '                <li>Introducimos nombre, apellido y fecha.</li>';
        echo '                <li>La dirección, el código postal, la provincia y el teléfono son opcionales.</li>';
        echo '                <li>Adjuntamos una foto y le damos a registrar.</li>';
        echo '            </ol>';
        echo '            <p>Una vez registrados, volvemos al login y nos identificamos con el correo y la contraseña que hemos puesto. Con eso podremos acceder a las Frases.</p>';
        echo '            <p>La foto que se ve a la izquierda son todos los archivos que he creado para la actividad de frases célebres. Así espero ayudar que no tengas que buscar entre los archivos de las otras actividades para encontrar los que he creado.</p>';
        echo '            <p>Por último, el XML con todas las frases se encuentra en config.</p>';
        echo '            <p>Todo lo que se crea nuevo ya sea una frase, autor o tema se crea en la última página.</p>';
        echo '            <p><strong>Espero ayudar en la corrección.</strong></p>';
        echo '            <p><strong>Leyenda de las flechas:</strong></p>';
        echo '            <h2 style="color: red"><strong>Para cargar los datos de la bd al inicio dale al boton de Recargar Base de Datos.</strong></h2>';
        echo '            <ul>';
        echo '                <li><strong>Flechas rojas:</strong> Son las views.</li>';
        echo '                <li><strong>Flechas verdes:</strong> Son modelos donde se hace el CRUD.</li>';
        echo '                <li><strong>Flechas azules:</strong> Objetos de negocio.</li>';
        echo '                <li><strong>Flecha amarilla:</strong> Donde se lee el XML con las credenciales de usr_generic con contraseña 2025@thos.</li>';
        echo '            </ul>';
        echo '            </div>';
        echo '            <div class="news-image">';
        echo '                <img src="../media/info.png" alt="Noticia" />';
        echo '            </div>';
        echo '        </div>';
        echo '    </section>';
        echo '        <div>';
        echo '            <form method="post" action="" class="login-form">';
        echo '                <p>' . $usuario . '</p>';
        echo '                <input type="email" name="user"';
        echo '                    id="' . ($userError ? "red" : "normal") . '"';
        echo '                    placeholder="' . ($userError ?: $placeholderUser) . '"';
        echo '                    value="' . $user->__get('user') . '" required>';
        echo '                <p>' . $password . '</p>';
        echo '                <input type="password" name="passwd"';
        echo '                    id="' . ($passwdError ? "red" : "normal") . '"';
        echo '                    placeholder="' . ($passwdError ?: $placeholderPasswd) . '"';
        echo '                    value="' . $user->__get('passwd') . '" required>';
        echo '                <button type="submit">' . $botonEnviar . '</button>';
        echo '                <button id="botonRegistrar" type="button" onclick="location.href=\'index.php?Registro/show\';">' . $botonRegistrar . '</button>';
        echo '            </form>';
        echo '            <br>';
        echo '        </div>';
        echo '    </main>';
        
        echo '</body>';
        
        echo '</html>';
        
    }
}

