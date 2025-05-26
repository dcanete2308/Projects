<?php

class MantenimientoController extends Controller
{
    public function __construct() {
        
    }
    
    public function show(){
        $bd = eventModel::getBD();
        $resultado = $bd->fetch_all(MYSQLI_NUM);
        $events = [];
        foreach ($resultado as $r) {
            $events[] = [
                "id" => $r[0],
                "titulo" => $r[1],
                "fecha_ini" => $r[2],
                "hora_ini" => $r[3],
                "fecha_fin" => $r[4],
                "hora_fin" => $r[5],
                "etiqueta" => $r[6],
                "desc" => $r[7]
            ];
        }
        
        if(isset($_SESSION['registro']) || isset($_SESSION['usuario'])) { //no deja entrar si no estas 
            $vMantenimiento = new MantenimientoView();
            $vMantenimiento->show($events);
        } else {
            header('Location: index.php?Calendario/show');
        }
        
    }
    
    public function deleteAll() {
        eventModel::deleteAll();
        header('Location: index.php?Mantenimiento/show');
    }
    
    public function deleteEspecific() {
        $id = isset($_POST['id']) ? parent::sanitize($_POST['id']) : ''; // cogo el id a traves de un input invisible para el usuario
        $evento = new Evento($id, null, null, null, null, null, null, null); 
        eventModel::deleteEspecific($evento);
        header("Location: index.php?Mantenimiento/show");
    }
    
    public function filtrar() {
        $nombre = isset($_POST['filtroNombre']) ? $_POST['filtroNombre'] : '';
        $etiqueta = isset($_POST['etiquetaFiltro']) ? $_POST['etiquetaFiltro'] : '';
        $evento = new Evento(null, $nombre, null, null, null, null, $etiqueta, null);
        $bd = eventModel::filtrar($evento); 
        
        
        $resultado = $bd->fetch_all(MYSQLI_NUM);
        $events = [];
        foreach ($resultado as $r) {
            $events[] = [
                "id" => $r[0],
                "titulo" => $r[1],
                "fecha_ini" => $r[2],
                "hora_ini" => $r[3],
                "fecha_fin" => $r[4],
                "hora_fin" => $r[5],
                "etiqueta" => $r[6],
                "desc" => $r[7]
            ];
        }
        
        $vMantenimiento = new MantenimientoView();
        $vMantenimiento->show($events);
    }
    
    
}

