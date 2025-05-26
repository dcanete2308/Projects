package Controller;

import javax.swing.Timer;

import java.util.Collections;
import java.util.List;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import Model.Usuari;
import Model.missatge;
import View.Chat;

public class Tiempo{

    private Timer timer;
    private Usuari usuari;
    private missatge mensaje;
    private Chat view;

    public Tiempo(Usuari usuari, missatge mensaje, Chat view) {
        this.usuari = usuari;
        this.mensaje = mensaje;
        this.view = view;
        iniciarTimer(); // llama al método para que cuando sea instanciado empieze a contar
    }
    
    /**
     * Iniciaa el timer que actualiza los mensajes cada 3 segundos
     */
    private void iniciarTimer() {
        timer = new Timer(3000, new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                actualizarUsuarios();
                actualizarMsg();
            }
        });
        timer.start();
    }
    
    /**
     * recoge los usuarios que devuyelve el procedure y los ordena, posteriormente lo pasa a la view
     */
    private void actualizarUsuarios() {
        List<String> usuariosConectados = usuari.getUsuarios();
        Collections.sort(usuariosConectados); // coleccion que sirve para ordenar los usuarios alfabeticamente
        view.setUsersList(usuariosConectados);
    }
    
    /**
     * recoge los mensajes que devuyelve el procedure y los ordena, posteriormente lo pasa a la view
     */
    private void actualizarMsg() {
        List<String> mensajes = mensaje.getMsg();
        view.setMsgList(mensajes);
    }

}
