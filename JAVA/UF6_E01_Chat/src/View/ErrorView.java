package View;

import javax.swing.*;
import java.awt.*;

public class ErrorView extends JFrame {

	private static final long serialVersionUID = 1L;
	private JPanel contentPane;
	private JLabel lblErrorMessage;

	/**
	 * Crea el marco de la ventana de error.
	 * 
	 * @param errorMessage Mensaje de error a mostrar.
	 */
	public ErrorView(String errorMessage) {
		setTitle("Error");
		setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE); 
		setBounds(100, 100, 300, 150);
		contentPane = new JPanel();
		contentPane.setBackground(new Color(230, 70, 70)); 
		contentPane.setBorder(BorderFactory.createEmptyBorder(20, 20, 20, 20)); 
		setContentPane(contentPane);
		contentPane.setLayout(new BorderLayout());

		lblErrorMessage = new JLabel(errorMessage, SwingConstants.CENTER);
		lblErrorMessage.setFont(new Font("Arial", Font.BOLD, 14));
		lblErrorMessage.setForeground(Color.WHITE);
		contentPane.add(lblErrorMessage, BorderLayout.CENTER); 

		setLocationRelativeTo(null);
	}

	public static void main(String[] args) {
		EventQueue.invokeLater(() -> {
			try {
				ErrorView frame = new ErrorView("Ha ocurrido un error inesperado.");
				frame.setVisible(true);
			} catch (Exception e) {
				e.printStackTrace();
			}
		});
	}
}
