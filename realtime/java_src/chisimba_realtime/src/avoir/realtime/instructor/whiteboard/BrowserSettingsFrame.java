/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * BrowserSettingsFrame.java
 *
 * Created on 2008/10/14, 12:56:59
 */
package avoir.realtime.instructor.whiteboard;


import avoir.realtime.classroom.RealtimeOptions;
import java.awt.BorderLayout;

/**
 *
 * @author developer
 */
public class BrowserSettingsFrame extends javax.swing.JFrame {

    private RealtimeOptions opts;

    /** Creates new form BrowserSettingsFrame */
    public BrowserSettingsFrame(RealtimeOptions opts) {
        initComponents();
        this.opts = opts;
        add(cPanel, BorderLayout.SOUTH);
        setSize(400, 300);
        if (opts.isBrowserUsingProxy()) {
            browserProxyOpt.setSelected(true);
        } else {
            browserDirectOpt.setSelected(true);
        }
        browserHostField.setText(opts.getBrowserProxyHost());
        browserHostField.setEnabled(opts.isBrowserUsingProxy());
        browserPortField.setText(opts.getBrowserProxyPort() + "");
        browserPortField.setEnabled(opts.isBrowserUsingProxy());
    }

    private void save() {
        opts.saveProperty("BrowserProxyHost", browserHostField.getText());
        opts.saveProperty("BrowserProxyPort", browserPortField.getText());
        System.setProperty("network.proxy_port", browserPortField.getText());
        System.setProperty("network.proxy_host", browserHostField.getText());
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
        cPanel = new javax.swing.JPanel();
        okbutton = new javax.swing.JButton();
        cancelButton = new javax.swing.JButton();
        mPanel = new javax.swing.JPanel();
        browserDirectOpt = new javax.swing.JRadioButton();
        browserProxyOpt = new javax.swing.JRadioButton();
        jLabel1 = new javax.swing.JLabel();
        browserHostField = new javax.swing.JTextField();
        jLabel2 = new javax.swing.JLabel();
        browserPortField = new javax.swing.JTextField();

        okbutton.setText("Ok");
        okbutton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                okbuttonActionPerformed(evt);
            }
        });
        cPanel.add(okbutton);

        cancelButton.setText("Cancel");
        cancelButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                cancelButtonActionPerformed(evt);
            }
        });
        cPanel.add(cancelButton);

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);

        mPanel.setLayout(new java.awt.GridBagLayout());

        buttonGroup1.add(browserDirectOpt);
        browserDirectOpt.setText("Direct Connection");
        browserDirectOpt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                browserDirectOptActionPerformed(evt);
            }
        });
        mPanel.add(browserDirectOpt, new java.awt.GridBagConstraints());

        buttonGroup1.add(browserProxyOpt);
        browserProxyOpt.setText("Connect via proxy");
        browserProxyOpt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                browserProxyOptActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 1;
        mPanel.add(browserProxyOpt, gridBagConstraints);

        jLabel1.setText("Host");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        mPanel.add(jLabel1, gridBagConstraints);
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        mPanel.add(browserHostField, gridBagConstraints);

        jLabel2.setText("Port");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 5;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 0);
        mPanel.add(jLabel2, gridBagConstraints);
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 6;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        mPanel.add(browserPortField, gridBagConstraints);

        getContentPane().add(mPanel, java.awt.BorderLayout.CENTER);

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void browserDirectOptActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_browserDirectOptActionPerformed
        opts.saveProperty("BrowserConnection", "Direct");
        browserHostField.setEnabled(false);
        browserPortField.setEnabled(false);
    }//GEN-LAST:event_browserDirectOptActionPerformed

    private void browserProxyOptActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_browserProxyOptActionPerformed
        opts.saveProperty("BrowserConnection", "Proxy");
        browserHostField.setEnabled(true);
        browserPortField.setEnabled(true);
    }//GEN-LAST:event_browserProxyOptActionPerformed

    private void okbuttonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_okbuttonActionPerformed
        save();
        dispose();
    }//GEN-LAST:event_okbuttonActionPerformed

    private void cancelButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_cancelButtonActionPerformed
        dispose();
    }//GEN-LAST:event_cancelButtonActionPerformed
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JRadioButton browserDirectOpt;
    private javax.swing.JTextField browserHostField;
    private javax.swing.JTextField browserPortField;
    private javax.swing.JRadioButton browserProxyOpt;
    private javax.swing.ButtonGroup buttonGroup1;
    private javax.swing.JPanel cPanel;
    private javax.swing.JButton cancelButton;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JPanel mPanel;
    private javax.swing.JButton okbutton;
    // End of variables declaration//GEN-END:variables
}
