<?php

class EditarEventoController extends Controller
{

    public function show($value)
    {
        $values =  [];
        $errors = [];
        if(isset($_SESSION['registro']) || isset($_SESSION['usuario'])) {
            $vEditarEvento = new EditarEventoView();
            $vEditarEvento->show($value, $values, $errors);
        }
    }

    public function update($id)
    {
        $errors = [];
        $values = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // recojo los datos
            $id = isset($_POST['id']) ? parent::sanitize($_POST['id']) : '';
            $nombre = isset($_POST['nombre']) ? parent::sanitize($_POST['nombre']) : '';
            $fechaIni = isset($_POST['fecha_inicio']) ? parent::sanitize($_POST['fecha_inicio']) : '';
            $horaIni = isset($_POST['hora_inicio']) ? parent::sanitize($_POST['hora_inicio']) : '';
            $fechaFin = isset($_POST['fecha_fin']) ? parent::sanitize($_POST['fecha_fin']) : '';
            $horaFin = isset($_POST['hora_fin']) ? parent::sanitize($_POST['hora_fin']) : '';
            $etiqueta = isset($_POST['etiqueta']) ? parent::sanitize($_POST['etiqueta']) : '';
            $desc = isset($_POST['desc']) ? parent::sanitize($_POST['desc']) : '';

            //añado los datos en un array de values para que en caso de error no se tengan que volver a introducir todos
            $values['id'] = $id; 
            $values['nombre'] = $nombre;
            $values['fecha_inicio'] = $fechaIni;
            $values['hora_inicio'] = $horaIni;
            $values['fecha_fin'] = $fechaFin;
            $values['hora_fin'] = $horaFin;
            $values['etiqueta'] = $etiqueta;
            $values['descripcion'] = $desc;
            
            //hago las validaciones
            $fechaIni_DateTime = DateTime::createFromFormat('Y-m-d', $fechaIni); // lo pasa formato DateTime para comprobar fechas
            $fechaFin_DateTime = DateTime::createFromFormat('Y-m-d', $fechaFin);
            
            $horaIni_DateTime = DateTime::createFromFormat('H:i', $horaIni);
            $horaFin_DateTime = DateTime::createFromFormat('H:i', $horaFin);
            
            // si es el mismo día comprueba la hora
            if ($fechaFin_DateTime == $fechaIni_DateTime) {
                if ($horaFin_DateTime < $horaIni_DateTime) {
                    $errors['horas'] = "La hora de finalización no puede ser anterior a la de inicio";
                };
            }

            if ($fechaFin_DateTime < $fechaIni_DateTime) {
                $errors['fechas'] = "La fecha de finalización no puede ser anterior a la de inicio";
            }

            if (!empty($desc)) {
                if (! preg_match('/[a-zA-Z]/', $desc)) {
                    $errors['desc'] = "La descripción ha de contener solo letras";
                }
            }
                   
            if (!empty($nombre)) {
                if (! preg_match('/[a-zA-Z]/', $nombre)) {
                    $errors['nombre'] = "El nombre ha de contener solo letras";
                }
            }

            // creo el objeto de negocio para pasarlo
            $evento = new Evento($id, $nombre, $fechaIni, $horaIni, $fechaFin, $horaFin, $etiqueta, $desc);
             
            //ejecuto el update si no hay errores
            if (! empty($errors)) {
                $vEditarEventoView = new EditarEventoView();
                $vEditarEventoView->show($id, $values, $errors);
                return;
            } else {
                eventModel::updateEvent($evento);
                header("Location: index.php?Mantenimiento/show");
            }
        }
    }
}

