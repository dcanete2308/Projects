<?php

class TemaView
{
    public static function show($temas, $pagina_actual, $total_paginas)
    {
        include "../langs/vars_esp.php";
        
        echo '<!DOCTYPE html>';
        echo '<html lang="es">';
        echo '<head>';
        echo '    <meta charset="UTF-8">';
        echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '    <link rel="stylesheet" href="../css/estilos.css">';
        echo '    <title>Temas</title>';
        echo '</head>';
        
        echo '<body id="frases">';
        echo '    <header>';
        echo '        <div class="logo-container">';
        echo '            <img src="../media/logoWild.png" alt="Logo" />';
        echo '        </div>';
        echo '        <nav>';
        echo '            <ul>';
        echo '                <li><a href="index.php?/Home/show">' . $tituloHome . '</a></li>';
        echo '                <li><a href="index.php?Login/show">' . $titulologin . '</a></li>';
        echo '                <li>' . $tituloIdioma;
        echo '                    <ul class="dropdown">';
        echo '                        <li><a href="index.php?Home/show&idioma=es">Español</a></li>';
        echo '                        <li><a href="index.php?Home/show&idioma=ch">Chino</a></li>';
        echo '                        <li><a href="index.php?Home/show&idioma=jp">Japonés</a></li>';
        echo '                        <li><a href="index.php?Home/show&idioma=ind">Indio</a></li>';
        echo '                        <li><a href="index.php?Home/show&idioma=morse">Morse</a></li>';
        echo '                    </ul>';
        echo '                </li>';
        echo '                <li><a href="index.php?Estudios/show">' . $tituloStudies . '</a></li>';
        echo '                <li><a href="index.php?Inversiones/show">' . $tituloinversions . '</a></li>';
        echo '                <li><a href="index.php?Calendario/show">Calendario</a></li>';
        echo '            </ul>';
        echo '        </nav>';
        echo '    </header>';
        
        echo '    <nav class="frasesNav">';
        echo '        <a href="index.php?Frase/show">Ver Frases</a>';
        echo '        <a href="index.php?Autor/show">Ver Autores</a>';
        echo '        <a href="#">Ver Temas</a>';
        echo '        <form method="POST" action="index.php?CargarXml/recargar" class="formRecargar">';
        echo '            <button type="submit" name="recargar" class="btnRecargar">Recargar Base de Datos</button>';
        echo '        </form>';
        echo '    </nav>';
        
        echo '    <form method="POST" action="index.php?Tema/filtrar" class="filtro">';
        echo '        <input type="text" name="tema" placeholder="Buscar por tema">';
        echo '        <input type="text" name="desc" placeholder="Buscar por descripción">';
        echo '        <button type="submit">Buscar</button>';
        echo '    </form>';
        
        echo '    <table>';
        echo '        <thead>';
        echo '            <tr>';
        echo '                <th>Nombre del Tema</th>';
        echo '                <th>Descripción</th>';
        echo '                <th>Número de Frases</th>';
        echo '                <th>Acciones</th>';
        echo '            </tr>';
        echo '        </thead>';
        echo '        <tbody>';
        
        foreach ($temas as $tema) {
            echo '            <tr>';
            echo '                <td>' . $tema->nombre . '</td>';
            echo '                <td>' . $tema->descripcion . '</td>';
            echo '                <td>' . $tema->numFrases . '</td>';
            echo '                <td class="acciones">';
            
            echo '                    <form method="POST" action="index.php?Tema/showEdit/' . $tema->id . '">';
            echo '                        <input type="hidden" name="id" value="' . $tema->id . '">';
            echo '                        <button type="submit" class="accBtn">Editar</button>';
            echo '                    </form>';
            
            echo '                    <form method="POST" action="index.php?Tema/delete/' . $tema->id . '">';
            echo '                        <input type="hidden" name="id" value="' . $tema->id . '">';
            echo '                        <button type="submit" class="accBtn">Eliminar</button>';
            echo '                    </form>';
            
            echo '                    <form method="POST" action="index.php?Tema/showCreate">';
            echo '                        <button type="submit" class="accBtn">Crear Tema</button>';
            echo '                    </form>';
            
            echo '                </td>';
            echo '            </tr>';
        }
        
        echo '        </tbody>';
        echo '    </table>';
        
        echo '    <div class="pagination">';
        
        if ($pagina_actual > 1) {
            echo '        <a href="index.php?Tema/show&pagina=1" class="pagination-link">Primera</a>';
            echo '        <a href="index.php?Tema/show&pagina=' . max(1, $pagina_actual - 10) . '" class="pagination-link">« 10 atrás</a>';
            echo '        <a href="index.php?Tema/show&pagina=' . ($pagina_actual - 1) . '" class="pagination-link">Anterior</a>';
        }
        
        echo '        <span class="pagination-info">Página ' . $pagina_actual . ' de ' . $total_paginas . '</span>';
        
        if ($pagina_actual < $total_paginas) {
            echo '        <a href="index.php?Tema/show&pagina=' . ($pagina_actual + 1) . '" class="pagination-link">Siguiente</a>';
            echo '        <a href="index.php?Tema/show&pagina=' . min($total_paginas, $pagina_actual + 10) . '" class="pagination-link">10 adelante »</a>';
            echo '        <a href="index.php?Tema/show&pagina=' . $total_paginas . '" class="pagination-link">Última</a>';
        }
        
        echo '    </div>';
        
        
        echo '</body>';
        echo '</html>';
    }
}
