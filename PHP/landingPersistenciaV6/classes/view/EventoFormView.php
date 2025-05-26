<?php
class EventoFormView
{
    
    public function show($values, $errors) {
        include "../langs/vars_esp.php";
        
        echo "<!DOCTYPE html>";
        echo "<html lang='en'>";
        
        echo "<head>";
        echo "    <meta charset='UTF-8'>";
        echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "    <title>Calendar</title>";
        echo "    <link rel='stylesheet' href='../css/estilos.css'>";
        echo "</head>";
        
        echo "<body id='calendar'>";
        echo "    <header id='header'>";
        echo "        <div class='logo-container'>";
        echo "            <img src='../media/logoWild.png' alt='Logo' />";
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
        echo "            </ul>";
        echo "        </nav>";
        echo "    </header>";
        
        echo "<body id='bodyFormEvent'>";
        echo "    <h1 id='nuevoEventoTitulo'>Nuevo evento</h1>";
        echo "    <main id='mainEvent'>";
        echo "        <form id='nuevoEvento' method='post' action='index.php?Evento/insertarEvento'>";

        echo "            <label id='nombreEvento'>Nombre del evento:</label>";
        echo "            <input type='text' name='nombre_evento' class='". (isset($errors['nombre']) ? 'error-campo' : 'sin-error') ."' value='" . htmlspecialchars($values['nombre'] ?? '') . "' placeholder='" . (isset($errors['nombre']) ? $errors['nombre'] : '') . "'>";
        
        
        echo "            <label id='fechaInicioEvento'>Fecha de inicio:</label>";
        echo '            <input type="date" name="fecha_inicio" class="' . (isset($errors['fechas']) ? 'error-campo' : 'sin-error') . '" value="' . htmlspecialchars($values['fecha_inicio'] ?? '') . '">';
        echo isset($errors['fecha_inicio']) ? "<span class='error-span'>" . $errors['fecha_inicio'] . "</span>" : '';
        
        echo "            <label id='horaInicioEvento'>Hora de inicio:</label>";
        echo '            <input type="time" name="hora_inicio" class="' . (isset($errors['horas']) ? 'error-campo' : 'sin-error') . '" value="' . htmlspecialchars($values['hora_inicio'] ?? '') . '">';
        echo isset($errors['horas']) ? "<span class='error-span'>" . $errors['horas'] . "</span>" : '';
        
        echo "            <label id='fechaFinalEvento'>Fecha de finalización:</label>";
        echo '            <input type="date" name="fecha_fin" class="' . (isset($errors['fechas']) ? 'error-campo' : 'sin-error') . '" value="' . htmlspecialchars($values['fecha_fin'] ?? '') . '">';
        echo isset($errors['fecha_fin']) ? "<span class='error-span'>" . $errors['fecha_fin'] . "</span>" : '';
        
        echo "            <label id='horaFinalEvento'>Hora de finalización:</label>";
        echo '            <input type="time" name="hora_fin" class="' . (isset($errors['horas']) ? 'error-campo' : 'sin-error') . '" value="' . htmlspecialchars($values['hora_fin'] ?? '') . '">';
        echo isset($errors['horas']) ? "<span class='error-span'>" . $errors['horas'] . "</span>" : '';
        
        echo "            <label for='etiqueta'>Etiqueta:</label>";
        echo "              <select name='etiqueta' id='etiquetaForm' class='" . (isset($errors['etiqueta']) ? 'error-campo' : 'sin-error') . "'>";
        echo "                  <option value='bodas'" . (isset($values['etiqueta']) && $values['etiqueta'] == 'bodas' ? ' selected' : '') . ">Bodas</option>";
        echo "                  <option value='evento cultural'" . (isset($values['etiqueta']) && $values['etiqueta'] == 'evento cultural' ? ' selected' : '') . ">Evento cultural</option>";
        echo "                  <option value='deporte'" . (isset($values['etiqueta']) && $values['etiqueta'] == 'deporte' ? ' selected' : '') . ">Deporte</option>";
        echo "                  <option value='cumpleaños'" . (isset($values['etiqueta']) && $values['etiqueta'] == 'cumpleaños' ? ' selected' : '') . ">Cumpleaños</option>";
        echo "                  <option value='otros'" . (isset($values['etiqueta']) && $values['etiqueta'] == 'otros' ? ' selected' : '') . ">Otros</option>";
        echo "              .</select>";
                
        echo "            <label id='descripcionEvento'>Descripción del evento:</label>";
        echo "            <textarea id='descripcionEvento' class='". (isset($errors['desc']) ? 'error-campo' : 'sin-error') ."' rows='4' cols='50' name='descripcion' placeholder='" . (isset($errors['desc']) ? $errors['desc'] : '') . "'>" . htmlspecialchars($values['descripcion'] ?? '') . "</textarea>";
        
        echo "            <button type='submit'>Enviar</button>";
        echo "        </form>";
        echo "    </main>";
        echo "</body>";
        
        echo "</html>";
        
    }
}

