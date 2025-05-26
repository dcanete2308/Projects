<?php

class CreateView
{

    public static function showCreateAuthor($errors = [])
    {
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';

        echo '<head>';
        echo '    <meta charset="UTF-8">';
        echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '    <link rel="stylesheet" href="../css/estilos.css">';
        echo '    <title>Crear Autor</title>';
        echo '</head>';
        echo '<body id="bodyAuthorEdit">';
        echo '    <form method="POST" class="authorEditForm" action="index.php?Autor/insert">';
        echo '        <label for="nom">Nombre del Autor:</label>';
        echo '        <input type="text" name="nom" id="nom" value="' . ($_POST['nom'] ?? '') . '" required>';
        
        if (! empty($errors['nom'])) {
            echo '<label class="error">' . $errors['nom'] . '</label>';
        }
        
        echo '        <label for="desc">Descripción:</label>';
        echo '        <textarea name="desc" id="desc" required>' . ($_POST['desc'] ?? '') . '</textarea>';

        if (! empty($errors['desc'])) {
            echo '<label class="error">' . $errors['desc'] . '</label>';
        }

        echo '        <label for="url">URL:</label>';
        echo '        <input type="text" name="url" id="url" value="' . ($_POST['url'] ?? '') . '" required>';
        
        if (! empty($errors['url'])) {
            echo '<label class="error">' . $errors['url'] . '</label>';
        }

        echo '        <button type="submit" class="accBtn">Crear Autor</button>';
        echo '    </form>';
        echo '</body>';
        echo '</html>';
    }

    public static function showCreateFrase($temas, $autores, $errors = [])
    {
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';

        echo '<head>';
        echo '    <meta charset="UTF-8">';
        echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '    <link rel="stylesheet" href="../css/estilos.css">';
        echo '    <title>Crear Frase</title>';
        echo '</head>';

        echo '<body id="bodyAuthorEdit">';

        echo '    <form method="POST" class="authorEditForm" action="index.php?Frase/createFrase">';

        echo '        <label for="texto">Texto de la Frase:</label>';
        echo '        <textarea name="texto" id="texto">' . ($_POST['texto'] ?? '') . '</textarea>';

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

        echo '        <button type="submit" class="accBtn">Crear Frase</button>';
        echo '    </form>';

        echo '</body>';
        echo '</html>';
    }

    public static function showCreateTema($errors = [])
    {
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';
        echo '<head>';
        echo '    <meta charset="UTF-8">';
        echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '    <link rel="stylesheet" href="../css/estilos.css">';
        echo '    <title>Crear Tema</title>';
        echo '</head>';
        echo '<body id="bodyAuthorEdit">';
        echo '    <form method="POST" class="authorEditForm" action="index.php?Tema/createTema">';
        
        echo '        <label for="nombre">Nombre del Tema:</label>';
        echo '        <input type="text" name="nombre" id="nombre" required>';
        
        if (!empty($errors['nombre'])) {
            echo '<label class="error">' . $errors['nombre'] . '</label>';
        }
        
        echo '        <label for="descripcion">Descripción:</label>';
        echo '        <textarea name="descripcion" id="descripcion" required></textarea>';
        
        if (!empty($errors['descripcion'])) {
            echo '<label class="error">' . $errors['descripcion'] . '</label>';
        }
        
        echo '        <button type="submit" class="accBtn">Crear Tema</button>';
        echo '    </form>';
        echo '</body>';
        echo '</html>';
    }
    
}

