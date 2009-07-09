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
 * RealtimeFileChooser.java
 *
 * Created on 2009/03/29, 03:49:15
 */
package org.avoir.realtime.gui;

import java.awt.Component;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.io.File;
import java.util.ArrayList;
import javax.swing.DefaultListModel;
import javax.swing.ImageIcon;
import javax.swing.JFileChooser;
import javax.swing.JLabel;
import javax.swing.JList;
import javax.swing.JMenuItem;
import javax.swing.JPopupMenu;
import javax.swing.ListCellRenderer;
import javax.swing.ListSelectionModel;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;
import org.avoir.realtime.common.RealtimeFile;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.FileChooserListener;
import org.avoir.realtime.net.RPacketListener;
import org.avoir.realtime.net.packets.RealtimeChAccess;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.avoir.realtime.net.packets.RealtimeQuestionPacket;
import org.jivesoftware.smack.util.Base64;

/**
 *
 * @author developer
 */
public class RealtimeFileChooser extends javax.swing.JDialog implements FileChooserListener,
        ActionListener {

    private DefaultListModel model = new DefaultListModel();
    private JList fileList = new JList(model);
    private ImageIcon publicFileIcon = ImageUtil.createImageIcon(this, "/images/file.png");
    private ImageIcon privateFileIcon = ImageUtil.createImageIcon(this, "/images/file_private.png");
    private ArrayList<RealtimeFile> privateFiles = new ArrayList<RealtimeFile>();
    private ArrayList<RealtimeFile> publicFiles = new ArrayList<RealtimeFile>();
    private RealtimeFile selectedFile;
    public static int APPROVE_OPTION = 0;
    public static int CANCEL_OPTION = 1;
    int selectedOption = CANCEL_OPTION;
    private JFileChooser presentationFC = new JFileChooser();
    private JFileChooser graphicFC = new JFileChooser();
    private String mode;
    private JPopupMenu popup = new JPopupMenu();
    private JMenuItem privateMenuItem = new JMenuItem("Make Private");
    private JMenuItem publicMenuItem = new JMenuItem("Make Public");
    private JMenuItem delMenuItem = new JMenuItem("Delete");
    private ArrayList<RealtimeFile> selectedFiles = new ArrayList<RealtimeFile>();

    /** Creates new form RealtimeFileChooser */
    public RealtimeFileChooser(String mode) {
        initComponents();

        this.mode = mode;
        setModal(true);
        fileList.setCellRenderer(new FileListRenderer());
        fileList.setLayoutOrientation(JList.HORIZONTAL_WRAP);

        fileList.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        if (mode.equals("images")) {
            fileList.setSelectionMode(ListSelectionModel.MULTIPLE_INTERVAL_SELECTION);
        }
        scrollPane.getViewport().add(fileList);
        presentationFC.addChoosableFileFilter(new PresentationFilter());
        presentationFC.setMultiSelectionEnabled(true);
        graphicFC.addChoosableFileFilter(new ImageFilter());
        graphicFC.setMultiSelectionEnabled(true);

        // setAlwaysOnTop(true);
        ListSelectionModel listSelectionModel = fileList.getSelectionModel();
        listSelectionModel.addListSelectionListener(
                new SharedListSelectionHandler());

        fileList.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseClicked(MouseEvent e) {
                if (e.getButton() == MouseEvent.BUTTON3) {
                    selectedFile = (RealtimeFile) model.elementAt(fileList.getSelectedIndex());
                    if (selectedFile != null) {
                        if (selectedFile.isPublicAccessible()) {
                            privateMenuItem.setEnabled(true);
                            publicMenuItem.setEnabled(false);
                            delMenuItem.setEnabled(false);
                        } else {
                            privateMenuItem.setEnabled(false);
                            publicMenuItem.setEnabled(true);
                            delMenuItem.setEnabled(true);
                        }
                    }
                    popup.show(RealtimeFileChooser.this, e.getX(), e.getY() + 50);

                    return;
                }
                if (e.getClickCount() == 2) {
                    processSelect();
                }
            }
        });
        privateMenuItem.setActionCommand("make-private");
        privateMenuItem.addActionListener(this);
        publicMenuItem.setActionCommand("make-public");
        publicMenuItem.addActionListener(this);
        popup.add(publicMenuItem);

        popup.add(delMenuItem);
        RPacketListener.addFileChooserListener(this);
    }

    public JList getFileList() {
        return fileList;
    }

    private void changeAccess(String type) {
        RealtimeChAccess p = new RealtimeChAccess();
        p.setPacketType("access");
        p.setFileType(mode);
        p.setAccess(type);
        p.setUsername(ConnectionManager.getUsername());
        p.setFileName(selectedFile.getFileName());

        ConnectionManager.getConnection().sendPacket(p);
    }

    public void actionPerformed(ActionEvent e) {
        if (e.getActionCommand().equals("make-private")) {
            if (selectedFile != null) {
                changeAccess("private");
            }
        }
        if (e.getActionCommand().equals("make-public")) {
            if (selectedFile != null) {
                changeAccess("public");
            }
        }
    }

    public void processFileView(ArrayList<RealtimeFile> files) {

        privateFiles.clear();
        publicFiles.clear();
        model.clear();
        for (int i = 0; i < files.size(); i++) {
            if (files.get(i).isPublicAccessible()) {
                publicFiles.add(files.get(i));
            } else {
                privateFiles.add(files.get(i));
            }
        }

        //public first
        for (int i = 0; i < publicFiles.size(); i++) {
            model.addElement(publicFiles.get(i));
            filePathField.setText(publicFiles.get(i).getFileName());
        }
        //then files
        for (int i = 0; i < privateFiles.size(); i++) {
            model.addElement(privateFiles.get(i));
            filePathField.setText(privateFiles.get(i).getFileName());
        }
    }

    public int showDialog() {
        setSize(400, 300);
        setLocationRelativeTo(null);
        setVisible(true);

        return selectedOption;
    }

    public ArrayList<RealtimeFile> getSelectedFiles() {
        return selectedFiles;
    }

    public RealtimeFile getSelectedFile() {
        return selectedFile;
    }

    public void enablePostButton() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    class SharedListSelectionHandler implements ListSelectionListener {

        public void valueChanged(ListSelectionEvent e) {
            ListSelectionModel lsm = (ListSelectionModel) e.getSource();

            if (lsm.isSelectionEmpty()) {
                selectButton.setEnabled(false);
            } else {
                selectedFiles.clear();
                // Find out which indexes are selected.
                int minIndex = lsm.getMinSelectionIndex();
                int maxIndex = lsm.getMaxSelectionIndex();
                for (int i = minIndex; i < maxIndex; i++) {
                    selectedFiles.add((RealtimeFile) model.elementAt(i));
                }
                selectButton.setEnabled(true);
                RealtimeFile file = (RealtimeFile) model.elementAt(minIndex);
                filePathField.setText(file.getFileName());
                if (file.isDirectory()) {
                    selectedFileField.setText("");
                } else {
                    selectedFileField.setText(file.getFileName());
                }

            }
        }
    }

    public void setSelectButtonText(String txt) {
        selectButton.setText(txt);
    }

    class FileListRenderer extends JLabel
            implements ListCellRenderer {

        public FileListRenderer() {
            setOpaque(true);
            setHorizontalAlignment(LEADING);
            setVerticalAlignment(CENTER);
        }

        /*
         * This method finds the image and text corresponding
         * to the selected value and returns the label, set up
         * to display the text and image.
         */
        public Component getListCellRendererComponent(
                JList list,
                Object value,
                int index,
                boolean isSelected,
                boolean cellHasFocus) {
            if (isSelected) {
                setBackground(list.getSelectionBackground());
                setForeground(list.getSelectionForeground());
            } else {
                setBackground(list.getBackground());
                setForeground(list.getForeground());
            }

            //Set the icon and text.  If icon was null, say so.
            RealtimeFile file = (RealtimeFile) value;
            ImageIcon icon = file.isPublicAccessible() ? publicFileIcon : privateFileIcon;

            setIcon(icon);
            if (icon != null) {
                setText(file.getFileName());
                setFont(list.getFont());
            } else {
                setText(file.getFileName());
            }

            return this;
        }
    }

    private void processSelect() {
        selectedFile = (RealtimeFile) model.elementAt(fileList.getSelectedIndex());
        if (selectedFile.isDirectory()) {
            //
        } else {//download image
            selectedOption = APPROVE_OPTION;
            dispose();
        }
    }

    public void showQuestionFrame(RealtimeQuestionPacket packet) {
        System.out.println("FileChooser: showQuestionFrame: should not be here");
    }

    public void processResourceFileView(ArrayList<RealtimeFile> fileView) {
        System.out.println("FileChooser: processResourceFileView: should not be here");
    }

    private void uploadNew() {

        if (graphicFC.showOpenDialog(this) == JFileChooser.APPROVE_OPTION) {
            File[] selectedFiles = graphicFC.getSelectedFiles();
            for (int i = 0; i < selectedFiles.length; i++) {
                String file = selectedFiles[i].getAbsolutePath();
                String fileName = selectedFiles[i].getName();
                String imageData = Base64.encodeFromFile(file);
                RealtimePacket p = new RealtimePacket();
                p.setMode(RealtimePacket.Mode.UPLOAD_IMAGE);
                StringBuilder sb = new StringBuilder();
                sb.append("<filename>").append(fileName).append("</filename>");
                sb.append("<image-data>").append(imageData).append("</image-data>");
                sb.append("<username>").append(ConnectionManager.getUsername()).append("</username>");
                p.setContent(sb.toString());
                ConnectionManager.sendPacket(p);
            }
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

        detailsPanel = new javax.swing.JPanel();
        scrollPane = new javax.swing.JScrollPane();
        backPanel = new javax.swing.JPanel();
        filePathField = new javax.swing.JTextField();
        backButton = new javax.swing.JButton();
        jPanel1 = new javax.swing.JPanel();
        jPanel2 = new javax.swing.JPanel();
        jPanel3 = new javax.swing.JPanel();
        cPanel = new javax.swing.JPanel();
        buttonsPanel = new javax.swing.JPanel();
        selectButton = new javax.swing.JButton();
        uploadButton = new javax.swing.JButton();
        cancelButton = new javax.swing.JButton();
        fileNamePanel = new javax.swing.JPanel();
        jLabel1 = new javax.swing.JLabel();
        selectedFileField = new javax.swing.JTextField();
        jLabel2 = new javax.swing.JLabel();
        jComboBox1 = new javax.swing.JComboBox();

        setDefaultCloseOperation(javax.swing.WindowConstants.DISPOSE_ON_CLOSE);
        setTitle("File Chooser");
        addWindowListener(new java.awt.event.WindowAdapter() {
            public void windowClosing(java.awt.event.WindowEvent evt) {
                formWindowClosing(evt);
            }
        });

        detailsPanel.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        detailsPanel.setLayout(new java.awt.BorderLayout());
        detailsPanel.add(scrollPane, java.awt.BorderLayout.CENTER);

        filePathField.setText("/server/");
        filePathField.setPreferredSize(new java.awt.Dimension(269, 19));
        backPanel.add(filePathField);

        backButton.setText("Refresh");
        backButton.setEnabled(false);
        backButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                backButtonActionPerformed(evt);
            }
        });
        backPanel.add(backButton);

        detailsPanel.add(backPanel, java.awt.BorderLayout.PAGE_START);
        detailsPanel.add(jPanel1, java.awt.BorderLayout.LINE_END);
        detailsPanel.add(jPanel2, java.awt.BorderLayout.LINE_START);
        detailsPanel.add(jPanel3, java.awt.BorderLayout.PAGE_END);

        getContentPane().add(detailsPanel, java.awt.BorderLayout.CENTER);

        cPanel.setLayout(new java.awt.BorderLayout());

        selectButton.setText("Insert");
        selectButton.setEnabled(false);
        selectButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                selectButtonActionPerformed(evt);
            }
        });
        buttonsPanel.add(selectButton);

        uploadButton.setText("Upload New");
        uploadButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                uploadButtonActionPerformed(evt);
            }
        });
        buttonsPanel.add(uploadButton);

        cancelButton.setText("Cancel");
        cancelButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                cancelButtonActionPerformed(evt);
            }
        });
        buttonsPanel.add(cancelButton);

        cPanel.add(buttonsPanel, java.awt.BorderLayout.CENTER);

        fileNamePanel.setLayout(new java.awt.GridBagLayout());

        jLabel1.setText("File Name:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        fileNamePanel.add(jLabel1, gridBagConstraints);

        selectedFileField.setColumns(2);
        selectedFileField.setHorizontalAlignment(javax.swing.JTextField.LEFT);
        selectedFileField.setPreferredSize(new java.awt.Dimension(226, 19));
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        fileNamePanel.add(selectedFileField, gridBagConstraints);

        jLabel2.setText("Files of Type:");
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        fileNamePanel.add(jLabel2, gridBagConstraints);

        jComboBox1.setModel(new javax.swing.DefaultComboBoxModel(new String[] { "All Files" }));
        jComboBox1.setPreferredSize(new java.awt.Dimension(268, 24));
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 1;
        fileNamePanel.add(jComboBox1, gridBagConstraints);

        cPanel.add(fileNamePanel, java.awt.BorderLayout.PAGE_START);

        getContentPane().add(cPanel, java.awt.BorderLayout.PAGE_END);

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void backButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_backButtonActionPerformed
}//GEN-LAST:event_backButtonActionPerformed

    private void selectButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_selectButtonActionPerformed
        processSelect();
}//GEN-LAST:event_selectButtonActionPerformed

    private void uploadButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_uploadButtonActionPerformed
        uploadNew();

}//GEN-LAST:event_uploadButtonActionPerformed

    private void cancelButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_cancelButtonActionPerformed
        selectedOption = CANCEL_OPTION;
        RPacketListener.removeFileChooserListener(this);
        dispose();
}//GEN-LAST:event_cancelButtonActionPerformed

    private void formWindowClosing(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_formWindowClosing
        selectedOption = CANCEL_OPTION;
        RPacketListener.removeFileChooserListener(this);
        dispose();
    }//GEN-LAST:event_formWindowClosing

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton backButton;
    private javax.swing.JPanel backPanel;
    private javax.swing.JPanel buttonsPanel;
    private javax.swing.JPanel cPanel;
    private javax.swing.JButton cancelButton;
    private javax.swing.JPanel detailsPanel;
    private javax.swing.JPanel fileNamePanel;
    private javax.swing.JTextField filePathField;
    private javax.swing.JComboBox jComboBox1;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JPanel jPanel3;
    private javax.swing.JScrollPane scrollPane;
    private javax.swing.JButton selectButton;
    private javax.swing.JTextField selectedFileField;
    private javax.swing.JButton uploadButton;
    // End of variables declaration//GEN-END:variables
}
