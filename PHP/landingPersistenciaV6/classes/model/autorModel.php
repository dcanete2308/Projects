<?php
class autorModel
{
    private $nom;
    private $desc;
    private $url;
    private $numFrases; 
    
    protected $id;
    
    public function __construct($nom = "", $desc = "", $url = "", $numFrases="", $id = null)
    {
        $this->nom = $nom;
        $this->desc = $desc;
        $this->url = $url;
        $this->numFrases = $numFrases;
        $this->id = $id;
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

