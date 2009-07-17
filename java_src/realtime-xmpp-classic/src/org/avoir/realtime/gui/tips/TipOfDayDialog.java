/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * TipOfDayDialog.java
 *
 * Created on 2009/07/15, 9:18:01 PM
 */

package org.avoir.realtime.gui.tips;

import org.avoir.realtime.common.util.GeneralUtil;

/**
 *
 * @author david
 */
public class TipOfDayDialog extends javax.swing.JDialog {

    /** Creates new form TipOfDayDialog */
    public TipOfDayDialog(java.awt.Frame parent, boolean modal) {
        super(parent, modal);
        initComponents();
        tipField.setText(Tips.tips[0]);
         String showtipofdayFlag = GeneralUtil.getProperty("showtipofday");
        boolean showTipOfDay = false;
        if (showtipofdayFlag == null) {
            showTipOfDay = true;
        } else {
            showTipOfDay = new Boolean(showtipofdayFlag);
        }
        showtipsOpt.setSelected(showTipOfDay);
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        cPanel = new javax.swing.JPanel();
        showtipsOpt = new javax.swing.JCheckBox();
        backButton = new javax.swing.JButton();
        nextButton = new javax.swing.JButton();
        closeButton = new javax.swing.JButton();
        jScrollPane1 = new javax.swing.JScrollPane();
        tipField = new javax.swing.JTextPane();

        setDefaultCloseOperation(javax.swing.WindowConstants.DISPOSE_ON_CLOSE);
        setTitle("Tip Of The Day");

        showtipsOpt.setText("Show Tips on Startup");
        showtipsOpt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                showtipsOptActionPerformed(evt);
            }
        });
        cPanel.add(showtipsOpt);

        backButton.setText("<Back");
        cPanel.add(backButton);

        nextButton.setText("Next>");
        cPanel.add(nextButton);

        closeButton.setText("Close");
        closeButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                closeButtonActionPerformed(evt);
            }
        });
        cPanel.add(closeButton);

        getContentPane().add(cPanel, java.awt.BorderLayout.PAGE_END);

        tipField.setContentType("text/html");
        tipField.setEditable(false);
        jScrollPane1.setViewportView(tipField);

        getContentPane().add(jScrollPane1, java.awt.BorderLayout.CENTER);

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void showtipsOptActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_showtipsOptActionPerformed
        GeneralUtil.saveProperty("showtipofday", showtipsOpt.isSelected()+"");
    }//GEN-LAST:event_showtipsOptActionPerformed

    private void closeButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_closeButtonActionPerformed
       dispose();
    }//GEN-LAST:event_closeButtonActionPerformed

    /**
    * @param args the command line arguments
    */
    public static void main(String args[]) {
        java.awt.EventQueue.invokeLater(new Runnable() {
            public void run() {
                TipOfDayDialog dialog = new TipOfDayDialog(new javax.swing.JFrame(), true);
                dialog.addWindowListener(new java.awt.event.WindowAdapter() {
                    public void windowClosing(java.awt.event.WindowEvent e) {
                        System.exit(0);
                    }
                });
                dialog.setVisible(true);
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton backButton;
    private javax.swing.JPanel cPanel;
    private javax.swing.JButton closeButton;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JButton nextButton;
    private javax.swing.JCheckBox showtipsOpt;
    private javax.swing.JTextPane tipField;
    // End of variables declaration//GEN-END:variables

}
