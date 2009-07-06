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
 * RoomListFrame.java
 *
 * Created on 2009/04/04, 11:59:57
 */
package org.avoir.realtime.gui.room;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Toolkit;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.util.ArrayList;
import java.util.Map;
import javax.swing.JOptionPane;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.ListSelectionModel;
import javax.swing.SwingUtilities;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;
import javax.swing.table.AbstractTableModel;
import javax.swing.table.TableColumn;
import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.avoir.realtime.startup.Startup;

/**
 *
 * @author developer
 */
public class RoomListFrame extends javax.swing.JDialog {

    private ArrayList<Map> rooms = new ArrayList<Map>();
    private RoomListingTableModel model = new RoomListingTableModel();
    private JTable table = new JTable();
    private int selectedRow = 0;
    public static ArrayList<String> roomList = new ArrayList<String>();
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private InviteParticipants inviteParticipants;

    public RoomListFrame(InviteParticipants inviteParticipants) {
        this();
        this.inviteParticipants = inviteParticipants;

    }

    public void setInviteParticipants(InviteParticipants inviteParticipants) {
        this.inviteParticipants = inviteParticipants;
    }

    /** Creates new form RoomListFrame */
    public RoomListFrame() {

        initComponents();
        table.setModel(model);

        table.setGridColor(new Color(238, 238, 238));
        //table.setShowHorizontalLines(false);
        table.setShowGrid(false);
        JScrollPane sp = new JScrollPane(table);
        sp.getViewport().setBackground(Color.WHITE);
        mPanel.add(sp, BorderLayout.CENTER);
        decorateTable();


        ListSelectionModel listSelectionModel = table.getSelectionModel();
        listSelectionModel.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        listSelectionModel.addListSelectionListener(new ListSelectionListener() {

            public void valueChanged(ListSelectionEvent e) {
                ListSelectionModel lsm = (ListSelectionModel) e.getSource();

                if (lsm.isSelectionEmpty()) {
                    joinButton.setEnabled(false);
                    selectButton.setEnabled(false);
                    deleteButton.setEnabled(false);
                } else {
                    selectedRow = lsm.getMinSelectionIndex();
                    boolean state1 = inviteParticipants == null ? true : false;
                    boolean state2 = inviteParticipants != null ? true : false;
                    joinButton.setEnabled(state1);
                    deleteButton.setEnabled(state1);
                    selectButton.setEnabled(state2);
                }
            }
        });
        table.addMouseListener(new MouseAdapter() {

            public void mousePressed(MouseEvent evt) {
                if (evt.getClickCount() == 2 && selectedRow > 0) {
                    Map map = rooms.get(selectedRow);
                    String jid = (String) map.get("room-jid");
                    String name = (String) map.get("room-name");
                    ChatRoomManager.oldRoom = ConnectionManager.getRoomName();

                    GUIAccessManager.mf.getChatRoomManager().joinAsParticipant(ConnectionManager.getUsername(), name.trim());
                    dispose();
                }
            }
        });

    }

    public void setRooms(ArrayList<Map> rooms) {
        this.rooms = rooms;
        model = new RoomListingTableModel();
        table.setModel(model);
        decorateTable();
    }

    public void requestRoomList() {
        RealtimePacket p = new RealtimePacket(RealtimePacket.Mode.REQUEST_ROOM_LIST);
        StringBuilder sb = new StringBuilder();
        sb.append("<room-owner>").append(ConnectionManager.getUsername()).append("</room-owner>");
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);
    }

    public void populateRooms(final ArrayList<Map> mapList) {

        roomList.clear();
        rooms.clear();
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                for (Map map : mapList) {
                    rooms.add(map);
                }
                model = new RoomListingTableModel();
                table.setModel(model);
                decorateTable();

            }
        });
    }

    private void decorateTable() {

        TableColumn column = null;
        if (model != null) {
            for (int i = 0; i < model.getColumnCount(); i++) {
                column = table.getColumnModel().getColumn(i);
                if (i == 0) {
                    column.setPreferredWidth((int) (getWidth() * .375));
                } else if (i == 1) {
                    column.setPreferredWidth((int) (getWidth() * 0.5));
                } else {
                    column.setPreferredWidth((int) (getWidth() * 0.125));
                }
            }
        }

    }

    private void doActualjoin(String jid, String roomName) {
        if (GUIAccessManager.mf.getChatRoomManager().doActualJoin(ConnectionManager.getUsername(),
                roomName, true)) {
            dispose();
        }
    }

    class RoomListingTableModel extends AbstractTableModel {

        private String[] columnNames = {
            "Room Name",
            "Description",
            "Type"
        };
        private Object[][] data = new Object[0][columnNames.length];

        public RoomListingTableModel() {
            data = new Object[rooms.size()][columnNames.length];

            for (int i = 0; i < rooms.size(); i++) {
                Map room = rooms.get(i);
                Object[] row = {
                    room.get("room-name"),
                    room.get("room-desc"),
                    room.get("room-type")
                };
                data[i] = row;
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

        @Override
        public boolean isCellEditable(int rowIndex, int columnIndex) {
            if (columnIndex == 2) {
                return true;
            }
            return false;
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

    private void deleteRoom() {
        Map map = rooms.get(selectedRow);
        String jid = (String) map.get("room-jid");
        String roomName = (String) map.get("room-name");
        if (Startup.realtimeFxInt.getChatRoomManager().destroyRoom(roomName)) {
            requestRoomList();
        } else {
            JOptionPane.showMessageDialog(null, "Unable to delete room. Are you the owner?");
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

        jPanel1 = new javax.swing.JPanel();
        jLabel1 = new javax.swing.JLabel();
        jPanel2 = new javax.swing.JPanel();
        selectButton = new javax.swing.JButton();
        joinButton = new javax.swing.JButton();
        createRoomButton = new javax.swing.JButton();
        deleteButton = new javax.swing.JButton();
        cancelButton = new javax.swing.JButton();
        mPanel = new javax.swing.JPanel();

        setDefaultCloseOperation(javax.swing.WindowConstants.DISPOSE_ON_CLOSE);
        setTitle("Join Room");

        jLabel1.setText("Select Room ");
        jPanel1.add(jLabel1);

        getContentPane().add(jPanel1, java.awt.BorderLayout.PAGE_START);

        selectButton.setText("Select");
        selectButton.setEnabled(false);
        selectButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                selectButtonActionPerformed(evt);
            }
        });
        jPanel2.add(selectButton);

        joinButton.setText("Join");
        joinButton.setEnabled(false);
        joinButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                joinButtonActionPerformed(evt);
            }
        });
        jPanel2.add(joinButton);

        createRoomButton.setText("Create Room");
        createRoomButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                createRoomButtonActionPerformed(evt);
            }
        });
        jPanel2.add(createRoomButton);

        deleteButton.setText("Delete");
        deleteButton.setEnabled(false);
        deleteButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                deleteButtonActionPerformed(evt);
            }
        });
        jPanel2.add(deleteButton);

        cancelButton.setText("Close");
        cancelButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                cancelButtonActionPerformed(evt);
            }
        });
        jPanel2.add(cancelButton);

        getContentPane().add(jPanel2, java.awt.BorderLayout.PAGE_END);

        mPanel.setLayout(new java.awt.BorderLayout());
        getContentPane().add(mPanel, java.awt.BorderLayout.CENTER);

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void cancelButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_cancelButtonActionPerformed
        dispose();
    }//GEN-LAST:event_cancelButtonActionPerformed

    private void joinButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_joinButtonActionPerformed
        Map map = rooms.get(selectedRow);
        String jid = (String) map.get("room-jid");
        String name = (String) map.get("room-name");
        //ChatRoomManager.oldRoom = ConnectionManager.getRoomName();
        Startup.realtimeFxInt.getChatRoomManager().joinAsParticipant(ConnectionManager.getUsername(), name.trim());
        dispose();
    // doActualjoin(jid, name);
    }//GEN-LAST:event_joinButtonActionPerformed

    private void createRoomButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_createRoomButtonActionPerformed
        CreateRoomDialog fr = new CreateRoomDialog(GUIAccessManager.mf);
        fr.setSize((ss.width / 2) * 1, (ss.height / 2) * 1);
        fr.setLocationRelativeTo(this);
        fr.setVisible(true);
    }//GEN-LAST:event_createRoomButtonActionPerformed

    private void selectButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_selectButtonActionPerformed
        if (inviteParticipants != null) {
            String selectedRoomName = (String) model.getValueAt(selectedRow, 0);
            inviteParticipants.getRoomNameField().setText(selectedRoomName);
            inviteParticipants.getRoomDescField().setText("" + model.getValueAt(selectedRow, 1));
            dispose();
        }
    }//GEN-LAST:event_selectButtonActionPerformed

    private void deleteButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_deleteButtonActionPerformed
        deleteRoom();
    }//GEN-LAST:event_deleteButtonActionPerformed

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton cancelButton;
    private javax.swing.JButton createRoomButton;
    private javax.swing.JButton deleteButton;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JButton joinButton;
    private javax.swing.JPanel mPanel;
    private javax.swing.JButton selectButton;
    // End of variables declaration//GEN-END:variables
}
