package Model;


import java.util.ArrayList;

import com.db4o.ObjectContainer;
import com.db4o.ObjectSet;
import com.db4o.ext.DatabaseClosedException;
import com.db4o.ext.Db4oException;

public class PartitModel {
	/**
	 * Guarda los partidos
	 * 
	 * @param db
	 * @param partido partidos que guadrda en la bd
	 * @throws Exception
	 */
	public static void guadrdarPartido(ObjectContainer db, Partit partido) throws Exception {

		try {
			Partit ejemplo = new Partit(partido.getAny(), partido.getSiglas(), 0, 0, null); // busca los partidos
			ObjectSet<Partit> result = db.queryByExample(ejemplo);

			if (result.isEmpty()) {
				db.store(partido); // lo almacena en la bd
				db.commit(); // se asegura que se guarde
				System.out.println("Partido guardado: " + partido.getSiglas() + " (" + partido.getAny() + ")");
			} else {
				System.out.println("Partido ya existe: " + partido.getSiglas() + " (" + partido.getAny() + ")");
			}
		} catch (DatabaseClosedException e) {
			throw new Exception("La base de datos esta cerrada: " + e);
		} catch (Db4oException e) {
			throw new Exception("Error en la escritura o lectura de la bd: " + e);
		}

	}
	
	/**
	 * Devuelve todos los partidos de la bd
	 * @param db
	 * @return String con los datos del partido
	 * @throws Exception
	 */
	public static String getPartits(ObjectContainer db) throws Exception {

		try {
			ObjectSet<Partit> result = db.query(Partit.class); // Coje todos los partidos
			StringBuilder sb = new StringBuilder();

			if (result.isEmpty()) { // si no hay partidos envia un mensaje
				return "No hay partidos guardados";
			} else {
				sb.append("Partidos:\n");
				for (Partit p : result) { // devuelve los partidos
					  sb.append(String.format("Siglas: %-10s Any: %-4d Vots: %-5d Regidors: %-4d%n", 
	                            p.getSiglas(), 
	                            p.getAny(), 
	                            p.getVots(), 
	                            p.getRegidores()));
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
	 * Pilla un patrido en base al año y al nombre
	 * @param db
	 * @param anyEleccio año que se hicierón las elecciones
	 * @param nom nombre del partido
	 * @return el partido o null
	 * @throws Exception
	 */
	public static Partit getPartit(ObjectContainer db, int anyEleccio, String nom) throws Exception {

		try {
			ObjectSet<Partit> result = db.queryByExample(new Partit(anyEleccio, nom, 0, 0, null)); // busca en la base de datos que el ejemplo coincida y devuelve todos los objetos
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
	 * Devuelve un arrayList de strings para coger el nombre del partido para el JcomboBox
	 * @param db
	 * @param anyEleccio año que se hicierón las elecciones
	 * @param nom nombre del partido
	 * @return el nombre del partido o null
	 * @throws Exception
	 */
	public static ArrayList<String> getPartitNombre(ObjectContainer db) throws Exception {
	    ArrayList<String> partits = new ArrayList<>();
		try {
			ObjectSet<Partit> result = db.queryByExample(Partit.class);
			while (result.hasNext()) {
				Partit p = result.next();
				partits.add(p.getSiglas()); 
			}
		} catch (DatabaseClosedException e) {
			throw new Exception("La base de datos esta cerrada: " + e);
		} catch (Db4oException e) {
			throw new Exception("Error en la escritura o lectura de la bd: " + e);
		}
		return partits;
	}
	
	
	/**
	 * Elimina un partido
	 * @param db
	 * @param anyEleccio año que se hicierón las elecciones
	 * @param nom nombre del partido
	 * @throws Exception
	 */
	public static void deletePartit(ObjectContainer db, int anyEleccio, String nom) throws Exception {
	    try {
	        Partit ejemplo = new Partit(anyEleccio, nom, 0, 0, null);
	        ObjectSet<Partit> result = db.queryByExample(ejemplo);
	        while (result.hasNext()) {
	            Partit res = result.next();
	            ObjectSet<Resultat> resultados = db.queryByExample(new Resultat(res, null, 0));
	            while (resultados.hasNext()) {
	                db.delete(resultados.next());
	            }
	            db.delete(res);
	        }
	        db.commit();
	        System.out.println("Partido(s) y sus resultados asociados eliminados");
	    } catch (DatabaseClosedException e) {
	        throw new Exception("La base de datos esta cerrada: " + e);
	    } catch (Db4oException e) {
	        throw new Exception("Error en la escritura o lectura de la bd: " + e);
	    }
	}
	
	/**
	 * Actualiza un partido
	 * @param db
	 * @param anyEleccio
	 * @param nom
	 * @param nuevosVotos
	 * @param nuevosRegidores
	 * @param nuevoMunicipio
	 * @throws Exception
	 */
	public static void updatePartit(ObjectContainer db, int anyEleccio, String nom, int nuevosVotos, int nuevosRegidores, Municipi nuevoMunicipio) throws Exception {
	    try {
	        Partit ejemplo = new Partit(anyEleccio, nom, 0, 0, null);
	        ObjectSet<Partit> result = db.queryByExample(ejemplo);
	        
	        while (result.hasNext()) {
	            Partit partido = result.next();
	            partido.setVots(nuevosVotos);
	            partido.setRegidores(nuevosRegidores);
	            partido.setMunicipi(nuevoMunicipio);
	            
	            db.store(partido);
	            System.out.println("Partido actualizado: " + partido.getSiglas());
	        }
	        
	        db.commit();
	    } catch (DatabaseClosedException e) {
	        db.rollback();
	        throw new Exception("La base de datos está cerrada: " + e);
	    } catch (Db4oException e) {
	        db.rollback();
	        throw new Exception("Error en la escritura o lectura de la bd: " + e);
	    }
	}

	/**
	 * Devuelve todos los resultados electorales de un municipio
	 * @param db Conexión a la base de datos
	 * @param nombre Nombre del municipio
	 * @return String con los datos formateados
	 * @throws Exception
	 */
	public static String resPartitMunicipi(ObjectContainer db, String nombre) throws Exception {
	    try {
	        Municipi ejemploMunicipi = new Municipi(nombre);
	        ObjectSet<Municipi> resultMunicipi = db.queryByExample(ejemploMunicipi);
	        
	        if (resultMunicipi.isEmpty()) {
	            return "No se ha encontrado el municipio: " + nombre;
	        }
	        
	        Municipi municipiFound = resultMunicipi.next();
	        
	        Resultat ejemploResultado = new Resultat();
	        ejemploResultado.setMunicipi(municipiFound);
	        ObjectSet<Resultat> resultados = db.queryByExample(ejemploResultado);
	        
	        StringBuilder sb = new StringBuilder();
	        sb.append("Resultados para el municipio: ").append(nombre).append("\n");
	        
	        if (resultados.isEmpty()) {
	            sb.append("No hay resultados electorales para este municipio");
	        } else {
	            for (Resultat r : resultados) {
	                Partit p = r.getPartit();
	                sb.append(municipiFound.getCodi()).append("\t")
	                  .append(municipiFound.getNom()).append("\t")
	                  .append(p.getAny()).append("\t")
	                  .append(p.getSiglas()).append("\t")
	                  .append(p.getVots()).append("\t")
	                  .append(String.format("%.2f", r.getPorcentajeVotos())).append("%\t")
	                  .append(p.getRegidores()).append("\n");
	            }
	        }
	        
	        return sb.toString();
	    } catch (DatabaseClosedException e) {
	        throw new Exception("La base de datos está cerrada: " + e.getMessage());
	    } catch (Db4oException e) {
	        throw new Exception("Error en la base de datos: " + e.getMessage());
	    }
	}

}
