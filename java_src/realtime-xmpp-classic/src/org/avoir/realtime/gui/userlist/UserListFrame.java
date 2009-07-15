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
 * AdminListFrame.java
 *
 * Created on 2009/04/10, 01:10:43
 */
package org.avoir.realtime.gui.userlist;

import java.awt.Color;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.ListSelectionModel;
import javax.swing.SwingUtilities;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;
import javax.swing.table.AbstractTableModel;
import javax.swing.table.TableColumn;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.avoir.realtime.net.providers.RealtimePacketProcessor;
import org.jivesoftware.smack.PacketCollector;
import org.jivesoftware.smack.SmackConfiguration;
import org.jivesoftware.smack.XMPPException;
import org.jivesoftware.smack.filter.PacketIDFilter;

/**
 *
 * @author developer
 */
public class UserListFrame extends javax.swing.JFrame {

    private ArrayList<Map> users = new ArrayList<Map>();
    private UserListingTableModel model = new UserListingTableModel();
    private JTable table = new JTable(model);
    private int selectedRow = 0;

    /** Creates new form AdminListFrame */
    public UserListFrame() {
        initComponents();
        JScrollPane sp = new JScrollPane(table);
        sp.getViewport().setBackground(Color.WHITE);
        mPanel.add(sp);
        table.setGridColor(new Color(238, 238, 238));
        table.setShowHorizontalLines(false);
        ListSelectionModel listModel = table.getSelectionModel();
        listModel.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        listModel.addListSelectionListener(new ListSelectionListener() {

            public void valueChanged(ListSelectionEvent e) {
                ListSelectionModel lsm = (ListSelectionModel) e.getSource();
                if (lsm.isSelectionEmpty()) {
                } else {
                    selectedRow = lsm.getMinSelectionIndex();
                }
            }
        });
        decorateTable();
        initPopulateUsers();
    }

    private void initPopulateUsers() {
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                populateUsers();
            }
        });
    }

    private void populateUsers() {
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.REQUEST_USER_PROPERTIES);
        ConnectionManager.getConnection().sendPacket(p);
    }

    public void setUsers(ArrayList<Map> users) {
        this.users = users;
        model = new UserListingTableModel();
        table.setModel(model);
        decorateTable();
    }

    private void decorateTable() {

        TableColumn column = null;
        if (model != null) {
            for (int i = 0; i < model.getColumnCount(); i++) {
                column = table.getColumnModel().getColumn(i);
                if (i == 1) {
                    column.setPreferredWidth(280);
                } else if (i == 2) {
                    column.setPreferredWidth(20);
                } else {
                    column.setPreferredWidth(120);
                }
            }
        }

    }

    class UserListingTableModel extends AbstractTableModel {

        private String[] columnNames = {
            "Username",
            "Name",
            "Instructor"
        };
        private Object[][] data = new Object[0][columnNames.length];

        public UserListingTableModel() {
            data = new Object[users.size()][columnNames.length];

            for (int i = 0; i < users.size(); i++) {
                Map user = users.get(i);
                Object[] row = {
                    user.get("userid"), user.get("username"), new Boolean(user.get("instructor").toString())
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

    private void updateInstructorList() {

        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.UPDATE_USER_PROPERTIES);
        StringBuilder buf = new StringBuilder();
        buf.append("<content>");
        for (int i = 0; i < model.getRowCount(); i++) {
            Boolean selection = (Boolean) model.getValueAt(i, 2);
            buf.append("<user>");
            buf.append("<userid>").append(model.getValueAt(i, 0)).append("</userid>");
            buf.append("<instructor>").append(selection.toString()).append("</instructor>");
            buf.append("</user>");
        }
        buf.append("</content>");
        p.setContent(buf.toString());
        ConnectionManager.getConnection().sendPacket(p);

    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        topPanel = new javax.swing.JPanel();
        southPanel = new javax.swing.JPanel();
        saveButton = new javax.swing.JButton();
        closeButton = new javax.swing.JButton();
        mPanel = new javax.swing.JPanel();

        setDefaultCloseOperation(javax.swing.WindowConstants.DISPOSE_ON_CLOSE);
        setTitle("Admin Users");
        getContentPane().add(topPanel, java.awt.BorderLayout.PAGE_START);

        saveButton.setText("Save");
        saveButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                saveButtonActionPerformed(evt);
            }
        });
        southPanel.add(saveButton);

        closeButton.setText("Close");
        closeButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                closeButtonActionPerformed(evt);
            }
        });
        southPanel.add(closeButton);

        getContentPane().add(southPanel, java.awt.BorderLayout.PAGE_END);

        mPanel.setLayout(new java.awt.BorderLayout());
        getContentPane().add(mPanel, java.awt.BorderLayout.CENTER);

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void closeButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_closeButtonActionPerformed
        dispose();
    }//GEN-LAST:event_closeButtonActionPerformed

    private void saveButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_saveButtonActionPerformed
        updateInstructorList();
        dispose();
    }//GEN-LAST:event_saveButtonActionPerformed

    /**
     * @param args the command line arguments
     */
    public static void main(String args[]) {
        java.awt.EventQueue.invokeLater(new Runnable() {

            public void run() {
                new UserListFrame().setVisible(true);
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton closeButton;
    private javax.swing.JPanel mPanel;
    private javax.swing.JButton saveButton;
    private javax.swing.JPanel southPanel;
    private javax.swing.JPanel topPanel;
    // End of variables declaration//GEN-END:variables
}
