package Controller;

import java.io.BufferedReader;
import java.io.FileReader;
import java.util.ArrayList;
import com.db4o.ObjectContainer;
import com.db4o.ObjectSet;
import Model.Municipi;
import Model.MunicipiModel;
import Model.Partit;
import Model.PartitModel;
import Model.Resultat;
import Model.ResultatModel;

public class CargarCsv {
    private ObjectContainer db;

    public CargarCsv(ObjectContainer db) {
        this.db = db;
    }

    
    /**
	 * carga el csv con las elecciones de los años, primero lo pilla con
	 * bufferReader y lo separa por comas con un split, el split lo divide para
	 * meterlo en el objeto corresponiente
	 * 
	 * @param rutaArchivo ruta que se pasa por la view del main
	 * @return
	 */
    public ArrayList<Resultat> cargarCsv(String rutaArchivo) {
        ArrayList<Resultat> resultados = new ArrayList<>();
        try (BufferedReader br = new BufferedReader(new FileReader(rutaArchivo))) {
            br.readLine();

            String line;
            while ((line = br.readLine()) != null) {
                String[] datos = line.split(",(?=(?:[^\"]*\"[^\"]*\")*[^\"]*$)");

                for (int i = 0; i < datos.length; i++) {
                    datos[i] = datos[i].trim().replaceAll("^\"|\"$", "");
                }

                if (datos.length < 7) {
                    System.err.println("Línea incompleta: " + line);
                    continue;
                }

                try {
                    long codiMunicipi = Long.parseLong(datos[0]);
                    String nombreMunicipi = datos[1];
                    int anyEleccio = Integer.parseInt(datos[2]);
                    String siglesPartit = datos[3];
                    int vots = Integer.parseInt(datos[4]);
                    double porcentajeVotos = Double.parseDouble(datos[5].replace("%", "").trim());
                    int regidors = Integer.parseInt(datos[6]);

                    Municipi municipio = MunicipiModel.getMunicipi(db, codiMunicipi);
                    if (municipio == null) {
                        municipio = new Municipi(codiMunicipi, nombreMunicipi);
                        MunicipiModel.guadrdarMunicipio(db, municipio);
                    } else if (!municipio.getNom().equals(nombreMunicipi)) {
                        MunicipiModel.updateMunicipi(db, codiMunicipi, nombreMunicipi);
                    }

                    Partit partido = PartitModel.getPartit(db, anyEleccio, siglesPartit);
                    if (partido == null) {
                        partido = new Partit(anyEleccio, siglesPartit, vots, regidors, municipio);
                        PartitModel.guadrdarPartido(db, partido);
                    } else {
                        PartitModel.updatePartit(db, anyEleccio, siglesPartit, vots, regidors, municipio);
                    }

                    Resultat resultado = ResultatModel.getResultado(db, municipio, partido, porcentajeVotos);
                    if (resultado == null) {
                        resultado = new Resultat(partido, municipio, porcentajeVotos);
                        resultados.add(resultado);
                        ResultatModel.guardarResultado(db, resultado);
                    } else {
                        if (resultado.getPorcentajeVotos() != porcentajeVotos) {
                            ResultatModel.updateResultat(db, municipio, partido, resultado.getPorcentajeVotos(),
                                    porcentajeVotos);
                        }
                    }

                } catch (NumberFormatException e) {
                    System.err.println("Error de formato numérico en línea: " + line);
                    e.printStackTrace();
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return resultados;
    }
    
    /**
     * Limpia la bd por completo
     */
    private void limpiarBaseDeDatos() {
        try {
            ObjectSet<Resultat> resultados = db.query(Resultat.class);
            while (resultados.hasNext()) {
                db.delete(resultados.next());
            }

            ObjectSet<Partit> partidos = db.query(Partit.class);
            while (partidos.hasNext()) {
                db.delete(partidos.next());
            }

            ObjectSet<Municipi> municipios = db.query(Municipi.class);
            while (municipios.hasNext()) {
                db.delete(municipios.next());
            }

            db.commit();
            System.out.println("Base de datos limpiada completamente");
        } catch (Exception e) {
            db.rollback();
            System.err.println("Error al limpiar la base de datos: " + e.getMessage());
        }
    }

    /**
     * carga el CSV limpiando primero la base de datos
     */
    public ArrayList<Resultat> cargarCsvConLimpieza(String rutaArchivo) {
        limpiarBaseDeDatos();
        return cargarCsv(rutaArchivo);
    }
}
