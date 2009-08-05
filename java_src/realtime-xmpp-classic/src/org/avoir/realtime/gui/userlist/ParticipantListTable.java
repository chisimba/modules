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
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.avoir.realtime.common.Constants.*;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.net.providers.RealtimePacketProcessor;
import org.avoir.realtime.privatechat.PrivateChatManager;
import org.jivesoftware.smack.XMPPException;

/**
 *
 * @author david
 */
public class ParticipantListTable extends JTable implements ActionListener {

    private ImageIcon handIcon = ImageUtil.createImageIcon(this, "/images/user_hand.png");
    private ImageIcon soundIcon = ImageUtil.createImageIcon(this, "/images/user_mic.png");
    private ImageIcon adminIcon = ImageUtil.createImageIcon(this, "/images/user.png");
    private ImageIcon ownerIcon = ImageUtil.createImageIcon(this, "/images/user_green.png");
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
    private JMenuItem giveVoice = new JMenuItem("All Text Chat");
    private JMenuItem removeVoice = new JMenuItem("Ban Text Chat");
    private JMenuItem allowWhiteboard = new JMenuItem("Allow Whiteboard");
    private JMenuItem removeWhiteboard = new JMenuItem("Remove Whiteboard");
    private JMenuItem privateChatMenuItem = new JMenuItem("Private Chat");
    private JMenuItem makeAdminMenuItem = new JMenuItem("Make Admin");
    private JMenuItem removeAdminMenuItem = new JMenuItem("Remove Admin Status");
    private int selectedRow = -1;
    private Map thisUser = null;
    private boolean hasMic = false;
    //private boolean micIconDone = false;

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
                    removeAdminMenuItem.setEnabled(false);
                    makeAdminMenuItem.setEnabled(false);
                    giveMICMenuItem.setEnabled(false);
                    takeMICMenuItem.setEnabled(false);
                    kickoutMenuItem.setEnabled(false);
                    banMenuItem.setEnabled(false);
                    privateChatMenuItem.setEnabled(true);
                    giveVoice.setEnabled(false);
                    removeVoice.setEnabled(false);
                    allowWhiteboard.setEnabled(false);
                    removeWhiteboard.setEnabled(false);

                    Map user = users.get(selectedRow);
                    String name = (String) model.getValueAt(selectedRow, 3);
                    PermissionList selectedUsersPermissions = (PermissionList) (user.get("permissions"));
                    PermissionList currentUsersPermissions = (PermissionList) (thisUser.get("permissions"));

                    if (currentUsersPermissions.canKick && !selectedUsersPermissions.isOwner) {
                        kickoutMenuItem.setEnabled(!isMe((String) user.get("username")));
                    }
                    if (currentUsersPermissions.canBan && !selectedUsersPermissions.isOwner) {
                        banMenuItem.setEnabled(!isMe((String) user.get("username")));
                    }
                    /*if (currentUsersPermissions.grantMic) {
                    if (!selectedUsersPermissions.isOwner && selectedUsersPermissions.hasMic) {
                    takeMICMenuItem.setEnabled(enableTakeMic(name));
                    } else if (!selectedUsersPermissions.hasMic) {
                    giveMICMenuItem.setEnabled(enableGiveMic(name));
                    }
                    }*/
                    if ((currentUsersPermissions.isOwner)) {
                        giveMICMenuItem.setEnabled(!selectedUsersPermissions.hasMic);
                        takeMICMenuItem.setEnabled(!giveMICMenuItem.isEnabled());
                        makeAdminMenuItem.setEnabled(!selectedUsersPermissions.grantAdmin);
                        removeAdminMenuItem.setEnabled(!makeAdminMenuItem.isEnabled());
                        allowWhiteboard.setEnabled(!selectedUsersPermissions.grantWhiteboard);
                        removeWhiteboard.setEnabled(!allowWhiteboard.isEnabled());
                        giveVoice.setEnabled(selectedUsersPermissions.grantVoice);
                        removeVoice.setEnabled(!giveVoice.isEnabled());


                    }
                    giveVoice.setEnabled(false);
                    removeVoice.setEnabled(false);

                    if (currentUsersPermissions.grantVoice) {
                        if (!selectedUsersPermissions.hasVoice) {
                            giveVoice.setEnabled(true);
                        } else {
                            removeVoice.setEnabled(true);
                        }
                    }
                    popup.show(ParticipantListTable.this, evt.getX(), evt.getY());
                    if (evt.getClickCount() == 2 && selectedRow > 0) {
                    }
                }

            /*
            if (ConnectionManager.isOwner) {// || ConnectionManager.isAdmin) {
            //if (WebPresentManager.isPresenter || StandAloneManager.isAdmin) {

            takeMICMenuItem.setEnabled(enableTakeMic(name));
            giveMICMenuItem.setEnabled(enableGiveMic(name));
            kickoutMenuItem.setEnabled(!isMe((String) user.get("username")));
            banMenuItem.setEnabled(!isMe((String) user.get("username")));
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


            }

            }
            }
             */
            }
        });


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
        giveVoice.addActionListener(this);
        giveVoice.setActionCommand("give-voice");
        removeVoice.addActionListener(this);
        removeVoice.setActionCommand("remove-voice");
        allowWhiteboard.addActionListener(this);
        allowWhiteboard.setActionCommand("allow-whiteboard");
        removeWhiteboard.addActionListener(this);
        removeWhiteboard.setActionCommand("remove-whiteboard");


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
        popup.add(allowWhiteboard);
        popup.add(removeWhiteboard);
        popup.add(giveVoice);
        popup.add(removeVoice);

        decorateTable();
    }

    private void kickOut(boolean permanently) {

        Map user = users.get(selectedRow);
        String username = (String) user.get("username");
        String names = (String) user.get("names");
        String jid = username + ":" + names;
        if (username.trim().equalsIgnoreCase(ConnectionManager.getUsername().trim())) {
            JOptionPane.showMessageDialog(null, "You cannot kick out yourself!!! :)");
            return;
        }
        String kikMessage = "Do you want to kick  " + names + " out ?";
        String banMessage = "Do you want to ban  " + names + "?";
        int n = JOptionPane.showConfirmDialog(null, permanently ? banMessage : kikMessage, "Confirm", JOptionPane.YES_NO_OPTION);
        if (n == JOptionPane.YES_OPTION) {
            if (permanently) {
                if (!GUIAccessManager.mf.getChatRoomManager().ban(jid)) {
                    JOptionPane.showMessageDialog(null, "Unable to ban " + names + ".");
                }
            } else {

                if (!GUIAccessManager.mf.getChatRoomManager().kick(jid)) {
                    JOptionPane.showMessageDialog(null, "Unable to kick " + names + " out");
                }
            }
        }

    }

    public void actionPerformed(ActionEvent e) {
        if (e.getActionCommand().equals("privatechat")) {
          Map user = users.get(selectedRow);
          String username = (String)user.get("username");
          String fullname = (String)user.get("names");
          PrivateChatManager.initPrivateChat(username, fullname);

        }
        if (e.getActionCommand().equals("ban")) {
            kickOut(true);
        }
        if (e.getActionCommand().equals("kick-out")) {
            kickOut(false);
        }
        if (e.getActionCommand().equals("take-mic")) {
            Map user = users.get(selectedRow);
            String username = (String) user.get("username");
            String names = (String) user.get("names");
            takeMic(username);
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
            if (isMe(username)) {
                JOptionPane.showMessageDialog(null, "You are already admin");
                return;
            }
            makeAdmin(username);

        }

        if (e.getActionCommand().equals("remove-admin")) {
            Map user = users.get(selectedRow);
            String username = (String) user.get("username");
            removeAdmin(username);
        }
        if (e.getActionCommand().equals("remove-whiteboard")) {
            Map user = users.get(selectedRow);
            String username = (String) user.get("username");
            effectPermissionForSelectedUser(username, "Your whiteboard is disabled from drawing", 'w', false);
        }
        if (e.getActionCommand().equals("allow-whiteboard")) {
            Map user = users.get(selectedRow);
            String username = (String) user.get("username");
            effectPermissionForSelectedUser(username, "Your whiteboard is enabled for drawing", 'w', true);
        }
        if (e.getActionCommand().equals("remove-voice")) {
            Map user = users.get(selectedRow);
            String username = (String) user.get("username");
            effectPermissionForSelectedUser(username, "You have been banned from chatting", 'v', false);
        }
        if (e.getActionCommand().equals("give-voice")) {
            Map user = users.get(selectedRow);
            String username = (String) user.get("username");
            effectPermissionForSelectedUser(username, "You have been given permission to chat", 'v', true);
        }
    }


    private ArrayList<Map> getCurrentMicHolders() {
        ArrayList<Map> micHolders = new ArrayList<Map>();
        for (Map user : users) {
            PermissionList permission = (PermissionList) user.get("permissions");
            if (permission.hasMic) {
                micHolders.add(user);
            }
        }
        return micHolders;
    }

    private void makeAdmin(String username) {
        StringBuilder sb = new StringBuilder();
        PermissionList permissions = getUserPermissions(username);
        permissions.grantAdmin = true;
        permissions.grantMic = true;
        permissions.canKick = true;
        permissions.grantVoice = true;
        permissions.grantWhiteboard = true;
        String permissionString = permissions.getPermissionString();
        RealtimePacketContent realtimePacketContent = new RealtimePacketContent();
        realtimePacketContent.addTag("username", username);
        realtimePacketContent.addTag("permissions", permissionString);
        realtimePacketContent.addTag("message", "You are now an admin.");
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.SET_ACCESS);
        p.setContent(realtimePacketContent.toString());
        ConnectionManager.sendPacket(p);
    }

    private void removeAdmin(String username) {
        StringBuilder sb = new StringBuilder();
        PermissionList permissions = getUserPermissions(username);
        permissions.grantAdmin = false;
        permissions.grantMic = false;
        permissions.canKick = false;
        permissions.grantVoice = false;
        permissions.grantWhiteboard = false;
        String permissionString = permissions.getPermissionString();
        RealtimePacketContent realtimePacketContent = new RealtimePacketContent();
        realtimePacketContent.addTag("username", username);
        realtimePacketContent.addTag("permissions", permissionString);
        realtimePacketContent.addTag("message", "You are no longer an admin.");
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.SET_ACCESS);
        p.setContent(realtimePacketContent.toString());
        ConnectionManager.sendPacket(p);
    }

    public boolean hasMic(String username) {
        StringBuilder sb = new StringBuilder();
        PermissionList permissions = getUserPermissions(username);
        return permissions.hasMic;
    }

    public void takeMic(String username) {

        PermissionList permissions = getUserPermissions(username);
        permissions.hasMic = false;

        String permissionString = permissions.getPermissionString();
        RealtimePacketContent realtimePacketContent = new RealtimePacketContent();
        realtimePacketContent.addTag("username", username);
        realtimePacketContent.addTag("permissions", permissionString);
        realtimePacketContent.addTag("message", "You no longer have the MIC");
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.SET_ACCESS);
        p.setContent(realtimePacketContent.toString());
        ConnectionManager.sendPacket(p);

    }

    public void effectPermissionForSelectedUser(String username, String message, char permission, boolean status) {

        PermissionList permissions = getUserPermissions(username);
        switch (permission) {
            case 'v': {
                permissions.hasVoice = status;
                break;
            }
            case 'w': {
                permissions.grantWhiteboard = status;
            }
        }

        String permissionString = permissions.getPermissionString();
        RealtimePacketContent realtimePacketContent = new RealtimePacketContent();
        realtimePacketContent.addTag("username", username);
        realtimePacketContent.addTag("permissions", permissionString);
        realtimePacketContent.addTag("message", message);
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.SET_ACCESS);
        p.setContent(realtimePacketContent.toString());
        ConnectionManager.sendPacket(p);

    }

    public void sendUserPermissions(String username) {
        PermissionList permissions = getUserPermissions(username);
        RealtimePacketContent realtimePacketContent = new RealtimePacketContent();
        realtimePacketContent.addTag("username", username);
        realtimePacketContent.addTag("permissions", permissions.getPermissionString());
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.SET_ACCESS);
        p.setContent(realtimePacketContent.toString());
        ConnectionManager.sendPacket(p);
    }

    public void raiseHand() {
        PermissionList permissions = (PermissionList) thisUser.get("permissions");
        permissions.raisedHand = true;
        sendUserPermissions((String) thisUser.get("username"));
    }

    public void lowerHand() {
        PermissionList permissions = (PermissionList) thisUser.get("permissions");
        permissions.raisedHand = false;
        sendUserPermissions((String) thisUser.get("username"));
    }

    public void giveMic(String username, String name) {
        String spks = GeneralUtil.getProperty("maxspeakers");
        if (spks == null) {
            GeneralUtil.saveProperty("maxspeakers", "1");
            spks = GeneralUtil.getProperty("maxspeakers");
        }
        Integer maxSpeakers = new Integer(spks);
        ArrayList<Map> currentMicHolders = getCurrentMicHolders();
        String holdersList = "";
        for (Map holder : currentMicHolders) {
            holdersList += holder.get("names") + "\n";
        }
        if (currentMicHolders.size() >= maxSpeakers) {
            JOptionPane.showMessageDialog(null, "<html>The system is configured to allow maximum of " + currentMicHolders.size() +
                    " users to have mics.\n" +
                    "Take a mic from one of the following users, before attempting to assign '" + name + "'.\n" +
                    "<font color=\"green\">MIC Holders</font>" +
                    "<font color=\"black\">" +
                    holdersList +
                    "</font>");
            return;
        }

        PermissionList permissions = getUserPermissions(username);
        permissions.hasMic = true;
        String permissionString = permissions.getPermissionString();
        RealtimePacketContent realtimePacketContent = new RealtimePacketContent();
        realtimePacketContent.addTag("username", username);
        realtimePacketContent.addTag("permissions", permissionString);
        realtimePacketContent.addTag("message", "You have been given the MIC");
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.SET_ACCESS);
        p.setContent(realtimePacketContent.toString());
        ConnectionManager.sendPacket(p);
    }

    public void sendAccessLevelPacket(String username, int accessLevel) {
        RealtimePacketContent realtimePacketContent = new RealtimePacketContent();
        realtimePacketContent.addTag("username", username);
        realtimePacketContent.addTag("access_level", accessLevel);
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.SET_ACCESS);
        p.setContent(realtimePacketContent.toString());
        ConnectionManager.sendPacket(p);
    }

    public PermissionList getUserPermissions(String username) {
        for (Map user : users) {
            if (((String) (user.get("username"))).equalsIgnoreCase(username)) {
                return (PermissionList) user.get("permissions");
            }
        }
        return null;
    }

    public boolean isAdmin(String xfrom) {
        int at = xfrom.indexOf("@");
        String from = xfrom.substring(0, at);
        for (Map user : users) {
            String username = (String) user.get("username");
            if (username.equals(from)) {
                PermissionList permissionList = (PermissionList) user.get("permissions");
                return permissionList.grantAdmin;
            }
        }
        return false;
    }

    private boolean enableTakeMic(String username) {
        //ImageIcon icon = (ImageIcon) model.getValueAt(selectedRow, 1);
        // return icon == micIcon;
        return ((String) model.getValueAt(selectedRow, 1)).equals("Mic");
    }

    private boolean enableGiveMic(String username) {

        /*ImageIcon icon = (ImageIcon) model.getValueAt(selectedRow, 1);
        return icon != micIcon;*/
        return !((String) model.getValueAt(selectedRow, 1)).equals("Mic");

    }

    private boolean isMe(String to) {
        return to.trim().equalsIgnoreCase(ConnectionManager.getUsername().trim());
    }

    public void clear() {
        users.clear();
    }

    private void giveMeAppropriateAccessLevel(int accessLevel) {
        if (accessLevel == AdminLevels.ADMIN_LEVEL ||
                accessLevel == AdminLevels.OWNER_LEVEL) {

            GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setDrawEnabled(true);
            GUIAccessManager.mf.getWhiteboardPanel().addSlideViewerNavigator();
            GUIAccessManager.mf.setWebBrowserEnabled(true);
            GUIAccessManager.enableMenus(true);
            GUIAccessManager.enableToolbarButtons(true);
            GUIAccessManager.enableWhiteboardButtons(true);
            GUIAccessManager.mf.getUserListPanel().getUserTabbedPane().setSelectedIndex(0);

        } else {
            GUIAccessManager.enableMenus(false);
            GUIAccessManager.enableToolbarButtons(false);
            GUIAccessManager.enableWhiteboardButtons(false);
            GUIAccessManager.setMenuEnabled(true, "screenviewer");
            GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setDrawEnabled(false);
            GUIAccessManager.mf.setWebBrowserEnabled(false);
            GUIAccessManager.mf.getUserListPanel().getUserTabbedPane().setSelectedIndex(0);
        }
    }
    //when the userlist is broadcast, this method gets invoked

    public void setUserPermissions(String targetUsername, String permissionString) {
        if (permissionString == null) {
            permissionString = "";
        }


        hasMic = permissionString.indexOf("m") > -1;
        if (hasMic) {
            RealtimePacketProcessor.displayVideoMicWindow(targetUsername); //.showExistingSpeakerOnJoinSession(targetUsername);
        } else {
            GUIAccessManager.mf.removeSpeaker(targetUsername);
        }

        int index = 0;
        for (Map user : users) {
            String currentUsername = (String) user.get("username");
            if (targetUsername.equalsIgnoreCase(currentUsername)) {
                PermissionList perm = new PermissionList(targetUsername, permissionString);
                perm.removeAllPermissions();
                perm.setAllPermissions();
                user.put("permissions", perm);
                users.set(index, user);
                break;
            }
            index++;
        }
        if (targetUsername.equalsIgnoreCase(ConnectionManager.getUsername())) {
            thisUser = users.get(index);
            PermissionList perm = (PermissionList) thisUser.get("permissions");
            GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setDrawEnabled(perm.grantWhiteboard);
        //GUIAccessManager.mf.getChatRoomManager().grantVoice(perm.grantVoice, targetUsername + ":" + ConnectionManager.fullnames);
        }
        model = new ParticipantListTableModel();
        setModel(model);
        decorateTable();
    }

    /*
    public void setUserAccessAndMIC(String targetUsername, int hasMIC, int accessLevel) {
    int index = 0;
    if (users.size() == 0) {
    giveMeAppropriateAccessLevel(accessLevel);
    }
    if (hasMIC == MIC.MIC_ON) {
    RealtimePacketProcessor.showExistingSpeakerOnJoinSession(targetUsername);
    }
    for (Map user : users) {
    String currentUsername = (String) user.get("username");

    if (currentUsername.equalsIgnoreCase(targetUsername)) {
    user.put("has_mic", hasMIC);
    user.put("access_level", accessLevel);
    users.set(index, user);

    //now if is target user

    if (currentUsername.equalsIgnoreCase(ConnectionManager.getUsername())) {

    if (accessLevel == AdminLevels.OWNER_LEVEL) {
    ConnectionManager.isOwner = true;
    } else {
    ConnectionManager.isOwner = false;
    }
    if (accessLevel == AdminLevels.ADMIN_LEVEL) {
    ConnectionManager.isAdmin = true;
    } else {
    ConnectionManager.isAdmin = false;
    }

    if (accessLevel == AdminLevels.ADMIN_LEVEL ||
    accessLevel == AdminLevels.OWNER_LEVEL) {

    GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setDrawEnabled(true);
    GUIAccessManager.mf.getWhiteboardPanel().addSlideViewerNavigator();
    GUIAccessManager.mf.setWebBrowserEnabled(true);
    GUIAccessManager.enableMenus(true);
    GUIAccessManager.enableToolbarButtons(true);
    GUIAccessManager.enableWhiteboardButtons(true);
    GUIAccessManager.mf.getUserListPanel().getUserTabbedPane().setSelectedIndex(0);
    //GUIAccessManager.mf.getUserListPanel().initAudioVideo(true, ConnectionManager.getRoomName());

    } else {
    GUIAccessManager.enableMenus(false);
    GUIAccessManager.enableToolbarButtons(false);
    GUIAccessManager.enableWhiteboardButtons(false);
    GUIAccessManager.setMenuEnabled(true, "screenviewer");
    GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setDrawEnabled(false);
    GUIAccessManager.mf.setWebBrowserEnabled(false);
    GUIAccessManager.mf.getUserListPanel().getUserTabbedPane().setSelectedIndex(0);
    GUIAccessManager.mf.getUserListPanel().initAudioVideo(false, ConnectionManager.getRoomName());
    }

    }
    }


    index++;
    }
    model = new ParticipantListTableModel();
    setModel(model);
    decorateTable();
    }
     */
    public Map<String, String> getUser(String username) {
        for (Map user : users) {
            String thisUsername = (String) user.get("username");
            if ((thisUsername).equalsIgnoreCase(username)) {
                return user;
            }
        }
        return null;
    }

    public void addUser(String name) {
        String username = name.split(":")[0];
        String nickname = name.split(":")[1];
        Map user = new HashMap();
        PermissionList perm = new PermissionList(username, "");
        perm.removeAllPermissions();
        perm.setAllPermissions();
        user.put("permissions", perm);
        user.put("names", nickname);
        user.put("username", username);
        users.add(user);
        model = new ParticipantListTableModel();
        setModel(model);
        decorateTable();
    }

    public void removeUser(String name) {

        String username = name.split(":")[0];
        String nickname = name.split(":")[1];
        int index = 0;
        ArrayList<Integer> toRemove = new ArrayList<Integer>();
        for (Map user : users) {
            String thisNickname = (String) user.get("names");
            if ((thisNickname).equalsIgnoreCase(nickname)) {
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
                } else if (i == 2) {
                    column.setPreferredWidth((int) (tableWidth * 0.1));
                } else {
                    column.setPreferredWidth((int) (tableWidth * 0.7));
                }
            }
        }
        this.repaint();
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

    public ArrayList<Map> getUsers() {
        return users;
    }

    public String getNames(String username) {
        String name = "Unknown";
        for (Map map : users) {
            String xusername = (String) map.get("username");
            if (xusername.equals(username)) {
                return (String) map.get("names");
            }
        }
        return name;
    }

    class ParticipantListTableModel extends AbstractTableModel {

        private String[] columnNames = {
            "A",
            "H",
            "M",
            "Names"
        };
        private Object[][] data = new Object[0][columnNames.length];

        public ParticipantListTableModel() {
            removeDuplicates();
            data = new Object[users.size()][columnNames.length];

            for (int i = 0; i < users.size(); i++) {

                Map user = users.get(i);
                PermissionList permissions = (PermissionList) user.get("permissions");
                String names = (String) user.get("names");
                StringBuilder accessLevel = new StringBuilder();

                /*  if (permissions.isOwner) {
                accessLevel.append("+");
                accessLevel.append("o");
                }
                if (permissions.grantAdmin) {
                accessLevel.append("+");
                accessLevel.append("a");
                }
                if (!permissions.hasVoice) {
                accessLevel.append("-v");
                }
                 */
                ImageIcon col1Icon = null;
                if (permissions.grantAdmin) {
                    col1Icon = adminIcon;
                }
                if (permissions.isOwner) {
                    col1Icon = ownerIcon;
                }

                if (permissions.raisedHand) {
                    col1Icon = handIcon;
                }

                if (permissions.hasMic) {
                    col1Icon = soundIcon;
                }

                Object[] row = {
                    col1Icon,
                    permissions.raisedHand ? "H" : "",
                    permissions.hasMic ? "Mic" : "",
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

    class PermissionList {

        public boolean isOwner = false;
        public boolean canKick = false;
        public boolean canBan = false;
        public boolean hasVoice = false;
        public boolean hasMic = false;
        public boolean grantVoice = false;
        public boolean grantMic = false;
        public boolean grantWhiteboard = false;
        public boolean canWhiteboard = false;
        public boolean grantAdmin = false;
        public boolean raisedHand = false;
        public String username = "";

        public PermissionList(String username, String permissionString) {
            this.username = username;
            char permissionArray[] = permissionString.toCharArray();
            for (char permission : permissionArray) {
                if (permission == 'm') { //has mic
                    hasMic = true;
                } else if (permission == 'o') { //is owner
                    isOwner = true;
                } else if (permission == 'k') { //kick permission
                    canKick = true;
                } else if (permission == 'b') { //ban permission
                    canBan = true;
                } else if (permission == 'v') { //voice - permission to chat
                    hasVoice = true;
                } else if (permission == 'g') { //give and remove voice permission
                    grantVoice = true;
                } else if (permission == 'i') { //give and remove mic permission
                    grantMic = true;
                } else if (permission == 'w') { //add and remove whiteboard permissions
                    grantWhiteboard = true;
                } else if (permission == 'h') { //has white board permissions
                    canWhiteboard = true;
                } else if (permission == 'a') { //admin permission - can give any permissions to a user that this admin has
                    grantAdmin = true;
                } else if (permission == 'r') { //hand is raised
                    raisedHand = true;
                }
            }
        }

        public void evaluatePermissions() {
            ConnectionManager.isOwner = false;
            ConnectionManager.hasVoice = false;
            ConnectionManager.canKick = false;
            ConnectionManager.canBan = false;
            ConnectionManager.grantVoice = false;
            ConnectionManager.grantMic = false;
            ConnectionManager.grantWhiteboard = false;
            ConnectionManager.canWhiteboard = false;
            ConnectionManager.grantAdmin = false;
            if (isOwner) {
                ConnectionManager.isOwner = true;
            }
            if (canKick) {
                ConnectionManager.canKick = true;
            }
            if (canBan) {
                ConnectionManager.canBan = true;
            }
            if (hasVoice) {
                ConnectionManager.hasVoice = true;
            }
            if (grantVoice) {
                ConnectionManager.grantVoice = true;
            }
            if (grantMic) {
                ConnectionManager.grantMic = true;
            }
            if (grantWhiteboard) {
                ConnectionManager.grantWhiteboard = true;
            }
            if (canWhiteboard) {
                ConnectionManager.canWhiteboard = true;
            }
            if (grantAdmin) {
                ConnectionManager.grantAdmin = true;
            }
        }

        public void removeAllPermissions() {
            //only remove permissions from this user if it is the current user
            if (username.equalsIgnoreCase(ConnectionManager.getUsername())) {
                GUIAccessManager.enableMenus(false);
                GUIAccessManager.enableToolbarButtons(false);
                GUIAccessManager.enableWhiteboardButtons(false);
                GUIAccessManager.setMenuEnabled(true, "screenviewer");
                GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setDrawEnabled(false);
                GUIAccessManager.mf.setWebBrowserEnabled(false);
                GUIAccessManager.mf.getUserListPanel().getUserTabbedPane().setSelectedIndex(0);
                GUIAccessManager.mf.getUserListPanel().initAudioVideo(false, ConnectionManager.getRoomName());
            }
        }

        private void grantEverything() {

            GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setDrawEnabled(true);
            GUIAccessManager.mf.getWhiteboardPanel().addSlideViewerNavigator();
            GUIAccessManager.mf.setWebBrowserEnabled(true);
            GUIAccessManager.enableMenus(true);
            GUIAccessManager.enableToolbarButtons(true);
            GUIAccessManager.enableWhiteboardButtons(true);
            GUIAccessManager.mf.getUserListPanel().getUserTabbedPane().setSelectedIndex(0);
        }

        public void setAllPermissions() {
            //supposed to give user access to what he has.
            //this is where the whiteboard/mic/voice etc. must be enabled
            try {
                if (hasVoice) {
                    GUIAccessManager.mf.getChatRoomManager().getMuc().grantVoice(username);
                } else {
                    GUIAccessManager.mf.getChatRoomManager().getMuc().revokeVoice(username);
                }
            } catch (XMPPException e) {
                e.printStackTrace();
            }
            if (username.equalsIgnoreCase(ConnectionManager.getUsername())) {
                evaluatePermissions();
                if (isOwner) {
                    grantEverything();
                    GUIAccessManager.mf.getUserListPanel().initAudioVideo(true, ConnectionManager.getRoomName());
                } else if (grantAdmin) {
                    grantEverything();
                } else if (grantWhiteboard) {
                    GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setDrawEnabled(true);
                }
            }
        }

        public String getPermissionString() {
            String permissionString = "";
            if (hasMic) {
                permissionString += "m";
            }
            if (isOwner) {
                permissionString += "o";
            }
            if (canKick) {
                permissionString += "k";
            }
            if (canBan) {
                permissionString += "b";
            }
            if (hasVoice) {
                permissionString += "v";
            }
            if (grantVoice) {
                permissionString += "g";
            }
            if (grantMic) {
                permissionString += "i";
            }
            if (grantWhiteboard) {
                permissionString += "w";
            }
            if (canWhiteboard) {
                permissionString += "h";
            }
            if (grantAdmin) {
                permissionString += "a";
            }
            if (raisedHand) {
                permissionString += "r";
            }
            return permissionString;
        }
    }
}
