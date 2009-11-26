/*
 * UserListPanel.java
 *
 * Created on 2009/03/21, 04:20:26
 */
package org.avoir.realtime.gui.userlist;

import chrriis.dj.nativeswing.swtimpl.components.JWebBrowser;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Cursor;
import java.awt.Dimension;
import java.awt.GradientPaint;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Rectangle;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import javax.swing.BorderFactory;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTabbedPane;
import javax.swing.SwingUtilities;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.net.ConnectionManager;
import org.jivesoftware.smack.AccountManager;

/**
 *
 * @author developer
 */
public class ParticipantListPanel extends javax.swing.JPanel {

    private ImageIcon availableIcon = ImageUtil.createImageIcon(this, "/images/im_available.png");
    private ImageIcon awayIcon = ImageUtil.createImageIcon(this, "/images/im_away.png");
    private ImageIcon dndIcon = ImageUtil.createImageIcon(this, "/images/im_dnd.png");
    private ImageIcon avator = ImageUtil.createImageIcon(this, "/images/default_avatar_32x32.png");
    private AccountManager accManager;
    private String[] statusMessages = {"Available", "Away", "Busy"};
    private ImageIcon[] statusIcons = {availableIcon, awayIcon, dndIcon};
    //private ParticipantListTree userListTree;
    private ParticipantListTable participantListTable;
    private UserDetailsFrame userDetailsFrame;
    private JPanel userListMainPanel = new JPanel(new BorderLayout());
    private JPanel audioVideoPanel = new JPanel(new BorderLayout());
    private JWebBrowser webBrowser = new JWebBrowser();
    private JButton startAudioVideoButton = new JButton("Enable");
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private boolean audioEnabled = false;

    /** Creates new form UserListPanel */
    public ParticipantListPanel() {
        initComponents();
        for (int i = 0; i < statusMessages.length; i++) {
            accStatusField.addItem(statusMessages[i]);
        }
        accStatusField.setRenderer(new ComboBoxRenderer(statusMessages, statusIcons));
        //accountPhotoField.setIcon(avator);
        participantListTable = new ParticipantListTable();
        displayAccountInfo();
        audioVideoPanel.setBorder(BorderFactory.createTitledBorder("Audio Video"));
        JScrollPane sp = new JScrollPane(participantListTable);
        sp.getViewport().setBackground(Color.WHITE);
        userListMainPanel.add(sp, BorderLayout.CENTER);
        userListTabbedPane.addTab("Users", userListMainPanel);
        userListTabbedPane.addTab("Audio/Video", audioVideoPanel);
        //userListTabbedPane.setSelectedIndex(1);
        webBrowser.setMenuBarVisible(false);
        webBrowser.setBarsVisible(false);
        webBrowser.setButtonBarVisible(false);

        audioVideoPanel.add(webBrowser, BorderLayout.CENTER);
        startAudioVideoButton.setEnabled(false);
        JPanel p = new JPanel();
        p.add(startAudioVideoButton);
        startAudioVideoButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {
                if (ConnectionManager.audioVideoUrlReady && ConnectionManager.flashUrlReady) {
                    enableAudio();

                } else {
                    JOptionPane.showMessageDialog(null, "It appears the audio video server is not ready.\n" +
                            "Please try again after few minutes");
                }
            }
        });
        audioVideoPanel.add(p, BorderLayout.SOUTH);
        JPanel p2 = new JPanel();

        audioVideoPanel.add(p2, BorderLayout.NORTH);
        add(userListTabbedPane, BorderLayout.CENTER);
        //statusPanel.add(soundAlerterField);
        soundAlerterField.setPreferredSize(new Dimension(100, 21));
        soundAlerterField.setBorder(BorderFactory.createEtchedBorder());
        statusPanel.add(alerterField);
        JPanel p3 = new JPanel(new BorderLayout());
        p3.add(statusPanel, BorderLayout.NORTH);
        p3.add(soundAlerterField, BorderLayout.SOUTH);
        add(p3, BorderLayout.NORTH);

    }

    public JLabel getSoundAlerterField() {
        return soundAlerterField;
    }

    public boolean isAudioEnabled() {
        return audioEnabled;
    }

    public void resetAlerterField() {
        statusPanel.add(alerterField);
    }

    public void enableAudio() {
        try{
        if (userListTabbedPane.getTabCount() > 1) {
            userListTabbedPane.setSelectedIndex(1);
        }
        }catch(Exception ex){
            ex.printStackTrace();
        }
        initAudioVideo(ConnectionManager.isOwner, ConnectionManager.getRoomName());
        GUIAccessManager.mf.getSoundMonitor().cancel();
        soundAlerterField.setText("");
        soundAlerterField.setIcon(null);
        audioEnabled = true;
    }

    public void showRoomOwnerAudioVideoWindow() {
        /*final JDialog dlg = new JDialog(GUIAccessManager.mf, "Room Owner", false);
        dlg.addWindowListener(new WindowAdapter() {

        @Override
        public void windowClosing(WindowEvent e) {
        dlg.setSize(100, 50);
        dlg.setLocation(ss.width - dlg.getWidth(), 5);
        }
        });
        JPanel p = (JPanel) dlg.getContentPane();
        p.setLayout(new BorderLayout());
        p.add(audioVideoPanel, BorderLayout.CENTER);
        dlg.setSize(350, 300);
        dlg.setLocation(ss.width - dlg.getWidth(), 10);
        dlg.setVisible(true);*/
    }

    public ParticipantListTable getParticipantListTable() {
        return participantListTable;
    }

    public JButton getStartAudioVideoButton() {
        return startAudioVideoButton;
    }

    public JTabbedPane getUserListTabbedPane() {
        return userListTabbedPane;
    }

    public void initAudioVideo(boolean instructor, String currentRoomName) {
        currentRoomName = currentRoomName.toLowerCase();
        //  String instructorUrl = "http://" + ConnectionManager.getConnection().getHost() + ":" + ConnectionManager.AUDIO_VIDEO_PORT + "/red5/video/broadcaster.html?me=" + ConnectionManager.getRoomName()+"&you=participant";
        if (!ConnectionManager.AUDIO_VIDEO_URL.startsWith("http://")) {
            ConnectionManager.AUDIO_VIDEO_URL = "http://" + ConnectionManager.AUDIO_VIDEO_URL;
        }
        String instructorUrl = ConnectionManager.AUDIO_VIDEO_URL +
                "/video/broadcaster.html?me=" +
                currentRoomName + "&you=participant";

        String participantUrl = ConnectionManager.AUDIO_VIDEO_URL +
                "/video/receiver.html?me=participant&you=" + currentRoomName;
        if (instructor) {
            navigateToURl(instructorUrl);
        } else {
            navigateToURl(participantUrl);
        }
        startAudioVideoButton.setText("Reload");
    }

    private void navigateToURl(final String url) {
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                webBrowser.navigate(url);
            }
        });
    }

    private void displayAccountInfo() {
        accManager = ConnectionManager.getConnection().getAccountManager();
        String name = accManager.getAccountAttribute("name");
        //String displayText = "<html><font color=\"#ff6600\" size=\"small\">" + name + "(" + ConnectionManager.getUsername() + ")</font><br><font color=\"blue\";size=\"30%\"><a href=\"\">Click here to change</a></font>";
        accountNameField.setText(name);
    }

    public JLabel getRoomInfoField() {
        return roomInfoField;
    }

    public JButton getPhotoButton() {
        return photoButton;
    }

    public void displayUserNames(String names) {
        accountNameField.setText(names);
    }

    public void displayAvator(ImageIcon icon) {
        photoButton.setIcon(icon);
    }

    class AccountUserPanel extends JPanel {

        public AccountUserPanel() {
            setBorder(BorderFactory.createEtchedBorder());
        }

        @Override
        public void paintComponent(Graphics g) {
            super.paintComponent(g);

            Graphics2D g2 = (Graphics2D) g;
            g2.setPaint(new GradientPaint(0, 0, Color.LIGHT_GRAY, getWidth(),
                    getHeight(), Color.WHITE, false));

            Rectangle r = new Rectangle(0, 0, getWidth(), getHeight());
            g2.fill(r);
        }
    }

    public JTabbedPane getUserTabbedPane() {
        return userListTabbedPane;
    }

    public void showUserDetailsFrame(String jid) {
        userDetailsFrame = new UserDetailsFrame(jid);
        userDetailsFrame.setSize(400, 300);
        userDetailsFrame.setLocationRelativeTo(null);
        userDetailsFrame.setVisible(true);
    }

    /**
     * getters
     */
    public JPanel getAudioVideoPanel() {
        return audioVideoPanel;
    }

    public JLabel getAlerterField() {
        return alerterField;
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        accountUserPanel = new AccountUserPanel();
        statusPanel = new javax.swing.JToolBar();
        soundAlerterField = new javax.swing.JLabel();
        roomInfoField = new javax.swing.JLabel();
        alerterField = new javax.swing.JLabel();
        photoButton = new javax.swing.JButton();
        accountNameField = new javax.swing.JLabel();
        accStatusField = new javax.swing.JComboBox();
        userListTabbedPane = new javax.swing.JTabbedPane();

        accountUserPanel.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        accountUserPanel.setPreferredSize(new java.awt.Dimension(46, 50));
        accountUserPanel.setLayout(new java.awt.BorderLayout());

        statusPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Status"));
        statusPanel.setFloatable(false);
        statusPanel.setRollover(true);

        soundAlerterField.setForeground(new java.awt.Color(255, 0, 0));
        soundAlerterField.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                soundAlerterFieldMouseClicked(evt);
            }
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                soundAlerterFieldMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                soundAlerterFieldMouseExited(evt);
            }
        });
        statusPanel.add(soundAlerterField);

        roomInfoField.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/warn.png"))); // NOI18N
        roomInfoField.setText("You are not in any Room");
        statusPanel.add(roomInfoField);

        accountUserPanel.add(statusPanel, java.awt.BorderLayout.CENTER);

        alerterField.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                alerterFieldMouseClicked(evt);
            }
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                alerterFieldMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                alerterFieldMouseExited(evt);
            }
        });
        accountUserPanel.add(alerterField, java.awt.BorderLayout.LINE_START);

        photoButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/default_avatar_32x32.png"))); // NOI18N
        photoButton.setBorderPainted(false);
        photoButton.setContentAreaFilled(false);
        photoButton.setPreferredSize(new java.awt.Dimension(50, 50));
        photoButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                photoButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                photoButtonMouseExited(evt);
            }
        });
        photoButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                photoButtonActionPerformed(evt);
            }
        });

        accountNameField.setFont(new java.awt.Font("Dialog", 1, 15));
        accountNameField.setForeground(new java.awt.Color(153, 51, 0));
        accountNameField.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                accountNameFieldMouseClicked(evt);
            }
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                accountNameFieldMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                accountNameFieldMouseExited(evt);
            }
        });
        accountNameField.addMouseMotionListener(new java.awt.event.MouseMotionAdapter() {
            public void mouseDragged(java.awt.event.MouseEvent evt) {
                accountNameFieldMouseDragged(evt);
            }
        });

        accStatusField.setPreferredSize(new java.awt.Dimension(100, 21));

        addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                formMouseEntered(evt);
            }
            public void mousePressed(java.awt.event.MouseEvent evt) {
                formMousePressed(evt);
            }
        });
        setLayout(new java.awt.BorderLayout());
        add(userListTabbedPane, java.awt.BorderLayout.PAGE_START);
    }// </editor-fold>//GEN-END:initComponents

    private void formMousePressed(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_formMousePressed
        // TODO add your handling code here:
    }//GEN-LAST:event_formMousePressed

    private void formMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_formMouseEntered
    }//GEN-LAST:event_formMouseEntered

    private void accountNameFieldMouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_accountNameFieldMouseClicked
    }//GEN-LAST:event_accountNameFieldMouseClicked

    private void accountNameFieldMouseDragged(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_accountNameFieldMouseDragged
        // TODO add your handling code here:
    }//GEN-LAST:event_accountNameFieldMouseDragged

    private void accountNameFieldMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_accountNameFieldMouseEntered
    }//GEN-LAST:event_accountNameFieldMouseEntered

    private void accountNameFieldMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_accountNameFieldMouseExited
    }//GEN-LAST:event_accountNameFieldMouseExited

    private void photoButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_photoButtonActionPerformed
        showUserDetailsFrame(ConnectionManager.getConnection().getUser());
    }//GEN-LAST:event_photoButtonActionPerformed

    private void photoButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_photoButtonMouseEntered
        setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));
        photoButton.setBorderPainted(true);
    }//GEN-LAST:event_photoButtonMouseEntered

    private void photoButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_photoButtonMouseExited
        setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
        photoButton.setBorderPainted(false);
    }//GEN-LAST:event_photoButtonMouseExited

    private void alerterFieldMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_alerterFieldMouseEntered
        alerterField.setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));

    }//GEN-LAST:event_alerterFieldMouseEntered

    private void alerterFieldMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_alerterFieldMouseExited
        alerterField.setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));

    }//GEN-LAST:event_alerterFieldMouseExited

    private void alerterFieldMouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_alerterFieldMouseClicked
        participantListTable.showHandRaisers();

    }//GEN-LAST:event_alerterFieldMouseClicked

    private void soundAlerterFieldMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_soundAlerterFieldMouseEntered
        soundAlerterField.setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));
    }//GEN-LAST:event_soundAlerterFieldMouseEntered

    private void soundAlerterFieldMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_soundAlerterFieldMouseExited
        soundAlerterField.setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
    }//GEN-LAST:event_soundAlerterFieldMouseExited

    private void soundAlerterFieldMouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_soundAlerterFieldMouseClicked
        enableAudio();
    }//GEN-LAST:event_soundAlerterFieldMouseClicked
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JComboBox accStatusField;
    private javax.swing.JLabel accountNameField;
    private javax.swing.JPanel accountUserPanel;
    private javax.swing.JLabel alerterField;
    private javax.swing.JButton photoButton;
    private javax.swing.JLabel roomInfoField;
    private javax.swing.JLabel soundAlerterField;
    private javax.swing.JToolBar statusPanel;
    private javax.swing.JTabbedPane userListTabbedPane;
    // End of variables declaration//GEN-END:variables
}
