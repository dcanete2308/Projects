package Model;

import java.sql.CallableStatement;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Timestamp;
import java.util.ArrayList;
import java.util.List;

public class missatge extends Connexio {

	public missatge() throws SQLException, ClassNotFoundException {
		super();
	}
	
	/**
	 * Llama al método de la bd, y le envia el mensaje que hemos introducido por la view
	 * @param msg mensaje a enviar
	 */
	public void sendMsg(String msg) {
		Connection con = getConnexioBD();
        String sql = "{CALL send(?)}"; 

        try (CallableStatement stmt = con.prepareCall(sql)) {
            stmt.setString(1, msg);

            stmt.execute();
            System.out.println("Mensaje enviado: " + msg);

        } catch (SQLException e) {
            throw new RuntimeException("Error al enviar el mensaje: " + e.getMessage(), e);
        }
   
	}
	
	/**
	 * llama al procedure que recoge los mensajes de la bd, luego al resultado del procedure se hace un while para coger los atr de la bd y posteriormente los mete en una lista
	 * @return devuyelve la lista con los mensajes que tiene en la bd
	 */
	public List<String> getMsg() {
		List<String> mensajes = new ArrayList<>();
		Connection con = getConnexioBD();
        String sql = "{CALL getMessages()}"; 

        try (CallableStatement stmt = con.prepareCall(sql);
             ResultSet result = stmt.executeQuery()) {

            while (result.next()) {
                String msg = result.getString("message");
                String nick = result.getString("nick");
                Timestamp date = result.getTimestamp("ts");
                String fechaFormateada = new java.text.SimpleDateFormat("dd/MM/yyyy HH:mm:ss")
                        .format(date);

				StringBuilder sb = new StringBuilder();
				sb.append("Mensaje de ").append(nick).append("*\n")
				.append("Fecha: ").append(fechaFormateada).append("\n")
				.append("").append(msg);
				
				mensajes.add(sb.toString());
            }

        } catch (SQLException e) {
            throw new RuntimeException("Error al recuperar el mensaje: " + e.getMessage(), e);
        }
		return mensajes;
	}
	
}
