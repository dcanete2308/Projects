<?php
class CargarXmlController extends Controller {
    public function recargar() {
        $daoXML = new DaoXML();
        $daoXML->cargarDatosXml('../config/frases.xml'); 
        header("Location: index.php?Autor/show");
    }
}


