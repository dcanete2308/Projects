<?php
class InversionesController extends Controller
{
    private $model;
    
    public function __construct()
    {
        $this->model = new ModeloInversiones();
    }
    
    public function show()
    {
        if (isset($_SESSION['registro']) || isset($_SESSION['usuario'])) {
            $datos = $this->model->obtenerDatosCotizaciones();
            
            $vInversiones = new InversionesView();
            
            $vInversiones->showInversiones($datos);
        } else {
            header("Location: index.php?Registro/show");
            exit(); 
        }
        
    }
}
