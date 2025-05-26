package Controller;

import Model.Usuari;
import Model.missatge;
import View.Chat;
import View.ErrorView;

import javax.swing.*;

public class AppMain {

    private static Usuari usuari;
    private static missatge mensaje;
    private static Chat view;
    private static ErrorView errorView;
    private static Tiempo actualizar;

    public static void main(String[] args) {
        SwingUtilities.invokeLater(() -> {
            try {
                view = new Chat(); //muestra la view
                view.setVisible(true);

                usuari = new Usuari(); 
                mensaje = new missatge();

                setupListeners();

                actualizar = new Tiempo(usuari, mensaje, view); //inicia el timer

            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }

    /**
     * Método que muesstra el error
     * @param errorMessage mensaje que saldrá en la view
     */
    private static void showErrorView(String errorMessage) {
    	 if (errorView != null) {
             errorView.dispose();
         }
         errorView = new ErrorView(errorMessage);  
         errorView.setVisible(true);
    }
    
    /**
     * coge los botones de la view y les añade un evento, dependiendo de que boton se clique hace una accion o otra.
     * El primero es el connect que si el nombre del usuario no esta vacio lo envia a la bd con el procedure
     * El segundo es el que envia los mensajes
     * El tercero es el que desconecta de la bd al usuario
     */
    private static void setupListeners() {
        view.getBtnConnect().addActionListener(e -> {
            String userName = view.getUserName();
            if (!userName.isEmpty()) {
            	try {
            		 usuari.connect(userName);
                     view.getBtnConnect().setEnabled(false);  
                     view.getBtnDisconnect().setEnabled(true); 
                     view.disableUserNameInput(); 
            	} catch (Exception ex) {
                    showErrorView("No se pudo conectar al usuario.");
                }
            } else {
                JOptionPane.showMessageDialog(view, "Ingresa un nombre de usuario válido.");
            }
        });

        view.getBtnSend().addActionListener(e -> {
            String message = view.getMessage();
            if (!message.isEmpty()) {
                try {
                    mensaje.sendMsg(message); 
                } catch (Exception ex) {
                    showErrorView("Error al enviar el mensaje.");
                }
            }
        });

        view.getBtnDisconnect().addActionListener(e -> {
        	try {
                usuari.disconnect(); 
                view.clearChat(); 
                view.getBtnConnect().setEnabled(true);  
                view.getBtnDisconnect().setEnabled(false); 
                view.enableUserNameInput(); 
        	} catch (Exception ex) {
                showErrorView("Error al desconectar.");
            }
        });
    }
}

