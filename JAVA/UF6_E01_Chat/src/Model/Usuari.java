package Model;

import java.sql.CallableStatement;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Timestamp;
import java.util.ArrayList;
import java.util.List;

public class Usuari extends Connexio {

	public Usuari() throws SQLException, ClassNotFoundException {
		super();
	}

	/**
	 * Se conecta con la bd, le envia el usuairio que nosotors hemos añadido en la view
	 * @param name usuario que entra por la view
	 */
	public void connect(String name) {
		Connection con = getConnexioBD();
		String sql = "{CALL connect(?)}";

		try (CallableStatement stmt = con.prepareCall(sql)) {
			stmt.setString(1, name);
			stmt.execute();

		} catch (SQLException e) {
		    throw new RuntimeException("Error al conectar el usuario: " + e.getMessage(), e);
		}
	}
	
	/**
	 * Se conecta con la bd y añade los usuarios que devuelve el procedure a una lista
	 * @return lista con los usuarios conectados
	 */
	public List<String> getUsuarios() {
        List<String> usuarios = new ArrayList<>();
        Connection con = getConnexioBD();
        String sql = "{CALL getConnectedUsers()}";

        try (CallableStatement stmt = con.prepareCall(sql);
             ResultSet result = stmt.executeQuery()) {

            while (result.next()) {
                String nick = result.getString("nick");
                usuarios.add(nick);
            }

        } catch (SQLException e) {
            System.err.println("Error al ejecutar el procedimiento almacenado: " + e.getMessage());
        }

        return usuarios;
    }
	
	/**
	 * Se desconecta de la bd
	 */
	public void disconnect() {
		Connection con = getConnexioBD();
		String sql = "{CALL disconnect()}";
		try (CallableStatement stmt = con.prepareCall(sql)) {
			stmt.execute();

		} catch (SQLException e) {
		    throw new RuntimeException("Error al desconectar el usuario: " + e.getMessage(), e);
		}
	}

}
