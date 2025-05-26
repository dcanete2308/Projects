<?php

class HomeView
{

    public function showHome($nombre, $telefono, $correo, $mensaje, $nombre_error, $telefono_error, $correo_error, $mensaje_error, $img)
    {
        $idioma = isset($_COOKIE["idioma"]) ? $_COOKIE["idioma"] : "es";

        if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['idioma'])) {
            $idioma = $_GET['idioma'];
            setcookie("idioma", $idioma, time() + 31536000, "/");
        }

        switch ($idioma) {
            case 'es':
                include "../langs/vars_esp.php";
                break;
            case 'ch':
                include "../langs/vars_ch.php";
                break;
            case 'jp':
                include "../langs/vars_jp.php";
                break;
            case 'ind':
                include "../langs/vars_ind.php";
                break;
            case 'morse':
                include "../langs/vars_morse.php";
                break;
            default:
                echo "No se ha podido traducir";
                return;
        }

        echo "<!DOCTYPE html>";
        echo "<html lang='es'>";
        echo "<head>";
        echo "    <meta charset='UTF-8'>";
        echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "    <title>$tituloPagina</title>";
        echo "    <link rel='stylesheet' href='../css/estilos.css'>";
        echo "    <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap' rel='stylesheet'>";
        echo "</head>";
        
        echo "<body>";
        echo "    <header id='header'>";
        echo "        <div class='logo-container'>";
        echo "            <img src='../media/logoWild.png' alt='Logo' />";
        echo "            <img src='{$img}' alt='Logo' />";
        echo "        </div>";
        echo "        <nav>";
        echo "            <ul>";
        echo "                <li><a href='index.php?/Home/show'>$tituloHome</a></li>";
        echo "                <li><a href='index.php?Login/show'>$titulologin</a></li>";
        echo "                <li>$tituloIdioma";
        echo "                    <ul class='dropdown'>";
        echo "                        <li><a href='index.php?Home/show&idioma=es'>Español</a></li>";
        echo "                        <li><a href='index.php?Home/show&idioma=ch'>Chino</a></li>";
        echo "                        <li><a href='index.php?Home/show&idioma=jp'>Japonés</a></li>";
        echo "                        <li><a href='index.php?Home/show&idioma=ind'>Indio</a></li>";
        echo "                        <li><a href='index.php?Home/show&idioma=morse'>Morse</a></li>";
        echo "                    </ul>";
        echo "                </li>";
        echo "                <li><a href='index.php?Estudios/show'>$tituloStudies</a></li>";
        echo "                <li><a href='index.php?Inversiones/show'>$tituloinversions</a></li>";
        echo "                <li><a href='index.php?Calendario/show'>Calendario</a></li>";
        echo "                <li><a href='index.php?Autor/show'>Frases</a></li>";
        echo "            </ul>";
        echo "        </nav>";
        echo "    </header>";
        
        echo "    <main>";
        echo "        <section id='about'>";
        echo "            <div class='content'>";
        echo "                <div class='text-content'>";
        echo "                    <h3>$descripcion</h3>";
        echo "                    <hr>";
        echo "                    <h4>$subtitulo</h4>";
        echo "                    <p>$textoSobreMi</p>";
        echo "                    <p>$textoFinal</p>";
        echo "                    <div class='form-container'>";
        echo "                        <h2>$formTitulo</h2>";
        echo "                        <form method='post' action='' class='contact-form'>";
        echo "                            <input type='text' name='nombre' id='" . ($nombre_error ? "red" : "normal") . "' placeholder='" . ($nombre_error ?: $placeholderNombre) . "' value='$nombre' required>";
        echo "                            <input type='email' name='correo' id='" . ($correo_error ? "red" : "normal") . "' placeholder='" . ($correo_error ?: $placeholderCorreo) . "' value='$correo' required>";
        echo "                            <input type='tel' name='telefono' id='" . ($telefono_error ? "red" : "normal") . "' placeholder='" . ($telefono_error ?: $placeholderTelefono) . "' value='$telefono' required>";
        echo "                            <textarea id='" . ($mensaje_error ? "red" : "normal") . "' name='mensaje' rows='4' cols='50' placeholder='" . ($mensaje_error ?: $placeholderMensaje) . "' required>$mensaje</textarea>";
        echo "                            <button type='submit'>$botonEnviar</button>";
        echo "                        </form>";
        echo "                    </div>";
        echo "                </div>";
        echo "                <div class='image-container'>";
        echo "                    <img id='imgDidac' src='../media/fotoDidac.JPG' alt='Foto de Dídac' />";
        echo "                </div>";
        echo "            </div>";
        echo "        </section>";
        echo "    </main>";
        
        echo "    <footer>";
        echo "        <div class='social-icons'>";
        echo "            <a href='#'><img src='../media/instagram.png' alt='Instagram' /></a>";
        echo "            <a href='#'><img src='../media/linkedin.png' alt='LinkedIn' /></a>";
        echo "            <a href='#'><img src='../media/facebook-f.png' alt='Facebook' /></a>";
        echo "            <a href='#'><img src='../media/X_logo.png' alt='X' /></a>";
        echo "        </div>";
        echo "    </footer>";
        echo "</body>";
        echo "</html>";
        
    }
}
