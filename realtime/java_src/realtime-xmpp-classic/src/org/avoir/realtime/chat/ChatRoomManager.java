package org.avoir.realtime.chat;

import java.awt.Color;
import java.awt.Dimension;
import java.awt.Toolkit;
import java.io.File;
import java.util.ArrayList;
import java.util.Collection;
import java.util.Iterator;
import java.util.List;
import javax.swing.ImageIcon;
import javax.swing.JOptionPane;
import javax.swing.JPasswordField;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.StandAloneManager;
import org.avoir.realtime.gui.main.WebPresentManager;
import org.avoir.realtime.gui.room.CreateRoomDialog;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.jivesoftware.smack.SmackConfiguration;
import org.jivesoftware.smack.XMPPException;
import org.jivesoftware.smack.packet.Message;
import org.jivesoftware.smack.packet.XMPPError;
import org.jivesoftware.smackx.Form;
import org.jivesoftware.smackx.FormField;
import org.jivesoftware.smackx.muc.DiscussionHistory;
import org.jivesoftware.smackx.muc.HostedRoom;
import org.jivesoftware.smackx.muc.MultiUserChat;
import org.jivesoftware.smackx.muc.ParticipantStatusListener;
import org.jivesoftware.smackx.muc.RoomInfo;

public class ChatRoomManager {

    private MultiUserChat muc;
    private ChatRoom chatRoom;
    private ImageIcon infoIcon = ImageUtil.createImageIcon(this, "/images/info.png");
    public static String currentRoomName = "";
    private ChatPopup chatPopup = new ChatPopup();
    public static String oldRoom = "";
    private char[] roomPassword;
    private RParticipantStatusListener participantStatusListener = new RParticipantStatusListener();
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();

    public ChatRoomManager(String roomName) {
        muc = new MultiUserChat(ConnectionManager.getConnection(), roomName + "@conference." + ConnectionManager.getConnection().getServiceName());

        chatRoom = new ChatRoom(this);
    }

    public void setMuc(MultiUserChat muc) {
        this.muc = muc;
    }

    public MultiUserChat getMuc() {
        return muc;
    }

    public ChatRoom getChatRoom() {
        return chatRoom;
    }

    public char[] getRoomPassword() {
        return roomPassword;
    }

    public boolean destroyRoom(String roomName) {
        MultiUserChat xmuc = new MultiUserChat(ConnectionManager.getConnection(), roomName + "@conference." + ConnectionManager.getConnection().getServiceName());
        try {
            xmuc.join(ConnectionManager.getUsername());
            xmuc.destroy("No longer needed", null);
            return true;
        } catch (XMPPException ex) {
            ex.printStackTrace();
        }

        return false;
    }

    private void addParticipantsListener() {
        muc.addParticipantStatusListener(participantStatusListener);
    }

    class RParticipantStatusListener implements ParticipantStatusListener {

        public void joined(String jid) {
            String user = jid.substring(jid.lastIndexOf("/") + 1);
            GUIAccessManager.mf.getUserListPanel().getParticipantListTable().addUser(user);

            //GUIAccessManager.mf.removeSpeaker(user);
            String xuser = user.split(":")[1];
            chatRoom.insertSystemMessage(xuser + " joined.");
            GeneralUtil.showChatPopup(currentRoomName, xuser + " joined.",false);
        }

        public void left(String jid) {
            String xuser = jid.substring(jid.lastIndexOf("/") + 1);
            GUIAccessManager.mf.getUserListPanel().getParticipantListTable().removeUser(xuser);
            String username = xuser.split(":")[0];
            String names = xuser.split(":")[1];
            GUIAccessManager.mf.removeSpeaker(username);
            chatRoom.insertSystemMessage(names + " left.");
            GeneralUtil.showChatPopup(currentRoomName, names + " left.",false);

        }

        public void kicked(String participant, String actor, String reason) {
            // chatRoom.insertSystemMessage(participant + " kicked out " + actor + ". Message: " + reason);
        }

        public void voiceGranted(String participant) {
        }

        public void voiceRevoked(String participant) {

            ;
        }

        public void banned(String participant, String actor, String reason) {
            chatRoom.insertSystemMessage(participant + " kicked out " + actor + ". Message: " + reason);

        }

        public void membershipGranted(String arg0) {
        }

        public void membershipRevoked(String arg0) {
        }

        public void moderatorGranted(String arg0) {
        }

        public void moderatorRevoked(String arg0) {
        }

        public void ownershipGranted(String arg0) {
        }

        public void ownershipRevoked(String arg0) {
        }

        public void adminGranted(String arg0) {
        }

        public void adminRevoked(String arg0) {
        }

        public void nicknameChanged(String arg0, String arg1) {
        }
    }

    public void cgrantVoice(boolean state, String nickname) {
        try {
            if (state) {

                muc.grantVoice(nickname);
            } else {
                muc.revokeVoice(nickname);
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    public boolean createRoom(String roomName, String desc, boolean requirePassword, String password) {
        return createRoom(roomName, desc, 30, requirePassword, password);
    }

    public boolean createRoom(String roomName, String desc, int maxMembers,
            boolean requirePassword, String password) {

        muc = new MultiUserChat(ConnectionManager.getConnection(), roomName + "@conference." + ConnectionManager.getConnection().getServiceName());

        try {
            Collection<HostedRoom> rooms = MultiUserChat.getHostedRooms(ConnectionManager.getConnection(), "conference." + ConnectionManager.getConnection().getServiceName());
            for (HostedRoom room : rooms) {

                if (roomName.equalsIgnoreCase(room.getName())) {
                    return false;
                }

            }
        } catch (XMPPException ex) {
            ex.printStackTrace();
        }
        try {

            muc.create(roomName);
        } catch (XMPPException ex) {
            ex.printStackTrace();
        }
        try {
            Form form = muc.getConfigurationForm();
            Form newForm = form.createAnswerForm();
            for (Iterator fields = form.getFields(); fields.hasNext();) {
                FormField field = (FormField) fields.next();
                if (!FormField.TYPE_HIDDEN.equals(field.getType()) && field.getVariable() != null) {

                    newForm.setDefaultAnswer(field.getVariable());
                }
            }
            newForm.setAnswer("muc#roomconfig_publicroom", true);
            newForm.setAnswer("muc#roomconfig_passwordprotectedroom", false);
            newForm.setAnswer("x-muc#roomconfig_registration", false);
            newForm.setAnswer("muc#roomconfig_persistentroom", true); //IF I SET THIS TO FALSE, I GET THE 404.
            newForm.setAnswer("muc#roomconfig_enablelogging", true);
            newForm.setAnswer("muc#roomconfig_membersonly", false);
            newForm.setAnswer("muc#roomconfig_roomdesc", desc);
            if (requirePassword) {
                newForm.setAnswer("muc#roomconfig_passwordprotectedroom", true);
                newForm.setAnswer("muc#roomconfig_roomsecret", password);
            }
            List<String> maxNumbersField = new ArrayList<String>();
            maxNumbersField.add(maxMembers + "");

            newForm.setAnswer("muc#roomconfig_maxusers", maxNumbersField);
            String roomType = "Open";
            if (requirePassword) {
                roomType = "Locked";
            }

            storeRoomInfo(roomName, desc, ConnectionManager.getUsername(), roomType);
            muc.sendConfigurationForm(newForm);
            return true;
        } catch (XMPPException ex) {
            ex.printStackTrace();
        }
        return false;
    }

    private void storeRoomInfo(String roomName, String roomDesc, String owner, String roomType) {
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.NEW_ROOM);
        StringBuilder sb = new StringBuilder();
        sb.append("<room-name>").append(roomName).append("</room-name>");
        sb.append("<room-desc>").append(roomDesc).append("</room-desc>");
        sb.append("<room-owner>").append(owner).append("</room-owner>");
        sb.append("<room-type>").append(roomType).append("</room-type>");
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);
    }

    public void invite(String user, String message) {
        muc.invite(user, message);
    }

    public boolean roomRequiresPassword(String roomName) {
        try {
            Collection<HostedRoom> hostedRooms = MultiUserChat.getHostedRooms(ConnectionManager.getConnection(), "conference." + ConnectionManager.getConnection().getServiceName());

            for (HostedRoom room : hostedRooms) {
                RoomInfo info = MultiUserChat.getRoomInfo(ConnectionManager.getConnection(), room.getJid());
                String xroomName = room.getName();
                if (xroomName.equals(roomName)) {
                    return info.isPasswordProtected();

                }
            }
        } catch (XMPPException ex) {
            ex.printStackTrace();
        }
        return false;
    }

    public boolean kick(String nickname) {
        try {

            muc.kickParticipant(nickname, "You have been kicked out.");
            return true;
        } catch (XMPPException ex) {
            ex.printStackTrace();
        }
        return false;
    }

    public boolean ban(String nickname) {
        try {

            muc.banUser(nickname + "@" + ConnectionManager.getConnection().getServiceName(), "You have been banned from this room.");

            return true;
        } catch (XMPPException ex) {
            ex.printStackTrace();
        }
        return false;
    }

    private String lookForLocalCopyLocation() {
        String roomsDir = Constants.HOME + "/rooms/";
        String[] rooms = new File(roomsDir).list();
        for (String room : rooms) {
            String slidesDir = Constants.HOME + "/rooms/" + room + "/" + WebPresentManager.presentationId;
            if (new File(slidesDir).exists()) {
                return room;
            }
        }
        return null;
    }

    public boolean roomExists(String roomName) {
        try {
            Collection<HostedRoom> hostedRooms = MultiUserChat.getHostedRooms(ConnectionManager.getConnection(), "conference." + ConnectionManager.getConnection().getServiceName());

            for (HostedRoom room : hostedRooms) {
                String xroomName = room.getName();
                if (xroomName.equalsIgnoreCase(roomName)) {
                    return true;

                }
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return false;
    }

    public void joinAsParticipant(String nickname, String roomName) {
        if (!roomExists(roomName)) {
            JOptionPane.showMessageDialog(null, "Room '" + roomName + "' does not exist");
            return;
        }
        StandAloneManager.isAdmin = false;
        WebPresentManager.isPresenter = false;
        doActualJoin(nickname, roomName, false);
    }

    private void requestRoomOwner(String roomName) {
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.REQUEST_ROOM_OWNER);
        StringBuilder sb = new StringBuilder();
        sb.append("<room-name>").append(roomName).append("</room-name>");
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);
    }

    public boolean doActualJoin(String nickname, String roomName, boolean requestslides) {
        if (!roomExists(roomName)) {
            if (WebPresentManager.isPresenter || StandAloneManager.isAdmin) {
                CreateRoomDialog fr = new CreateRoomDialog(roomName, ConnectionManager.fullnames, false);
                fr.setSize((ss.width / 2) * 1, (ss.height / 2) * 1);
                fr.setLocationRelativeTo(GUIAccessManager.mf);
                fr.setVisible(true);
            } else {
                JOptionPane.showMessageDialog(null, "Room '" + roomName + "' is not active yet or does not exists. The application will exit.");
                System.exit(0);
            }
        }
        JPasswordField passwordField = new JPasswordField();
        nickname = ConnectionManager.fullnames;

        muc.removeParticipantStatusListener(participantStatusListener);
        GUIAccessManager.mf.getUserListPanel().getParticipantListTable().clear();
        //release MIC too
        GUIAccessManager.mf.releaseMIC();
        chatRoom.getChatTranscriptField().setText("");
        GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().clearWhiteboard();
        //not allawed in more than one room at a time, so must leave old one
        muc.leave();
        muc = new MultiUserChat(ConnectionManager.getConnection(), roomName + "@conference." + ConnectionManager.getConnection().getServiceName());
        addParticipantsListener();
        DiscussionHistory history = new DiscussionHistory();
        history.setMaxStanzas(5);
        try {
            if (nickname == null) {
                JOptionPane.showMessageDialog(null, "You dont have a name set.\n" +
                        "You will not be able to start/join a meeeting.");
            }
            if (nickname.trim().equals("")) {
                JOptionPane.showMessageDialog(null, "You dont have a name set.\n" +
                        "You will not be able to start/join a meeeting.");

            }
            String password = null;
            if (roomRequiresPassword(roomName.trim())) {

                JOptionPane.showMessageDialog(null, passwordField, "Enter room password", JOptionPane.INFORMATION_MESSAGE);
                roomPassword = passwordField.getPassword();
                password = new String(roomPassword);
            }
            muc.join(ConnectionManager.getUsername() + ":" + nickname, password, history, SmackConfiguration.getPacketReplyTimeout());

            //GUIAccessManager.mf.getChatTabbedPane().setTitleAt(0, ConnectionManager.fullnames);
            GUIAccessManager.mf.getUserListPanel().getRoomInfoField().setText("<html>You are in <font color=\"#ff6600\">" + roomName.toUpperCase() + "</font></html>");
            GUIAccessManager.mf.getUserListPanel().getRoomInfoField().setIcon(infoIcon);
            currentRoomName = roomName;
            ConnectionManager.setRoomName(roomName);
            new File(Constants.HOME + "/rooms/" + currentRoomName).mkdirs();


            GUIAccessManager.mf.setTitle("Virtual Meeting: " + nickname);
            GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setSlideImage(null);
            sendMessage(ConnectionManager.fullnames + " joined", 10, Color.LIGHT_GRAY, "sys-text");

            requestRoomOwner(roomName);
            requestRoomResources(requestslides);
            GUIAccessManager.mf.getSoundMonitor().init();
            return true;
        } catch (XMPPException ex) {
            XMPPError errorCode = ex.getXMPPError();
            if (errorCode != null) {
                switch (errorCode.getCode()) {
                    case 407:
                        JOptionPane.showMessageDialog(null, "This is a members-only room and you are not allowed to join.");
                    case 401:
                        JOptionPane.showMessageDialog(null, "You are not authorized to join this room.");
                    case 404:
                        JOptionPane.showMessageDialog(null, "This room is not available for joining.");
                }
            }
            ex.printStackTrace();
        }

        return false;
    }

    private void requestRoomResources(boolean requestslides) {

        RealtimePacket p = new RealtimePacket();
        //get room specific resources
        p.setMode(RealtimePacket.Mode.REQUEST_ROOM_RESOURCES);
        StringBuilder sb = new StringBuilder();
        sb.append("<room-name>").append(currentRoomName).append("</room-name>");
        p.setContent(sb.toString());
        ConnectionManager.getConnection().sendPacket(p);

        //is this launched as web present, then get the details
        //to prevent subsequent downlaods, there might be a copy, fisrt look for it
        String localCopyLocation = lookForLocalCopyLocation();
        if (localCopyLocation != null) {
            oldRoom = localCopyLocation;
            requestslides = false;
        }
        if (WebPresentManager.hasBeenLaunchedAsWebPresent) {
            String thisPresentation = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName + "/" + WebPresentManager.presentationId;

            if (!new File(thisPresentation).exists() && requestslides) {
                requestNewSlides("");
            }
            if (!new File(thisPresentation).exists() && !requestslides) {
                String oldRoomPresentation = Constants.HOME + "/rooms/" + oldRoom + "/" + WebPresentManager.presentationId;
                File srcDir = new File(oldRoomPresentation);
                File desDir = new File(thisPresentation);
                try {
                    GeneralUtil.copyDirectory(srcDir, desDir);
                } catch (Exception ex) {
                    ex.printStackTrace();
                    requestNewSlides("");
                }
            }

        }


    }

    public void requestNewSlides(String ext) {
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.REQUEST_WEBPRESENT_SLIDES);
        StringBuilder sb = new StringBuilder();
        sb.append("<slides-dir>").append(WebPresentManager.slidesDir).append("</slides-dir>");
        sb.append("<presentation-name>").append(WebPresentManager.presentationName + ext).append("</presentation-name>");
        sb.append("<presentation-id>").append(WebPresentManager.presentationId).append("</presentation-id>");
        p.setContent(sb.toString());

        ConnectionManager.getConnection().sendPacket(p);
    }

    public ChatPopup getChatPopup() {
        return chatPopup;
    }

    /*  public void doGUIAccessLevel() {

    SwingUtilities.invokeLater(new Runnable() {

    public void run() {
    Thread t = new Thread() {

    public void run() {
    if (WebPresentManager.isPresenter || StandAloneManager.isAdmin) {
    //  GUIAccessManager.mf.showInstructorToolbar();
    GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setDrawEnabled(true);
    // GUIAccessManager.mf.getWhiteboardPanel().addSlideViewerNavigator();
    GUIAccessManager.mf.setWebBrowserEnabled(true);
    enableMenus(true);
    } else {
    enableMenus(false);
    // GUIAccessManager.mf.showParticipantToolbar();
    GUIAccessManager.setMenuEnabled(true, "screenviewer");
    GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setDrawEnabled(false);
    GUIAccessManager.mf.getUserListPanel().initAudioVideo(false, currentRoomName);
    GUIAccessManager.mf.setWebBrowserEnabled(false);
    }

    sendMessage(ConnectionManager.fullnames + " joined", 10, Color.LIGHT_GRAY, "sys-text");
    }
    };
    t.start();
    }
    });

    }*/
    public boolean sendMessage(String str, int size, Color color) {
        try {
            Message msg = muc.createMessage();
            msg.setBody(str);
            msg.setProperty("size", new Integer(size));
            msg.setProperty("color", color);
            muc.sendMessage(msg);
            return true;
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return false;
    }

    public boolean sendMessage(String str, int size, Color color, String type) {
        try {
            Message msg = muc.createMessage();
            msg.setBody(str);
            msg.setProperty("size", new Integer(size));
            msg.setProperty("color", color);
            msg.setProperty("message-type", type);
            muc.sendMessage(msg);
            return true;
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return false;
    }

    public void sendParticipantCleanupMessage() {
        Message msg = muc.createMessage();
        msg.setBody("Userlist cleanup request from " + ConnectionManager.fullnames);
        msg.setProperty("size", new Integer(9));
        msg.setProperty("color", Color.LIGHT_GRAY);
        msg.setProperty("message-type", "sys-participant-cleanup");
        sendMessage(msg);
    }

    public void sendMessage(Message message) {
        try {
            muc.sendMessage(message);
        } catch (XMPPException ex) {
            ex.printStackTrace();
        }
    }
}
