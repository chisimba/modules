/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.userlist;

import java.awt.Color;
import java.awt.Dimension;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import javax.swing.ImageIcon;
import javax.swing.JMenuItem;
import javax.swing.JPopupMenu;
import javax.swing.JTable;
import javax.swing.ListSelectionModel;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;
import javax.swing.table.AbstractTableModel;
import javax.swing.table.TableColumn;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.common.util.RealtimePacketContent;
import org.avoir.realtime.gui.main.StandAloneManager;
import org.avoir.realtime.gui.main.WebPresentManager;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;

/**
 *
 * @author david
 */
public class ParticipantListTable extends JTable implements ActionListener {

    private ImageIcon micIcon = ImageUtil.createImageIcon(this, "/images/mic_on.png");
    private ArrayList<Map> users = new ArrayList<Map>();
    private ParticipantListTableModel model = new ParticipantListTableModel();
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private JPopupMenu popup = new JPopupMenu();
    private JMenuItem acceptMenuItem = new JMenuItem("Accept");
    private JMenuItem profileMenuItem = new JMenuItem("Profile");
    private JMenuItem denyMenuItem = new JMenuItem("Deny");
    private JMenuItem removeMenuItem = new JMenuItem("Remove");
    private JMenuItem giveMICMenuItem = new JMenuItem("Give MIC");
    private JMenuItem takeMICMenuItem = new JMenuItem("Take MIC");
    private JMenuItem kickoutMenuItem = new JMenuItem("Kick Out");
    private JMenuItem banMenuItem = new JMenuItem("Ban");
    private JMenuItem privateChatMenuItem = new JMenuItem("Private Chat");
    private int selectedRow = -1;

    public ParticipantListTable() {
        model = new ParticipantListTableModel();
        setModel(model);
        setTableHeader(null);
        setShowVerticalLines(false);
        setGridColor(Color.LIGHT_GRAY);


        ListSelectionModel listSelectionModel = getSelectionModel();
        listSelectionModel.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        listSelectionModel.addListSelectionListener(new ListSelectionListener() {

            public void valueChanged(ListSelectionEvent e) {
                ListSelectionModel lsm = (ListSelectionModel) e.getSource();

                if (lsm.isSelectionEmpty()) {
                } else {
                    selectedRow = lsm.getMinSelectionIndex();

                }
            }
        });
        addMouseListener(new MouseAdapter() {

            public void mousePressed(MouseEvent evt) {
                if (evt.getButton() == MouseEvent.BUTTON3) {
                    if (WebPresentManager.isPresenter || StandAloneManager.isAdmin) {
                        String name = (String) model.getValueAt(selectedRow, 2);
                        takeMICMenuItem.setEnabled(enableTakeMic(name));
                        giveMICMenuItem.setEnabled(enableGiveMic(name));
                        kickoutMenuItem.setEnabled(!isMe(name));
                        banMenuItem.setEnabled(!isMe(name));
                        privateChatMenuItem.setEnabled(false);//!isMe(label.getText()));
                        popup.show(ParticipantListTable.this, evt.getX(), evt.getY());
                    }
                }
                if (evt.getClickCount() == 2 && selectedRow > 0) {
                }
            }
        });

        acceptMenuItem.setEnabled(false);
        denyMenuItem.setEnabled(false);
        removeMenuItem.setEnabled(false);
        profileMenuItem.setEnabled(false);

        acceptMenuItem.addActionListener(this);
        acceptMenuItem.setActionCommand("accept");

        denyMenuItem.addActionListener(this);
        denyMenuItem.setActionCommand("deny");

        removeMenuItem.addActionListener(this);
        removeMenuItem.setActionCommand("remove");

        profileMenuItem.addActionListener(this);
        profileMenuItem.setActionCommand("profile");

        privateChatMenuItem.addActionListener(this);
        privateChatMenuItem.setActionCommand("privatechat");

        giveMICMenuItem.addActionListener(this);
        giveMICMenuItem.setActionCommand("give-mic");
        takeMICMenuItem.addActionListener(this);
        takeMICMenuItem.setActionCommand("take-mic");
        kickoutMenuItem.addActionListener(this);
        kickoutMenuItem.setActionCommand("kick-out");
        banMenuItem.addActionListener(this);
        banMenuItem.setActionCommand("ban");

        popup.add(giveMICMenuItem);
        popup.add(takeMICMenuItem);
        popup.addSeparator();
        popup.add(kickoutMenuItem);
        popup.add(banMenuItem);
        popup.addSeparator();
        popup.add(privateChatMenuItem);

        decorateTable();
    }

    public void actionPerformed(ActionEvent e) {
        if (e.getActionCommand().equals("privatechat")) {
        }
        if (e.getActionCommand().equals("ban")) {
            //kickOut(true);
        }
        if (e.getActionCommand().equals("kick-out")) {
            //kickOut(false);
        }
        if (e.getActionCommand().equals("take-mic")) {
            StringBuilder sb = new StringBuilder();
            String to = (String) model.getValueAt(selectedRow, model.getColumnCount() - 1);
            sb.append("<recipient>").append(to).append("</recipient>");
            sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.TAKE_MIC);
            p.setContent(sb.toString());
            ConnectionManager.sendPacket(p);

        }
        if (e.getActionCommand().equals("give-mic")) {
            Map user = users.get(selectedRow);
            String username = (String) user.get("username");
            String names = (String) user.get("names");
            giveMic(username, names);
        }


    }

    public void giveMic(String username, String name) {

        RealtimePacketContent realtimePacketContent = new RealtimePacketContent();
        realtimePacketContent.addTag("recipient-username", username);
        realtimePacketContent.addTag("recipient-name", name);
        realtimePacketContent.addTag("room-name", ConnectionManager.getUsername());
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.GIVE_MIC);
        p.setContent(realtimePacketContent.toString());
        ConnectionManager.sendPacket(p);
    }

    private boolean enableTakeMic(String username) {
        ImageIcon icon = (ImageIcon) model.getValueAt(selectedRow, 1);
        return icon == micIcon;
    }

    private boolean enableGiveMic(String username) {
        ImageIcon icon = (ImageIcon) model.getValueAt(selectedRow, 1);
        return icon != micIcon;

    }

    private boolean isMe(String to) {
        return to.trim().equalsIgnoreCase(ConnectionManager.fullnames.trim());
    }

    public void clear() {
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

    public void addUser(String name) {
        String username = name.split(":")[0];
        String nickname = name.split(":")[1];
        Map user = new HashMap();
        user.put("has_mic", 0);
        user.put("names", nickname);
        user.put("username", username);
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
            String username = (String) user.get("username");
            if ((username + ":" + names).equalsIgnoreCase(nickname)) {
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

    private void removeDuplicates() {
        int index = 0;
        String prev = "";
        ArrayList<Integer> toRemove = new ArrayList<Integer>();
        for (Map user : users) {
            String names = (String) user.get("names");
            if (names.equalsIgnoreCase(prev)) {
                toRemove.add(index);

            }
            prev = names;
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
