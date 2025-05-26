package Model;

import java.util.HashSet;

import com.db4o.ObjectContainer;
import com.db4o.ObjectSet;
import com.db4o.ext.DatabaseClosedException;
import com.db4o.ext.Db4oException;

public class ResultatModel {
	/**
	 * Guarda los resultados en la bd
	 * 
	 * @param db
	 * @param resultado Objeto de negocio
	 * @return String de comprobación
	 * @throws Exception
	 */
	public static String guardarResultado(ObjectContainer db, Resultat resultado) throws Exception {

		try {
			db.store(resultado); // guarda los dtos en la bd
			db.commit();
			return "Resultado guardado para municipio: " + resultado.getMunicipi().getNom();
		} catch (DatabaseClosedException e) {
			throw new Exception("La base de datos esta cerrada: " + e);
		} catch (Db4oException e) {
			throw new Exception("Error en la escritura o lectura de la bd: " + e);
		}
	}

	/**
	 * Pilla solo 1 resultado en base al partido, municipio y porcentaje
	 * 
	 * @param db
	 * @param municipi   obj de negocio
	 * @param partit     obj de negocio
	 * @param porcentaje obj de negocio
	 * @return el resultado de la bd o null
	 * @throws Exception
	 */
	public static Resultat getResultado(ObjectContainer db, Municipi municipi, Partit partit, double porcentaje)
			throws Exception {

		try {
			ObjectSet<Resultat> result = db.queryByExample(new Resultat(partit, municipi, porcentaje)); // enseña el resultado de la bd
			if (result.hasNext()) { // si hay un resultado lo devuele
				return result.next();
			}
			return null;
		} catch (DatabaseClosedException e) {
			throw new Exception("La base de datos esta cerrada: " + e);
		} catch (Db4oException e) {
			throw new Exception("Error en la escritura o lectura de la bd: " + e);
		}
	}
		
	/**
	 * Elimina un Resultdado
	 * @param db
	 * @param municipi   obj de negocio
	 * @param partit     obj de negocio
	 * @param porcentaje obj de negocio
	 * @throws Exception
	 */
	public static void deleteResultat(ObjectContainer db, Municipi municipi, Partit partit, double porcentaje) throws Exception {
	    try {
	        ObjectSet<Resultat> result = db.queryByExample(new Resultat(partit, municipi, porcentaje));
	        while (result.hasNext()) {  
	            Resultat res = result.next();
	            db.delete(res);
	        }
	        db.commit();
	        System.out.println("Resultado(s) eliminado(s)");
	    } catch (DatabaseClosedException e) {
	        throw new Exception("La base de datos esta cerrada: " + e);
	    } catch (Db4oException e) {
	        throw new Exception("Error en la escritura o lectura de la bd: " + e);
	    }
	}
	
	/**
	 * Actualiza un resulatado, concretamente actualiza el porcentaje
	 * @param db
	 * @param municipi
	 * @param partit
	 * @param porcentajeAntiguo
	 * @param nuevoPorcentaje
	 * @throws Exception
	 */
	public static void updateResultat(ObjectContainer db, Municipi municipi, Partit partit, double porcentajeAntiguo, double nuevoPorcentaje) throws Exception {
	    try {
	        ObjectSet<Resultat> result = db.queryByExample(new Resultat(partit, municipi, porcentajeAntiguo));
	        
	        while (result.hasNext()) {
	            Resultat resultado = result.next();
	            resultado.setPorcentajeVotos(nuevoPorcentaje);
	            db.store(resultado);
	            System.out.println("Resultado actualizado para municipio: " + municipi.getNom());
	        }
	        db.commit();
	    } catch (DatabaseClosedException e) {
	        throw new Exception("La base de datos está cerrada: " + e);
	    } catch (Db4oException e) {
	        throw new Exception("Error en la escritura o lectura de la bd: " + e);
	    }
	}

	/**
	 * Pilla entras el municipio y te enseña los partidos de se municipio, según mi interpretación ha de enseñar eso
	 * 
	 * @param db
	 * @param nombre nombre del municipio que buscas
	 * @return String con los datos de los partidos
	 * @throws Exception
	 */
	public static String resultadosPartidox1Provincia(ObjectContainer db, String nombre) throws Exception {

		try {
			ObjectSet<Partit> result = db.queryByExample(new Partit(0, nombre, 0, 0, null)); // devuelve todos los partidos
			StringBuilder sb = new StringBuilder();

			if (result.isEmpty()) { // si esta vacio devuelve un mensaje
				return "No se ha encontrado el partido: " + nombre;
			}

			boolean found = false;
			int contador = 0;

			for (Partit p : result) { // for each de todos los resultados de los partidos
				ObjectSet<Resultat> resultados = db.queryByExample(new Resultat(p, null, 0)); // aquí busca en base al partido el resultado

				if (resultados.isEmpty()) { // si esta vacio devuelve un mensaje
					sb.append("No se han encontrado resultados para el partido: ").append(p.getSiglas()).append("\n");
				} else {
					for (Resultat r : resultados) { // For each para enseñar lo que devuelve
						contador++;
						sb.append(String.format("Partido: %-10s Año: %-4d%n", p.getSiglas(), p.getAny()))
						  .append(String.format("Resultado elecciones %-2d: %-12.2f Votos en el municipio: %-25s%n", contador, r.getPorcentajeVotos(), r.getMunicipi().getNom()));
					}
					found = true;
				}
			}

			if (!found) {
				return "No se han encontrado resultados para el partido: " + nombre;
			}

			return sb.toString();
		} catch (DatabaseClosedException e) {
			throw new Exception("La base de datos esta cerrada: " + e);
		} catch (Db4oException e) {
			throw new Exception("Error en la escritura o lectura de la bd: " + e);
		}
	}

	/**
	 * Eneña todos los municipios que tienen al menos un registro de ese partido
	 * 
	 * @param db
	 * @param nombre nombre del partido
	 * @return
	 * @throws Exception
	 */
	public static String municipisQueTienenPartido(ObjectContainer db, String nombre) throws Exception {
		try {
			StringBuilder sb = new StringBuilder();
			ObjectSet<Partit> partidos = db.queryByExample(new Partit(0, nombre, 0, 0, null)); // devuelve todos los partidos

			if (partidos.isEmpty()) { // si esta vacio devuelve un mensaje
				return "No se ha encontrado el partido: " + nombre;
			}
			HashSet<String> municipiosSet = new HashSet<>(); // hace un hashSet para solo enseñar un único municipio
			for (Partit partit : partidos) {
				ObjectSet<Resultat> resultados = db.queryByExample(new Resultat(partit, null, 0)); // en base a los partidos busca un resultado
				for (Resultat r : resultados) {
					municipiosSet.add(r.getMunicipi().getNom()); // en base al añade al hashSet el municipio que lo contiene
				}
			}
			if (municipiosSet.isEmpty()) { // si esta vacio devuelve un mensaje
				return "No se han encontrado municipios con resultados para el partido: " + nombre;
			}
			sb.append("Municipios donde ha habido resultados para el partido ").append(nombre).append(":\n");
			for (String municipi : municipiosSet) { // enseña todos los municipios
				sb.append("- ").append(municipi).append("\n");
			}
			return sb.toString();
		} catch (DatabaseClosedException e) {
			throw new Exception("La base de datos esta cerrada: " + e);
		} catch (Db4oException e) {
			throw new Exception("Error en la escritura o lectura de la bd: " + e);
		}
	}

}
