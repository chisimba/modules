/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * ConnectionSettingPanel.java
 *
 * Created on 2009/03/28, 04:10:53
 */
package org.avoir.realtime.gui.main;

import javax.swing.JCheckBox;
import javax.swing.JPasswordField;
import javax.swing.JRadioButton;
import javax.swing.JSpinner;
import javax.swing.JTextField;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.util.GeneralUtil;

/**
 *
 * @author developer
 */
public class OptionsPanel extends javax.swing.JPanel {

    /** Creates new form ConnectionSettingPanel */
    public OptionsPanel(String host, int port, String audioVideoUrl) {
        initComponents();
        serverHostField.setText(host);
        serverPortField.setText(port + "");
        audioVideoHttpUrlField.setText(audioVideoUrl);
        requireAuthOpt.setSelected(new Boolean(GeneralUtil.getProperty("proxy.require.auth")));
        showDebugOpt.setSelected(new Boolean(GeneralUtil.getProperty("debug.enabled")));
        if(GeneralUtil.getProperty("maxspeakers") == null){
            GeneralUtil.saveProperty("maxspeakers", "1");
        }
        maxSpeakersField.setValue(new Integer(GeneralUtil.getProperty("maxspeakers")));
        browserProxyOpt.setSelected(new Boolean(GeneralUtil.getProperty("browser.proxy.required")));
        int connectionType = Constants.Proxy.NO_PROXY;
        httpProxyHostField.setText(GeneralUtil.getProperty("proxy.host"));

        httpProxyPortField.setText(GeneralUtil.getProperty("proxy.port"));

        usernameField.setText(GeneralUtil.getProperty("proxy.username"));

        passwordField.setText(GeneralUtil.getProperty("proxy.password"));

        try {
            connectionType = Integer.parseInt(GeneralUtil.getProperty("connection.type"));
        } catch (NumberFormatException ex) {
        }
        switch (connectionType) {
            case Constants.Proxy.NO_PROXY:
                noProxyOpt.setSelected(true);
                enableControls(false);
                break;
            case Constants.Proxy.HTTP_PROXY:
                httpProxyOpt.setSelected(true);
                enableControls(true);
                break;
            case Constants.Proxy.SOCKS_PROXY:
                break;
            default:
                noProxyOpt.setSelected(true);
        }

    }

    public JTextField getHttpProxyHostField() {
        return httpProxyHostField;
    }

    public void setHttpProxyHostField(JTextField httpProxyHostField) {
        this.httpProxyHostField = httpProxyHostField;
    }

    public JRadioButton getHttpProxyOpt() {
        return httpProxyOpt;
    }

    public JCheckBox getBrowserProxyOpt() {
        return browserProxyOpt;
    }

    public void setHttpProxyOpt(JRadioButton httpProxyOpt) {
        this.httpProxyOpt = httpProxyOpt;
    }

    public JTextField getHttpProxyPortField() {
        return httpProxyPortField;
    }

    public JRadioButton getNoProxyOpt() {
        return noProxyOpt;
    }

    public void setNoProxyOpt(JRadioButton noProxyOpt) {
        this.noProxyOpt = noProxyOpt;
    }

    public JPasswordField getPasswordField() {
        return passwordField;
    }

    public JTextField getUsernameField() {
        return usernameField;
    }

    public void setUsernameField(JTextField usernameField) {
        this.usernameField = usernameField;
    }

    public JTextField getAudioVideoHttpUrlField() {
        return audioVideoHttpUrlField;
    }

    public JTextField getServerHostField() {
        return serverHostField;
    }

    public JTextField getServerPortField() {
        return serverPortField;
    }

    public JCheckBox getRequireAuthOpt() {
        return requireAuthOpt;
    }

    public JSpinner getMaxSpeakersField() {
        return maxSpeakersField;
    }

    private void enableControls(boolean state) {
        httpProxyHostField.setEditable(state);
        httpProxyPortField.setEditable(state);
        usernameField.setEditable(state);
        passwordField.setEditable(state);
        requireAuthOpt.setEnabled(state);
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

        bg = new javax.swing.ButtonGroup();
        jLabel1 = new javax.swing.JLabel();
        serverHostField = new javax.swing.JTextField();
        jLabel2 = new javax.swing.JLabel();
        serverPortField = new javax.swing.JTextField();
        jLabel3 = new javax.swing.JLabel();
        audioVideoHttpUrlField = new javax.swing.JTextField();
        proxyPanel = new javax.swing.JPanel();
        noProxyOpt = new javax.swing.JRadioButton();
        httpProxyOpt = new javax.swing.JRadioButton();
        jLabel4 = new javax.swing.JLabel();
        httpProxyHostField = new javax.swing.JTextField();
        jLabel5 = new javax.swing.JLabel();
        httpProxyPortField = new javax.swing.JTextField();
        jLabel6 = new javax.swing.JLabel();
        usernameField = new javax.swing.JTextField();
        jLabel7 = new javax.swing.JLabel();
        passwordField = new javax.swing.JPasswordField();
        requireAuthOpt = new javax.swing.JCheckBox();
        browserProxyOpt = new javax.swing.JCheckBox();
        showDebugOpt = new javax.swing.JCheckBox();
        jLabel8 = new javax.swing.JLabel();
        maxSpeakersField = new javax.swing.JSpinner();

        setLayout(new java.awt.GridBagLayout());

        jLabel1.setText("Server Host:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHEAST;
        add(jLabel1, gridBagConstraints);

        serverHostField.setPreferredSize(new java.awt.Dimension(204, 19));
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        add(serverHostField, gridBagConstraints);

        jLabel2.setText("Server Port:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHEAST;
        gridBagConstraints.insets = new java.awt.Insets(4, 0, 0, 0);
        add(jLabel2, gridBagConstraints);

        serverPortField.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                serverPortFieldActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.insets = new java.awt.Insets(4, 0, 0, 0);
        add(serverPortField, gridBagConstraints);

        jLabel3.setText("Audio Video URL:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHEAST;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        add(jLabel3, gridBagConstraints);
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        add(audioVideoHttpUrlField, gridBagConstraints);

        proxyPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Connection Details"));
        proxyPanel.setLayout(new java.awt.GridBagLayout());

        bg.add(noProxyOpt);
        noProxyOpt.setSelected(true);
        noProxyOpt.setText("No Proxy");
        noProxyOpt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                noProxyOptActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        proxyPanel.add(noProxyOpt, gridBagConstraints);

        bg.add(httpProxyOpt);
        httpProxyOpt.setText("HTTP Proxy");
        httpProxyOpt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                httpProxyOptActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        proxyPanel.add(httpProxyOpt, gridBagConstraints);

        jLabel4.setText("Http Proxy  Host:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        proxyPanel.add(jLabel4, gridBagConstraints);

        httpProxyHostField.setEditable(false);
        httpProxyHostField.setPreferredSize(new java.awt.Dimension(200, 21));
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        proxyPanel.add(httpProxyHostField, gridBagConstraints);

        jLabel5.setText("Port:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 2;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(0, 6, 0, 0);
        proxyPanel.add(jLabel5, gridBagConstraints);

        httpProxyPortField.setEditable(false);
        httpProxyPortField.setPreferredSize(new java.awt.Dimension(50, 21));
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 3;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        proxyPanel.add(httpProxyPortField, gridBagConstraints);

        jLabel6.setText("Username:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 6;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        proxyPanel.add(jLabel6, gridBagConstraints);

        usernameField.setEditable(false);
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 6;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        proxyPanel.add(usernameField, gridBagConstraints);

        jLabel7.setText("Password:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 2;
        gridBagConstraints.gridy = 6;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(0, 5, 0, 0);
        proxyPanel.add(jLabel7, gridBagConstraints);

        passwordField.setEditable(false);
        passwordField.setPreferredSize(new java.awt.Dimension(200, 21));
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 3;
        gridBagConstraints.gridy = 6;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        proxyPanel.add(passwordField, gridBagConstraints);

        requireAuthOpt.setText("Require Proxy Authentication");
        requireAuthOpt.setEnabled(false);
        requireAuthOpt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                requireAuthOptActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 5;
        gridBagConstraints.gridwidth = 4;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        proxyPanel.add(requireAuthOpt, gridBagConstraints);

        browserProxyOpt.setText("Use Proxy for Web Browser");
        proxyPanel.add(browserProxyOpt, new java.awt.GridBagConstraints());

        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 4;
        gridBagConstraints.gridwidth = 2;
        gridBagConstraints.gridheight = 6;
        gridBagConstraints.fill = java.awt.GridBagConstraints.BOTH;
        add(proxyPanel, gridBagConstraints);

        showDebugOpt.setText("Show Debug Information");
        showDebugOpt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                showDebugOptActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 12;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        add(showDebugOpt, gridBagConstraints);

        jLabel8.setText("Maximum Speakers");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 10;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        gridBagConstraints.insets = new java.awt.Insets(3, 0, 0, 0);
        add(jLabel8, gridBagConstraints);
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 11;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.NORTHWEST;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        add(maxSpeakersField, gridBagConstraints);
    }// </editor-fold>//GEN-END:initComponents

    private void serverPortFieldActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_serverPortFieldActionPerformed
        // TODO add your handling code here:
    }//GEN-LAST:event_serverPortFieldActionPerformed

    private void noProxyOptActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_noProxyOptActionPerformed
        enableControls(false);
    }//GEN-LAST:event_noProxyOptActionPerformed

    private void httpProxyOptActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_httpProxyOptActionPerformed
        enableControls(httpProxyOpt.isSelected());
    }//GEN-LAST:event_httpProxyOptActionPerformed

    private void requireAuthOptActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_requireAuthOptActionPerformed
}//GEN-LAST:event_requireAuthOptActionPerformed

    private void showDebugOptActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_showDebugOptActionPerformed
        GeneralUtil.saveProperty("debug.enabled", showDebugOpt.isSelected() + "");
    }//GEN-LAST:event_showDebugOptActionPerformed

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JTextField audioVideoHttpUrlField;
    private javax.swing.ButtonGroup bg;
    private javax.swing.JCheckBox browserProxyOpt;
    private javax.swing.JTextField httpProxyHostField;
    private javax.swing.JRadioButton httpProxyOpt;
    private javax.swing.JTextField httpProxyPortField;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JLabel jLabel3;
    private javax.swing.JLabel jLabel4;
    private javax.swing.JLabel jLabel5;
    private javax.swing.JLabel jLabel6;
    private javax.swing.JLabel jLabel7;
    private javax.swing.JLabel jLabel8;
    private javax.swing.JSpinner maxSpeakersField;
    private javax.swing.JRadioButton noProxyOpt;
    private javax.swing.JPasswordField passwordField;
    private javax.swing.JPanel proxyPanel;
    private javax.swing.JCheckBox requireAuthOpt;
    private javax.swing.JTextField serverHostField;
    private javax.swing.JTextField serverPortField;
    private javax.swing.JCheckBox showDebugOpt;
    private javax.swing.JTextField usernameField;
    // End of variables declaration//GEN-END:variables
}
