<?php
class TemaModel
{
    private $id;
    private $nombre;
    private $descripcion;
    private $numFrases;
    
    public function __construct($id, $nombre, $descripcion, $numFrases)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->numFrases = $numFrases;
    }
    
    public function __get($atributo)
    {
        if (property_exists($this, $atributo)) {
            return $this->$atributo;
        }
    }
    
    public function __set($atributo, $valor)
    {
        if (property_exists($this, $atributo)) {
            $this->$atributo = $valor;
        }
    }
}
?>
