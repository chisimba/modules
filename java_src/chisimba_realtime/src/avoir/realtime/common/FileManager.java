/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * FileManager.java
 *
 * Created on 2009/03/01, 11:09:39
 */
package avoir.realtime.common;

import avoir.realtime.common.packet.FileDownloadRequest;
import avoir.realtime.common.packet.FileVewRequestPacket;
import avoir.realtime.common.packet.PresentationRequest;
import avoir.realtime.common.packet.XmlQuestionRequestPacket;
import avoir.realtime.filetransfer.FileUploader;
import avoir.realtime.classroom.tcp.TCPConnector;
import java.awt.Component;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.io.File;
import java.util.ArrayList;
import javax.swing.DefaultListModel;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JFileChooser;
import javax.swing.JLabel;
import javax.swing.JList;
import javax.swing.JOptionPane;
import javax.swing.ListCellRenderer;
import javax.swing.ListSelectionModel;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;

/**
 *
 * @author developer
 */
public class FileManager extends javax.swing.JFrame {

    private DefaultListModel model = new DefaultListModel();
    private JList fileList = new JList(model);
    private ImageIcon folderIcon = ImageUtil.createImageIcon(this, "/icons/folder.png");
    private ImageIcon fileIcon = ImageUtil.createImageIcon(this, "/icons/file.png");
    private ArrayList<RealtimeFile> files;
    private TCPSocket tcpConnector;
    private JFileChooser fc = new JFileChooser();
    private FileUploader fileUploader;
    private String targetPath = "/documents";
    private ArrayList<String> filters;
    private JFileChooser presentationFC = new JFileChooser();
    private JFileChooser graphicFC = new JFileChooser();
    private JFileChooser flashFC = new JFileChooser();
    private ArrayList<RealtimeFile> pureFiles = new ArrayList<RealtimeFile>();
    private ArrayList<RealtimeFile> folders = new ArrayList<RealtimeFile>();

    public FileManager(ArrayList<RealtimeFile> xfiles, TCPSocket xtcpConnector, ArrayList<String> xfilters) {
        presentationFC.addChoosableFileFilter(new PresentationFilter());
        presentationFC.setMultiSelectionEnabled(true);
        graphicFC.addChoosableFileFilter(new ImageFilter());
        graphicFC.setMultiSelectionEnabled(true);
        flashFC.addChoosableFileFilter(new FlashFilter());
        flashFC.setMultiSelectionEnabled(true);
        fc.setMultiSelectionEnabled(true);
        this.filters = xfilters;
        initComponents();
        this.files = xfiles;
        this.tcpConnector = xtcpConnector;
        fileList.setCellRenderer(new FileListRenderer());
        fileList.setLayoutOrientation(JList.HORIZONTAL_WRAP);
        populateFiles(files);
        fileList.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        scrollPane.getViewport().add(fileList);
        // setAlwaysOnTop(true);
        ListSelectionModel listSelectionModel = fileList.getSelectionModel();
        listSelectionModel.addListSelectionListener(
                new SharedListSelectionHandler());

        fileList.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseClicked(MouseEvent e) {
                if (e.getClickCount() == 2) {
                    processSelect();
                }
            }
        });
        fileUploader = new FileUploader(tcpConnector.getMf());

    }

    private void populateFiles(ArrayList<RealtimeFile> files) {
        folders.clear();
        pureFiles.clear();
        model.clear();
        for (int i = 0; i < files.size(); i++) {
            if (files.get(i).isDirectory()) {
                folders.add(files.get(i));
            } else {
                pureFiles.add(files.get(i));
            }
        }
        //dirs first
        for (int i = 0; i < folders.size(); i++) {
            model.addElement(folders.get(i));
            filePathField.setText(folders.get(i).getPath());
        }
        //then files
        for (int i = 0; i < pureFiles.size(); i++) {
            model.addElement(pureFiles.get(i));
            filePathField.setText(pureFiles.get(i).getPath());
        }


    }

    /** Creates new form FileManager */
    public FileManager(ArrayList<RealtimeFile> xfiles, TCPConnector xtcpConnector) {
        this(xfiles, xtcpConnector, null);
    }

    public ArrayList<String> getFilters() {
        return filters;
    }

    public void setFilters(ArrayList<String> filters) {
        this.filters = filters;
    }

    private void processSelect() {
        RealtimeFile file = (RealtimeFile) model.elementAt(fileList.getSelectedIndex());
        targetPath = file.getPath();
        tcpConnector.setSelectedFilePath(targetPath);
        filePathField.setText(targetPath);
        if (file.isDirectory()) {
            tcpConnector.sendPacket(new FileVewRequestPacket(targetPath));
            filePathField.setText(targetPath);
        } else {
            if (tcpConnector.getFileManagerMode().equals("notepad")) {
                tcpConnector.sendPacket(new FileDownloadRequest(targetPath, Constants.NOTEPAD));
                tcpConnector.setSelectedFilePath(targetPath);

            }
            if (tcpConnector.getFileManagerMode().equals("question-image")) {
                // Utils.showStatusWindow("Downloading...", false);
                tcpConnector.sendPacket(new FileDownloadRequest(targetPath, Constants.QUESTION_IMAGE));
                tcpConnector.setSelectedFilePath(targetPath);

            }
            if (tcpConnector.getFileManagerMode().equals("slide-show")) {
                // Utils.showStatusWindow("Downloading...", false);
                tcpConnector.sendPacket(new FileDownloadRequest(targetPath, Constants.SLIDE_SHOW));
                tcpConnector.setSelectedFilePath(targetPath);

            }
            if (tcpConnector.getFileManagerMode().equals("slide-builder-image")) {
                // Utils.showStatusWindow("Downloading...", false);
                tcpConnector.sendPacket(new FileDownloadRequest(targetPath, Constants.SLIDE_BUILDER_IMAGE));
                tcpConnector.setSelectedFilePath(targetPath);

            }
            if (tcpConnector.getFileManagerMode().equals("whiteboard-image")) {
                //Utils.showStatusWindow("Downloading...", false);
                tcpConnector.sendPacket(new FileDownloadRequest(targetPath, Constants.IMAGE));
                tcpConnector.setSelectedFilePath(targetPath);

            }
            if (tcpConnector.getFileManagerMode().equals("slide-builder-text")) {
                tcpConnector.sendPacket(new FileDownloadRequest(targetPath, Constants.SLIDE_BUILDER_TEXT));
                tcpConnector.setSelectedFilePath(targetPath);
            }
            if (tcpConnector.getFileManagerMode().equals("question-list")) {
                tcpConnector.sendPacket(new XmlQuestionRequestPacket(targetPath));
                tcpConnector.setSelectedFilePath(targetPath);

            }
            if (tcpConnector.getFileManagerMode().equals("presentation")) {
                // Utils.showStatusWindow("Downloading...", false);
                tcpConnector.sendPacket(new PresentationRequest(targetPath));
                tcpConnector.setSelectedFilePath(targetPath);

            }
            if (tcpConnector.getFileManagerMode().equals("slide-builder-question")) {
                //Utils.showStatusWindow("Downloading...", false);
                tcpConnector.sendPacket(new FileDownloadRequest(targetPath, Constants.QUESTION_FILE));
                tcpConnector.setSelectedFilePath(targetPath);

            }
            setVisible(false);
        }
    }

    public void show(String filePath) {
    }

    public JButton getSelectButton() {
        return selectButton;
    }

    public JButton getUploadButton() {
        return uploadButton;
    }

    private boolean display(RealtimeFile file) {
        if (file.isDirectory()) {
            return true;
        }
        String filename = file.getFileName();
        for (int i = 0; i < filters.size(); i++) {
            if (filename.endsWith(filters.get(i))) {
                return true;
            }
        }
        return false;
    }

    public void setFiles(ArrayList<RealtimeFile> files) {
        this.files = files;
        /* model.clear();
        for (int i = 0; i < files.size(); i++) {
        if (files.get(i) != null) {
        try {
        if (filters != null) {
        if (display(files.get(i))) {
        model.addElement(files.get(i));

        }
        } else {
        model.addElement(files.get(i));
        }
        } catch (Exception ex) {
        ex.printStackTrace();
        }
        }
        }*/
        populateFiles(files);
    }

    class SharedListSelectionHandler implements ListSelectionListener {

        public void valueChanged(ListSelectionEvent e) {
            ListSelectionModel lsm = (ListSelectionModel) e.getSource();

            int firstIndex = e.getFirstIndex();
            int lastIndex = e.getLastIndex();
            boolean isAdjusting = e.getValueIsAdjusting();

            if (lsm.isSelectionEmpty()) {
                selectButton.setEnabled(false);
            } else {
                // Find out which indexes are selected.
                int minIndex = lsm.getMinSelectionIndex();
                int maxIndex = lsm.getMaxSelectionIndex();
                selectButton.setEnabled(true);
                RealtimeFile file = (RealtimeFile) model.elementAt(minIndex);
                filePathField.setText(file.getPath());
                if (file.isDirectory()) {
                    selectedFileField.setText("");
                } else {
                    selectedFileField.setText(file.getFileName());
                }

            }
        }
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
            ImageIcon icon = file.isDirectory() ? folderIcon : fileIcon;

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

    private void uploadFile() {
        if (tcpConnector.getFileManagerMode().equals("question-image") ||
                tcpConnector.getFileManagerMode().equals("slide-builder-image") ||
                tcpConnector.getFileManagerMode().equals("whiteboard-image")) {
            if (graphicFC.showOpenDialog(null) == JFileChooser.APPROVE_OPTION) {
                File[] selectedFiles = graphicFC.getSelectedFiles();
                Utils.showStatusWindow("Uploading...", false);
                for (int i = 0; i < selectedFiles.length; i++) {
                    File file = selectedFiles[i];
                    targetPath = tcpConnector.getMf().getUser().getUserName() + "/images";

                    fileUploader.transferFile(file.getAbsolutePath(), Constants.SLIDE_BUILDER_IMAGE, targetPath);
                }
            }
        }
        if (tcpConnector.getFileManagerMode().equals("presentation")) {
            if (presentationFC.showOpenDialog(null) == JFileChooser.APPROVE_OPTION) {
                File[] selectedFiles = presentationFC.getSelectedFiles();
                for (int i = 0; i < selectedFiles.length; i++) {
                    Utils.showStatusWindow("Uploading...", false);
                    File file = selectedFiles[i];
                    targetPath = tcpConnector.getMf().getUser().getUserName() + "/presentations";
                    fileUploader.transferFile(file.getAbsolutePath(), Constants.PRESENTATION, targetPath);
                }
            }
        }
        if (tcpConnector.getFileManagerMode().equals("documents") ||
                tcpConnector.getFileManagerMode().equals("slide-builder-text")) {
            if (fc.showOpenDialog(null) == JFileChooser.APPROVE_OPTION) {
                Utils.showStatusWindow("Uploading...", false);
                File[] selectedFiles = fc.getSelectedFiles();
                for (int i = 0; i < selectedFiles.length; i++) {
                    File file = selectedFiles[i];
                    String path = tcpConnector.getMf().getUser().getUserName() + "/documents";
                    fileUploader.transferFile(file.getAbsolutePath(), Constants.FILE_UPLOAD, path);
                }
            }
        }


    }

    private void processRefresh() {
        tcpConnector.sendPacket(new FileVewRequestPacket(targetPath));
        filePathField.setText(targetPath);
    }

    private void processBack() {
        /*if (!targetPath.endsWith(tcpConnector.getMf().getUser().getUserName())) {
        String parentPath=new File(targetPath).getParent();
        tcpConnector.sendPacket(new FileVewRequestPacket(parentPath));
        targetPath=parentPath;
        filePathField.setText(targetPath);
        }*/
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
        setTitle("File Manager");
        getContentPane().setLayout(new java.awt.BorderLayout(30, 10));

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

    private void cancelButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_cancelButtonActionPerformed
        dispose();
    }//GEN-LAST:event_cancelButtonActionPerformed

    private void uploadButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_uploadButtonActionPerformed
        uploadFile();
    }//GEN-LAST:event_uploadButtonActionPerformed

    private void selectButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_selectButtonActionPerformed
        processSelect();
    }//GEN-LAST:event_selectButtonActionPerformed

    private void backButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_backButtonActionPerformed
        processRefresh();
}//GEN-LAST:event_backButtonActionPerformed
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
