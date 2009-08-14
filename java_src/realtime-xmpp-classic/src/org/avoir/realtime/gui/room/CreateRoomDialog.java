/*

 *
 * Copyright (C) GNU/GPL AVOIR 2008
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

 */

/*
 * CreateRoom.java
 *
 * Created on 2009/04/04, 08:07:13
 */
package org.avoir.realtime.gui.room;

import java.awt.Toolkit;
import javax.swing.JOptionPane;
import javax.swing.event.DocumentEvent;
import javax.swing.event.DocumentListener;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.MainFrame;
import org.avoir.realtime.gui.main.StandAloneManager;
import org.avoir.realtime.net.ConnectionManager;

/**
 *
 * @author developer
 */
public class CreateRoomDialog extends javax.swing.JDialog {

    private boolean showRoomList = true;

    public CreateRoomDialog() {
        this(null, null, true);
    }

    /** Creates new form CreateRoom */
    public CreateRoomDialog(String roomName, String roomDesc, boolean showRoomList) {
        super(GUIAccessManager.mf);
        setModal(true);
        this.showRoomList = showRoomList;
        initComponents();

        roomNameField.getDocument().addDocumentListener(new DocumentListener() {

            public void insertUpdate(DocumentEvent e) {
                roomDescField.setText(roomNameField.getText());
            }

            public void removeUpdate(DocumentEvent e) {
                roomDescField.setText(roomNameField.getText());
            }

            public void changedUpdate(DocumentEvent e) {
            }
        });
        if (roomName != null) {
            roomNameField.setText(roomName);

        }
        if (roomDesc != null) {
            roomDescField.setText(roomDesc);
        }
    }

    private void createRoom() {
        String roomName = roomNameField.getText();

        if (roomName.trim().equals("")) {
            JOptionPane.showMessageDialog(this, "Empty room name: Please " +
                    "provide a name", "Empty Room", JOptionPane.ERROR_MESSAGE);
            return;
        }
        String badChars[] = GeneralUtil.getIllegalCharacters();
        String badCharsStr = "";
        for (String badChar : badChars) {
            badCharsStr += badChar + " ";
        }
        if (GeneralUtil.isInvalidRoomName(roomName)) {
            JOptionPane.showMessageDialog(null,
                    "<html>Please choose different room name. The chosen name contains illegal characters\n" +
                    "The following characters are forbidden:\n" +
                    "<html><strong><font color=\"red\">" + badCharsStr + "</font></strong>.");
            return;
        }
        roomName = GeneralUtil.formatStr(roomName, " ");
        String desc = roomDescField.getText();
        if (desc.trim().equals("")) {
            // JOptionPane.showMessageDialog(null, "Provide room description");
            // return;
            //if no desc, then pick room name as default
            desc = roomName;
        }
        int maxNumber = (Integer) maxMembersField.getValue();
        if (maxNumber < 1) {
            JOptionPane.showMessageDialog(null, "Maximum number cannot be less than one.");
            return;
        }

        boolean requirePassword = requirePasswordOpt.isSelected();
        String password = null;
        if (requirePassword) {
            String pwd1 = new String(passwordField1.getPassword());
            String pwd2 = new String(passwordField2.getPassword());
            if (pwd1.trim().equals("") || pwd2.trim().equals("")) {
                JOptionPane.showMessageDialog(null, "Empty passwords not allowed");
                return;
            }
            if (!pwd1.equals(pwd2)) {
                JOptionPane.showMessageDialog(null, "Passwords do not match");
                return;
            } else {
                password = pwd2;
            }
        }
        GUIAccessManager.mf.getChatRoomManager().createRoom(roomName, desc, maxNumber,
                requirePassword, password);
        if (showRoomList) {
            GUIAccessManager.mf.showRoomList(null);
        }
        dispose();

    }

    private void pause(long time) {
        try {
            Thread.sleep(time);
        } catch (Exception ex) {
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

        mPanel = new javax.swing.JPanel();
        instsPanel = new javax.swing.JPanel();
        jLabel2 = new javax.swing.JLabel();
        topPanel = new javax.swing.JPanel();
        jLabel1 = new javax.swing.JLabel();
        roomNameField = new javax.swing.JTextField();
        jLabel3 = new javax.swing.JLabel();
        roomDescField = new javax.swing.JTextField();
        jLabel4 = new javax.swing.JLabel();
        maxMembersField = new javax.swing.JSpinner();
        requirePasswordOpt = new javax.swing.JCheckBox();
        jLabel5 = new javax.swing.JLabel();
        passwordField1 = new javax.swing.JPasswordField();
        passwordField2 = new javax.swing.JPasswordField();
        jLabel6 = new javax.swing.JLabel();
        cPanel = new javax.swing.JPanel();
        createRoomButton = new javax.swing.JButton();
        closeButton = new javax.swing.JButton();

        mPanel.setLayout(new java.awt.BorderLayout());

        instsPanel.setLayout(new java.awt.BorderLayout());

        jLabel2.setText("Select the invitees you want to be members of this room:");
        instsPanel.add(jLabel2, java.awt.BorderLayout.CENTER);

        mPanel.add(instsPanel, java.awt.BorderLayout.PAGE_START);

        setTitle("Create Room");

        topPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Room"));
        topPanel.setLayout(new java.awt.GridBagLayout());

        jLabel1.setText("Room Name:");
        jLabel1.setPreferredSize(new java.awt.Dimension(284, 17));
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        topPanel.add(jLabel1, gridBagConstraints);

        roomNameField.setName("roomName"); // NOI18N
        roomNameField.setPreferredSize(new java.awt.Dimension(269, 21));
        roomNameField.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                roomNameFieldActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        topPanel.add(roomNameField, gridBagConstraints);

        jLabel3.setText("Room Description:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        topPanel.add(jLabel3, gridBagConstraints);

        roomDescField.setName("roomDesc"); // NOI18N
        roomDescField.setPreferredSize(new java.awt.Dimension(100, 21));
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        topPanel.add(roomDescField, gridBagConstraints);

        jLabel4.setText("Maximum Members");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 8;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        topPanel.add(jLabel4, gridBagConstraints);

        maxMembersField.setName("maxMembersField"); // NOI18N
        maxMembersField.setPreferredSize(new java.awt.Dimension(50, 27));
        maxMembersField.setValue(30);
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 9;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        topPanel.add(maxMembersField, gridBagConstraints);

        requirePasswordOpt.setText("Require Password");
        requirePasswordOpt.setName("requirePasswordField"); // NOI18N
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 12;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        topPanel.add(requirePasswordOpt, gridBagConstraints);

        jLabel5.setText("Room Password:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 13;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        topPanel.add(jLabel5, gridBagConstraints);

        passwordField1.setName("roomPasswordField"); // NOI18N
        passwordField1.setPreferredSize(new java.awt.Dimension(200, 21));
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 14;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        topPanel.add(passwordField1, gridBagConstraints);

        passwordField2.setName("confirmPasswordField"); // NOI18N
        passwordField2.setPreferredSize(new java.awt.Dimension(10, 21));
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 16;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        topPanel.add(passwordField2, gridBagConstraints);

        jLabel6.setText("Confirm Password:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 15;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        topPanel.add(jLabel6, gridBagConstraints);

        getContentPane().add(topPanel, java.awt.BorderLayout.CENTER);

        createRoomButton.setText("Create Room");
        createRoomButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                createRoomButtonActionPerformed(evt);
            }
        });
        cPanel.add(createRoomButton);

        closeButton.setText("Close");
        closeButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                closeButtonActionPerformed(evt);
            }
        });
        cPanel.add(closeButton);

        getContentPane().add(cPanel, java.awt.BorderLayout.PAGE_END);

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void closeButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_closeButtonActionPerformed
        if (GUIAccessManager.mf.getChatRoomManager().getMuc().isJoined()) {
            dispose();
        } else {
            JOptionPane.showMessageDialog(null, "You are not in any room, the system will exit");
            System.exit(0);
        }

}//GEN-LAST:event_closeButtonActionPerformed

    private void createRoomButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_createRoomButtonActionPerformed

        createRoom();


    }//GEN-LAST:event_createRoomButtonActionPerformed

    private void roomNameFieldActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_roomNameFieldActionPerformed
        //createRoom();
    }//GEN-LAST:event_roomNameFieldActionPerformed

    /**
     * @param args the command line arguments
     */
    public static void main(String xargs[]) {
        final String[] args = {"localhost", "5222", "localhost"};
        java.awt.EventQueue.invokeLater(new Runnable() {

            public void run() {
                StandAloneManager.isAdmin = true;
                ConnectionManager.audioVideoUrlReady = true;
                ConnectionManager.flashUrlReady = true;
                //NativeInterface.open();

                if (ConnectionManager.init(args[0], Integer.parseInt(args[1]), args[2])) {
                    String roomName = "dwaf_default";
                    String username = "dwaf";
                    String password = "a";
                    if (ConnectionManager.login(username, password, roomName)) {
                        MainFrame fr = new MainFrame(roomName);
                        //  fr.setTitle(username + "@" + roomName + ": Realtime Virtual Classroom");
                        fr.setSize(Toolkit.getDefaultToolkit().getScreenSize());
                        //  fr.setVisible(true);
                        GUIAccessManager.mf = fr;
                        CreateRoomDialog dialog = new CreateRoomDialog();
                        dialog.addWindowListener(new java.awt.event.WindowAdapter() {

                            @Override
                            public void windowClosing(java.awt.event.WindowEvent e) {
                                System.exit(0);
                            }
                        });
                        dialog.setSize(400, 300);
                        dialog.setVisible(true);
                    } else {
                        System.out.println("cant login");
                    }
                } else {
                    System.out.println("cant connect to server");
                }


            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JPanel cPanel;
    private javax.swing.JButton closeButton;
    private javax.swing.JButton createRoomButton;
    private javax.swing.JPanel instsPanel;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JLabel jLabel3;
    private javax.swing.JLabel jLabel4;
    private javax.swing.JLabel jLabel5;
    private javax.swing.JLabel jLabel6;
    private javax.swing.JPanel mPanel;
    private javax.swing.JSpinner maxMembersField;
    private javax.swing.JPasswordField passwordField1;
    private javax.swing.JPasswordField passwordField2;
    private javax.swing.JCheckBox requirePasswordOpt;
    private javax.swing.JTextField roomDescField;
    private javax.swing.JTextField roomNameField;
    private javax.swing.JPanel topPanel;
    // End of variables declaration//GEN-END:variables
}
