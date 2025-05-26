<?php

class EditView
{

    public static function showAutor($autor, $errors = [])
    {
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';
        
        echo '<head>';
        echo '    <meta charset="UTF-8">';
        echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '    <link rel="stylesheet" href="../css/estilos.css">';
        echo '    <title>Editar Autor</title>';
        echo '</head>';
        echo '<body id="bodyAuthorEdit">';
        echo '    <form method="POST" class="authorEditForm" action="index.php?Autor/update">';
        echo '        <input type="hidden" name="id" value="' . htmlspecialchars($autor->__get("id")) . '">';
        echo '        <label for="nom">Nombre del Autor:</label>';
        echo '        <input type="text" name="nom" id="nom" value="' . htmlspecialchars($autor->__get("nom")) . '" required>';
        
        if (!empty($errors['nom'])) {
            echo '<label class="error">' . $errors['nom'] . '</label>';
        }
        
        echo '        <label for="desc">Descripción:</label>';
        echo '        <textarea name="desc" id="desc" required>' . htmlspecialchars($autor->__get("desc")) . '</textarea>';
        
        if (!empty($errors['desc'])) {
            echo '<label class="error">' . $errors['desc'] . '</label>';
        }
        
        echo '        <label for="url">URL:</label>';
        echo '        <input type="text" name="url" id="url" value="' . htmlspecialchars($autor->__get("url")) . '" required>';
        if (!empty($errors['url'])) {
            echo '<label class="error">' . $errors['url'] . '</label>';
        }
        
        echo '        <button type="submit" class="accBtn">Actualizar Autor</button>';
        echo '    </form>';
        echo '</body>';
        echo '</html>';
    }
    

    public static function showFrase($frase, $temas, $autores, $errors = [])
    {
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';

        echo '<head>';
        echo '    <meta charset="UTF-8">';
        echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '    <link rel="stylesheet" href="../css/estilos.css">';
        echo '    <title>Editar Autor</title>';
        echo '</head>';

        echo '<body id="bodyAuthorEdit">';

        echo '    <form method="POST" class="authorEditForm" action="index.php?Frase/update">';
        echo '        <input type="hidden" name="id" value="' . htmlspecialchars($frase->__get("id")) . '">';

        echo '        <label for="texto">Texto de la Frase:</label>';
        echo '        <textarea name="texto" id="texto">' . ($_POST['texto'] ?? htmlspecialchars($frase->__get("texto"))) . '</textarea>';
        
        if (! empty($errors['texto'])) {
            echo '<label class="error">' . $errors['texto'] . '</label>';
        }

        echo '        <label for="autor">Autor:</label>';
        echo '        <select name="autor" id="autor">';
        echo '            <option value="">Seleccione un autor</option>';
        foreach ($autores as $autor) {
            $selected = (isset($_POST['autor']) && $_POST['autor'] == $autor->nom) ? 'selected' : '';
            echo '<option value="' . $autor->nom . '" ' . $selected . '>' . $autor->nom . '</option>';
        }
        echo '        </select>';
        
        
        if (! empty($errors['autor'])) {
            echo '<label class="error">' . $errors['autor'] . '</label>';
        }

        echo '        <label for="tema">Tema:</label>';
        echo '        <select name="tema" id="tema">';
        echo '            <option value="">Seleccione un tema</option>';
        foreach ($temas as $tema) {
            $selected = (isset($_POST['tema']) && $_POST['tema'] == $tema->nom) ? 'selected' : '';
            echo '<option value="' . $tema->nom . '" ' . $selected . '>' . $tema->nom . '</option>';
        }
        echo '        </select>';
        
        
        if (! empty($errors['tema'])) {
            echo '<label class="error">' . $errors['tema'] . '</label>';
        }

        echo '        <button type="submit" class="accBtn">Actualizar Frase</button>';
        echo '    </form>';

        echo '</body>';
        echo '</html>';
    }

    public static function showTema($tema, $errors = [])
    {
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';
        echo '<head>';
        echo '    <meta charset="UTF-8">';
        echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '    <link rel="stylesheet" href="../css/estilos.css">';
        echo '    <title>Editar Tema</title>';
        echo '</head>';
        echo '<body id="bodyAuthorEdit">';
        echo '    <form method="POST" class="authorEditForm" action="index.php?Tema/update">';
        echo '        <input type="hidden" name="id" value="' . htmlspecialchars($tema->__get("id")) . '">';
        echo '        <label for="tema">Nombre del Tema:</label>';
        echo '        <input type="text" name="tema" id="tema" value="' . htmlspecialchars($tema->__get("nombre")) . '" required>';
        
        if (!empty($errors['tema'])) {
            echo '<label class="error">' . $errors['tema'] . '</label>';
        }
        
        echo '        <label for="descripcion">Descripción del Tema:</label>';
        echo '        <textarea name="descripcion" id="descripcion" required>' . htmlspecialchars($tema->__get("descripcion")) . '</textarea>';
        
        if (!empty($errors['descripcion'])) {
            echo '<label class="error">' . $errors['descripcion'] . '</label>';
        }
        
        echo '        <button type="submit" class="accBtn">Actualizar Tema</button>';
        echo '    </form>';
        echo '</body>';
        echo '</html>';
    }
    
    
    
}

