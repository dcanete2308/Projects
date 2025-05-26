<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
define("__ROOT__", __DIR__ . "/../");

function my_autoload($classe) {
    $carpetes = array(".","core","controller", "model", "view");
    
    foreach($carpetes as $carpeta) {
        if (file_exists(__ROOT__ ."classes/$carpeta/$classe.php")) {
            include __ROOT__."classes/$carpeta/$classe.php";
            return;
        }
    }
}

function second_autoload($classe) {
    $carpetes = array(".","core","controller", "model", "view");
    
    foreach($carpetes as $carpeta) {
        if (file_exists(__ROOT__ ."classes/$carpeta/$classe.class.php")) {
            include __ROOT__."classes/$carpeta/$classe.class.php";
            return;
        }
    }
    throw new Exception("Definició de classe no trobada $classe");
    
}


try {
    spl_autoload_register("my_autoload");
    spl_autoload_register("second_autoload");
    
    include __ROOT__."classes/model/UsuariModel.php";
    
        
    
    FrontController::dispatch();
        
} catch (Exception $e) {
    ErrorView::show($e);
}
?>