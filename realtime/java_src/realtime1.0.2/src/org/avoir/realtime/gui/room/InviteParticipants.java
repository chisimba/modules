/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * InviteParticipants.java
 *
 * Created on 18 May 2009, 2:07:39 AM
 */
package org.avoir.realtime.gui.room;

import java.awt.Dimension;
import java.awt.Toolkit;
import java.util.ArrayList;
import java.util.Collection;
import java.util.Map;
import javax.swing.JOptionPane;
import javax.swing.JTextArea;
import javax.swing.JTextField;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.WebPresentManager;
import org.avoir.realtime.gui.search.UserSearch;
import org.jivesoftware.smack.XMPPException;
import org.jivesoftware.smack.util.Base64;


import org.jivesoftware.smackx.muc.HostedRoom;
import org.jivesoftware.smackx.muc.MultiUserChat;
import org.jivesoftware.smackx.muc.RoomInfo;

/**
 *
 * @author developer
 */
public class InviteParticipants extends javax.swing.JFrame {

    String defaultMessage = "Please join me in a meeting";
    ArrayList<Map> rooms = new ArrayList<Map>();
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();

    /** Creates new form InviteParticipants */
    public InviteParticipants() {
        initComponents();

        setTitle("Invite Participnats");
        messageField.setText(defaultMessage);
        roomNameField.setText(ConnectionManager.getRoomName());
        roomDescField.setText(ConnectionManager.fullnames);
        emailField.setWrapStyleWord(true);
        emailField.setLineWrap(true);
    }

    public JTextField getRoomNameField() {
        return roomNameField;
    }

    public JTextField getRoomDescField() {
        return roomDescField;
    }

    private void inviteParticipants() {
        String xroomName = roomNameField.getText();
        if (xroomName.trim().equals("")) {
            JOptionPane.showMessageDialog(null, "Provide room name");
            return;
        }
        String badChars[] = GeneralUtil.getIllegalCharacters();
        String badCharsStr = "";
        for (String badChar : badChars) {
            badCharsStr += badChar + " ";
        }
        if (GeneralUtil.isInvalidRoomName(xroomName)) {
            JOptionPane.showMessageDialog(null,
                    "<html>Please choose different room name. The chosen name contains illegal characters\n" +
                    "The following characters are forbidden:\n" +
                    "<html><strong><font color=\"red\">" + badCharsStr + "</font></strong>.");
            return;
        }
        /*if (xroomName.trim().equalsIgnoreCase(ConnectionManager.getUsername())) {
        JOptionPane.showMessageDialog(null, "Please choose different room name other than your username");
        return;
        }*/
        if (emailField.getText().trim().equals("")) {
            JOptionPane.showMessageDialog(null, "Invite atleast one person");
            return;

        }

        String roomName = GeneralUtil.formatStr(xroomName, " ");
        String desc = roomDescField.getText();
        if (desc.trim().equals("")) {
            JOptionPane.showMessageDialog(null, "Provide room description");
            return;
        }
        boolean requirePassword = roomRequiresPassword(roomName);
        if (!requirePassword) {
            int n = JOptionPane.showConfirmDialog(this, "This room is unprotected. Anyone can join.\n" +
                    "You can select/create a password protected room from 'Room List' button above, and invite users into it.\n" +
                    "Do you still want to invite users to this room?", "Unprotected room",
                    JOptionPane.YES_NO_OPTION);
            if (n == JOptionPane.NO_OPTION) {
                return;
            }
        }
        if (GUIAccessManager.mf.getChatRoomManager().doActualJoin(ConnectionManager.getUsername(), roomName, false)) {

            String name = ConnectionManager.getConnection().getAccountManager().getAccountAttribute("name");
            String xmessage = messageField.getText();
            if (xmessage.trim().equals("")) {
                JOptionPane.showMessageDialog(null, "Please provide message");
                return;
            }
            String message = xmessage;
            String inviteUrl = ConnectionManager.inviteUrl;
            int roomParamIndex = inviteUrl.indexOf("&room=");
            if (roomParamIndex > -1) {
                inviteUrl = inviteUrl.substring(0, roomParamIndex);
            } else {
                inviteUrl = GeneralUtil.formatStr(inviteUrl, " ");
            }
            String needpassword = requirePassword ? "yes" : "no";
            String url = inviteUrl + "/index.php?module=realtime&action=showStartLinks&room=" + roomName + "&presenter=no&needpassword=" + needpassword + "&id=" + WebPresentManager.presentationId + "&agenda=" + WebPresentManager.presentationName;
            //JOptionPane.showMessageDialog(null, url);
            WebPresentManager.finalInviteURL = url;
            String presenterUrl = inviteUrl + "/index.php?module=realtime&action=showStartLinks&room=" + roomName + "&presenter=yes&needpassword=" + needpassword + "&id=" + WebPresentManager.presentationId + "&agenda=" + WebPresentManager.presentationName;
            //url = getTinyUrl(url);
            url = Base64.encodeBytes(url.getBytes());
            if (requirePassword) {
                message += " . The room password is " + new String(GUIAccessManager.mf.getChatRoomManager().getRoomPassword());
            }
            //presenterUrl = getTinyUrl(presenterUrl);
            presenterUrl = Base64.encodeBytes(presenterUrl.getBytes());
            String subject = "Meeting Invitation from " + name;
            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.INVITE_PARTICIPANTS);
            String[] emails = emailField.getText().split(";");
            StringBuilder sb = new StringBuilder();
            sb.append("<emails>");
            for (int i = 0; i < emails.length; i++) {
                sb.append("<user>");
                sb.append("<email>").append(emails[i]).append("</email>");
                sb.append("<is-presenter>").append("false").append("</is-presenter>");
                sb.append("</user>");
                //also make them members, since the room is private
                String jid = emails[i];
                int atIndex = jid.indexOf("@");
                if (atIndex > -1) {
                    jid = jid.substring(0, atIndex);
                }
                jid = jid + "@" + ConnectionManager.getConnection().getServiceName();
                GUIAccessManager.mf.getChatRoomManager().invite(jid, message);
            }
            //invite me too.. but with special message
            sb.append("<user>");
            sb.append("<email>").append(GeneralUtil.formatStrUsingIllegalChars(ConnectionManager.myEmail, " ")).append("</email>");
            sb.append("<is-presenter>").append("true").append("</is-presenter>");
            sb.append("</user>");

            sb.append("</emails>");
            sb.append("<email-from>").append(GeneralUtil.formatStrUsingIllegalChars(ConnectionManager.myEmail, " ")).append("</email-from>");

            sb.append("<subject>").append(subject).append("</subject>");
            sb.append("<message>").append(message).append("</message>");
            sb.append("<url>").append(url).append("</url>");
            sb.append("<presenter-url>").append(presenterUrl).append("</presenter-url>");
            sb.append("<presenter-name>").append(name).append("</presenter-name>");
            sb.append("<meeting-type>").append("now").append("</meeting-type>");
            sb.append("<start-date>").append(startDateField.getValue() + "").append("</start-date>");
            sb.append("<end-date>").append(startDateField.getValue() + "").append("</end-date>");
            sb.append("<room-name>").append(roomName).append("</room-name>");
            sb.append("<room-url>").append(Base64.encodeBytes(ConnectionManager.inviteUrl.getBytes())).append("</room-url>");
            p.setContent(sb.toString());
            if (ConnectionManager.oldConnection != null) {
                ConnectionManager.oldConnection.sendPacket(p);
            } else {
                ConnectionManager.sendPacket(p);
            }

            dispose();
        } else {
            JOptionPane.showMessageDialog(null, "An error occured. You could be able to join the room.\n" +
                    "You can only invite others if you  join the room.\n" +
                    "Is there another room already by same name and is private?\n" +
                    "If problem persist, contact system administrator");
        }
    }

    public boolean roomRequiresPassword(String roomName) {
        try {
            Collection<HostedRoom> hostedRooms = MultiUserChat.getHostedRooms(ConnectionManager.getConnection(), "conference." + ConnectionManager.getConnection().getServiceName());

            for (HostedRoom room : hostedRooms) {
                RoomInfo info = MultiUserChat.getRoomInfo(ConnectionManager.getConnection(), room.getJid());
                String xroomName = room.getName();
                if (xroomName.equals(roomName)) {
                    return info.isPasswordProtected();

                }
            }
        } catch (XMPPException ex) {
            ex.printStackTrace();
        }
        return false;
    }

    private void addEmail() {
        String email = userEmailField.getText();
        if (email.indexOf("@") > -1) {
            if (email.indexOf(";") > -1) {
                emailField.append(email);
            } else {
                emailField.append(email + ";");
            }
            userEmailField.setText("");
        } else {
            JOptionPane.showMessageDialog(this, "Invalid email address");
        }
    }

    public JTextArea getEmailField() {
        return emailField;
    }

    private void doSearch() {
        UserSearch userSearch = new UserSearch(this);
        userSearch.setLocationRelativeTo(GUIAccessManager.mf);
        userSearch.setVisible(true);
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

        buttonGroup1 = new javax.swing.ButtonGroup();
        invitePanel = new javax.swing.JPanel();
        searchTopPane = new javax.swing.JPanel();
        jLabel3 = new javax.swing.JLabel();
        jPanel1 = new javax.swing.JPanel();
        inviteButton = new javax.swing.JButton();
        closeButton = new javax.swing.JButton();
        detailsPanel = new javax.swing.JPanel();
        roomNameLabel = new javax.swing.JLabel();
        jScrollPane1 = new javax.swing.JScrollPane();
        emailField = new javax.swing.JTextArea();
        messageLabel = new javax.swing.JLabel();
        messageField = new javax.swing.JTextField();
        userEmailLabel = new javax.swing.JLabel();
        userEmailField = new javax.swing.JTextField();
        addButton = new javax.swing.JButton();
        searchButton = new javax.swing.JButton();
        schedulePanel = new javax.swing.JPanel();
        jLabel5 = new javax.swing.JLabel();
        jLabel6 = new javax.swing.JLabel();
        startDateField = new javax.swing.JSpinner();
        endDateField = new javax.swing.JSpinner();
        roomDescLabel = new javax.swing.JLabel();
        roomDescField = new javax.swing.JTextField();
        jSeparator1 = new javax.swing.JSeparator();
        jSeparator2 = new javax.swing.JSeparator();
        roomNameField = new javax.swing.JTextField();
        roomListButton = new javax.swing.JButton();

        setDefaultCloseOperation(javax.swing.WindowConstants.DISPOSE_ON_CLOSE);

        invitePanel.setLayout(new java.awt.BorderLayout());

        jLabel3.setText("Invite Users to a conference room");
        searchTopPane.add(jLabel3);

        invitePanel.add(searchTopPane, java.awt.BorderLayout.PAGE_START);

        inviteButton.setText("Invite");
        inviteButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                inviteButtonActionPerformed(evt);
            }
        });
        jPanel1.add(inviteButton);

        closeButton.setText("Close");
        closeButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                closeButtonActionPerformed(evt);
            }
        });
        jPanel1.add(closeButton);

        invitePanel.add(jPanel1, java.awt.BorderLayout.PAGE_END);

        detailsPanel.setLayout(new java.awt.GridBagLayout());

        roomNameLabel.setText("Room Name:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        detailsPanel.add(roomNameLabel, gridBagConstraints);

        emailField.setColumns(20);
        emailField.setRows(10);
        jScrollPane1.setViewportView(emailField);

        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 18;
        gridBagConstraints.gridwidth = 5;
        gridBagConstraints.gridheight = 4;
        gridBagConstraints.fill = java.awt.GridBagConstraints.BOTH;
        gridBagConstraints.insets = new java.awt.Insets(0, 0, 0, 5);
        detailsPanel.add(jScrollPane1, gridBagConstraints);

        messageLabel.setText("Message:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 9;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(0, 0, 6, 0);
        detailsPanel.add(messageLabel, gridBagConstraints);

        messageField.setPreferredSize(new java.awt.Dimension(100, 27));
        messageField.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                messageFieldActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 10;
        gridBagConstraints.gridwidth = 2;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        detailsPanel.add(messageField, gridBagConstraints);

        userEmailLabel.setText("User Email(Separated by semi colon):");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 14;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(0, 0, 5, 0);
        detailsPanel.add(userEmailLabel, gridBagConstraints);

        userEmailField.setPreferredSize(new java.awt.Dimension(280, 27));
        userEmailField.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                userEmailFieldActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 15;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.insets = new java.awt.Insets(0, 0, 5, 0);
        detailsPanel.add(userEmailField, gridBagConstraints);

        addButton.setText("Add");
        addButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                addButtonActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 15;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        gridBagConstraints.insets = new java.awt.Insets(0, 0, 5, 0);
        detailsPanel.add(addButton, gridBagConstraints);

        searchButton.setText("Search");
        searchButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                searchButtonActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 3;
        gridBagConstraints.gridy = 15;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        gridBagConstraints.insets = new java.awt.Insets(4, 0, 0, 1);
        detailsPanel.add(searchButton, gridBagConstraints);

        schedulePanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Meeting Time"));
        schedulePanel.setLayout(new java.awt.GridBagLayout());

        jLabel5.setText("Start Date:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        schedulePanel.add(jLabel5, gridBagConstraints);

        jLabel6.setText("End Date");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 3;
        gridBagConstraints.gridy = 0;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.insets = new java.awt.Insets(0, 10, 0, 0);
        schedulePanel.add(jLabel6, gridBagConstraints);

        startDateField.setModel(new javax.swing.SpinnerDateModel());
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.insets = new java.awt.Insets(0, 0, 12, 0);
        schedulePanel.add(startDateField, gridBagConstraints);

        endDateField.setModel(new javax.swing.SpinnerDateModel());
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 3;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        gridBagConstraints.insets = new java.awt.Insets(0, 10, 12, 0);
        schedulePanel.add(endDateField, gridBagConstraints);

        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 22;
        gridBagConstraints.gridwidth = 5;
        gridBagConstraints.gridheight = 5;
        gridBagConstraints.fill = java.awt.GridBagConstraints.BOTH;
        detailsPanel.add(schedulePanel, gridBagConstraints);

        roomDescLabel.setText("Room Description");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        detailsPanel.add(roomDescLabel, gridBagConstraints);

        roomDescField.setPreferredSize(new java.awt.Dimension(100, 27));
        roomDescField.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                roomDescFieldActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.gridwidth = 2;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        detailsPanel.add(roomDescField, gridBagConstraints);
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 11;
        gridBagConstraints.gridwidth = 4;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.insets = new java.awt.Insets(10, 0, 10, 0);
        detailsPanel.add(jSeparator1, gridBagConstraints);
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 7;
        gridBagConstraints.gridwidth = 4;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.insets = new java.awt.Insets(11, 0, 10, 0);
        detailsPanel.add(jSeparator2, gridBagConstraints);

        roomNameField.setEditable(false);
        roomNameField.setPreferredSize(new java.awt.Dimension(200, 27));
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        detailsPanel.add(roomNameField, gridBagConstraints);

        roomListButton.setText("Room List");
        roomListButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                roomListButtonActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        detailsPanel.add(roomListButton, gridBagConstraints);

        invitePanel.add(detailsPanel, java.awt.BorderLayout.CENTER);

        getContentPane().add(invitePanel, java.awt.BorderLayout.CENTER);

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void inviteButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_inviteButtonActionPerformed
        inviteParticipants();
    }//GEN-LAST:event_inviteButtonActionPerformed

    private void closeButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_closeButtonActionPerformed
        dispose();
    }//GEN-LAST:event_closeButtonActionPerformed

    private void messageFieldActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_messageFieldActionPerformed
        // TODO add your handling code here:
    }//GEN-LAST:event_messageFieldActionPerformed

    private void userEmailFieldActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_userEmailFieldActionPerformed
        addEmail();
    }//GEN-LAST:event_userEmailFieldActionPerformed

    private void addButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_addButtonActionPerformed
        addEmail();
    }//GEN-LAST:event_addButtonActionPerformed

    private void searchButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_searchButtonActionPerformed
        doSearch();
    }//GEN-LAST:event_searchButtonActionPerformed

    private void roomDescFieldActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_roomDescFieldActionPerformed
        // TODO add your handling code here:
}//GEN-LAST:event_roomDescFieldActionPerformed

    private void roomListButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_roomListButtonActionPerformed
        RoomListFrame roomListFrame = new RoomListFrame(this);
        roomListFrame.setTitle("Room List");
        GUIAccessManager.mf.setRoomListFrame(roomListFrame);
        GUIAccessManager.mf.showRoomList(this);

    }//GEN-LAST:event_roomListButtonActionPerformed

    /**
     * @param args the command line arguments
     */
    public static void main(String args[]) {
        java.awt.EventQueue.invokeLater(new Runnable() {

            public void run() {
                new InviteParticipants().setVisible(true);
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton addButton;
    private javax.swing.ButtonGroup buttonGroup1;
    private javax.swing.JButton closeButton;
    private javax.swing.JPanel detailsPanel;
    private javax.swing.JTextArea emailField;
    private javax.swing.JSpinner endDateField;
    private javax.swing.JButton inviteButton;
    private javax.swing.JPanel invitePanel;
    private javax.swing.JLabel jLabel3;
    private javax.swing.JLabel jLabel5;
    private javax.swing.JLabel jLabel6;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JSeparator jSeparator1;
    private javax.swing.JSeparator jSeparator2;
    private javax.swing.JTextField messageField;
    private javax.swing.JLabel messageLabel;
    private javax.swing.JTextField roomDescField;
    private javax.swing.JLabel roomDescLabel;
    private javax.swing.JButton roomListButton;
    private javax.swing.JTextField roomNameField;
    private javax.swing.JLabel roomNameLabel;
    private javax.swing.JPanel schedulePanel;
    private javax.swing.JButton searchButton;
    private javax.swing.JPanel searchTopPane;
    private javax.swing.JSpinner startDateField;
    private javax.swing.JTextField userEmailField;
    private javax.swing.JLabel userEmailLabel;
    // End of variables declaration//GEN-END:variables
}
