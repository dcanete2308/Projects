<?php
class FraseModel
{
    private $texto;
    private $autor;
    private $tema;
    private $creacion;
    private $updated;
    
    protected $id;
    
    public function __construct($id = null, $texto = "", $autor = "", $tema = "", $creacion = "", $updated = "") {
        $this->id = $id;
        $this->texto = $texto;
        $this->autor = $autor;
        $this->tema = $tema;
        $this->creacion = $creacion;
        $this->updated = $updated;
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
