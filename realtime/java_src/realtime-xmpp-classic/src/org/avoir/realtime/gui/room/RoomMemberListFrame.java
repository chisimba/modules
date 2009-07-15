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
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.common.util.RealtimePacketContent;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.MainFrame;
import org.avoir.realtime.gui.main.StandAloneManager;
import org.avoir.realtime.gui.search.UserSearch;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;

/**
 *
 * @author developer
 */
public class RoomMemberListFrame extends javax.swing.JFrame {

    private ArrayList<Map> members = new ArrayList<Map>();
    private RoomListingTableModel model = new RoomListingTableModel();
    private JTable table = new JTable();
    private int selectedRow = 0;
    public static ArrayList<String> memberList = new ArrayList<String>();
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();

    /** Creates new form RoomListFrame */
    public RoomMemberListFrame(java.awt.Frame parent, boolean modal) {
//        super(parent, modal);
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
                   // addButton.setEnabled(false);
                    deleteButton.setEnabled(false);
                } else {
                    selectedRow = lsm.getMinSelectionIndex();
                   // addButton.setEnabled(true);
                    deleteButton.setEnabled(true);
                }
            }
        });
        table.addMouseListener(new MouseAdapter() {

            public void mousePressed(MouseEvent evt) {
                if (evt.getClickCount() == 2 && selectedRow > 0) {
                }
            }
        });

    }

    public void setMembers(ArrayList<Map> members) {
        this.members = members;
        model = new RoomListingTableModel();
        table.setModel(model);
        decorateTable();
    }

    public void requestRoomMemberList() {
        RealtimePacket p = new RealtimePacket(RealtimePacket.Mode.REQUEST_ROOM_MEMBERS);
        StringBuilder sb = new StringBuilder();
        sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);
    }

    public void populateRooms(final ArrayList<Map> mapList) {
        memberList.clear();
        members.clear();
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                for (Map map : mapList) {
                    members.add(map);
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

    class RoomListingTableModel extends AbstractTableModel {

        private String[] columnNames = {
            "Username",
            "Name"
        };
        private Object[][] data = new Object[0][columnNames.length];

        public RoomListingTableModel() {
            data = new Object[members.size()][columnNames.length];

            for (int i = 0; i < members.size(); i++) {
                Map room = members.get(i);
                Object[] row = {
                    room.get("username"),
                    room.get("name"),};
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
        deleteButton = new javax.swing.JButton();
        addButton = new javax.swing.JButton();
        cancelButton = new javax.swing.JButton();
        mPanel = new javax.swing.JPanel();

        setDefaultCloseOperation(javax.swing.WindowConstants.DISPOSE_ON_CLOSE);
        setTitle("Room Members");

        jLabel1.setText("Room Members");
        jPanel1.add(jLabel1);

        getContentPane().add(jPanel1, java.awt.BorderLayout.PAGE_START);

        deleteButton.setText("Delete");
        deleteButton.setEnabled(false);
        deleteButton.setName("deleteButton"); // NOI18N
        deleteButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                deleteButtonActionPerformed(evt);
            }
        });
        jPanel2.add(deleteButton);

        addButton.setText("Add");
        addButton.setEnabled(false);
        addButton.setName("addButton"); // NOI18N
        addButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                addButtonActionPerformed(evt);
            }
        });
        jPanel2.add(addButton);

        cancelButton.setText("Close");
        cancelButton.setName("closeButton"); // NOI18N
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

    private void addButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_addButtonActionPerformed
        UserSearch userSearch = new UserSearch(this);
        userSearch.setLocationRelativeTo(GUIAccessManager.mf);
        userSearch.setVisible(true);
}//GEN-LAST:event_addButtonActionPerformed

    private void deleteMember() {
        String username=(String)table.getValueAt(selectedRow, 0);
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.DELETE_ROOM_MEMBER);
        RealtimePacketContent realtimePacketContent = new RealtimePacketContent();
        realtimePacketContent.addTag("room-name", ConnectionManager.getRoomName());
        realtimePacketContent.addTag("username",username);
        realtimePacketContent.addTag("room-owner", GeneralUtil.getThisRoomOwner());
        p.setContent(realtimePacketContent.toString());
        ConnectionManager.sendPacket(p);
    }

    private void deleteButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_deleteButtonActionPerformed
        int n = JOptionPane.showConfirmDialog(null, "You sure you want to delete this member?",
                "Delete", JOptionPane.YES_OPTION);
        if (n == JOptionPane.YES_OPTION) {
            deleteMember();
        }
}//GEN-LAST:event_deleteButtonActionPerformed

    /**
     * @param args the command line arguments
     */
    public static void main(String xargs[]) {
        final String[] args = {"localhost", "5222", "localhost"};
        java.awt.EventQueue.invokeLater(new Runnable() {

            public void run() {
                StandAloneManager.isAdmin = true;
                ConnectionManager.audioVideoUrlReady = true;
                ConnectionManager.flashUrlReady = true;
                //NativeInterface.open();

                if (ConnectionManager.init(args[0], Integer.parseInt(args[1]), args[2])) {
                    String roomName = "dwaf_default";
                    String username = "dwaf";
                    String  password="a";
                    if (ConnectionManager.login(username,password, roomName)) {
                        MainFrame fr = new MainFrame(roomName);
                        //  fr.setTitle(username + "@" + roomName + ": Realtime Virtual Classroom");
                        fr.setSize(Toolkit.getDefaultToolkit().getScreenSize());
                        //  fr.setVisible(true);
                        GUIAccessManager.mf = fr;


                        RoomMemberListFrame dialog = new RoomMemberListFrame(fr, true);
                        GUIAccessManager.mf.setRoomMemberListFrame(dialog);
                        dialog.addWindowListener(new java.awt.event.WindowAdapter() {

                            @Override
                            public void windowClosing(java.awt.event.WindowEvent e) {
                                System.exit(0);
                            }
                        });
                        dialog.requestRoomMemberList();
                        dialog.setSize(400, 300);
                        dialog.setVisible(true);
                        System.out.println("dialog open");
                    } else {
                        System.out.println("cant login");
                    }
                } else {
                    System.out.println("cant connect to server");
                }


            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton addButton;
    private javax.swing.JButton cancelButton;
    private javax.swing.JButton deleteButton;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JPanel mPanel;
    // End of variables declaration//GEN-END:variables
}
