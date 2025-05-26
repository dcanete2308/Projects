<?php
class AutoLoad
{
    
    public function __construct(){
        spl_autoload_register([$this, 'my_autoload']);
    }
    
    function my_autoload($classe){
        
        $ruta = "../classes/";
        
        $archivos = scandir($ruta);
        
        foreach ($archivos as $a) {
            if ($a !== '.' && $a !== '..') {
                $fichero = $ruta."/".$a."/".$classe.".php";
                $ficheroClass = $ruta."/".$a."/".$classe.".class.php";
                if(file_exists($fichero)) {
                    include $fichero;
                    return;
                } else if(file_exists($ficheroClass)){
                    include $ficheroClass;
                    return;
                }
            }
            
        }
        
        throw new Exception("Definició de classe no trobada $classe");
        
    }
    
}

