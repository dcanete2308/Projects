package Model;
import java.sql.*;

public class Connexio {
		private Connection connexioBD = null;
//	    private String servidor = "jdbc:mysql://192.168.103.50:3306/";
		private String servidor = "jdbc:mysql://localhost:3306/";
	    private String bbdd = "chat";
//	    private String user = "appuser";
//	    private String password = "TiC.123456";
	    private String user = "didacAdmin";
	    private String password = "didac";
		
		
	    /**
	     * Se conecta a la bd cogiendo los atr de la clase
	     * @throws SQLException
	     * @throws ClassNotFoundException
	     */
	    public Connexio() throws SQLException, ClassNotFoundException {
	    	
	    	try {
				if (this.connexioBD == null) {
					Class.forName("com.mysql.cj.jdbc.Driver");
					this.connexioBD = DriverManager.getConnection(this.servidor+this.bbdd, this.user, this.password);				
				}
			} catch (Exception e){
				throw e;			
			} 
	    }
	    
	    /**
	     * Cierra la connexión a la bd
	     */
	    public void cerrarConexion() {
	        try {
	            if (connexioBD != null && !connexioBD.isClosed()) {
	                connexioBD.close();
	                System.out.println("Conexión cerrada correctamente.");
	            }
	        } catch (SQLException e) {
	            System.err.println("Error al cerrar la conexión: " + e.getMessage());
	        }
	    }
			
	    public Connection getConnexioBD() {
			return connexioBD;
		}
		
		
}
