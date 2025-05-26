package View;

import javax.swing.*;
import java.awt.*;
import java.util.List;

public class Chat extends JFrame {
    private static final long serialVersionUID = 1L;
    
    private JPanel contentPane;
    private JTextField txtUserName;
    private JTextField txtMessage;
    private JTextArea txtUsers;
    private JPanel chatPanel;
    private JScrollPane scrollChat;
    private JButton btnConnect;
    private JButton btnSend;
    private JButton btnDisconnect;

    public Chat() {
        setTitle("WhatsApp Plus");
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setBounds(100, 100, 600, 500);
        
        contentPane = new JPanel();
        contentPane.setLayout(new BorderLayout());
        setContentPane(contentPane);

        JPanel panelTop = new JPanel(new FlowLayout());
        panelTop.setBackground(new Color(53, 132, 228)); 
        txtUserName = new JTextField(15);
        btnConnect = new JButton("Conectarse");
        btnConnect.setBackground(new Color(28, 113, 216)); 
        btnConnect.setForeground(Color.WHITE); 
        panelTop.add(new JLabel("Usuario:"));
        panelTop.add(txtUserName);
        panelTop.add(btnConnect);
        contentPane.add(panelTop, BorderLayout.NORTH);

        txtUsers = new JTextArea(5, 15);
        txtUsers.setEditable(false);
        txtUsers.setBackground(new Color(204, 229, 255)); 
        JScrollPane scrollUsers = new JScrollPane(txtUsers);
        contentPane.add(scrollUsers, BorderLayout.WEST);

        chatPanel = new JPanel();
        chatPanel.setLayout(new BoxLayout(chatPanel, BoxLayout.Y_AXIS));
        chatPanel.setBackground(new Color(230, 245, 255));

        scrollChat = new JScrollPane(chatPanel);
        scrollChat.setVerticalScrollBarPolicy(JScrollPane.VERTICAL_SCROLLBAR_ALWAYS);
        contentPane.add(scrollChat, BorderLayout.CENTER);

        JPanel panelBottom = new JPanel(new FlowLayout());
        panelBottom.setBackground(new Color(53, 132, 228)); 
        txtMessage = new JTextField(30);
        btnSend = new JButton("Enviar");
        btnSend.setBackground(new Color(70, 130, 180));
        btnSend.setForeground(Color.WHITE); 
        panelBottom.add(txtMessage);
        panelBottom.add(btnSend);
        contentPane.add(panelBottom, BorderLayout.SOUTH);
        
        btnDisconnect = new JButton("Desconectarse");
        btnDisconnect.setBackground(new Color(28, 113, 216)); 
        btnDisconnect.setForeground(Color.WHITE);
        btnDisconnect.setEnabled(false); 
        panelTop.add(btnDisconnect);

        contentPane.setBackground(new Color(200, 230, 255)); 
        txtUserName.setBackground(Color.WHITE); 
        txtMessage.setBackground(Color.WHITE); 
    }

    public String getUserName() {
        return txtUserName.getText().trim();
    }

    public String getMessage() {
        return txtMessage.getText().trim();
    }

    public void appendStyledMessage(String user, String message) {
        JPanel messagePanel = new JPanel();
        messagePanel.setLayout(new BorderLayout());
        messagePanel.setBorder(BorderFactory.createCompoundBorder(
            BorderFactory.createLineBorder(Color.GRAY, 1, true), 
            BorderFactory.createEmptyBorder(5, 10, 5, 10)
        ));

        JLabel lblUser = new JLabel(user + ": ");
        lblUser.setFont(new Font("Arial", Font.BOLD, 12));

        JLabel lblMessage = new JLabel("<html><p style='width: 200px;'>" + message + "</p></html>");
        lblMessage.setFont(new Font("Arial", Font.PLAIN, 12));

        if (user.equalsIgnoreCase(getUserName())) {
            messagePanel.setBackground(new Color(173, 216, 230));
            lblUser.setForeground(new Color(0, 102, 204));
        } else {
            messagePanel.setBackground(new Color(144, 238, 144)); 
            lblUser.setForeground(new Color(0, 153, 51));
        }

        messagePanel.add(lblUser, BorderLayout.NORTH);
        messagePanel.add(lblMessage, BorderLayout.CENTER);
        messagePanel.setMaximumSize(new Dimension(500, 60));

        chatPanel.add(messagePanel);
        chatPanel.revalidate();
        chatPanel.repaint();

        scrollChat.getVerticalScrollBar().setValue(scrollChat.getVerticalScrollBar().getMaximum());
    }
    
    public void updateUsers(String user) {
        txtUsers.append(user + "\n");
    }

    public void clearUsers() {
        txtUsers.setText("");
    }

    public void clearChat() {
        chatPanel.removeAll();
        chatPanel.revalidate();
        chatPanel.repaint();
    }

    public void disableUserNameInput() {
        txtUserName.setEnabled(false);
    }

    public void enableUserNameInput() {
        txtUserName.setEnabled(true);
    }
    
    public void setUsersList(List<String> users) {
        txtUsers.setText("");
        for (String user : users) {
            txtUsers.append(user + "\n");
        }
    }
    
    public void setMsgList(List<String> msgs) {
        for (String msg : msgs) {
            String[] parts = msg.split("\\*", 2); 
            if (parts.length == 2) {
                appendStyledMessage(parts[0].replace("Mensaje de ", "").trim(), parts[1].trim());
            } else {
                appendStyledMessage("Desconocido", msg);
            }
        }
    }

    public JButton getBtnConnect() {
        return btnConnect;
    }

    public JButton getBtnSend() {
        return btnSend;
    }

    public JButton getBtnDisconnect() {
        return btnDisconnect;
    }
}
