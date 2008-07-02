/*
 * FileTransferFrame.java
 *
 * Created on 05 June 2008, 07:27
 */
package avoir.realtime.tcp.base.filetransfer;

import avoir.realtime.tcp.base.RealtimeBase;
import avoir.realtime.tcp.common.packet.BinaryFileSaveRequestPacket;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Component;
import java.util.Vector;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JFileChooser;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.ListSelectionModel;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;
import javax.swing.table.AbstractTableModel;
import javax.swing.table.DefaultTableCellRenderer;
import javax.swing.table.TableColumn;

/**
 *
 * @author  developer
 */
public class FileTransferPanel extends javax.swing.JPanel {

    JFileChooser fc = new JFileChooser();
    RealtimeBase base;
    FileSharingEngine engine;
    private SendFilesTableModel sendFilesModel = new SendFilesTableModel();
    JTable table = new JTable(sendFilesModel);
    Vector<SendFile> sendFiles = new Vector<SendFile>();
    String selectedFile = null;
    int selectedRow = 0;

    /** Creates new form FileTransferFrame */
    public FileTransferPanel(RealtimeBase base) {
        initComponents();
        this.base = base;
        engine = new FileSharingEngine(this.base);
        sendFilesPanel.add(new JScrollPane(table), BorderLayout.CENTER);
        this.base.getTcpClient().setFileTransfer(this);
        table.setGridColor(new Color(238, 238, 238));
        table.setShowHorizontalLines(false);
        table.setDefaultRenderer(SendFile.class, new LRenderer());
        newButton.setEnabled(this.base.getControl() || this.base.isPresenter());
        //Ask to be notified of selection changes.
        ListSelectionModel rowSM = table.getSelectionModel();
        rowSM.addListSelectionListener(new ListSelectionListener() {

            public void valueChanged(ListSelectionEvent e) {
                //Ignore extra messages.
                if (e.getValueIsAdjusting()) {
                    return;
                }

                ListSelectionModel lsm =
                        (ListSelectionModel) e.getSource();
                if (lsm.isSelectionEmpty()) {
                } else {
                    selectedRow = lsm.getMinSelectionIndex();

                }
            }
        });
        decorateTable();
    }

    private void decorateTable() {
        table.setDefaultRenderer(SendFile.class, new LRenderer());
        TableColumn column = null;
        if (sendFilesModel != null) {
            for (int i = 0; i < sendFilesModel.getColumnCount(); i++) {
                column = table.getColumnModel().getColumn(i);
                if (i == 0) {
                    column.setPreferredWidth(300);
                } else {
                    column.setPreferredWidth(50);
                }
            }
        }
        table.setDefaultRenderer(SendFile.class, new LRenderer());
    }

    class LRenderer extends DefaultTableCellRenderer {

        public LRenderer() {
            super();
            this.setFont(new java.awt.Font("Dialog", 1, 10));
            setOpaque(true); //MUST do this for background to show up.

        }

        @Override
        public void setValue(Object val) {
            if (val instanceof ImageIcon) {
                this.setIcon((ImageIcon) val);
            }
            if (val instanceof SendFile) {
                SendFile file = (SendFile) val;
                String display = "";
                Color color = new Color(0, 131, 0);
                int row = file.getRow();
                switch (row) {
                    case 0: {
                        display = file.getFilename();
                        color = file.isAccepted() ? new Color(0, 131, 0) : Color.RED;
                        this.setText(display);
                        this.setForeground(color);
                    }
                    case 1: {
                        display = file.getReceipient();
                        color = file.isAccepted() ? new Color(0, 131, 0) : Color.RED;
                        this.setText(display);
                        this.setForeground(color);
                    }
                    case 2: {
                        display = file.getProgress() + "";
                        color = file.isAccepted() ? new Color(0, 131, 0) : Color.RED;
                        this.setText(display);
                        this.setForeground(color);
                    }
                    case 3: {
                        display = file.isAccepted() ? "Yes" : "No";
                        color = file.isAccepted() ? new Color(0, 131, 0) : Color.RED;
                        this.setText(display);
                        this.setForeground(color);
                    }
                }


            /*  if (file.isAccepted()) {
            setFont(new java.awt.Font("Dialog", 0, 10));
            this.setText(display);
            this.setForeground(color);
            } else {
            setFont(new java.awt.Font("Dialog", 3, 10));
            this.setText(display);
            this.setForeground(color);
            }
             */
            }

        }

        @Override
        public Component getTableCellRendererComponent(JTable table, Object val,
                boolean isSelected,
                boolean hasFocus,
                int row,
                int column) {

            if (isSelected) {

                setBackground(Color.yellow);
            } else {
                setBackground(table.getBackground());
            }

            setFont(new java.awt.Font("Dialog", 0, 11));
            this.setHorizontalAlignment(javax.swing.SwingConstants.LEFT);

            if (val instanceof ImageIcon) {
                this.setIcon((ImageIcon) val);
            }
            if (val instanceof SendFile) {
                SendFile file = (SendFile) val;
                String display = "";
                Color color = new Color(0, 131, 0);
                switch (row) {
                    case 0: {
                        display = file.getFilename();
                        color = file.isAccepted() ? new Color(0, 131, 0) : Color.RED;
                        this.setText(display);
                        this.setForeground(color);
                    }
                    case 1: {
                        display = file.getReceipient();
                        color = file.isAccepted() ? new Color(0, 131, 0) : Color.RED;
                        this.setText(display);
                        this.setForeground(color);
                    }
                    case 2: {
                        display = file.getProgress() + "";
                        color = file.isAccepted() ? new Color(0, 131, 0) : Color.RED;
                        this.setText(display);
                        this.setForeground(color);
                    }
                    case 3: {
                        display = file.isAccepted() ? "Yes" : "No";
                        color = file.isAccepted() ? new Color(0, 131, 0) : Color.RED;
                        this.setText(display);
                        this.setForeground(color);
                    }
                }
            }
            return this;
        }
    }

    public JButton getNewButton() {
        return newButton;
    }

    public void processBinaryFileSaveReplyPacket(String filename, String from, boolean accepted) {
       // sendFiles.add(new SendFile(filename, from, accepted, 0, "Unknown", sendFiles.size()));
       // sendFilesModel = new SendFilesTableModel(sendFiles);
       // table.setModel(sendFilesModel);
        String status=accepted? "Yes":"No";
        sendFilesModel.setValueAt(status, sendFiles.size() - 1, 3);
        sendFilesModel.setValueAt(this.base.getUser().getFullName(), sendFiles.size() - 1, 1);
        if (accepted) {
            sendFile(filename, sendFiles.size() - 1, from);
        }
        decorateTable();
    }

    public void setProgress(int index, String value) {
        sendFilesModel.setValueAt(value, index, 2);
        sendFiles.elementAt(index).setProgress(value);
    }

    private void sendFile(final String filename, final int index, final String rec) {
        Thread t = new Thread() {

            public void run() {
                engine.readBinaryFile(filename, index, rec);
            }
        };
        t.start();
    }

    class SendFilesTableModel extends AbstractTableModel {

        public SendFilesTableModel() {
        }
        private String[] columnNames = {
            "File",
            "Receiver",
            "Progress",
            "Accepted"
        };
        private Object[][] data = new Object[0][columnNames.length];

        public SendFilesTableModel(Vector<SendFile> files) {
            try {
                data = new Object[files.size()][columnNames.length];
                for (int i = 0; i < files.size(); i++) {
                    SendFile file = sendFiles.elementAt(i);
                    Object[] row = {
                        file.getFilename(),
                        file.getReceipient(),
                        file.getProgress(),
                        file
                    };

                    data[i] = row;
                }
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }

        public int getColumnCount() {
            return columnNames.length;
        }

        public int getRowCount() {
            return data.length;
        }

        @Override
        public String getColumnName(int col) {
            return columnNames[col];
        }

        public Object getValueAt(int row, int col) {
            return data[row][col];

        }

        @Override
        public void setValueAt(Object value, int row, int col) {

            data[row][col] = value;
            fireTableCellUpdated(row, col);
        }

        /*
         * JTable uses this method to determine the default renderer/
         * editor for each cell.  If we didn't implement this method,
         * then the last column would contain text ("true"/"false"),
         * rather than a check box.
         */
        @Override
        public Class getColumnClass(int c) {

            Object obj = getValueAt(0, c);
            if (obj != null) {
                return getValueAt(0, c).getClass();
            } else {
                return new Object().getClass();
            }
        }
    }

    private void processFileToSend(String filename) {
        base.getTcpClient().sendPacket(new BinaryFileSaveRequestPacket(base.getSessionId(), filename, base.getUser().getFullName(), base.getUser().getUserName(),
                true));
        sendFiles.add(new SendFile(filename, "Unknown", false, 0, "Unknown", sendFiles.size()));
        sendFilesModel = new SendFilesTableModel(sendFiles);
        table.setModel(sendFilesModel);

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
        tabbedPane = new javax.swing.JTabbedPane();
        sendFilesPanel = new javax.swing.JPanel();
        sendCPanel = new javax.swing.JPanel();
        newButton = new javax.swing.JButton();

        setPreferredSize(new java.awt.Dimension(113, 40));
        setLayout(new java.awt.BorderLayout());
        add(cPanel, java.awt.BorderLayout.PAGE_START);

        sendFilesPanel.setLayout(new java.awt.BorderLayout());

        newButton.setText("Send File");
        newButton.setEnabled(false);
        newButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                newButtonActionPerformed(evt);
            }
        });
        sendCPanel.add(newButton);

        sendFilesPanel.add(sendCPanel, java.awt.BorderLayout.PAGE_START);

        tabbedPane.addTab("Send Files", sendFilesPanel);

        add(tabbedPane, java.awt.BorderLayout.CENTER);
    }// </editor-fold>//GEN-END:initComponents

private void newButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_newButtonActionPerformed
    if (fc.showOpenDialog(this) == JFileChooser.APPROVE_OPTION) {
        selectedFile = fc.getSelectedFile().getAbsolutePath();
        processFileToSend(selectedFile);
    }
}//GEN-LAST:event_newButtonActionPerformed
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JPanel cPanel;
    private javax.swing.JButton newButton;
    private javax.swing.JPanel sendCPanel;
    private javax.swing.JPanel sendFilesPanel;
    private javax.swing.JTabbedPane tabbedPane;
    // End of variables declaration//GEN-END:variables
}
