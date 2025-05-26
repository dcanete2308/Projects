package Model;
import com.db4o.Db4oEmbedded;
import com.db4o.ObjectContainer;
import com.db4o.ObjectSet;

public class Database {
	public static ObjectContainer db;
	
	public Database() {
		if (db == null) {
			this.conectar();
		}
	}
	
	private void conectar() {
		this.db = Db4oEmbedded.openFile(Db4oEmbedded.newConfiguration(), "../UF6_votacionsv2/src/baseDatos/votaciones.db4o");
		
	}
	

	public void desconectar() {
        if (db != null) {
            db.close();
            db = null;
        }
    }
}
