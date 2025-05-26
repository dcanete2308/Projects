<?php

class Http {
    private $controller;
    private $action;
    private $params;
    
    public function __construct($controller_name, $action, $params) {
        if (file_exists(__ROOT__."classes/controller/{$controller_name}Controller.php")) {
            $classe = $controller_name."Controller";
            $this->controller = new $classe();
            if (method_exists($this->controller, $action)){
                $this->action = $action;
                $this->params = $params;
            } else {
                throw new Exception("no existeix l'acció definida de $controller_name");
            }
        } else {
            throw new Exception("no existeix la definició de $controller_name");
        }
    }
    public function get(){
        $acc = $this->action;
        $this->controller->$acc($this->params);
    }
    
    public function post(){
        $acc = $this->action;
        $this->controller->$acc($this->params);
    }
}

