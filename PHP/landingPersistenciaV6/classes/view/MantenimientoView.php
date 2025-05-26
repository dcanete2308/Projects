<?php

class MantenimientoView
{
    
    public function show($events) {
        $numeroEventos = 0;
        
        
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
        echo "    <div id='filtro'>";
        echo "        <form id='filtroFormulario' action='index.php?Mantenimiento/filtrar' method='POST'>";
        echo "            <div id='filtroContenedor'>";
        echo "                <label for='filtroNombre' id='filtroNombre'>Filtrar por Nombre:</label>";
        echo "                <input type='text' name='filtroNombre' id='filtroNombre' placeholder='Escribe el nombre...' class='inputFiltro' />";
        echo "            </div>";
        echo "            <div id='filtroContenedor'>";
        echo "                <label for='etiqueta' id='filtroEtiqueta'>Filtrar por Etiqueta:</label>";
        echo "                <select name='etiquetaFiltro' id='etiquetaFiltro' class='inputFiltro'>";
        echo "                    <option value=''>Seleccione una etiqueta</option>";
        echo "                    <option value='bodas'>Bodas</option>";
        echo "                    <option value='evento cultural'>Evento cultural</option>";
        echo "                    <option value='deporte'>Deporte</option>";
        echo "                    <option value='cumpleaños'>Cumpleaños</option>";
        echo "                    <option value='otros'>Otros</option>";
        echo "                </select>";
        echo "            </div>";
        echo "            <button type='submit' id='filtroBoton'>Filtrar</button>";
        echo "        </form>";
        echo "    </div>";
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
        echo "    </nav>";
        echo "    </header>";
        echo "    <h1 id='tituloEventos'>GESTOR DE EVENTOS</h1>";
        echo "    <table id='tablaEventos'>";
        echo "        <tr>";
        echo "            <th>ID</th>";
        echo "            <th>Nombre</th>";
        echo "            <th>Fecha Inicio</th>";
        echo "            <th>Fecha Final</th>";
        echo "            <th>Hora Inicio</th>";
        echo "            <th>Hora Final</th>";
        echo "            <th>Etiqueta</th>";
        echo "            <th>Descripcion</th>";
        echo "            <th>Editar</th>";
        echo "            <th>Eliminar</th>";
        echo "        </tr>";
        
        foreach ($events as $evento) {
            $numeroEventos++;
            echo "        <tr class='". ($numeroEventos % 2 == 0 ? 'eventoPar' : 'eventoImpar') ."' >";
            echo "            <td>{$evento['id']}</td>";
            echo "            <td>{$evento['titulo']}</td>";
            echo "            <td>{$evento['fecha_ini']}</td>";
            echo "            <td>{$evento['fecha_fin']}</td>";
            echo "            <td>" . (isset($evento['hora_ini']) ? $evento['hora_ini'] : 'Todo el dia') . "</td>";
            echo "            <td>" . (isset($evento['hora_ini']) ? $evento['hora_ini'] : 'Todo el dia') . "</td>";
            echo "            <td>{$evento['etiqueta']}</td>";
            echo "            <td>{$evento['desc']}</td>";
            echo "            <td><a href='index.php?EditarEvento/show/{$evento['id']}' class='editarBtn' id='editarManBtn'>Editar</a></td>";
            echo "    <form method='POST' action='index.php?Mantenimiento/deleteEspecific'>";
            echo "      <input type='hidden' name='id' value='{$evento['id']}'>";
            echo "      <td><button type='submit' class='editarBtn' id='editarManBtn'>Borrar</button></td>";
            echo "    </form>";
            echo "    </tr>";
        }
        echo "    </table>";
        echo "    <form method='POST' action='index.php?Mantenimiento/deleteAll'>";
        echo "      <td><button type='submit' class='editarBtn' id='eliminarTodo'>Borrar todo</button></td>";
        echo "    </form>";
        echo "</body>";
        
        echo "</html>";      
        
    }
}

