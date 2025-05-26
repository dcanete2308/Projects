<?php

class EventoController extends Controller
{

    public function __construct()
    {}

    public function show()
    {
        $values = [];
        $errors = [];
        $vEventoFormView = new EventoFormView();
        $vEventoFormView->show($values, $errors);
    }

    public function insertarEvento()
    {
        $errors = [];
        $values = [];

        // rocojo los datos
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = isset($_POST['nombre_evento']) ? parent::sanitize($_POST['nombre_evento']) : '';
            $fecha_inicio = isset($_POST['fecha_inicio']) ? parent::sanitize($_POST['fecha_inicio']) : '';
            $hora_inicio = isset($_POST['hora_inicio']) ? parent::sanitize($_POST['hora_inicio']) : '';
            $fecha_fin = isset($_POST['fecha_fin']) ? parent::sanitize($_POST['fecha_fin']) : '';
            $hora_fin = isset($_POST['hora_fin']) ? parent::sanitize($_POST['hora_fin']) : '';
            $etiqueta = isset($_POST['etiqueta']) ? parent::sanitize($_POST['etiqueta']) : ''; 
            $desc = isset($_POST['descripcion']) ? parent::sanitize($_POST['descripcion']) : '';

            // para que en caso que haya errores pasar el value que esta bien al formulario evento
            $values['nombre'] = $nombre;
            $values['fecha_inicio'] = $fecha_inicio;
            $values['hora_inicio'] = $hora_inicio;
            $values['fecha_fin'] = $fecha_fin;
            $values['hora_fin'] = $hora_fin;
            $values['etiqueta'] = $etiqueta;
            $values['descripcion'] = $desc;

            //hago las validaciones 
            if (empty($nombre)) {
                $errors['nombre'] = "El nombre es obligatorio";
            } else if (! preg_match('/[a-zA-Z]/', $nombre)) {
                $errors['nombre'] = "El nombre no puede tener solo números";
            }

            if (empty($fecha_inicio)) {
                $errors['fecha_inicio'] = "La fecha de inicio es obligatoria";
            }
            if (empty($fecha_fin)) {
                $errors['fecha_fin'] = "La fecha de finalización es obligatoria";
            }
            
            $fecha_inicio_DateTime = DateTime::createFromFormat('Y-m-d', $fecha_inicio); // lo pasa formato dateTime, para coprobar si la fecha de final es posterior a la de inico
            $fecha_fin_DateTime = DateTime::createFromFormat('Y-m-d', $fecha_fin);

            $horaIni_DateTime = DateTime::createFromFormat('H:i', $hora_inicio);
            $horaFin_DateTime = DateTime::createFromFormat('H:i', $hora_fin);

            if ($fecha_fin_DateTime == $fecha_inicio_DateTime) {
                if ($horaFin_DateTime < $horaIni_DateTime) {
                    $errors['horas'] = "La hora de finalización no puede ser anterior a la de inicio";
                }
            }

            if ($fecha_fin_DateTime < $fecha_inicio_DateTime) {
                $errors['fechas'] = "La fecha de finalización no puede ser anterior a la de inicio";
            }

            if (! empty($desc)) {
                if (! preg_match('/[a-zA-Z]/', $desc)) {
                    $errors['desc'] = "La descripción no puede tener solo números";
                }
            }

            // creo el objeto de negocio para pasarlo
            $evento = new Evento(null, $nombre, $fecha_inicio, $hora_inicio, $fecha_fin, $hora_fin, $etiqueta, $desc);
            
            //ejecuto el insert si no hay errores
            if (! empty($errors)) {
                $vEventoFormView = new EventoFormView();
                $vEventoFormView->show($values, $errors);
            } else {
                eventModel::insertEvent($evento);
                header('Location: index.php?Calendario/show');
            }
        }
    }
}

