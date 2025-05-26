<?php
class MailView
{
    public static function show($frm_user) {
        $user = new UsuariModel();
        $user->user = $frm_user;
        
        echo "<html>\n";
        echo "<head>\n";
        echo "    <title>Correo de Registro</title>\n";
        echo "</head>\n";
        echo "<body>\n";
        echo "    <h2>Bienvenido a nuestro sitio</h2>\n";
        echo "    <p>Para completar tu registro, por favor haz clic en el siguiente botón de autorización.</p>\n";
        echo '        <button onclick="'. $user->actualizarAutorizacion($user).'">Autorizar Registro</button>';
        echo '        <a href="index.php?Login/show">Volver</a>';
        echo "    <p>Si no te registraste en nuestro sitio, por favor ignora este correo.</p>\n";
        echo "</body>\n";
        echo "</html>\n";
    }
}
