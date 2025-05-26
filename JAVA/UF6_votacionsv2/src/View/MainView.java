package View;

import javax.swing.*;
import java.awt.*;
import java.io.File;
import java.util.ArrayList;

import Controller.CargarCsv;
import Model.Database;
import Model.MunicipiModel;
import Model.PartitModel;
import Model.ResultatModel;
import com.db4o.ObjectContainer;

public class MainView extends JFrame {

    private JComboBox<String> comboBox, municipioComboBox, partidoComboBox;
    private JButton filtrarButton, cargarCsvButton;
    private ObjectContainer db;
    private TextArea textArea;

    public MainView(ObjectContainer db) throws Exception {
        this.db = db;

        setTitle("Thos i Codina - M03 UF6 DB4o");
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setSize(900, 800);

        getContentPane().setBackground(new Color(235, 245, 255));
        getContentPane().setLayout(null);

        JPanel panel = new JPanel();
        panel.setBounds(10, 306, 878, 470);
        panel.setBackground(new Color(225, 240, 255));
        getContentPane().add(panel);
        panel.setLayout(null);

        textArea = new TextArea();
        textArea.setEditable(false);
        textArea.setForeground(new Color(0, 0, 0));
        textArea.setFont(new Font("FreeSans", Font.PLAIN, 18));
        textArea.setBounds(10, 10, 858, 440);
        textArea.setBackground(new Color(245, 250, 255));
        panel.add(textArea);

        JPanel panel_1 = new JPanel();
        panel_1.setBounds(10, 45, 878, 234);
        panel_1.setBackground(new Color(230, 242, 255));
        getContentPane().add(panel_1);
        panel_1.setLayout(null);

        municipioComboBox = new JComboBox<>(getMunicipiosDesdeBD().toArray(new String[0]));
        municipioComboBox.setFont(new Font("FreeSans", Font.PLAIN, 16));
        municipioComboBox.setBounds(87, 5, 177, 41);
        municipioComboBox.setBackground(new Color(240, 248, 255));

        partidoComboBox = new JComboBox<>(getPartidosDesdeBD().toArray(new String[0]));
        partidoComboBox.setFont(new Font("FreeSans", Font.PLAIN, 16));
        partidoComboBox.setBounds(334, 7, 169, 39);
        partidoComboBox.setBackground(new Color(240, 248, 255));

        JPanel inputPanel = new JPanel();
        inputPanel.setBounds(181, 116, 515, 58);
        inputPanel.setBackground(new Color(225, 240, 255));
        panel_1.add(inputPanel);
        inputPanel.setLayout(null);
        JLabel label = new JLabel("Municipi:");
        label.setFont(new Font("FreeSans", Font.BOLD, 16));
        label.setBounds(12, 6, 78, 39);
        inputPanel.add(label);
        inputPanel.add(municipioComboBox);
        JLabel label_1 = new JLabel("Partit:");
        label_1.setFont(new Font("FreeSans", Font.BOLD, 16));
        label_1.setBounds(282, 6, 47, 39);
        inputPanel.add(label_1);
        inputPanel.add(partidoComboBox);

        comboBox = new JComboBox<>(new String[] {
            "1. Llistat de tots els partits",
            "2. Llistat de tots els municipis",
            "3. Resultats per partit en un municipi",
            "4. Resultats per municipi d'un partit",
            "5. Resultats per partit en una província"
        });
        comboBox.setFont(new Font("FreeSans", Font.BOLD, 16));
        comboBox.setBounds(12, 12, 297, 55);
        comboBox.setBackground(new Color(240, 248, 255));

        filtrarButton = new JButton("Filtrar");
        filtrarButton.setFont(new Font("FreeSans", Font.BOLD, 16));
        filtrarButton.setBounds(331, 10, 91, 60);
        filtrarButton.setBackground(new Color(200, 220, 255));
        cargarCsvButton = new JButton("Cargar CSV");
        cargarCsvButton.setFont(new Font("FreeSans", Font.BOLD, 16));
        cargarCsvButton.setBounds(433, 12, 180, 55);
        cargarCsvButton.setBackground(new Color(200, 220, 255));

        JPanel topPanel = new JPanel();
        topPanel.setBounds(140, 12, 625, 82);
        topPanel.setBackground(new Color(225, 240, 255));
        panel_1.add(topPanel);
        topPanel.setLayout(null);
        topPanel.add(comboBox);
        topPanel.add(filtrarButton);
        topPanel.add(cargarCsvButton);

        filtrarButton.addActionListener(e -> {
            try {
                filtrar();
            } catch (Exception e1) {
                e1.printStackTrace();
            }
        });
        cargarCsvButton.addActionListener(e -> seleccionarArchivoCSV());
    }

    private void filtrar() throws Exception {
        int opcion = comboBox.getSelectedIndex();
        textArea.setText("");
        String resultado = "";

        String municipio = (String) municipioComboBox.getSelectedItem();
        String partido = (String) partidoComboBox.getSelectedItem();

        switch (opcion) {
            case 0 -> resultado = PartitModel.getPartits(db);
            case 1 -> resultado = MunicipiModel.getMunicipis(db);
            case 2 -> resultado = PartitModel.resPartitMunicipi(db, municipio);
            case 3 -> resultado = ResultatModel.municipisQueTienenPartido(db, partido);
            case 4 -> resultado = ResultatModel.resultadosPartidox1Provincia(db, partido);
        }
        textArea.setText(resultado);
    }

    private void seleccionarArchivoCSV() {
        JFileChooser fileChooser = new JFileChooser();
        fileChooser.setDialogTitle("Seleccionar archivo CSV");
        fileChooser.setFileSelectionMode(JFileChooser.FILES_ONLY);

        if (fileChooser.showOpenDialog(this) == JFileChooser.APPROVE_OPTION) {
            cargarCSV(fileChooser.getSelectedFile().getAbsolutePath());
        }
    }

    private void cargarCSV(String rutaArchivo) {
        new CargarCsv(db).cargarCsv(rutaArchivo);
        actualizarCombos(); 
        JOptionPane.showMessageDialog(this, "CSV cargado exitosamente.", "Éxito", JOptionPane.INFORMATION_MESSAGE);
    }

    private ArrayList<String> getMunicipiosDesdeBD() {
        try {
            return MunicipiModel.getMunicipiNombre(db);
        } catch (Exception e) {
            e.printStackTrace();
            return new ArrayList<>(); // en caso de que pete da un arraylist vacio
        }
    }

    private ArrayList<String> getPartidosDesdeBD() {
        try {
            return PartitModel.getPartitNombre(db);
        } catch (Exception e) {
            e.printStackTrace();
            return new ArrayList<>(); // en caso de que pete da un arraylist vacio
        }
    }
    
    /**
     * Este método lo que hace es refrescar los JComboBox por si se ha añadido un nuevo csv
     */
    private void actualizarCombos() {
        String municipioSeleccionado = (String) municipioComboBox.getSelectedItem(); // coge el selecionado y lo guarda
        String partidoSeleccionado = (String) partidoComboBox.getSelectedItem();
        
        municipioComboBox.removeAllItems(); // elimina todos los items que tenia el comboBox y coge los del array de nombres
        for (String municipio : getMunicipiosDesdeBD()) {
            municipioComboBox.addItem(municipio);
        }
        
        partidoComboBox.removeAllItems();
        for (String partido : getPartidosDesdeBD()) {
            partidoComboBox.addItem(partido);
        }
        
        if (municipioSeleccionado != null) { // si había laguno selecionado lo devuelve y sale el que tenias
            municipioComboBox.setSelectedItem(municipioSeleccionado);
        }
        if (partidoSeleccionado != null) {
            partidoComboBox.setSelectedItem(partidoSeleccionado);
        }
    }
    
    public static void main(String[] args) {
        ObjectContainer db = new Database().db;
        SwingUtilities.invokeLater(() -> {
			try {
				new MainView(db).setVisible(true);
			} catch (Exception e) {
				e.printStackTrace();
			}
		});
    }
}
