/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.userlist;

import java.awt.Color;
import java.awt.Dimension;
import java.awt.Toolkit;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import javax.swing.ImageIcon;
import javax.swing.JTable;
import javax.swing.table.AbstractTableModel;
import javax.swing.table.TableColumn;
import org.avoir.realtime.common.util.ImageUtil;

/**
 *
 * @author david
 */
public class ParticipantListTable extends JTable {

    private ImageIcon micIcon = ImageUtil.createImageIcon(this, "/images/mic_on.png");
    private ArrayList<Map> users = new ArrayList<Map>();
    private ParticipantListTableModel model = new ParticipantListTableModel();
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();

    public ParticipantListTable() {
        model = new ParticipantListTableModel();
        setModel(model);
        setTableHeader(null);
        setShowVerticalLines(false);
        setGridColor(Color.LIGHT_GRAY);
        decorateTable();
    }

    public void clear(){
        users.clear();
    }
    public void setUserHasMIC(String nickname, boolean hasMIC) {
        int index = 0;
        for (Map user : users) {
            String names = (String) user.get("names");

            if (names.equalsIgnoreCase(nickname)) {
                user.put("has_mic", hasMIC ? 1 : 0);
                users.set(index, user);
            }
            index++;
        }
        model = new ParticipantListTableModel();
        setModel(model);
        decorateTable();
    }

    public void addUser(String nickname) {
        Map user = new HashMap();
        user.put("has_mic", 0);
        user.put("names", nickname);
        users.add(user);
        model = new ParticipantListTableModel();
        setModel(model);
        decorateTable();
    }

    public void removeUser(String nickname) {
        int index = 0;
        ArrayList<Integer> toRemove = new ArrayList<Integer>();
        for (Map user : users) {
            String names = (String) user.get("names");
            if (names.equalsIgnoreCase(nickname)) {
                toRemove.add(index);
            }
            index++;
        }

        for (int i : toRemove) {
            users.remove(i);
        }
        model = new ParticipantListTableModel();
        setModel(model);
        decorateTable();
    }

    private void decorateTable() {
        int tableWidth = ss.width / 4;
        TableColumn column = null;
        if (model != null) {
            for (int i = 0; i < model.getColumnCount(); i++) {
                column = getColumnModel().getColumn(i);
                if (i == 0) {
                    column.setPreferredWidth((int) (tableWidth * 0.05));
                } else if (i == 1) {
                    column.setPreferredWidth((int) (tableWidth * 0.05));
                } else {
                    column.setPreferredWidth((int) (tableWidth * 0.9));
                }
            }
        }

    }

    private void removeDuplicates(){
        int index = 0;
        String prev="";
        ArrayList<Integer> toRemove = new ArrayList<Integer>();
        for (Map user : users) {
            String names = (String) user.get("names");
            if (names.equalsIgnoreCase(prev)) {
                toRemove.add(index);

            }
            prev=names;
            index++;
        }

        for (int i : toRemove) {
            users.remove(i);
        }
    }

    class ParticipantListTableModel extends AbstractTableModel {

        private String[] columnNames = {
            "A",
            "M",
            "Names"
        };
        private Object[][] data = new Object[0][columnNames.length];

        public ParticipantListTableModel() {
            removeDuplicates();
            data = new Object[users.size()][columnNames.length];
       
            for (int i = 0; i < users.size(); i++) {
                Map user = users.get(i);
                int hasMIC = 0;
                try {
                    hasMIC = (Integer) user.get("has_mic");
                    
                } catch (NumberFormatException ex) {
                    ex.printStackTrace();
                }
                String names = (String) user.get("names");
                Object[] row = {
                    null,
                    hasMIC == 1 ? micIcon : null,
                    names
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
}
