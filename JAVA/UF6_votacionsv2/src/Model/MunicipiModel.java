package Model;

import java.util.ArrayList;

import com.db4o.ObjectContainer;
import com.db4o.ObjectSet;
import com.db4o.ext.DatabaseClosedException;
import com.db4o.ext.Db4oException;

public class MunicipiModel {
	/**
	 * Guarda un municipio en la bd
	 * @param db
	 * @param municipi municipio que ha de guadrar o actualizar
	 * @return String de comprobación
	 * @throws Exception
	 */
	public static String guadrdarMunicipio(ObjectContainer db, Municipi municipi) throws Exception {
		try {
			Municipi ejemplo = new Municipi(municipi.getCodi(), null);
			ObjectSet<Municipi> result = db.queryByExample(ejemplo); // busca en la bd un registro que coincida

			if (result.isEmpty()) {
				db.store(municipi); // lo almacena en la bd
				db.commit(); // se asegura que se guarde
				return "Municipio guardado: " + municipi.getNom();
			} else {
				return "Municipio ya existe: " + municipi.getNom();
			}
		} catch (DatabaseClosedException e) {
			throw new Exception("La base de datos esta cerrada: " + e);
		} catch (Db4oException e) {
			throw new Exception("Error en la escritura o lectura de la bd: " + e);
		}

	}
	
	/**
	 * Pilla los municipios que estan en la bd y los enseña
	 * @param db
	 * @return String con los datos
	 * @throws Exception
	 */
	public static String getMunicipis(ObjectContainer db) throws Exception {
		try {
			ObjectSet<Municipi> result = db.query(Municipi.class); // Coje todos los municipios
			StringBuilder sb = new StringBuilder();

			if (result.isEmpty()) { // si no hay municipios envia un mensaje
				return "No hay municipios guardados";
			} else {
				sb.append("Municipios:\n");
				for (Municipi m : result) { // si hay municipios enseña el registro de la bd
					sb.append(String.format("%-25s\tCode: %-12d%n", m.getNom(), m.getCodi()));
				}
			}
			return sb.toString();
		} catch (DatabaseClosedException e) {
			throw new Exception("La base de datos esta cerrada: " + e);
		} catch (Db4oException e) {
			throw new Exception("Error en la escritura o lectura de la bd: " + e);
		}
		
	}

	/**
	 * devuelve un muncicipio en base a su codigo
	 * @param db
	 * @param codiMunicipi del municipio que busca
	 * @return el municipio o null
	 * @throws Exception
	 */
	public static Municipi getMunicipi(ObjectContainer db, long codiMunicipi) throws Exception {
		try {
			ObjectSet<Municipi> result = db.queryByExample(new Municipi(codiMunicipi, null)); // busca en la base de datos que el ejemplo coincida y devuelve todos los objetos
			if (result.hasNext()) { // mira si al menos tiene 1 resultado
				return result.next(); // si hay lo devuelve
			}
			return null;
		} catch (DatabaseClosedException e) {
			throw new Exception("La base de datos esta cerrada: " + e);
		} catch (Db4oException e) {
			throw new Exception("Error en la escritura o lectura de la bd: " + e);
		}

	}
	
	/**
	 * devuelve un arrayList de nombres del municipio para el JcomboBox
	 * @param db
	 * @param codiMunicipi del municipio que busca
	 * @return el nommbre del municipio o null
	 * @throws Exception
	 */
	public static ArrayList<String> getMunicipiNombre(ObjectContainer db) throws Exception {
		ArrayList<String> municipios = new ArrayList<>();
		try {
			ObjectSet<Municipi> result = db.queryByExample(Municipi.class); 
			while (result.hasNext()) {
				Municipi m = result.next();
	            municipios.add(m.getNom());
			}
		} catch (DatabaseClosedException e) {
			throw new Exception("La base de datos esta cerrada: " + e);
		} catch (Db4oException e) {
			throw new Exception("Error en la escritura o lectura de la bd: " + e);
		}
	    return municipios;
	}
	
	/**
	 * Elimina un Municipio
	 * @param db
	 * @param codiMunicipi del municipio que busca
	 * @return el municipio o null
	 * @throws Exception
	 */
	public static void deleteMunicipi(ObjectContainer db, long codiMunicipi) throws Exception {
	    try {
	        ObjectSet<Municipi> result = db.queryByExample(new Municipi(codiMunicipi, null));
	        while (result.hasNext()) {
	            Municipi res = result.next();
	            db.delete(res);
	        }
	        db.commit();
	        System.out.println("Municipio(s) y sus datos asociados eliminados");
	    } catch (DatabaseClosedException e) {
	        throw new Exception("La base de datos esta cerrada: " + e);
	    } catch (Db4oException e) {
	        throw new Exception("Error en la escritura o lectura de la bd: " + e);
	    }
	}
	

	/**
	 * Actualiza un municipio en concreto
	 * @param db
	 * @param codiMunicipi
	 * @param nuevoNombre
	 * @throws Exception
	 */
	public static void updateMunicipi(ObjectContainer db, long codiMunicipi, String nuevoNombre) throws Exception {
	    try {
	        ObjectSet<Municipi> result = db.queryByExample(new Municipi(codiMunicipi, null));
	        
	        while (result.hasNext()) {
	            Municipi municipio = result.next();
	            municipio.setNom(nuevoNombre);
	            
	            db.store(municipio);
	            System.out.println("Municipio actualizado: " + municipio.getNom());
	        }
	        
	        db.commit();
	    } catch (DatabaseClosedException e) {
	        throw new Exception("La base de datos está cerrada: " + e);
	    } catch (Db4oException e) {
	        throw new Exception("Error en la escritura o lectura de la bd: " + e);
	    }
	}
	
	

}
