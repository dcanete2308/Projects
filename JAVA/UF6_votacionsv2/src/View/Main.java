package View;

import java.util.ArrayList;

import javax.swing.JOptionPane;
import javax.swing.SwingUtilities;

import Controller.CargarCsv;
import Model.Database;
import Model.MunicipiModel;
import Model.PartitModel;
import Model.Resultat;
import Model.ResultatModel;
import com.db4o.ObjectContainer;

public class Main {
    public static void main(String[] args) {
        Database database = new Database();
        ObjectContainer db = database.db;

        try {
            SwingUtilities.invokeLater(() -> {
                MainView mainView = null;
				try {
					mainView = new MainView(db);
				} catch (Exception e) {
					e.printStackTrace();
				}
                mainView.setVisible(true);
            });

           
            
            //PARA COMPROBAR LA CARGA DE OTRO CSV HE CREADO UNO IDENTICO AL QUE SE CARGA POR DEFECTO PERO HE AÑADIDO UN NUEVO REGISTRO EN EL MUNICIPIO Abella de la Conca
            //TAMBIEN HE CREADO UN NUEVO PARTIDO LLAMADO DIDACSUPREMO EN EL MUNICIPIO DIDAC
            //SI VAMOS A CARGAR PARTIDOS POR MUNICIPIO SALE EL NUEVO EN EL ULTIMO REGISTRO
            
//            pruebas(db);
//            throw new Exception("PRUEBA");

        } catch (Exception e) {
            JOptionPane.showMessageDialog(null, 
                "Error: " + e.getMessage(), 
                "Error", 
                JOptionPane.ERROR_MESSAGE);
            e.printStackTrace();
        } 
    }

    private static void pruebas(ObjectContainer db) {
        try {
        	CargarCsv cargador = new CargarCsv(db);
            ArrayList<Resultat> resultados = cargador.cargarCsv("src/datos/votos2.csv");
            System.out.println("\n=== RESUMEN ===");
            System.out.println("\n=== LISTADO DE MUNICIPIOS ===");
            System.out.println(MunicipiModel.getMunicipis(db));

            System.out.println("\n=== LISTADO DE PARTIDOS ===");
            System.out.println(PartitModel.getPartits(db));

            System.out.println("\n=== PARTIDOS EN ABELLA DE LA CONCA ===");
            System.out.println(PartitModel.resPartitMunicipi(db, "Abella de la Conca"));

            System.out.println("\n=== Municipio===");
            System.out.println(ResultatModel.municipisQueTienenPartido(db, "PP"));

            System.out.println("\n===Res por partido===");
            System.out.println(ResultatModel.resultadosPartidox1Provincia(db, "PP"));

        } catch (Exception e) {
            JOptionPane.showMessageDialog(null, 
                "Error en consulta: " + e.getMessage(), 
                "Error", 
                JOptionPane.ERROR_MESSAGE);
            e.printStackTrace();
        }
    }
}