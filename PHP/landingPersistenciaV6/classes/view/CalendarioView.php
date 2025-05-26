<?php

class CalendarioView
{

    public function show($params, $events)
    {
        $currentMonth = $params['currentMonth'];
        $currentYear = $params['currentYear'];
        $diasEnElMes = $params['diasEnElMes'];
        $diasMesAnterior = $params['diasMesAnterior'];
        $diasSiguenteMes = $params['diasSiguenteMes'];
        $diasMesAnteriorMes = $params['diasMesAnteriorMes'];
        
        $dateTime = new DateTime('');
        $diaActual = $dateTime->format('j');
        $mesActual = $dateTime->format('n');
        $añoActual = $dateTime->format('Y');
        
        $diasConEvento = [];
        
        foreach ($events as $e) {
            $fechaInicio = new DateTime($e['fecha_ini']);
            $fechaFin = new DateTime($e['fecha_fin']);
            $fechaFin->modify('+1 day');
            
            while ($fechaInicio < $fechaFin) {
                $diaInicio = $fechaInicio->format('j');
                $mesInicio = $fechaInicio->format('n');
                $añoInicio = $fechaInicio->format('Y');
                
                $diasConEvento[] = "$diaInicio-$mesInicio-$añoInicio";
                
                $fechaInicio->modify('+1 day');
            }
        }
        
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
        echo "                <li><a href='index.php?Evento/show'>Creación evento</a></li>";
        if(isset($_SESSION['registro']) || isset($_SESSION['usuario'])) {
            echo "                <li>"; 
            echo "    <form method='POST' action='index.php?Mantenimiento/deleteEspecific'>";
            echo "        <button type='submit' id='mantenimientoBtn' >Mantenimiento</button>";
            echo "    </form>";
            echo "                </li>"; 
        }
        echo "            </ul>";
        echo "        </nav>";
        echo "    </header>";
        echo "    <h1 id='tituloCal'>Agenda de eventos</h1>";

        echo "    <div id='calendar-container'>";
        echo "        <div class='calendar'>";
        echo "            <div class='calendar-header'>";
        echo "                <a href='index.php?Calendario/show/" . ($currentMonth - 1) . "/$currentYear'>&lt;</a>";
        echo "                <h2 id='month-year'>" . $this->getMonthName($currentMonth) . " $currentYear</h2>";
        echo "                <a href='index.php?Calendario/show/" . ($currentMonth + 1) . "/$currentYear'>&gt;</a>";
        echo "            </div>";

        echo "            <div class='calendar-weekdays'>";
        echo "                <div>Sun</div>";
        echo "                <div>Mon</div>";
        echo "                <div>Tue</div>";
        echo "                <div>Wed</div>";
        echo "                <div>Thu</div>";
        echo "                <div>Fri</div>";
        echo "                <div>Sat</div>";
        echo "            </div>";

        echo "            <div class='calendar-grid'>";
        
        
        $mes = $currentMonth+1; //sumamos 1 al mes actual porque empieza desde el 0
        for ($i = $diasMesAnterior; $i <= $diasMesAnteriorMes; $i++) {
            $evento = '';
            $fecha = "$i-" . ($mes == 1 ? 12 : $mes - 1) . "-$currentYear"; // creamos la fecha que queremos buscar en el array donde estan todos los eventos, en caso que el mes sea > 12 se pasa a 1 o sino le resta 1 al mes 
            if ($currentMonth == 1 && $i > 28) { 
                $fecha = "$i-12-" . ($currentYear - 1);
            }
            if (in_array($fecha, $diasConEvento)) { // si conicide con el la fecha le cambia la clase
                $evento = 'dia-evento';
            }
            echo "<div class='calendar-day empty $evento'>" . $i . "</div>";
        }
        
        for ($day = 1; $day <= $diasEnElMes; $day++) {
            $evento = '';
            $fecha = "$day-" . ($mes) . "-$currentYear"; //creamos la fecha a buscar
            
            if (in_array($fecha, $diasConEvento)) { // si conicide con el la fecha le cambia la clase
                $evento = 'dia-evento';
            }
            $id = ($diaActual == $day && $mesActual == ($currentMonth+1) && $añoActual == $currentYear) ? "id=diaActual" : "";
            echo "<div $id class='calendar-day $evento'>" . $day . "</div>";
        }
        
        for ($day = 1; $day <= $diasSiguenteMes; $day++) {
            $evento = '';
            $fecha = "$day-" . ($mes == 12 ? 1 : $mes + 1) . "-$currentYear"; // creamos la fecha que queremos buscar en el array donde estan todos los eventos, en caso que el mes sea > 12 se pasa a 1 o sino le sumamos 1 al mes 
            if ($currentMonth == 12) {
                $fecha = "$day-1-" . ($currentYear + 1);
            }
            if (in_array($fecha, $diasConEvento)) {
                $evento = 'dia-evento';
            }
            echo "<div class='calendar-day empty $evento'>" . $day . "</div>";
        }

        echo "            </div>";
        echo "        </div>";

        echo "        <div class='events'>";
        echo "            <h2>Upcoming Events</h2>";
        foreach ($events as $e) {
            echo '<div class="evento-carta">';
            echo '<h3 class="evento-titulo">' . $e['titulo'] . '</h3>';
            echo '<p class="evento-fecha">Fecha de inicio: <strong>' . $e['fecha_ini'] . '</strong></p>';
            if (!is_null($e['hora_ini'])) {
                echo '<p class="evento-hora">Hora de inicio: <strong>' . $e['hora_ini'] . '</strong></p>';
            }
            echo '<p class="evento-fecha">Fecha de fin: <strong>' . $e['fecha_fin'] . '</strong></p>';
            if (!is_null($e['hora_fin'])) {
                echo '<p class="evento-hora">Hora de fin: <strong>' . $e['hora_fin'] . '</strong></p>';
                
            }
            
            if (!is_null($e['desc'])) {
                echo '<p class="evento-descripcion">Descripción: <br>';
                echo '<strong>' . $e['desc'] . '</strong>';
                echo '</p>';
            }
            
            if (is_null($e['hora_ini']) && is_null($e['hora_fin'])) {
                echo '<p class="evento-diaEntero">Duración : Todo el día <strong>' . $e['hora_fin'] . '</strong></p>'; //si la hora de inicio y la de final son nulas, el evento dura todo el dia
            }
            
            $etiqueta = $e['etiqueta'];
            echo '<p class="evento-diaEntero">Etiqueta: </p>';
            if ($etiqueta == 'cumpleaños') {
                echo '<img src="../media/cumple.png" alt="Cumpleaños" class="evento-imagen">';
            } elseif ($etiqueta == 'boda') {
                echo '<img src="../media/boda.png" alt="Boda" class="evento-imagen">';
            } elseif ($etiqueta == 'evento cultural') {
                echo '<img src="../media/cine.png" alt="Evento Cultural" class="evento-imagen">';
            } elseif ($etiqueta == 'deportes') {
                echo '<img src="../media/deporte.png" alt="Deportes" class="evento-imagen">';
            } elseif ($etiqueta == 'otros') {
                echo '<img src="../media/otro.png" alt="Otros" class="evento-imagen">';
            } else {
                echo '<img src="../media/otro.png" alt="Evento" class="evento-imagen">';
            }
            
            echo '</div>';
        }
        echo "        </div>";
        echo "    </div>";

        echo "</body>";
        echo "</html>";
    }

    private function getMonthName($monthIndex)
    {
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];
        return $months[$monthIndex];
    }
}
?>
