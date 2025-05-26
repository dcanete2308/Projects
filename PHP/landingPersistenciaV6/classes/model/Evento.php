<?php
class Evento
{
    private $id;
    private $nombre_evento;
    private $fecha_inicio;
    private $hora_inicio;
    private $fecha_fin;
    private $hora_fin;
    private $etiqueta;
    private $descripcion;
    
    
    public function __construct($id, $nombre_evento, $fecha_inicio, $hora_inicio, $fecha_fin, $hora_fin, $etiqueta, $descripcion)
    {
        $this->id = $id;
        $this->nombre_evento = $nombre_evento;
        $this->fecha_inicio = $fecha_inicio;
        $this->hora_inicio = $hora_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->hora_fin = $hora_fin;
        $this->etiqueta = $etiqueta;
        $this->descripcion = $descripcion;
    }
    
    public function __get($atributo) {
        if(property_exists($this, $atributo)) {
            return $this->$atributo;
        }
    }
    
    public function __set($atributo, $value){
        if(property_exists($this, $atributo)) {
            $this->$atributo = $value;
        }
    }
    
}

