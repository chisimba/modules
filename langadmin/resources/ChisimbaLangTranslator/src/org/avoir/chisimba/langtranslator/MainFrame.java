/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * MainFrame.java
 *
 * Created on 01 Sep 2011, 12:31:58 PM
 */
package org.avoir.chisimba.langtranslator;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.DataInputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.InputStreamReader;
import java.util.Timer;
import java.util.TimerTask;
import javax.swing.DefaultListModel;
import javax.swing.JFileChooser;
import javax.swing.JList;
import javax.swing.JOptionPane;
import javax.swing.JScrollPane;
import javax.swing.ListSelectionModel;
import javax.swing.event.DocumentEvent;
import javax.swing.event.DocumentListener;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;

/**
 *
 * @author davidwaf
 */
public class MainFrame extends javax.swing.JFrame {

    private JFileChooser fc = new JFileChooser();
    private DefaultListModel model = new DefaultListModel();
    private JList list = new JList(model);
    private int selectedIndex = 0;
    private String filename;
    private Timer timer = new Timer();

    /** Creates new form MainFrame */
    public MainFrame() {

        initComponents();
        fc.setFileSelectionMode(JFileChooser.FILES_ONLY);
        itemsPanel.add(new JScrollPane(list));
        list.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        list.addListSelectionListener(new ListSelectionListener() {

            @Override
            public void valueChanged(ListSelectionEvent e) {
                selectedIndex = list.getSelectedIndex();
                showItemDetails();
            }
        });
        searchField.getDocument().addDocumentListener(new DocumentListener() {

            @Override
            public void insertUpdate(DocumentEvent e) {
                search();
            }

            @Override
            public void removeUpdate(DocumentEvent e) {
                search();
            }

            @Override
            public void changedUpdate(DocumentEvent e) {
                search();
            }
        });
        translationField.getDocument().addDocumentListener(new DocumentListener() {

            @Override
            public void insertUpdate(DocumentEvent e) {
                update();
            }

            @Override
            public void removeUpdate(DocumentEvent e) {
                update();
            }

            @Override
            public void changedUpdate(DocumentEvent e) {
                update();
            }
        });




    }

    private void writeToFile(String filename) {
        try {

            BufferedWriter bw = new BufferedWriter(new FileWriter(new File(
                    filename), false));
            if (!filename.endsWith(".txt")) {
                filename = filename + ".txt";
            }

            int size = model.getSize();
            for (int i = 0; i < size; i++) {
                Item item = (Item) model.get(i);
                bw.write(item.getLine());
                bw.newLine();
            }

            bw.close();
        } catch (Exception e) {
            e.printStackTrace();
        }

    }

    private void update() {


        Item item = (Item) model.get(selectedIndex);
        item.setTranslation(translationField.getText());
        model.set(selectedIndex, item);


    }

    private void search() {
        int size = model.getSize();
        String val = searchField.getText();
        if (val.length() > 3) {
            for (int i = 0; i < size; i++) {
                Item item = (Item) model.get(i);
                CharSequence charSequence = val;
                if (item.getCode().contains(charSequence)) {

                    list.ensureIndexIsVisible(i);
                    list.setSelectedIndex(i);

                    break;
                }
            }
        }
    }

    private void showItemDetails() {
        Item item = (Item) model.get(list.getSelectedIndex());
        langItemField.setText(item.getCode());
        descField.setText(item.getDescription());
        translationField.setText(item.getTranslation());
        englishTranslationField.setText(item.getEnglishTranslation());
    }

    private class SaveTask extends TimerTask {

        @Override
        public void run() {
            writeToFile(filename);
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

        topPanel = new javax.swing.JPanel();
        mainPanel = new javax.swing.JPanel();
        splitPane = new javax.swing.JSplitPane();
        itemsPanel = new javax.swing.JPanel();
        searchPanel = new javax.swing.JPanel();
        searchField = new javax.swing.JTextField();
        jPanel2 = new javax.swing.JPanel();
        langItemPanel = new javax.swing.JPanel();
        jLabel1 = new javax.swing.JLabel();
        langItemField = new javax.swing.JTextField();
        jLabel2 = new javax.swing.JLabel();
        jLabel3 = new javax.swing.JLabel();
        translationField = new javax.swing.JTextField();
        jScrollPane1 = new javax.swing.JScrollPane();
        descField = new javax.swing.JTextArea();
        jLabel4 = new javax.swing.JLabel();
        jScrollPane2 = new javax.swing.JScrollPane();
        englishTranslationField = new javax.swing.JTextArea();
        jPanel3 = new javax.swing.JPanel();
        nextButton = new javax.swing.JButton();
        backButton = new javax.swing.JButton();
        southPanel = new javax.swing.JPanel();
        infoLabel = new javax.swing.JLabel();
        exportButton = new javax.swing.JButton();
        closeButton = new javax.swing.JButton();
        menuBar = new javax.swing.JMenuBar();
        fileMenu = new javax.swing.JMenu();
        openMenuItem = new javax.swing.JMenuItem();
        closeMenuItem = new javax.swing.JMenuItem();
        helpMenu = new javax.swing.JMenu();
        aboutMenuItem = new javax.swing.JMenuItem();

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);
        addWindowListener(new java.awt.event.WindowAdapter() {
            public void windowClosing(java.awt.event.WindowEvent evt) {
                formWindowClosing(evt);
            }
        });
        getContentPane().add(topPanel, java.awt.BorderLayout.PAGE_START);

        mainPanel.setLayout(new java.awt.BorderLayout());

        splitPane.setDividerLocation(180);
        splitPane.setLastDividerLocation(150);

        itemsPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Items"));
        itemsPanel.setLayout(new java.awt.BorderLayout());

        searchField.setPreferredSize(new java.awt.Dimension(150, 28));
        searchPanel.add(searchField);

        itemsPanel.add(searchPanel, java.awt.BorderLayout.PAGE_START);

        splitPane.setLeftComponent(itemsPanel);

        jPanel2.setLayout(new java.awt.BorderLayout());

        langItemPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Language Item"));
        langItemPanel.setLayout(new java.awt.GridBagLayout());

        jLabel1.setText("Langauge Item:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.FIRST_LINE_END;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 5);
        langItemPanel.add(jLabel1, gridBagConstraints);

        langItemField.setEditable(false);
        langItemField.setPreferredSize(new java.awt.Dimension(486, 28));
        langItemField.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                langItemFieldActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 5);
        langItemPanel.add(langItemField, gridBagConstraints);

        jLabel2.setText("Description:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.FIRST_LINE_END;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 5);
        langItemPanel.add(jLabel2, gridBagConstraints);

        jLabel3.setText("Translation:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.gridheight = 3;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.FIRST_LINE_END;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 5);
        langItemPanel.add(jLabel3, gridBagConstraints);
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 3;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 5);
        langItemPanel.add(translationField, gridBagConstraints);

        descField.setColumns(20);
        descField.setEditable(false);
        descField.setLineWrap(true);
        descField.setRows(5);
        jScrollPane1.setViewportView(descField);

        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.insets = new java.awt.Insets(5, 1, 5, 0);
        langItemPanel.add(jScrollPane1, gridBagConstraints);

        jLabel4.setText("English Translation:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.FIRST_LINE_END;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 5, 5);
        langItemPanel.add(jLabel4, gridBagConstraints);

        englishTranslationField.setColumns(20);
        englishTranslationField.setEditable(false);
        englishTranslationField.setLineWrap(true);
        englishTranslationField.setRows(5);
        jScrollPane2.setViewportView(englishTranslationField);

        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.fill = java.awt.GridBagConstraints.BOTH;
        langItemPanel.add(jScrollPane2, gridBagConstraints);

        jPanel2.add(langItemPanel, java.awt.BorderLayout.CENTER);

        nextButton.setText("Next");
        nextButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                nextButtonActionPerformed(evt);
            }
        });
        jPanel3.add(nextButton);

        backButton.setText("Back");
        backButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                backButtonActionPerformed(evt);
            }
        });
        jPanel3.add(backButton);

        jPanel2.add(jPanel3, java.awt.BorderLayout.SOUTH);

        splitPane.setRightComponent(jPanel2);

        mainPanel.add(splitPane, java.awt.BorderLayout.CENTER);

        getContentPane().add(mainPanel, java.awt.BorderLayout.CENTER);

        infoLabel.setText("Ready");
        southPanel.add(infoLabel);

        exportButton.setText("Save");
        exportButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                exportButtonActionPerformed(evt);
            }
        });
        southPanel.add(exportButton);

        closeButton.setText("Close");
        southPanel.add(closeButton);

        getContentPane().add(southPanel, java.awt.BorderLayout.PAGE_END);

        fileMenu.setText("File");

        openMenuItem.setText("Open");
        openMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                openMenuItemActionPerformed(evt);
            }
        });
        fileMenu.add(openMenuItem);

        closeMenuItem.setText("Close");
        closeMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                closeMenuItemActionPerformed(evt);
            }
        });
        fileMenu.add(closeMenuItem);

        menuBar.add(fileMenu);

        helpMenu.setText("Help");

        aboutMenuItem.setText("About");
        helpMenu.add(aboutMenuItem);

        menuBar.add(helpMenu);

        setJMenuBar(menuBar);

        pack();
    }// </editor-fold>//GEN-END:initComponents

private void langItemFieldActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_langItemFieldActionPerformed
// TODO add your handling code here:
}//GEN-LAST:event_langItemFieldActionPerformed

    private void openMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_openMenuItemActionPerformed
        if (fc.showOpenDialog(this) == JFileChooser.APPROVE_OPTION) {

            File file = fc.getSelectedFile();
            try {
                // Open the file that is the first 
                // command line parameter
                setTitle(file.getName());
                filename = file.getAbsolutePath();
                FileInputStream fstream = new FileInputStream(filename);
                // Get the object of DataInputStream
                DataInputStream in = new DataInputStream(fstream);
                BufferedReader br = new BufferedReader(new InputStreamReader(in));
                String strLine;
                model.clear();
                //Read File Line By Line
                while ((strLine = br.readLine()) != null) {

                    String[] parts = strLine.split("~");
                    if (parts != null) {
                        if (parts.length > 3) {
                            try {
                                Item item = new Item(parts[0], parts[1], parts[2],parts[3]);
                                model.addElement(item);

                            } catch (Exception ex) {
                                ex.printStackTrace();
                            }
                        }
                    }
                }
                //Close the input stream
                in.close();
                list.setSelectedIndex(0);
                //do auto saving
                timer.cancel();
                timer = new Timer();
                timer.scheduleAtFixedRate(new SaveTask(), 1000, 1000 * 5);
            } catch (Exception e) {//Catch exception if any
                e.printStackTrace();
                JOptionPane.showMessageDialog(this, "Error reading file: " + e.getMessage());
            }
        }
    }//GEN-LAST:event_openMenuItemActionPerformed

    private void nextButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_nextButtonActionPerformed
        if (selectedIndex < model.getSize() - 1) {
            list.setSelectedIndex(++selectedIndex);
        }
    }//GEN-LAST:event_nextButtonActionPerformed

    private void backButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_backButtonActionPerformed
        if (selectedIndex > 0) {
            list.setSelectedIndex(--selectedIndex);
        }
    }//GEN-LAST:event_backButtonActionPerformed

    private void exportButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_exportButtonActionPerformed
        writeToFile(filename);
    }//GEN-LAST:event_exportButtonActionPerformed

    private void closeMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_closeMenuItemActionPerformed
        writeToFile(filename);
        System.exit(0);
    }//GEN-LAST:event_closeMenuItemActionPerformed

    private void formWindowClosing(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_formWindowClosing
        writeToFile(filename);
        System.exit(0);
    }//GEN-LAST:event_formWindowClosing

    /**
     * @param args the command line arguments
     */
    public static void main(String args[]) {
        /* Set the Nimbus look and feel */
        //<editor-fold defaultstate="collapsed" desc=" Look and feel setting code (optional) ">
        /* If Nimbus (introduced in Java SE 6) is not available, stay with the default look and feel.
         * For details see http://download.oracle.com/javase/tutorial/uiswing/lookandfeel/plaf.html 
         */
        try {
            for (javax.swing.UIManager.LookAndFeelInfo info : javax.swing.UIManager.getInstalledLookAndFeels()) {
                if ("Nimbus".equals(info.getName())) {
                    javax.swing.UIManager.setLookAndFeel(info.getClassName());
                    break;
                }
            }
        } catch (ClassNotFoundException ex) {
            java.util.logging.Logger.getLogger(MainFrame.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            java.util.logging.Logger.getLogger(MainFrame.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            java.util.logging.Logger.getLogger(MainFrame.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (javax.swing.UnsupportedLookAndFeelException ex) {
            java.util.logging.Logger.getLogger(MainFrame.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        }
        //</editor-fold>

        /* Create and display the form */
        java.awt.EventQueue.invokeLater(new Runnable() {

            @Override
            public void run() {
                new MainFrame().setVisible(true);
            }
        });
    }
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JMenuItem aboutMenuItem;
    private javax.swing.JButton backButton;
    private javax.swing.JButton closeButton;
    private javax.swing.JMenuItem closeMenuItem;
    private javax.swing.JTextArea descField;
    private javax.swing.JTextArea englishTranslationField;
    private javax.swing.JButton exportButton;
    private javax.swing.JMenu fileMenu;
    private javax.swing.JMenu helpMenu;
    private javax.swing.JLabel infoLabel;
    private javax.swing.JPanel itemsPanel;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JLabel jLabel3;
    private javax.swing.JLabel jLabel4;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JPanel jPanel3;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JScrollPane jScrollPane2;
    private javax.swing.JTextField langItemField;
    private javax.swing.JPanel langItemPanel;
    private javax.swing.JPanel mainPanel;
    private javax.swing.JMenuBar menuBar;
    private javax.swing.JButton nextButton;
    private javax.swing.JMenuItem openMenuItem;
    private javax.swing.JTextField searchField;
    private javax.swing.JPanel searchPanel;
    private javax.swing.JPanel southPanel;
    private javax.swing.JSplitPane splitPane;
    private javax.swing.JPanel topPanel;
    private javax.swing.JTextField translationField;
    // End of variables declaration//GEN-END:variables
}
