<?php

class FraseView
{
    public static function show($frases, $pagina_actual, $total_paginas)
    {
        include "../langs/vars_esp.php";
        
        echo '<!DOCTYPE html>';
        echo '<html lang="es">';
        
        echo '<head>';
        echo '    <meta charset="UTF-8">';
        echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo "    <link rel='stylesheet' href='../css/estilos.css'>";
        echo '    <title>Frases</title>';
        echo '</head>';
        
        echo '<body id="frases">';
        echo '  <header>';
        echo "        <div class='logo-container'>";
        echo "            <img src='../media/logoWild.png' alt='Logo' />";
        echo "        </div>";
        echo '  </header>';
        
        echo '    <nav class="frasesNav">';
        echo '        <a href="#">Ver Frases</a>';
        echo '        <a href="index.php?Autor/show">Ver Autores</a>';
        echo '        <a href="index.php?Tema/show">Ver Temas</a>';
        echo '        <form method="POST" action="index.php?CargarXml/recargar" class="formRecargar">';
        echo '            <button type="submit" name="recargar" class="btnRecargar">Recargar Base de Datos</button>';
        echo '        </form>';
        echo '    </nav>';
        
        echo '    <form method="POST" action="index.php?Frase/filtrar" class="filtro">';
        echo '        <input type="text" name="autor" placeholder="Buscar por autor">';
        echo '        <input type="text" name="frase" placeholder="Buscar por frase">';
        echo '        <input type="text" name="tema" placeholder="Buscar por tema">';
        echo '        <button type="submit">Buscar</button>';
        echo '    </form>';
        
        echo '    <table>';
        echo '        <thead>';
        echo '            <tr>';
        echo '                <th>Texto de la Frase</th>';
        echo '                <th>Autor</th>';
        echo '                <th>Tema</th>';
        echo '                <th>Fecha de Creación</th>';
        echo '                <th>Última Actualización</th>';
        echo '                <th>Acciones</th>';
        echo '            </tr>';
        echo '        </thead>';
        echo '        <tbody>';
        
        foreach ($frases as $frase) {
            echo '            <tr>';
            echo '                <td>' . $frase->texto . '</td>';
            echo '                <td>' . $frase->autor . '</td>';
            echo '                <td>' . $frase->tema . '</td>';
            echo '                <td>' . $frase->creacion . '</td>';
            echo '                <td>' . $frase->updated . '</td>';
            echo '                <td class="acciones">';
            
            echo "    <form method='POST' action='index.php?Frase/showEdit/$frase->id'>";
            echo "        <input type='hidden' name='id' value='$frase->id'>";
            echo "        <button type='submit' class='accBtn'>Editar</button>";
            echo "    </form>";
            
            echo "    <form method='POST' action='index.php?Frase/delete/$frase->id'>";
            echo "        <input type='hidden' name='id' value='$frase->id'>";
            echo "        <button type='submit' class='accBtn'>Eliminar</button>";
            echo "    </form>";
            
            echo "    <form method='POST' action='index.php?Frase/showCreateFrase'>";
            echo "        <button type='submit' class='accBtn'>Crear</button>";
            echo "    </form>";
            
            echo '</td>';
            echo '            </tr>';
        }
        
        echo '        </tbody>';
        echo '    </table>';
        
        echo '    <div class="pagination">';
        
        if ($pagina_actual > 1) {
            echo '        <a href="index.php?Frase/show&pagina=1" class="pagination-link">Primera</a>';
            echo '        <a href="index.php?Frase/show&pagina=' . max(1, $pagina_actual - 10) . '" class="pagination-link">« 10 atrás</a>';
            echo '        <a href="index.php?Frase/show&pagina=' . ($pagina_actual - 1) . '" class="pagination-link">Anterior</a>';
        }
        
        echo '        <span class="pagination-info">Página ' . $pagina_actual . ' de ' . $total_paginas . '</span>';
        
        if ($pagina_actual < $total_paginas) {
            echo '        <a href="index.php?Frase/show&pagina=' . ($pagina_actual + 1) . '" class="pagination-link">Siguiente</a>';
            echo '        <a href="index.php?Frase/show&pagina=' . min($total_paginas, $pagina_actual + 10) . '" class="pagination-link">10 adelante »</a>';
            echo '        <a href="index.php?Frase/show&pagina=' . $total_paginas . '" class="pagination-link">Última</a>';
        }
        
        echo '</div>';
        
        
        echo '</body>';
        echo '</html>';
    }
}
