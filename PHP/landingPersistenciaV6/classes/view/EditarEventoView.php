<?php

class EditarEventoView
{

    public function show($value, $values, $errors)
    {
        $id = $value[0];
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';
        echo '<head>';
        echo '    <meta charset="UTF-8">';
        echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '    <title>Editar Evento</title>';
        echo '    <link rel="stylesheet" href="../css/estilos.css">';
        echo '</head>';
        echo '<body id="editarBody">';
        echo '    <div class="form-container" id="editar-evento-container">';
        echo '        <h1 id="form-title">Editar Evento</h1>';
        echo '        <form action="index.php?EditarEvento/update/' . $id . '" method="POST" id="form-editar-evento">';
        echo '            <div class="form-grid" id="form-grid">';

        echo "                <input type='hidden' name='id' value='$id'>";

        echo '                <div class="form-group" id="form-group-nombre">';
        echo '                    <label for="nombre">Nombre del evento</label>';
        echo "                    <input type='text' id='nombre' name='nombre'  class='" . (isset($errors['nombre']) ? 'error-campo' : 'sin-error') . "' value='" . htmlspecialchars($values['nombre'] ?? '') . "' placeholder='" . (isset($errors['nombre']) ? $errors['nombre'] : '') . "'>";
        echo '                </div>';

        echo '                <div class="form-group" id="form-group-fecha-inicio">';
        echo '                    <label for="fecha_inicio">Fecha de inicio</label>';
        echo '                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="' . (isset($errors['fechas']) ? 'error-campo' : 'sin-error') . '" value="' . htmlspecialchars($values['fecha_inicio'] ?? '') . '">';
        echo isset($errors['fechas']) ? "<span class='error-span'>" . $errors['fechas'] . "</span>" : '';
        echo '                </div>';

        echo '                <div class="form-group" id="form-group-hora-inicio">';
        echo '                    <label for="hora_inicio">Hora de inicio</label>';
        echo '                    <input type="time" id="hora_inicio" class="' . (isset($errors['horas']) ? 'error-campo' : 'sin-error') . '" name="hora_inicio" value="' . htmlspecialchars($values['hora_inicio'] ?? '') . '">';
        echo isset($errors['horas']) ? "<span class='error-span'>" . $errors['horas'] . "</span>" : '';

        echo '                </div>';

        echo '                <div class="form-group" id="form-group-fecha-fin">';
        echo '                    <label for="fecha_fin">Fecha de fin</label>';
        echo '                    <input type="date" id="fecha_fin" name="fecha_fin" class="' . (isset($errors['fechas']) ? 'error-campo' : 'sin-error') . '" value="' . htmlspecialchars($values['fecha_fin'] ?? '') . '">';
        echo isset($errors['fechas']) ? "<span class='error-span'>" . $errors['fechas'] . "</span>" : '';
        echo '                </div>';

        echo '                <div class="form-group" id="form-group-hora-fin">';
        echo '                    <label for="hora_fin">Hora de fin</label>';
        echo '                    <input type="time" id="hora_fin" name="hora_fin" class="' . (isset($errors['horas']) ? 'error-campo' : 'sin-error') . '" value="' . htmlspecialchars($values['hora_fin'] ?? '') . '">';
        echo isset($errors['horas']) ? "<span class='error-span'>" . $errors['horas'] . "</span>" : '';

        echo '                </div>';

        echo "<div class='form-group'>";
        echo "    <label for='etiqueta'>Etiqueta:</label>";
        echo "               <select name='etiqueta' id='etiqueta' class='" . (isset($errors['etiqueta']) ? 'error-campo' : 'sin-error') . "'>";
        echo "                  <option value='bodas'" . (isset($values['etiqueta']) && $values['etiqueta'] == 'bodas' ? ' selected' : '') . ">Bodas</option>";
        echo "                  <option value='evento cultural'" . (isset($values['etiqueta']) && $values['etiqueta'] == 'evento cultural' ? ' selected' : '') . ">Evento cultural</option>";
        echo "                  <option value='deporte'" . (isset($values['etiqueta']) && $values['etiqueta'] == 'deporte' ? ' selected' : '') . ">Deporte</option>";
        echo "                  <option value='cumpleaños'" . (isset($values['etiqueta']) && $values['etiqueta'] == 'cumpleaños' ? ' selected' : '') . ">Cumpleaños</option>";
        echo "                  <option value='otros'" . (isset($values['etiqueta']) && $values['etiqueta'] == 'otros' ? ' selected' : '') . ">Otros</option>";
        echo "               </select>";
        echo isset($errors['etiqueta']) ? "<span class='error-span'>" . $errors['etiqueta'] . "</span>" : '';
        echo "                  </div>";

        echo '                <div class="form-group" id="form-group-descripcion">';
        echo '                    <label for="desc">Descripción</label>';
        echo "                    <textarea id='desc' class='" . (isset($errors['desc']) ? 'error-campo' : 'sin-error') . "' name='desc' placeholder='" . (isset($errors['desc']) ? $errors['desc'] : '') . "'>" . htmlspecialchars($values['descripcion'] ?? '') . "</textarea>";
        echo '                </div>';
        echo '            </div>';

        echo '            <div class="btns" id="form-btns">';
        echo '                <form class="form-actions" id="form-guardar">';
        echo '                    <button type="submit" class="btn-submit" id="btn-guardar">Guardar</button>';
        echo '                </form>';
        echo '                <form class="form-actions" id="form-cancelar">';
        echo '                    <a href="index.php?Mantenimiento/show" class="btn-submit" id="btn-a">Cancelar</a>';
        echo '                </form>';
        echo '            </div>';
        echo '        </form>';
        echo '    </div>';
        echo '</body>';
        echo '</html>';
    }
}

