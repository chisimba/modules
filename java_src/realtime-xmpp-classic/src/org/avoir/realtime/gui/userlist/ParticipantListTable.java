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
import javax.swing.JOptionPane;
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
import org.avoir.realtime.common.Constants.*;
import org.avoir.realtime.gui.main.GUIAccessManager;

/**
 *
 * @author david
 */
public class ParticipantListTable extends JTable implements ActionListener {

    private ImageIcon micIcon = ImageUtil.createImageIcon(this, "/images/mic_on.png");
    private ArrayList<Map> users = new ArrayList<Map>();
    private ParticipantListTableModel model;
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
    private JMenuItem makeAdminMenuItem = new JMenuItem("Make Admin");
    private JMenuItem removeAdminMenuItem = new JMenuItem("Remove Admin Status");
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
                    Map user = users.get(selectedRow);
                    System.out.println(ConnectionManager.isOwner + ", " + ConnectionManager.isAdmin);
                    if (ConnectionManager.isOwner || ConnectionManager.isAdmin) {
                    //if(WebPresentManager.isPresenter || StandAloneManager.isAdmin){
                        String name = (String) model.getValueAt(selectedRow, 2);
                        takeMICMenuItem.setEnabled(enableTakeMic(name));
                        giveMICMenuItem.setEnabled(enableGiveMic(name));
                        kickoutMenuItem.setEnabled(!isMe((String)user.get("username")));
                        banMenuItem.setEnabled(!isMe((String)user.get("username")));
                        privateChatMenuItem.setEnabled(false);//!isMe(label.getText()));
                        makeAdminMenuItem.setEnabled(false);
                        removeAdminMenuItem.setEnabled(false);
                        if (ConnectionManager.isOwner) {
                            int access = (Integer) user.get("access_level");
                            makeAdminMenuItem.setEnabled(access != 1);
                            removeAdminMenuItem.setEnabled(!makeAdminMenuItem.isEnabled());
                        }

                        popup.show(ParticipantListTable.this, evt.getX(), evt.getY());
                    }

                /*if (ConnectionManager.isOwner) {
                int access = (Integer) user.get("accessLevel");
                makeAdminMenuItem.setEnabled(access != 1);
                removeAdminMenuItem.setEnabled(!makeAdminMenuItem.isSelected());

                if ((Integer)user.get("accessLevel") != 0) {
                if ((Integer)user.get("accessLevel") == 1) {
                removeAdminMenuItem.setEnabled(true);
                }
                else {
                makeAdminMenuItem.setEnabled(true);
                }
                }
                }*/
                }
                if (evt.getClickCount() == 2 && selectedRow > 0) {
                }
            }
        });

        removeAdminMenuItem.setEnabled(false);
        makeAdminMenuItem.setEnabled(false);
        acceptMenuItem.setEnabled(false);
        denyMenuItem.setEnabled(false);
        removeMenuItem.setEnabled(false);
        profileMenuItem.setEnabled(false);

        removeAdminMenuItem.addActionListener(this);
        removeAdminMenuItem.setActionCommand("remove-admin");

        makeAdminMenuItem.addActionListener(this);
        makeAdminMenuItem.setActionCommand("add-admin");

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
        popup.addSeparator();
        popup.add(makeAdminMenuItem);
        popup.add(removeAdminMenuItem);

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
            Map user = users.get(selectedRow);
            String username = (String) user.get("username");
            String names = (String) user.get("names");
            sb.append("<recipient-username>").append(username).append("</recipient-username>");
            sb.append("<recipient-names>").append(names).append("</recipient-names>");
            sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.TAKE_MIC);
            p.setContent(sb.toString());
            ConnectionManager.sendPacket(p);

        }
        if (e.getActionCommand().equals("give-mic")) {

            Map user = users.get(selectedRow);
            String username = (String) user.get("username");
            if (username.equals(ConnectionManager.getUsername())) {
                JOptionPane.showMessageDialog(null, "You cannot assign a MIC to yourself");
                return;
            }
            String names = (String) user.get("names");
            giveMic(username, names);
        }
        if (e.getActionCommand().equals("add-admin")) {

            Map user = users.get(selectedRow);
            String username = (String) user.get("username");
            if(isMe(username)){
                JOptionPane.showMessageDialog(null, "You are already admin");
                return;
            }
            //int accessLevel = (Integer)user.get("access_level");
            int accessLevel = AdminLevels.ADMIN_LEVEL;
            int hasMIC = (Integer) user.get("has_mic");
            setAdmin(username, accessLevel);
            setUserAccessAndMIC(username, hasMIC == 1 ? true : false, accessLevel);
        }

        if (e.getActionCommand().equals("remove-admin")) {
            Map user = users.get(selectedRow);
            String username = (String) user.get("username");
            //int accessLevel = (Integer)user.get("access_level");
            int accessLevel = AdminLevels.PARTICIPANT_LEVEL;
            int hasMIC = (Integer) user.get("has_mic");
            setAdmin(username, accessLevel);
            setUserAccessAndMIC(username, hasMIC == 1 ? true : false, accessLevel);
        }


    }

    public void giveMic(String username, String name) {

        RealtimePacketContent realtimePacketContent = new RealtimePacketContent();
        realtimePacketContent.addTag("recipient-username", username);
        realtimePacketContent.addTag("recipient-names", name);
        realtimePacketContent.addTag("room-name", ConnectionManager.getUsername());
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.GIVE_MIC);
        p.setContent(realtimePacketContent.toString());
        ConnectionManager.sendPacket(p);
    }

    public void setAdmin(String username, int accessLevel) {
        RealtimePacketContent realtimePacketContent = new RealtimePacketContent();
        realtimePacketContent.addTag("username", username);
        realtimePacketContent.addTag("access_level", accessLevel);
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.SET_ACCESS);
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
        return to.trim().equalsIgnoreCase(ConnectionManager.getUsername().trim());
    }

    public void clear() {
        users.clear();
    }

    public void setUserHasMIC(String nickname, boolean hasMIC) {
        int index = 0;
        for (Map user : users) {
            String names = (String) user.get("nickname");

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

    public void setUserAccess(String username, int accessLevel) {
        int index = 0;
        for (Map user : users) {
            String names = (String) user.get("username");

            if (names.equalsIgnoreCase(username)) {
                user.put("access_level", accessLevel);
                users.set(index, user);
                if (accessLevel == 1) {
                    GUIAccessManager.mf.showInstructorToolbar();
                    GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setDrawEnabled(true);
                    GUIAccessManager.mf.getWhiteboardPanel().addSlideViewerNavigator();
                    GUIAccessManager.mf.setWebBrowserEnabled(true);
                    GUIAccessManager.mf.getChatRoomManager().enableMenus(true);
                } else {
                    GUIAccessManager.mf.getChatRoomManager().enableMenus(false);
                    GUIAccessManager.mf.showParticipantToolbar();
                    GUIAccessManager.setMenuEnabled(true, "screenviewer");
                    GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setDrawEnabled(false);
                    GUIAccessManager.mf.setWebBrowserEnabled(false);
                }
            }
        }
    }
    //when the userlist is broadcast, this method gets invoked

    public void setUserAccessAndMIC(String username, boolean hasMIC, int accessLevel) {
        int index = 0;
        for (Map user : users) {
            String names = (String) user.get("username");

            if (names.equalsIgnoreCase(username)) {
                user.put("has_mic", hasMIC ? 1 : 0);
                user.put("access_level", accessLevel);
                users.set(index, user);
                if (names.equalsIgnoreCase(ConnectionManager.getUsername())) {
                  if (accessLevel == AdminLevels.OWNER_LEVEL) {
                      ConnectionManager.isOwner = true;
                  }
                  else {
                    ConnectionManager.isOwner = false;
                  }
                  if (accessLevel == AdminLevels.ADMIN_LEVEL) {
                      ConnectionManager.isAdmin = true;
                  } else {
                 ConnectionManager.isAdmin = false;
                 }
                }
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
        user.put("access_level", 3);
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
                    column.setPreferredWidth((int) (tableWidth * 0.1));
                } else if (i == 1) {
                    column.setPreferredWidth((int) (tableWidth * 0.1));
                } else {
                    column.setPreferredWidth((int) (tableWidth * 0.8));
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
