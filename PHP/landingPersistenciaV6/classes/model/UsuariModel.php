<?php
require_once ('DBAbstractModel.php');

class UsuariModel extends DBAbstractModel
{

    public $user = '';

    public $passwd = '';

    public $repetirPasswd = '';

    public $dni = '';

    public $nombre = '';

    public $apellido = '';

    public $fecha = '';

    public $genero = '';

    public $direccion = '';

    public $cp = '';

    public $provincia = '';

    public $telefono = '';

    public $tipo_identificacion = '';

    public $fotoPerfil = '';

    public $errors;

    public $contrasenya;

    public $autorizado = 0;

    protected $id;

    function __construct()
    {
        $this->db_name = 'myweb';
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

    public function get($user_email = '')
    {
        if ($user_email != '') {
            $this->query = "
                 SELECT contrasenya
                 FROM registro
                 WHERE usuario = '$user_email'
                 ";
            $this->get_results_from_query();
        }

        if (count($this->rows) == 1) {
            foreach ($this->rows[0] as $propiedad => $valor) {
                $this->$propiedad = $valor;
            }
        }
    }
    
    public function getAutorizado($user_email = '')
    {
        if ($user_email != '') {
            $this->query = "
                 SELECT autorizado
                 FROM registro
                 WHERE usuario = '$user_email'
                 ";
            $this->get_results_from_query();
        }
        
        if (count($this->rows) == 1) {
            foreach ($this->rows[0] as $propiedad => $valor) {
                $this->$propiedad = $valor;
            }
        }
    }

    public function set($user_data = array()) {
        if (array_key_exists('usuario', $user_data)) {
            $this->get($user_data['usuario']);
            
            foreach ($user_data as $camp => $valor) {
                $$camp = $valor;
            }
            
            $direccion = !empty($this->direccion) ? "'$this->direccion'" : "NULL";
            $codigoPostal = !empty($this->cp) ? "'$this->cp'" : "NULL";
            $provincia = !empty($this->provincia) ? "'$this->provincia'" : "NULL";
            $telefono = !empty($this->telefono) ? "'$this->telefono'" : "NULL";
            
            $passwdHash = password_hash($this->passwd, PASSWORD_DEFAULT);
            
            $this->query = "
            INSERT INTO registro
            (usuario, contrasenya, repetirContrasenya, dni, identificacion, nombre, apellido, fechaNacimiento, sexo, direccion, provincia, codigoPostal, telefono, fotoPerfil, autorizado)
            VALUES
            ('$this->user', '$passwdHash', '$this->repetirPasswd', '$this->dni', '$this->tipo_identificacion', '$this->nombre', '$this->apellido', '$this->fecha', '$this->genero',
            $direccion, $provincia, $codigoPostal, $telefono, '$this->fotoPerfil', '0')
        ";
            
            $this->execute_single_query();
        }
    }
    
    
    public function edit($user_data = array())
    {
        foreach ($user_data as $campo => $valor) {
            $$campo = $valor;
            $this->query = "
                 UPDATE registro
                 SET apellido='a'
                 WHERE usuario = '$this->user'
                 ";
            $this->execute_single_query();
        }
    }

    public function actualizarAutorizacion($user) {
            $frm_user=$user->user;
            $this->query = "
                 UPDATE registro
                 SET autorizado=1
                 WHERE usuario = '$user->user'
                 ";
            $this->execute_single_query();
    }
    public function delete($user_email = '')
    {
        $this->query = "
             DELETE FROM registro
             WHERE usuario = '$user_email'
             ";
        $this->execute_single_query();
    }

    public function verificarUser($user)
    {
        $this->query = "
            SELECT usuario
            FROM registro 
            WHERE usuario = '$user'";
    }

    public function verificarContra($user)
    {
        $this->query = "
            SELECT contrasenya
            FROM registro
            WHERE usuario = '$user'";
    }

    // public function __destruct() {
    // unset($this);
    // }
}