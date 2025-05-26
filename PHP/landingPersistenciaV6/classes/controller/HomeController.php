<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class HomeController extends Controller
{

    private $model;

    // variable para crear una instancia del modelo home
    public function __construct()
    {
        $this->model = new ModeloHome(); // creamos el objeto del modelo home
    }

    public function show()
    {
        $this->model->cogerImgUser();
        $vHome = new HomeView();
        $vHome->showHome($this->model->getNombre(), $this->model->getTelefono(), $this->model->getCorreo(), $this->model->getMensaje(), $this->model->getNombreError(), $this->model->getTelefonoError(), $this->model->getCorreoError(), $this->model->getMensajeError(), $this->model->getImg());
    }

    public function requestHome()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->model->setNombre(isset($_POST['nombre']) ? parent::sanitize(trim($_POST['nombre'])) : ''); // de esta manera entramos a los atributtos del modelo sin romper la encapsulación
            $this->model->setCorreo(isset($_POST['correo']) ? parent::sanitize(trim($_POST['correo'])) : '');
            $this->model->setTelefono(isset($_POST['telefono']) ? parent::sanitize(trim($_POST['telefono'])) : '');
            $this->model->setMensaje(isset($_POST['mensaje']) ? parent::sanitize(trim($_POST['mensaje'])) : '');
            
            $this->model->validar();

            if ($this->model->getCorrecto() == 4) {
                $this->model->save(); // Llamamos al metodo del save que esta en el modelo home
            }
        }
    }
}


