/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.userlist;

import java.awt.Color;
import java.awt.Component;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.GridLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.net.URL;
import java.util.ArrayList;
import java.util.Collection;
import java.util.Enumeration;
import java.util.HashMap;
import java.util.Map;
import java.util.TimerTask;
import javax.swing.ImageIcon;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JPopupMenu;
import javax.swing.JScrollPane;
import javax.swing.JTree;
import javax.swing.event.TreeSelectionEvent;
import javax.swing.event.TreeSelectionListener;
import javax.swing.tree.DefaultMutableTreeNode;
import javax.swing.tree.DefaultTreeCellRenderer;
import javax.swing.tree.DefaultTreeModel;
import javax.swing.tree.MutableTreeNode;
import javax.swing.tree.TreeNode;
import javax.swing.tree.TreePath;
import javax.swing.tree.TreeSelectionModel;
import org.avoir.realtime.chat.ChatRoom;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.StandAloneManager;
import org.avoir.realtime.gui.main.WebPresentManager;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.SubscribePacketInt;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.jivesoftware.smack.Roster;
import org.jivesoftware.smack.RosterEntry;
import org.jivesoftware.smack.RosterListener;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.XMPPException;
import org.jivesoftware.smack.packet.Presence;
import org.jivesoftware.smack.packet.RosterPacket;

public class ParticipantListTree extends JPanel implements SubscribePacketInt,
        ActionListener {

    protected DefaultMutableTreeNode rootNode;
    protected DefaultTreeModel treeModel;
    private DefaultMutableTreeNode onlineNode;
    // private DefaultMutableTreeNode offlineNode;
    // private DefaultMutableTreeNode incomingRequestsNode;
    //  private DefaultMutableTreeNode outgoingRequestsNode;
    protected JTree tree;
    private ImageIcon availableIcon = ImageUtil.createImageIcon(this, "/images/im_available.png");
    private ImageIcon unavailableIcon = ImageUtil.createImageIcon(this, "/images/im_unavailable.png");
    private ImageIcon awayIcon = ImageUtil.createImageIcon(this, "/images/im_away.png");
    private ImageIcon helpIcon = ImageUtil.createImageIcon(this, "/images/help_16x16.png");
    private URL micIconURL = null;
    private Collection<RosterEntry> entries;
    private XMPPConnection connection = ConnectionManager.getConnection();
    
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
    private ArrayList<Map> currentSpeakers = new ArrayList<Map>();
    private ArrayList<Map<String, Object>> privateChats = new ArrayList<Map<String, Object>>();

    public ParticipantListTree() {
        super(new GridLayout(1, 0));
  
        init();
        try {
            micIconURL = this.getClass().getResource("/images/mic_on.png");
        } catch (Exception ex) {
            ex.printStackTrace();
        }

    }

    public void init() {
        rootNode = new DefaultMutableTreeNode("Participants");
        treeModel = new DefaultTreeModel(rootNode);

        tree = new JTree(treeModel);
        tree.getSelectionModel().setSelectionMode(TreeSelectionModel.SINGLE_TREE_SELECTION);
        //tree.putClientProperty("JTree.lineStyle", "None");

        tree.setCellRenderer(new UserRenderer());

        TreeSelectionModel rowSM = tree.getSelectionModel();
        rowSM.addTreeSelectionListener(new TreeSelectionListener() {

            public void valueChanged(TreeSelectionEvent e) {
                TreeSelectionModel lsm = (TreeSelectionModel) e.getSource();
                if (lsm.isSelectionEmpty()) {
                } else {
                    TreePath parentPath = tree.getSelectionPath();
                    DefaultMutableTreeNode node = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());

                }
            }
        });
        tree.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseClicked(MouseEvent e) {
                if (e.getButton() == MouseEvent.BUTTON3) {
                    if (WebPresentManager.isPresenter || StandAloneManager.isAdmin) {
                        Object obj = getSelectedObject();
                        if (obj instanceof JLabel) {
                            JLabel label = (JLabel) obj;
                            takeMICMenuItem.setEnabled(enableTakeMic(label.getText()));
                            giveMICMenuItem.setEnabled(enableGiveMic(label.getText()));
                            kickoutMenuItem.setEnabled(!isMe(label.getText()));
                            banMenuItem.setEnabled(!isMe(label.getText()));
                            privateChatMenuItem.setEnabled(false);//!isMe(label.getText()));
                        }
                        popup.show(tree, e.getX(), e.getY());
                    }
                }
                TreePath parentPath = tree.getSelectionPath();
                DefaultMutableTreeNode node = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());
                DefaultMutableTreeNode parentNode = (DefaultMutableTreeNode) node.getParent();
                if (e.getClickCount() == 2) {

                    Object obj = node.getUserObject();

                    if (parentNode != null) {

                        if (obj instanceof JLabel) {
                            JLabel label = (JLabel) obj;
                        // GUIAccessManager.mf.getUserListPanel().showUserDetailsFrame(label.getName());

                        }
                    }
                }

            }
        });
        JScrollPane scrollPane = new JScrollPane(tree);
        add(scrollPane);

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
        /* popup.add(acceptMenuItem);
        popup.add(denyMenuItem);
        popup.add(profileMenuItem);
        popup.addSeparator();
        popup.add(removeMenuItem);
         */
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
        loadUsers();
    }

    private boolean enableGiveMic(String text) {
        boolean isMe = isMe(text);
        if (isMe) {
            return false;
        }
        boolean hasMic = hasMIC(text);
        if (hasMic) {
            return false;
        }
        return true;
    }

    private boolean enableTakeMic(String text) {
        boolean isMe = isMe(text);
        if (isMe) {
            return false;
        }
        boolean hasMic = hasMIC(text);
        if (hasMic) {
            return true;
        }
        return false;
    }

    private Object getSelectedObject() {
        TreePath parentPath = tree.getSelectionPath();
        DefaultMutableTreeNode node = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());

        Object obj = node.getUserObject();
        return obj;
    }

    private boolean isMe(String to) {
        return to.trim().equalsIgnoreCase(ConnectionManager.fullnames.trim());
    }

    public ArrayList<String> getUsers() {
        ArrayList<String> users = new ArrayList<String>();
        DefaultMutableTreeNode treeNode = searchNode("Online");
        if (treeNode != null) {
            Enumeration list = treeNode.breadthFirstEnumeration();
            while (list.hasMoreElements()) {
                System.out.println(list.nextElement());
            }
        }
        return users;
    }

    private String getDisplayText(String user) {
        synchronized (currentSpeakers) {
            for (Map map : currentSpeakers) {
                if (map.containsValue(user)) {
                    return (String) map.get("display-text");
                }
            }
            return null;
        }
    }

    public void setUserHasMIC(String user, String jid, boolean hasMIC) {
        if (!hasMIC) {
            System.out.println("Searching for " + user + " to take mic from");
        }
        DefaultMutableTreeNode treeNode = searchNode(user);

        if (treeNode != null) {
            JLabel l = (JLabel) treeNode.getUserObject();
            removeNode(treeNode);
            String micPic = hasMIC ? "<html><img src='" + micIconURL + "'>" : "";
            String displayText = micPic + user;
            JLabel node = new JLabel(displayText);

            node.setIcon(availableIcon);
            node.setName(jid);
            addObject(onlineNode, node, true);
            if (hasMIC) {
                Map<String, String> map = new HashMap<String, String>();
                map.put("username", user);
                map.put("display-text", displayText);
                synchronized (currentSpeakers) {
                    currentSpeakers.add(map);
                }

            } else {

                synchronized (currentSpeakers) {
                    ArrayList<Map> speakersToRemove = new ArrayList<Map>();
                    for (Map map : currentSpeakers) {
                        String username = (String) map.get("username");
                        if (user.equalsIgnoreCase(username)) {
                            speakersToRemove.add(map);
                        }
                    }
                    for (Map map : speakersToRemove) {
                        currentSpeakers.remove(map);
                    }
                }
            }
        }
    }

    public void updateUser(String user, String jid, boolean passed) {
        DefaultMutableTreeNode treeNode = searchNode(user);
        if (treeNode != null) {
            int passCount = 0;
            int failCount = 0;
            JLabel l = (JLabel) treeNode.getUserObject();
            String existingUser = l.getText();
            if (existingUser.indexOf("p=") > 0) {
                String passesStr = existingUser.substring(existingUser.indexOf("p=") + 2);
                String failStr = existingUser.substring(existingUser.indexOf("f=") + 2);
                passesStr = passesStr.substring(2, passesStr.indexOf(","));
                failStr = passesStr.substring(2, failStr.indexOf("]"));
                int existingPasses = Integer.parseInt(passesStr.trim());
                int existingFails = Integer.parseInt(failStr.trim());
                if (passed) {
                    passCount += existingPasses;
                } else {
                    failCount += existingFails;
                }
            } else {
                if (passed) {
                    passCount++;
                } else {
                    failCount++;
                }
            }
            removeNode(treeNode);
            JLabel node = new JLabel("<html>" + user + " <font color=\"green\">[p=" + passCount + ",</font><font color=\"red\">f=" + failCount + "]</font></html>");
            node.setIcon(availableIcon);
            node.setName(jid);
            addObject(onlineNode, node, true);
        }
    }

    public void addUser(String names) {


        DefaultMutableTreeNode treeNode = searchNode(names);

        if (treeNode != null) {
            removeNode(treeNode);
        }
        JLabel node = new JLabel(names);
        node.setIcon(availableIcon);

        addObject(onlineNode, node, true);

    }

    public void removeUser(String user) {

        DefaultMutableTreeNode treeNode = searchNode(user);

        if (treeNode != null) {
            removeNode(treeNode);
        }
    }

    private void kickOut(boolean permanently) {
        TreePath parentPath = tree.getSelectionPath();
        DefaultMutableTreeNode node = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());
        Object obj = node.getUserObject();
        if (obj instanceof JLabel) {
            JLabel l = (JLabel) obj;
            String to = l.getText();
            String jid = l.getName();
            if (to.trim().equalsIgnoreCase(ConnectionManager.fullnames.trim())) {
                JOptionPane.showMessageDialog(null, "You cannot kick out yourself!!! :)");
                return;
            }
            String kikMessage = "Do you want to kick  " + to + " out ?";
            String banMessage = "Do you want to ban  " + to + "?";
            int n = JOptionPane.showConfirmDialog(null, permanently ? banMessage : kikMessage, "Confirm", JOptionPane.YES_NO_OPTION);
            if (n == JOptionPane.YES_OPTION) {
                if (permanently) {
                    if (!GUIAccessManager.mf.getChatRoomManager().ban(jid)) {
                        JOptionPane.showMessageDialog(null, "Unable to ban " + to + ".");
                    }
                } else {

                    if (!GUIAccessManager.mf.getChatRoomManager().kick(to)) {
                        JOptionPane.showMessageDialog(null, "Unable to kick " + to + " out");
                    }
                }
            }
        }
    }

    public void clearCurrentSpeakers(){
        currentSpeakers.clear();
    }
    private boolean hasMIC(String user) {
        user = getUsername(user);
        synchronized (currentSpeakers) {
            for (Map map : currentSpeakers) {
                String username = (String) map.get("username");
                if (user.equals(username)) {
                    return true;
                }
            }
            return false;
        }
    }

    private String getUsername(String displayText) {
        synchronized (currentSpeakers) {
            for (Map map : currentSpeakers) {
                String dispText = (String) map.get("display-text");
                if (displayText.equals(dispText)) {
                    return (String) map.get("username");
                }
            }
            return displayText;
        }
    }

    private Map<String, Object> getPrivateChatFrame(String sender, String receiver) {
        for (int i = 0; i < privateChats.size(); i++) {
            Map<String, Object> map = privateChats.get(i);
            if ((map.get("sender").equals(sender) && map.get("receiver").equals(receiver)) ||
                    (map.get("receiver").equals(sender) && map.get("sender").equals(receiver))) {
                return map;
            }
        }
        return null;
    }

    public void appendPrivateChat(String msg, String sender, String receiver, boolean returnChat) {
        //String username = returnChat ? sender: receiver;
        Map<String, Object> map = getPrivateChatFrame(sender, receiver);
        if (map == null) {
            if (returnChat) {
                initPrivateChat(sender, receiver, receiver);
            } else {
                initPrivateChat(ConnectionManager.getUsername(), sender, sender);
            }
        }

        map = getPrivateChatFrame(sender, receiver);

        PrivateChatFrame fr = (PrivateChatFrame) map.get("chatframe");
        fr.getChatPanel().insertParticipantName("(" + (sender) + ") ");
        fr.getChatPanel().insertParticipantMessage(sender, msg, 17, Color.BLACK);
        if (!fr.isVisible()) {
            fr.setSize(400, 300);
            fr.setVisible(true);
        }
    }

    public void initPrivateChat(String sender, String receiverUsername, String receiverName) {
        Map<String, Object> map = getPrivateChatFrame(sender, receiverUsername);
        if (map == null) {
            PrivateChatPanel privateChatPanel = new PrivateChatPanel(receiverUsername);
            PrivateChatFrame privateChatFrame = new PrivateChatFrame(privateChatPanel);
            privateChatFrame.setContentPane(privateChatPanel);
            privateChatFrame.setTitle(ConnectionManager.fullnames + ": Chat with " + receiverName);
            privateChatFrame.setSize(400, 300);
            privateChatFrame.setLocationRelativeTo(null);
            privateChatFrame.setVisible(true);
            Map<String, Object> m = new HashMap<String, Object>();
            m.put("receiver", receiverUsername);
            m.put("sender", sender);
            m.put("chatframe", privateChatFrame);
            privateChats.add(m);
        } else {
            map = getPrivateChatFrame(sender, receiverUsername);
            PrivateChatFrame privateChatFrame = (PrivateChatFrame) map.get("chatframe");
            privateChatFrame.setSize(400, 300);
            privateChatFrame.setVisible(true);
        }

    }

    class PrivateChatFrame extends JFrame {

        private PrivateChatPanel chatPanel;

        public PrivateChatFrame(PrivateChatPanel chatPanel) {
            this.chatPanel = chatPanel;
        }

        public PrivateChatPanel getChatPanel() {
            return chatPanel;
        }
    }

    class PrivateChatPanel extends ChatRoom {

        private String receiver;

        public PrivateChatPanel(String receiver) {
            super();
            this.receiver = receiver;
        }

        @Override
        protected void sendChat() {
            String msg = chatInputField.getText();
            if (sendPrivateChat(msg, receiver)) {
                chatInputField.setText("");
            } else {
                insertErrorMessage("Your message was not send\n");
            }
        }
    }

    public boolean sendPrivateChat(String msg, String receiver) {
        try {
            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.PRIVATE_CHAT_SEND);
            StringBuilder sb = new StringBuilder();
            sb.append("<private-chat-receiver>").append(receiver).append("</private-chat-receiver>");
            sb.append("<private-chat-sender>").append(ConnectionManager.getUsername()).append("</private-chat-sender>");
            sb.append("<private-chat-msg>").append(msg).append("</private-chat-msg>");
            p.setContent(sb.toString());
            ConnectionManager.sendPacket(p);
            return true;
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return false;
    }

    public void giveMic(String to) {
        StringBuilder sb = new StringBuilder();
        sb.append("<recipient>").append(to).append("</recipient>");
        sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.GIVE_MIC);
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);
    }

    public void giveMic() {
        Object obj = getSelectedObject();
        if (obj instanceof JLabel) {
            JLabel l = (JLabel) obj;
            String to = l.getText();
            to = getUsername(to);
            // to=Base64.encodeBytes(to.getBytes());
            if (hasMIC(to)) {
                JOptionPane.showMessageDialog(null, "<html>" + to + " already has a MIC");
                return;
            }
            String toJID = l.getName();
            if (to.trim().equalsIgnoreCase(ConnectionManager.fullnames.trim())) {
                JOptionPane.showMessageDialog(null, "You cannot give MIC to yourself");
                return;
            }
            int n = JOptionPane.showConfirmDialog(null, "<html>Do you want to give " + to + " MIC?", "Confirm", JOptionPane.YES_NO_OPTION);
            if (n == JOptionPane.YES_OPTION) {
                StringBuilder sb = new StringBuilder();
                sb.append("<recipient>").append(to).append("</recipient>");
                sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
                RealtimePacket p = new RealtimePacket();
                p.setMode(RealtimePacket.Mode.GIVE_MIC);
                p.setContent(sb.toString());
                ConnectionManager.sendPacket(p);
            }
        }
    }

    public void actionPerformed(ActionEvent e) {
        if (e.getActionCommand().equals("privatechat")) {
            TreePath parentPath = tree.getSelectionPath();
            DefaultMutableTreeNode node = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());
            Object obj = node.getUserObject();
            if (obj instanceof JLabel) {
                JLabel l = (JLabel) obj;
                String to = l.getText();
                if (to.trim().equalsIgnoreCase(ConnectionManager.fullnames.trim())) {
                    JOptionPane.showMessageDialog(null, "You cannot send private message to yourself!");
                    return;
                }
                String jid = GeneralUtil.getJID(to);
                int at = jid.indexOf("@");
                String username = jid.substring(0, at);
                initPrivateChat(ConnectionManager.getUsername(), username, to);
            }
        }
        if (e.getActionCommand().equals("ban")) {
            kickOut(true);
        }
        if (e.getActionCommand().equals("kick-out")) {
            kickOut(false);
        }
        if (e.getActionCommand().equals("take-mic")) {
            Object obj = getSelectedObject();
            if (obj instanceof JLabel) {
                JLabel l = (JLabel) obj;
                String to = l.getText();
                to = getUsername(to);
                System.out.println("Going to take MIC  from " + to);
                //to=Base64.encodeBytes(to.getBytes());
                StringBuilder sb = new StringBuilder();
                sb.append("<recipient>").append(to).append("</recipient>");
                sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
                RealtimePacket p = new RealtimePacket();
                p.setMode(RealtimePacket.Mode.TAKE_MIC);
                p.setContent(sb.toString());
                ConnectionManager.sendPacket(p);
            }

        }
        if (e.getActionCommand().equals("give-mic")) {
            giveMic();
        }
        if (e.getActionCommand().equals("accept")) {
            TreePath parentPath = tree.getSelectionPath();
            DefaultMutableTreeNode node = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());
            Object obj = node.getUserObject();
            if (obj instanceof JLabel) {
                JLabel l = (JLabel) obj;
                accept(l.getText());
            }
        }
    }

    private void accept(String username) {
        String to = username + "@" + ConnectionManager.getConnection().getServiceName();
        Presence packet = new Presence(Presence.Type.subscribed);
        packet.setTo(to);
        ConnectionManager.getConnection().sendPacket(packet);
        loadUsers();
    }

    public void loadUsers() {
        clear();
        onlineNode = addObject(rootNode, "Online", true);
    }

    private void initUsers() {
        Roster roster = connection.getRoster();
        roster.addRosterListener(new RosterListener() {

            public void entriesAdded(Collection<String> addresses) {
            }

            public void entriesDeleted(Collection<String> addresses) {
            }

            public void entriesUpdated(Collection<String> addresses) {
            }

            public void presenceChanged(Presence presence) {
            }
        });


        entries = roster.getEntries();

        for (RosterEntry entry : entries) {

            addEntry(entry);
        }
    }

    private void addEntry(RosterEntry entry) {
        String username = entry.getUser();
        if (username.indexOf("@") > -1) {
            username = username.substring(0, username.indexOf("@"));
        }

        Roster roster = ConnectionManager.getConnection().getRoster();
        Presence presence = roster.getPresence(entry.getUser());
        if (presence.getType() == Presence.Type.available) {
            JLabel node = new JLabel(username);
            node.setIcon(availableIcon);

            if (presence.getMode() == Presence.Mode.away) {
                node.setIcon(awayIcon);
            }
            addObject(onlineNode, node, true);
            onlineNode.setUserObject("Online (" + (onlineNode.getChildCount()) + ")");
        } else if (presence.getType() == Presence.Type.unavailable) {
            JLabel node = new JLabel(username);
            if (entry.getStatus() == RosterPacket.ItemStatus.SUBSCRIPTION_PENDING) {
                node.setIcon(helpIcon);
                //JOptionPane.showMessageDialog(null, presence.toXML()+" ME = "+connection.getUser());
                //addObject(incomingRequestsNode, node, true);
                return;
            }
            if (entry.getStatus() == null) {
                //auto request for subscribtion
                String serviceName = ConnectionManager.getConnection().getServiceName();
                String group = "";
                String jid = username;
                if (jid.indexOf("@") == -1) {
                    jid = jid + "@" + serviceName;
                }
                try {
                    roster.createEntry(jid, jid, new String[]{group});
                } catch (XMPPException ex) {
                    JOptionPane.showMessageDialog(null, "Error accepting " + username);
                    ex.printStackTrace();
                }
                node.setIcon(unavailableIcon);

            }
        }

    }

    public void addIncomingRequests(String from) {
        JLabel node = new JLabel(from);
        node.setIcon(helpIcon);

    }

    public void addOutgoingRequests(String from) {
        JLabel node = new JLabel(from);
        node.setIcon(helpIcon);

    }

    public void addOffline(String from) {
        DefaultMutableTreeNode treeNode = searchNode(from);

        if (treeNode != null) {
            removeNode(treeNode);
        }
        JLabel node = new JLabel(from);
        node.setIcon(unavailableIcon);

    }

    public void addOnlineAvailable(String from) {
        DefaultMutableTreeNode treeNode = searchNode(from);

        if (treeNode != null) {
            removeNode(treeNode);
        }
        JLabel node = new JLabel(from);
        node.setIcon(availableIcon);
        addObject(onlineNode, node, true);
    }

    public void addOnlineAway(String from) {
        DefaultMutableTreeNode treeNode = searchNode(from);

        if (treeNode != null) {
            removeNode(treeNode);
        }
        JLabel node = new JLabel(from);
        node.setIcon(awayIcon);
        addObject(onlineNode, node, true);
    }

    public void processSubscription(Presence presence) {
        String user = presence.getFrom();
        if (user.indexOf("@") > -1) {
            user = user.substring(0, user.indexOf("@"));
        }


        if (presence.isAvailable()) {
            addOnlineAvailable(user);
            return;
        }
        if (presence.isAway()) {
            addOnlineAway(user);
            return;
        }

        if (presence.getType() == Presence.Type.subscribe) {
            addIncomingRequests(user);

            return;
        }

    }

    private void processPresenceChanged(Presence presence) {
        String user = presence.getFrom();
        //RosterEntry entry=roster.
        //user=entry.getUser();
        if (user.indexOf("@") > -1) {
            user = user.substring(0, user.indexOf("@"));
        }
        //search the node
        DefaultMutableTreeNode node = searchNode(user);

        if (node != null) {
            removeNode(node);
            if (presence.getType() == Presence.Type.available) {
                JLabel val = new JLabel(user);
                val.setIcon(availableIcon);

                if (presence.getMode() == Presence.Mode.away) {
                    val.setIcon(awayIcon);
                }

                addObject(onlineNode, val, true);

            } else if (presence.getType() == Presence.Type.unavailable) {
                JLabel val = new JLabel(user);
                val.setIcon(unavailableIcon);
            //addObject(offlineNode, val, true);
            }
            onlineNode.setUserObject("Online (" + (onlineNode.getChildCount()) + ")");
        }
    }

    /**
     * This method takes the node string and
     * traverses the tree till it finds the node
     * matching the string. If the match is found
     * the node is returned else null is returned
     *
     * @param nodeStr node string to search for
     * @return tree node
     */
    public DefaultMutableTreeNode searchNode(String nodeStr) {
        // System.out.println("Searching for " + nodeStr);
        String displayText = getDisplayText(nodeStr);
        if (displayText != null) {
            nodeStr = displayText;
        }
        // System.out.println("Using for " + nodeStr);
        DefaultMutableTreeNode node = null;

        //Get the enumeration
        Enumeration xenum = rootNode.breadthFirstEnumeration();

        //iterate through the enumeration
        while (xenum.hasMoreElements()) {
            //get the node
            node = (DefaultMutableTreeNode) xenum.nextElement();
            Object obj = node.getUserObject();
            if (obj instanceof JLabel) //match the string with the user-object of the node
            {
                JLabel l = (JLabel) obj;
                if (nodeStr.equalsIgnoreCase(l.getText())) {
                    //tree node with string found
                    return node;
                }
            }
        }

        //tree node with string node found return null
        return null;
    }

    /**
     * This method removes the passed tree node from the tree
     * and selects appropiate node
     *
     * @param selNode node to be removed
     */
    public void removeNode(DefaultMutableTreeNode selNode) {
        if (selNode != null) {
            //get the parent of the selected node
            MutableTreeNode parent = (MutableTreeNode) (selNode.getParent());

            // if the parent is not null
            if (parent != null) {
                //get the sibling node to be selected after removing the
                //selected node
                MutableTreeNode toBeSelNode = getSibling(selNode);

                //if there are no siblings select the parent node after removing the node
                if (toBeSelNode == null) {
                    toBeSelNode = parent;
                }

                //make the node visible by scroll to it
                TreeNode[] nodes = treeModel.getPathToRoot(toBeSelNode);
                TreePath path = new TreePath(nodes);
                tree.scrollPathToVisible(path);
                tree.setSelectionPath(path);

                //remove the node from the parent
                treeModel.removeNodeFromParent(selNode);
            }
        }
    }

    /**
     * This method returns the previous sibling node
     * if there is no previous sibling it returns the next sibling
     * if there are no siblings it returns null
     *
     * @param selNode selected node
     * @return previous or next sibling, or parent if no sibling
     */
    private MutableTreeNode getSibling(DefaultMutableTreeNode selNode) {
        //get previous sibling
        MutableTreeNode sibling = (MutableTreeNode) selNode.getPreviousSibling();

        if (sibling == null) {
            //if previous sibling is null, get the next sibling
            sibling = (MutableTreeNode) selNode.getNextSibling();
        }

        return sibling;
    }

    class UserRenderer extends DefaultTreeCellRenderer {

        @Override
        public Component getTreeCellRendererComponent(
                JTree tree,
                Object value,
                boolean sel,
                boolean expanded,
                boolean leaf,
                int row,
                boolean hasFocus) {

            super.getTreeCellRendererComponent(
                    tree, value, sel,
                    expanded, leaf, row,
                    hasFocus);
            DefaultMutableTreeNode node =
                    (DefaultMutableTreeNode) value;
            Object obj = node.getUserObject();
            setPreferredSize(new Dimension(200, 21));
            if (obj instanceof JLabel) {
                JLabel l = (JLabel) obj;
                setIcon(l.getIcon());
                if (isMe(l.getText())) {
                    setForeground(new Color(255,51,51));
                }
                setText(l.getText());
                setFont(new Font("Dialog", 0, 14));

            } else {
                setIcon(null);
                setFont(new Font("Dialog", 1, 15));
                setForeground(new Color(255, 153, 51));//new Color(102, 102, 255));
            }

            return this;
        }
    }

    /** Remove all nodes except the root node. */
    public void clear() {
        rootNode.removeAllChildren();
        treeModel.reload();
    }

    /** Remove the currently selected node. */
    public void removeCurrentNode() {
        TreePath currentSelection = tree.getSelectionPath();
        if (currentSelection != null) {
            DefaultMutableTreeNode currentNode = (DefaultMutableTreeNode) (currentSelection.getLastPathComponent());
            MutableTreeNode parent = (MutableTreeNode) (currentNode.getParent());
            if (parent != null) {
                treeModel.removeNodeFromParent(currentNode);
                return;
            }
        }

    }

    /** Add child to the currently selected node. */
    public DefaultMutableTreeNode addObject(Object child) {
        DefaultMutableTreeNode parentNode = null;
        TreePath parentPath = tree.getSelectionPath();

        if (parentPath == null) {
            parentNode = rootNode;
        } else {
            parentNode = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());
        }

        return addObject(parentNode, child, true);
    }

    public DefaultMutableTreeNode addObject(DefaultMutableTreeNode parent,
            Object child) {
        return addObject(parent, child, false);
    }

    public DefaultMutableTreeNode addObject(DefaultMutableTreeNode parent,
            Object child,
            boolean shouldBeVisible) {
        DefaultMutableTreeNode childNode =
                new DefaultMutableTreeNode(child);

        if (parent == null) {
            parent = rootNode;
        }

        //It is key to invoke this on the TreeModel, and NOT DefaultMutableTreeNode
        treeModel.insertNodeInto(childNode, parent,
                parent.getChildCount());

        //Make sure the user can see the lovely new node.
        if (shouldBeVisible) {
            tree.scrollPathToVisible(new TreePath(childNode.getPath()));
        }
        return childNode;
    }

    class UserListTimer extends TimerTask {

        public void run() {
            loadUsers();
        }
    }
}
