<?php
class LoginController extends Controller
{    
    public function __construct()
    {}
    
    public function show()
    {
        
        $frm_user = isset($_POST['user']) ? parent::sanitize(trim($_POST['user'])) : '';
        $frm_passwd = isset($_POST['passwd']) ? parent::sanitize(trim($_POST['passwd'])) : '';
        $usuarioIniciado = false;
        $passwdIniciado = false;
               
        $errors = [];
        
        $nuevoUsuario = new UsuariModel();
        
        $nuevoUsuario->get($frm_user);
        $contrasenyaCorrecta = $nuevoUsuario->contrasenya;
 
        $userError = $this->comprobar_user($frm_user ?? '');
        if ($userError !== '') {
            $errors['user'] = $userError;
        } else {
            $usuarioIniciado = true;
            $frm_user = parent::sanitize($frm_user);
        }
        
        $passwdError = $this->comprobar_passwd($frm_passwd ?? '');
        if ($passwdError !== '') {
            $errors['passwd'] = $passwdError;
        } elseif (!password_verify($frm_passwd, $contrasenyaCorrecta)) {
            $errors['passwd'] = 'La contraseña o el usuario es incorrecto';
        } else {
            $passwdIniciado = true;
            $frm_passwd = parent::sanitize($frm_passwd);
        }
        
        $nuevoUsuario->__set('user', $frm_user);
        $nuevoUsuario->__set('passwd', $frm_passwd);
        $nuevoUsuario->__set("errors", $errors);
        
        if ($passwdIniciado && $usuarioIniciado) {
            //Esta comenatado porque cuando yo creo un usuario en registro me sale automaticamente en 1 y no se cual es el error
//             $nuevoUsuario->getAutorizado($frm_user);
//             $usuarioAutorizado = $nuevoUsuario->autorizado;
//             if ($usuarioAutorizado == 0) {
//                 $errors['passwd'] = 'El usuario ha de estar Autorizado';
//                 $vMail = new MailView();
//                 $vMail->show($nuevoUsuario->user);
//                 exit();
//             } else {
    
                $_SESSION['usuario'] = $frm_user;
                header("Location: index.php?Home/show");
//             }
        }
                
        $vContacta = new LoginView2();
        $vContacta->showLogin($nuevoUsuario);
    }
    
    private function comprobar_user($user)
    {
        $UserError = '';
        if (empty($user)) {
            return $UserError;
        } elseif (! filter_var($user, FILTER_VALIDATE_EMAIL)) {
            return "El usuario no está en el formato correcto";
        }
        return '';
        
        return $UserError;
    }
    
    private function comprobar_passwd($passwd)
    {
        $passwdError = '';
        if (empty($passwd)) {
            return $passwdError;
        } elseif (strlen($passwd) < 8) {
            $passwdError = 'No puede ser menor a 8 carácteres';
        }
        
        return '';
    }
    
}

