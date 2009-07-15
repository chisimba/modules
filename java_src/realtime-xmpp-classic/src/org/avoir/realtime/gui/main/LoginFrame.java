
/*
 * LoginFrame.java
 *
 * Created on 2009/03/21, 04:42:15
 */
package org.avoir.realtime.gui.main;

import java.awt.Toolkit;
import javax.swing.ImageIcon;
import javax.swing.JOptionPane;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.EC2Manager;

/**
 *
 * @author developer
 */
public class LoginFrame extends javax.swing.JFrame {

    private ImageIcon loadingIcon = ImageUtil.createImageIcon(this, "/images/loader.gif");
    private static String server = "localhost";
    private static int port = 443;
    private String mediaUrl = "localhost";
    private boolean firstTimeClick = true;
    private String roomName = "default";
    boolean preSuppliedConnectionDetails = false;

    public LoginFrame(String serverHost, int serverPort, String audioVideoUrl) {
        initComponents();

        server = serverHost;
        port = serverPort;
        mediaUrl = audioVideoUrl;
        GeneralUtil.saveProperty("port", serverPort + "");
        GeneralUtil.saveProperty("audio.video.url", audioVideoUrl);
        GeneralUtil.saveProperty("server", serverHost);
        //setModal(true);
        server = serverHost;
        String username = GeneralUtil.getProperty("username");
        usernameField.setText(username);
        if (WebPresentManager.hasBeenLaunchedAsWebPresent) {
            usernameField.setText(WebPresentManager.username);
        }
        String roomStr = GeneralUtil.getProperty("rooms");
        if (roomStr != null) {
            String[] rooms = roomStr.split("#");
            for (int i = 0; i < rooms.length; i++) {
                if (!rooms[i].equals(roomName)) {
                    roomNameField.addItem(rooms[i]);
                }
            }
        }

        passwordField.requestFocus();
    }

    /** Creates new form LoginFrame */
    public LoginFrame() {
        //setModal(true);
        server = GeneralUtil.getProperty("server");
        try {
            port = Integer.parseInt(GeneralUtil.getProperty("port"));
        } catch (NumberFormatException ex) {
            ex.printStackTrace();
        }
        mediaUrl = GeneralUtil.getProperty("audio.video.url");
        ConnectionManager.PORT = port;
        initComponents();
        usernameField.setText(GeneralUtil.getProperty("username"));
        String roomStr = GeneralUtil.getProperty("rooms");
        if (roomStr != null) {
            String[] rooms = roomStr.split("#");
            for (int i = 0; i < rooms.length; i++) {
                roomNameField.addItem(rooms[i]);

            }
        }

        passwordField.requestFocus();
    }

    private void login(final String server, final int port, final String mediaUrl) {

        Thread t = new Thread() {

            @Override
            public void run() {

                statusLabel.setIcon(loadingIcon);
                setControlsEnabled(false);
                String username = usernameField.getText();
                GeneralUtil.saveProperty("username", username);
                String password = new String(passwordField.getPassword());
                if (ConnectionManager.init(server, port, mediaUrl)) {

                    int atIndex = username.indexOf("@");
                    if (atIndex > -1) {
                        username = username.substring(0, atIndex);
                    }

                    roomName = username;
                    if (ConnectionManager.login(username, password, roomName)) {
                        if (ConnectionManager.useEC2) {
                            EC2Manager.requestLaunchEC2Instance();
                        } else {
                            ConnectionManager.fullnames = ConnectionManager.getConnection().getAccountManager().getAccountAttribute("name");
                            GeneralUtil.saveProperty("rooms", "");
                            String rooms = "";
                            for (int i = 0; i < roomNameField.getItemCount(); i++) {
                                rooms = roomNameField.getSelectedItem() + "#";

                            }
                            saveRoomList(rooms);
                            if (roomName == null) {
                                roomName = "default";
                            }
                            MainFrame fr = new MainFrame(roomName);
                            fr.setTitle(username + "@" + roomName + ": Realtime Virtual Classroom");
                            fr.setSize(Toolkit.getDefaultToolkit().getScreenSize());
                            fr.setVisible(true);
                            dispose();
                        }
                    } else {
                        JOptionPane.showMessageDialog(LoginFrame.this, "Error: Invalid login details",
                                "Error", JOptionPane.ERROR_MESSAGE);
                        setControlsEnabled(true);
                    }
                } else {
                    JOptionPane.showMessageDialog(null, "Error: Unable to connect to server.");
                    setControlsEnabled(true);
                }

            }
        };

        t.start();
    }

    private void saveRoomList(String roomList) {
        GeneralUtil.saveProperty("rooms", roomList);
    }

    private void setControlsEnabled(boolean enabled) {
        loginButton.setEnabled(enabled);
        usernameField.setEnabled(enabled);
        passwordField.setEnabled(enabled);
        optionsButton.setEnabled(enabled);
        roomNameField.setEnabled(enabled);
        if (enabled) {
            statusLabel.setIcon(null);
        }
    }

    public static void showOptionsFrame() {

        OptionsPanel panel = new OptionsPanel(server, ConnectionManager.PORT,
                ConnectionManager.AUDIO_VIDEO_URL);
        int n = JOptionPane.showConfirmDialog(null, panel, "Connection Settings", JOptionPane.YES_NO_OPTION);
        if (n == 0) {
            server = panel.getServerHostField().getText();
            port = 5222;
            String audioVideoUrl = ConnectionManager.AUDIO_VIDEO_URL = panel.getAudioVideoHttpUrlField().getText();

            try {
                port = Integer.parseInt(panel.getServerPortField().getText().trim());
            } catch (NumberFormatException ex) {
                ex.printStackTrace();
            }
            int connectionType = Constants.Proxy.NO_PROXY;
            // if (panel.getHttpProxyOpt().isSelected()) {
            boolean useProxy = panel.getHttpProxyOpt().isSelected();
            connectionType = useProxy ? Constants.Proxy.HTTP_PROXY : Constants.Proxy.NO_PROXY;
            String proxyHost = panel.getHttpProxyHostField().getText();
            int proxyPort = 8080;
            try {
                proxyPort = Integer.parseInt(panel.getHttpProxyPortField().getText().trim());
            } catch (NumberFormatException ex) {
            }

            GeneralUtil.saveProperty("proxy.host", proxyHost);
            GeneralUtil.saveProperty("proxy.port", proxyPort + "");
            GeneralUtil.saveProperty("proxy.require.auth", panel.getRequireAuthOpt().isSelected() + "");
            String username = panel.getUsernameField().getText();
            String password = new String(panel.getPasswordField().getPassword());
            GeneralUtil.saveProperty("proxy.username", username);
            GeneralUtil.saveProperty("proxy.password", password);

            GeneralUtil.saveProperty("browser.proxy.required", "" + panel.getBrowserProxyOpt().isSelected());
            GeneralUtil.saveProperty("connection.type", connectionType + "");
            GeneralUtil.saveProperty("server", server);
            GeneralUtil.saveProperty("port", port + "");
            GeneralUtil.saveProperty("audio.video.url", audioVideoUrl);
            ConnectionManager.server = server;
            ConnectionManager.setPORT(port);

        }
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {
        java.awt.GridBagConstraints gridBagConstraints;

        roomNameField = new javax.swing.JComboBox();
        instructorOpt = new javax.swing.JCheckBox();
        roomNameLAbel = new javax.swing.JLabel();
        southPanel = new javax.swing.JPanel();
        loginButton = new javax.swing.JButton();
        closeButton = new javax.swing.JButton();
        imagePanel = new javax.swing.JPanel();
        logoLabel = new javax.swing.JLabel();
        mainPanel = new javax.swing.JPanel();
        usernameLabel = new javax.swing.JLabel();
        passwordLabel = new javax.swing.JLabel();
        usernameField = new javax.swing.JTextField();
        passwordField = new javax.swing.JPasswordField();
        statusLabel = new javax.swing.JLabel();
        optionsButton = new javax.swing.JButton();

        roomNameField.setEditable(true);

        instructorOpt.setText("Am an instructor");
        instructorOpt.addChangeListener(new javax.swing.event.ChangeListener() {
            public void stateChanged(javax.swing.event.ChangeEvent evt) {
                instructorOptStateChanged(evt);
            }
        });

        roomNameLAbel.setText("Room:");

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);
        setTitle("Realtime Avoir Login");

        loginButton.setText("Login");
        loginButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                loginButtonActionPerformed(evt);
            }
        });
        southPanel.add(loginButton);

        closeButton.setText("Close");
        closeButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                closeButtonActionPerformed(evt);
            }
        });
        southPanel.add(closeButton);

        getContentPane().add(southPanel, java.awt.BorderLayout.PAGE_END);

        imagePanel.setPreferredSize(new java.awt.Dimension(100, 100));
        imagePanel.setLayout(new java.awt.BorderLayout());

        logoLabel.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/logo.png"))); // NOI18N
        logoLabel.setText("jLabel1");
        imagePanel.add(logoLabel, java.awt.BorderLayout.CENTER);

        getContentPane().add(imagePanel, java.awt.BorderLayout.PAGE_START);

        mainPanel.setLayout(new java.awt.GridBagLayout());

        usernameLabel.setText("Username:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHEAST;
        mainPanel.add(usernameLabel, gridBagConstraints);

        passwordLabel.setText("Password:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHEAST;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        mainPanel.add(passwordLabel, gridBagConstraints);

        usernameField.setPreferredSize(new java.awt.Dimension(269, 21));
        usernameField.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                usernameFieldMouseClicked(evt);
            }
        });
        usernameField.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                usernameFieldActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        mainPanel.add(usernameField, gridBagConstraints);

        passwordField.setPreferredSize(new java.awt.Dimension(10, 21));
        passwordField.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                passwordFieldMouseClicked(evt);
            }
        });
        passwordField.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                passwordFieldActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        mainPanel.add(passwordField, gridBagConstraints);
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 6;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        mainPanel.add(statusLabel, gridBagConstraints);

        optionsButton.setText("Options");
        optionsButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                optionsButtonActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 5;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(3, 0, 0, 0);
        mainPanel.add(optionsButton, gridBagConstraints);

        getContentPane().add(mainPanel, java.awt.BorderLayout.CENTER);

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void passwordFieldActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_passwordFieldActionPerformed
        login(server, port, mediaUrl);
    }//GEN-LAST:event_passwordFieldActionPerformed

    private void usernameFieldActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_usernameFieldActionPerformed
        passwordField.requestFocus();
        if (firstTimeClick) {
            passwordField.selectAll();
            firstTimeClick = false;
        }
    }//GEN-LAST:event_usernameFieldActionPerformed

    private void loginButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_loginButtonActionPerformed
        login(server, port, mediaUrl);
    }//GEN-LAST:event_loginButtonActionPerformed

    private void closeButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_closeButtonActionPerformed
        System.exit(-1);
    }//GEN-LAST:event_closeButtonActionPerformed

    private void instructorOptStateChanged(javax.swing.event.ChangeEvent evt) {//GEN-FIRST:event_instructorOptStateChanged
    }//GEN-LAST:event_instructorOptStateChanged

    private void optionsButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_optionsButtonActionPerformed
        showOptionsFrame();
    }//GEN-LAST:event_optionsButtonActionPerformed

    private void usernameFieldMouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_usernameFieldMouseClicked
        if (firstTimeClick) {
            usernameField.selectAll();
            firstTimeClick = false;
        }
    }//GEN-LAST:event_usernameFieldMouseClicked

    private void passwordFieldMouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_passwordFieldMouseClicked
        //unselect
        usernameField.select(0, 0);
    }//GEN-LAST:event_passwordFieldMouseClicked

    /**
     * @param args the command line arguments
     */
    public static void main(String args[]) {
        java.awt.EventQueue.invokeLater(new Runnable() {

            public void run() {
                new LoginFrame().setVisible(true);
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton closeButton;
    private javax.swing.JPanel imagePanel;
    private javax.swing.JCheckBox instructorOpt;
    private javax.swing.JButton loginButton;
    private javax.swing.JLabel logoLabel;
    private javax.swing.JPanel mainPanel;
    private javax.swing.JButton optionsButton;
    private javax.swing.JPasswordField passwordField;
    private javax.swing.JLabel passwordLabel;
    private javax.swing.JComboBox roomNameField;
    private javax.swing.JLabel roomNameLAbel;
    private javax.swing.JPanel southPanel;
    private javax.swing.JLabel statusLabel;
    private javax.swing.JTextField usernameField;
    private javax.swing.JLabel usernameLabel;
    // End of variables declaration//GEN-END:variables
}
