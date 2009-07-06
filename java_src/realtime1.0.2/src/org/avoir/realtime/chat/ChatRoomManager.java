package org.avoir.realtime.chat;

import java.awt.Color;
import java.io.File;
import java.util.ArrayList;
import java.util.Collection;
import java.util.Iterator;
import java.util.List;
import javax.swing.ImageIcon;
import javax.swing.JOptionPane;
import javax.swing.JPasswordField;
import javax.swing.SwingUtilities;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.RealtimeFxInt;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.StandAloneManager;
import org.avoir.realtime.gui.main.WebPresentManager;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.avoir.realtime.startup.Startup;
import org.jivesoftware.smack.SmackConfiguration;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.XMPPException;
import org.jivesoftware.smack.packet.Message;
import org.jivesoftware.smack.packet.XMPPError;
import org.jivesoftware.smackx.Form;
import org.jivesoftware.smackx.FormField;
import org.jivesoftware.smackx.muc.DiscussionHistory;
import org.jivesoftware.smackx.muc.HostedRoom;
import org.jivesoftware.smackx.muc.InvitationListener;
import org.jivesoftware.smackx.muc.MultiUserChat;
import org.jivesoftware.smackx.muc.ParticipantStatusListener;
import org.jivesoftware.smackx.muc.RoomInfo;

public class ChatRoomManager {

    private MultiUserChat muc;
    private ChatRoom chatRoom;
    private ImageIcon infoIcon = ImageUtil.createImageIcon(this, "/images/info.png");
    private static boolean admin = false;
    public static boolean instructor = false;
    public static Collection<String> instructors = new ArrayList<String>();
    public static String currentRoomName = "";
    private static ArrayList<RoomListener> roomListeners = new ArrayList<RoomListener>();
    private ChatPopup chatPopup = new ChatPopup();
    public static String oldRoom = "";
    private static boolean guiAccessDone = false;
    private char[] roomPassword;
    private RealtimeFxInt realtimeFxInt;

    public ChatRoomManager(RealtimeFxInt realtimeFxInt) {
        this.realtimeFxInt = realtimeFxInt;
        chatRoom = new ChatRoom(this);
    }

    public static void addRoomListener(RoomListener roomListener) {
        roomListeners.add(roomListener);
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

    public void notifyListenersOfJoining(String user) {
        for (RoomListener roomListener : roomListeners) {
            roomListener.joined(user);
        }
    }

    public void notifyListenersOfLeaving(String user) {
        for (RoomListener roomListener : roomListeners) {
            roomListener.left(user);
        }
    }

    public void notifyListenersOfSlide(String imagePath) {
        for (RoomListener roomListener : roomListeners) {
            System.out.print("send to " + roomListener);
            roomListener.processSlide(imagePath);
        }
    }

    public void notifyListenersOfMessages(String from, String time, String message) {
        for (RoomListener roomListener : roomListeners) {
            roomListener.processMessage(from, time, message);
        }
    }

    private void addRoomInvitationsListener() {
        // User3 listens for MUC invitations
        MultiUserChat.addInvitationListener(ConnectionManager.getConnection(), new InvitationListener() {

            public void invitationReceived(XMPPConnection conn, String room, String inviter, String reason, String password) {
                System.out.println(inviter + " " + reason + " to " + room);
            }

            public void invitationReceived(XMPPConnection conn, String room, String inviter, String reason, String password, Message message) {
                System.out.println(inviter + "; " + reason + " ;to; " + room);
            }
        });

    }

    private void addParticipantsListener() {
        muc.addParticipantStatusListener(new ParticipantStatusListener() {

            public void joined(String jid) {

                String user = jid.substring(jid.lastIndexOf("/") + 1);
                if (user.startsWith("jointest:")) {
                    return;
                }
                int i = user.lastIndexOf(":");
                if (i > -1) {
                    String[] s = user.split(":");
                    user = s[1];
                }
                String xjid = user + "@" + ConnectionManager.getConnection().getServiceName();
                // user = user + "(" + ConnectionManager.getEmail(xjid) + ")";
                realtimeFxInt.addUserToParticipantList(user, jid, "nomail", "nolocation");
                notifyListenersOfJoining(user);
            }

            public void left(String jid) {

                String user = jid.substring(jid.lastIndexOf("/") + 1);

                String xjid = user + "@" + ConnectionManager.getConnection().getServiceName();

                int i = user.lastIndexOf(":");
                if (i > -1) {

                    String[] s = user.split(":");
                    if (s.length > 2) {

                        user = s[2];

                    }
                }
                GUIAccessManager.mf.getUserListPanel().getUserListTree().removeUser(user);
                GUIAccessManager.mf.removeSpeaker(user);
                notifyListenersOfLeaving(user);
            }

            public void kicked(String participant, String actor, String reason) {
                chatRoom.insertSystemMessage(participant + " kicked out " + actor + ". Message: " + reason);
            }

            public void voiceGranted(String arg0) {
            }

            public void voiceRevoked(String arg0) {
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
        });
    }

    public boolean destroyRoom(String roomName) {
        String currentRoom = currentRoomName;
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

    public boolean doTestJoin(String nickname, String roomName) {
        nickname = "jointest:" + nickname;

        boolean result = false;
        try {
            MultiUserChat xmuc = new MultiUserChat(ConnectionManager.getConnection(), roomName + "@conference." + ConnectionManager.getConnection().getServiceName());
            String password = null;
            if (roomRequiresPassword(roomName)) {
                // JPasswordField passwordField = new JPasswordField();
                // JOptionPane.showMessageDialog(null, passwordField, "Enter " + roomName + "'s password", JOptionPane.INFORMATION_MESSAGE);
                // password = new String(passwordField.getPassword());
                xmuc.join(nickname, password, null, SmackConfiguration.getPacketReplyTimeout());

            } else {
                xmuc.join(nickname);
            }
            result = xmuc.isJoined();
            xmuc.leave();
        } catch (XMPPException ex) {
            System.out.println(ex.getMessage());
        }

        return result;

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
        //doActualJoin(nickname, roomName, false);
        Startup.startRoomService(roomName, nickname, nickname, roomName, false);
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
        if (WebPresentManager.isPresenter || StandAloneManager.isAdmin) {
            createRoom(roomName, ConnectionManager.fullnames, false, null);
        }
        JPasswordField passwordField = new JPasswordField();
        nickname = nickname + ":" + ConnectionManager.fullnames;

        //not allawed in more than one room at a time, so must leave old one
        if (muc != null) {
            muc.leave();
        }
        muc = new MultiUserChat(ConnectionManager.getConnection(), roomName + "@conference." + ConnectionManager.getConnection().getServiceName());
        addParticipantsListener();
        //addRoomInvitationsListener();
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
            muc.join(nickname, password, history, SmackConfiguration.getPacketReplyTimeout());
            currentRoomName = roomName;
            ConnectionManager.setRoomName(roomName);
            new File(Constants.HOME + "/rooms/" + currentRoomName).mkdirs();
            requestRoomOwner(roomName);
         

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

    public void requestRoomResources() {
        RealtimePacket p = new RealtimePacket();
        //get room specific resources
        p.setMode(RealtimePacket.Mode.REQUEST_ROOM_RESOURCES);
        StringBuilder sb = new StringBuilder();
        sb.append("<room-name>").append(currentRoomName).append("</room-name>");
        p.setContent(sb.toString());
        ConnectionManager.getConnection().sendPacket(p);
    }

    private void setAccessLevel(boolean requestslides) {
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

        doGUIAccessLevel();
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

    public static boolean isInstructor(String username) {
        boolean result = false;

        for (String usr : instructors) {

            if (usr.equalsIgnoreCase(username)) {
                result = true;
            }

        }
        return result;
    }

    public static void doGUIAccessLevel() {
        instructors.clear();
//        GUIAccessManager.mf.resetGUIccess();
        try {
            GUIAccessManager.mf.getWebPresentNavigator().populateWithRoomResources();
        } catch (Exception ex) {
            // ex.printStackTrace();
        }
        /*if (guiAccessDone) {
        return;
        }*/


        if (WebPresentManager.isPresenter || StandAloneManager.isAdmin) {
            instructors.add(ConnectionManager.getUsername());
        }
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                Thread t = new Thread() {

                    public void run() {
                        if (WebPresentManager.isPresenter || StandAloneManager.isAdmin) {
                           /* GUIAccessManager.mf.showInstructorToolbar();
                            GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setDrawEnabled(true);
                            GUIAccessManager.mf.getWhiteboardPanel().addSlideViewerNavigator();
                            GUIAccessManager.mf.setWebBrowserEnabled(true);
                            enableMenus(true);*/
                        } else {
                            enableMenus(false);
                            /*GUIAccessManager.mf.showParticipantToolbar();
                            GUIAccessManager.setMenuEnabled(true, "screenviewer");
                            GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setDrawEnabled(false);
                            //   GUIAccessManager.mf.getUserListPanel().initAudioVideo(false, currentRoomName);
                            GUIAccessManager.mf.setWebBrowserEnabled(false);
                             * */
                        }
                    }
                };
                t.start();
            }
        });
        guiAccessDone = true;
    }

    private static void enableMenus(boolean enable) {
        GUIAccessManager.setMenuEnabled(enable, "screenShot");
        GUIAccessManager.setMenuEnabled(enable, "screenshare");
        GUIAccessManager.setMenuEnabled(enable, "screenviewer");
        GUIAccessManager.setMenuEnabled(enable, "schedule");
        GUIAccessManager.setMenuEnabled(!enable, "privatechat");
        GUIAccessManager.setMenuEnabled(enable, "createRoom");
        GUIAccessManager.setMenuEnabled(enable, "roomList");
        GUIAccessManager.setMenuEnabled(enable, "actions");
        GUIAccessManager.setMenuEnabled(true, "joinRoom"); ///for every one
        GUIAccessManager.setMenuEnabled(enable, "invitationLink");
        GUIAccessManager.setMenuEnabled(enable, "insertGraphic");
        GUIAccessManager.setMenuEnabled(enable, "insertPresentation");
        GUIAccessManager.setMenuEnabled(enable, "roomResources");
        GUIAccessManager.setMenuEnabled(true, "requestMIC");
        GUIAccessManager.setMenuEnabled(enable, "addroommembers");

    }

    public boolean isAdmin() {
        return admin;
    }

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

    public void sendMessage(Message message) {
        try {
            muc.sendMessage(message);
        } catch (XMPPException ex) {
            ex.printStackTrace();
        }
    }
}