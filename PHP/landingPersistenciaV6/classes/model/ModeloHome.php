<?php
class ModeloHome
{
    private $nombre;
    private $correo;
    private $telefono;
    private $mensaje;
    
    private $nombre_error;
    private $correo_error;
    private $telefono_error;
    private $mensaje_error;
    
    private $correcto = 0;
    private $img;
    private $conexion;
    
    public function __construct(){
        $this->conexion = mysqli_connect('localhost', "didacAdmin", "didac", "myweb");
        if (! $this->conexion) {
            die("La conexión ha fallado, error número " . mysqli_connect_errno() . ": " . mysqli_connect_error());
        }
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
    public function getCorreo() {
        return $this->correo;
    }
    
    public function setCorreo($correo) {
        $this->correo = $correo;
    }
    
    public function getTelefono() {
        return $this->telefono;
    }
    
    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }
    
    public function getMensaje() {
        return $this->mensaje;
    }
    
    public function setMensaje($mensaje) {
        $this->mensaje = $mensaje;
    }
    
    public function getNombreError() {
        return $this->nombre_error;
    }
    
    public function setNombreError($nombre_error) {
        $this->nombre_error = $nombre_error;
    }
    
    public function getCorreoError() {
        return $this->correo_error;
    }
    
    public function setCorreoError($correo_error) {
        $this->correo_error = $correo_error;
    }
    
    public function getTelefonoError() {
        return $this->telefono_error;
    }
    
    public function setTelefonoError($telefono_error) {
        $this->telefono_error = $telefono_error;
    }
    
    public function getMensajeError() {
        return $this->mensaje_error;
    }
    
    public function setMensajeError($mensaje_error) {
        $this->mensaje_error = $mensaje_error;
    }
    
    public function getImg() {
        return $this->img;
    }
    
    public function setImg($img) {
        $this->img = $img;
    }
    
    public function getCorrecto() {
        return $this->correcto;
    }
    
    public function setCorrecto($correcto) {
        $this->correcto = $correcto;
    }
    
    public function getConexion() {
        return $this->conexion;
    }
    
    public function setConexion($conexion) {
        $this->conexion = $conexion;
    }
    
    public function cogerImgUser()
    {
        $default = '../media/usuarioNoRegistrado.jpg';
        if (isset($_SESSION['nombreUser'])) {
            $nombreUser = $_SESSION['nombreUser'];;
        }
        $stmt = $this->conexion->prepare("SELECT fotoPerfil FROM registro WHERE usuario = ?"); 
        $stmt->bind_param("s", $nombreUser); 
        $stmt->execute(); 
        $stmt->bind_result($fotoUsuario); 
        $stmt->fetch(); 
        $stmt->close();
        if (isset($fotoUsuario)) {
            $this->img = $fotoUsuario;
        } else {
            $this->img = $default;
        } 
    }
    
    public function validar()
    {
        $this->nombre_error = $this->comprobar_nombre($this->nombre);
        if ($this->nombre_error !== '') {
            $this->nombre = '';
        } else {
            $this->correcto++;
        }
        
        $this->correo_error = $this->comprobar_correo($this->correo);
        if ($this->correo_error !== '') {
            $this->correo = '';
        } else {
            $this->correcto++;
        }
        
        $this->telefono_error = $this->comprobar_tel($this->telefono);
        if ($this->telefono_error !== '') {
            $this->telefono = '';
        } else {
            $this->correcto++;
        }
        
        $this->mensaje_error = $this->comprobar_msg($this->mensaje);
        if ($this->mensaje_error !== '') {
            $this->mensaje = '';
        } else {
            $this->correcto++;
        }
    }
    
    private function comprobar_nombre($nombre)
    {
        global $errorNombre;
        if (empty($nombre)) {
            return $errorNombre;
        } elseif (! ctype_alpha($nombre)) {
            return "El nombre no está en el formato correcto";
        }
        return '';
    }
    
    private function comprobar_correo($correo)
    {
        global $errorCorreo;
        if (empty($correo)) {
            return $errorCorreo;
        } elseif (! filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return "El correo no está en el formato correcto";
        }
        return '';
    }
    
    private function comprobar_tel($tel)
    {
        global $errorTelefono;
        if (empty($tel)) {
            return $errorTelefono;
        } elseif (! ctype_digit($tel) || strlen($tel) != 9) {
            return "El teléfono no está en el formato correcto";
        }
        return '';
    }
    
    private function comprobar_msg($msg)
    {
        global $errorMensaje;
        if (empty($msg)) {
            return $errorMensaje;
        }
        return '';
    }
    
    public function save()
    {
        if ($this->correcto == 4) {
            $file = "../formDatos.xml";
            $contenido = file_get_contents($file);
            
            $contactador = "\n<contact>\n";
            $contactador .= "   <name>{$this->nombre}</name>\n";
            $contactador .= "   <email>{$this->correo}</email>\n";
            $contactador .= "   <telefono>{$this->telefono}</telefono>\n";
            $contactador .= "   <mensaje>{$this->mensaje}</mensaje>\n";
            $contactador .= "   <date>" . date('Y-m-d H:i:s') . "</date>\n";
            $contactador .= "</contact>";
            
            $contenido = str_replace("</contactos>", $contactador . "\n</contactos>", $contenido);
            
            file_put_contents($file, $contenido);
        }
    }
}
