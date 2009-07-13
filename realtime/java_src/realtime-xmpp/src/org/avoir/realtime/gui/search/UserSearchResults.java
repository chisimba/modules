/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.search;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.util.ArrayList;
import java.util.Iterator;
import javax.swing.BorderFactory;
import javax.swing.JButton;
import javax.swing.JDialog;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.JTextField;
import javax.swing.ListSelectionModel;
import javax.swing.event.DocumentEvent;
import javax.swing.event.DocumentListener;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;
import javax.swing.table.AbstractTableModel;
import javax.swing.table.DefaultTableCellRenderer;
import javax.swing.table.TableColumn;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.gui.room.InviteParticipants;
import org.avoir.realtime.gui.room.RoomMemberListFrame;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.jivesoftware.smackx.ReportedData.Row;

/**
 *
 * @author david
 */
public class UserSearchResults extends JDialog implements ActionListener {

    BorderLayout borderLayout1 = new BorderLayout();
    JPanel cPanel = new JPanel();
    JButton insertButton = new JButton("Insert");
    JButton addToRoomButton = new JButton("Add to Room");
    JButton closeButton = new JButton("Cancel");
    JScrollPane jScrollPane1 = new JScrollPane();
    UserListTableModel model;
    JTable table;
    Dimension screenSize = Toolkit.getDefaultToolkit().getScreenSize();
    JTextField searchField = new JTextField();
    int width = screenSize.width;
    int height = screenSize.height;
    int selectedRow = -1;
    ArrayList<Row> tableRows = new ArrayList<Row>();
    ArrayList<String> colHeaders = new ArrayList<String>();
    Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private InviteParticipants inviteParticipants;
    private RoomMemberListFrame roomMemberListFrame;

    public UserSearchResults(InviteParticipants inviteParticipants, ArrayList<Row> tableRows, ArrayList<String> colHeaders) {
        super(inviteParticipants);
        setTitle("User Search Results");
        this.tableRows = tableRows;
        this.colHeaders = colHeaders;
        this.inviteParticipants = inviteParticipants;
        insertButton.setEnabled(false);
        addToRoomButton.setEnabled(false);
        addToRoomButton.setName("addToRoom");
        try {

            jbInit();
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    public UserSearchResults(RoomMemberListFrame roomMemberListFrame, ArrayList<Row> tableRows, ArrayList<String> colHeaders) {
        this.tableRows = tableRows;
        this.colHeaders = colHeaders;
        this.roomMemberListFrame = roomMemberListFrame;
        setTitle("Search Results");
        try {

            jbInit();
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    public UserSearchResults() {

        try {

            jbInit();

        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    void jbInit() throws Exception {
        model = new UserListTableModel();
        table = new JTable(model);
        table.setGridColor(Color.LIGHT_GRAY);
        table.setShowHorizontalLines(false);
        insertButton.setEnabled(false);
        addToRoomButton.setEnabled(false);
        decorateTable();
        this.getContentPane().setLayout(borderLayout1);
        cPanel.setBorder(BorderFactory.createEtchedBorder());


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
                    insertButton.setEnabled(false);
                    addToRoomButton.setEnabled(false);
                } else {
                    selectedRow = lsm.getMinSelectionIndex();
                    if (inviteParticipants != null) {
                        insertButton.setEnabled(true);
                        addToRoomButton.setEnabled(false);
                    } else {
                        insertButton.setEnabled(false);
                        addToRoomButton.setEnabled(true);
                    }
                }
            }
        });
        table.addMouseListener(new MouseAdapter() {

            public void mouseClicked(MouseEvent e) {
                if (e.getClickCount() == 2) {
                }
            }
        });

        cPanel.add(insertButton);
        cPanel.add(addToRoomButton);
        cPanel.add(closeButton);


        insertButton.setActionCommand("insert");
        insertButton.addActionListener(this);

        addToRoomButton.setActionCommand("addtoroom");
        addToRoomButton.addActionListener(this);

        closeButton.setActionCommand("close");
        closeButton.addActionListener(this);

        this.getContentPane().add(cPanel, BorderLayout.SOUTH);
        searchField.setPreferredSize(new Dimension(200, 21));
        searchField.getDocument().addDocumentListener(new DocumentListener() {

            public void removeUpdate(DocumentEvent e) {
            }

            public void changedUpdate(DocumentEvent e) {
            }

            public void insertUpdate(DocumentEvent e) {
            }
        });

        this.getContentPane().add(jScrollPane1, BorderLayout.CENTER);
        jScrollPane1.getViewport().add(table, null);
        setSize((ss.width / 4) * 3, (ss.height / 4) * 3);
    }

    public void actionPerformed(ActionEvent e) {
        if (e.getActionCommand().equals("close")) {
            dispose();
        }

        if (e.getActionCommand().equals("insert")) {
            if (inviteParticipants != null) {
                for (int i = 0; i < model.getRowCount(); i++) {
                    try {
                        Boolean selected = (Boolean) model.getValueAt(i, 3);
                        if (selected.booleanValue()) {
                            inviteParticipants.getEmailField().append(model.getValueAt(i, 2) + ";");
                        }
                    } catch (Exception ex) {
                        ex.printStackTrace();
                    }
                }

            }
            dispose();
        }
        if (e.getActionCommand().equals("addtoroom")) {

            for (int i = 0; i < model.getRowCount(); i++) {
                try {
                    Boolean selected = (Boolean) model.getValueAt(i, 3);
                    if (selected.booleanValue()) {
                        RealtimePacket p = new RealtimePacket();
                        p.setMode(RealtimePacket.Mode.ADD_ROOM_MEMBER);
                        StringBuilder sb = new StringBuilder();
                        sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
                        sb.append("<username>").append(model.getValueAt(i, 0)).append("</username>");
                        sb.append("<names>").append(model.getValueAt(i, 1)).append("</names>");
                        sb.append("<room-owner>").append(GeneralUtil.getThisRoomOwner()).append("</room-owner>");
                        p.setContent(sb.toString());
                        ConnectionManager.sendPacket(p);
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            if (roomMemberListFrame != null) {
                roomMemberListFrame.requestRoomMemberList();
            }
            dispose();
        }
    }

    public void refreshTable() {
        model = new UserListTableModel();
        table.setModel(model);
        decorateTable();
    }

    private void decorateTable() {
        TableColumn column = null;
        for (int i = 0; i < 2; i++) {
            column = table.getColumnModel().getColumn(i);
            if (i == 1) {
                column.setPreferredWidth(250); //desc column is bigger
                DefaultTableCellRenderer renderer =
                        new DefaultTableCellRenderer();
                renderer.setForeground(new Color(0, 131, 1));
                column.setCellRenderer(renderer);

            } else {
                column.setPreferredWidth(25);
                DefaultTableCellRenderer renderer =
                        new DefaultTableCellRenderer();
                renderer.setForeground(Color.red);
                column.setCellRenderer(renderer);
            }
        }

    }

    class UserListTableModel extends AbstractTableModel {

        private String[] columnNames = {"Username", "Name", "Email", "Select"};

        public UserListTableModel() {
            try {

                data = new Object[tableRows.size()][columnNames.length];

                for (int i = 0; i < tableRows.size(); i++) {
                    Row tableRow = tableRows.get(i);
                    Object[] row = new Object[4];
                    Iterator usernameI = tableRow.getValues("username");
                    if (usernameI != null) {
                        if (usernameI.hasNext()) {
                            row[0] = (usernameI.next());
                        }
                    }


                    Iterator nameI = tableRow.getValues("name");
                    if (nameI != null) {
                        if (nameI.hasNext()) {
                            row[1] = (nameI.next());
                        }
                    }
                    Iterator emailI = tableRow.getValues("email");
                    if (emailI != null) {
                        if (emailI.hasNext()) {
                            row[2] = (emailI.next());
                        }
                    }
                    row[3] = new Boolean("false");
                    data[i] = row;
                }

            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        private Object[][] data = new Object[0][4];

        public int getColumnCount() {
            return columnNames.length;
        }

        public int getRowCount() {
            return data.length;
        }

        public Class getColumnClass(int c) {
            return getValueAt(0, c).getClass();
        }

        public String getColumnName(int col) {
            return columnNames[col];
        }

        public Object getValueAt(int row, int col) {
            return data[row][col];

        }

        public void setValueAt(Object value, int row, int col) {
            try {
                data[row][col] = value;
                fireTableCellUpdated(row, col);

            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }

        public boolean isCellEditable(int row, int col) {
            if (col == 3) {
                return true;
            } else {
                return false;
            }
        }
    }
}
