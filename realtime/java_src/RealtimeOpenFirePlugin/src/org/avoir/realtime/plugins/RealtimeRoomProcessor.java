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
package org.avoir.realtime.plugins;

import java.io.ByteArrayInputStream;
import java.util.ArrayList;
import java.util.Collection;
import java.util.List;
import java.util.Map;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;


import org.dom4j.DocumentHelper;
import org.dom4j.Element;
import org.dom4j.QName;
import org.jivesoftware.openfire.XMPPServer;
import org.jivesoftware.openfire.muc.MUCRoom;
import org.jivesoftware.openfire.muc.MultiUserChatService;
import org.jivesoftware.openfire.muc.spi.LocalMUCRoom;
import org.jivesoftware.openfire.user.User;
import org.jivesoftware.openfire.user.UserNotFoundException;
import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xmpp.packet.IQ;
import org.xmpp.packet.JID;
import org.xmpp.packet.PacketError;

/**
 *
 * @author developer
 */
public class RealtimeRoomProcessor {

    private XMPPServer server = XMPPServer.getInstance();
    private AvoirRealtimePlugin pl;

    public RealtimeRoomProcessor(AvoirRealtimePlugin pl) {
        this.pl = pl;
    }

    private Collection<JID> getInvitees(IQ packet) {
        Collection<JID> invitees = new ArrayList<JID>();
        DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();
        String xmlContents = packet.toXML();
        try {
            DocumentBuilder documentBuilder = documentBuilderFactory.newDocumentBuilder();
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));

            NodeList users = doc.getElementsByTagName("user");
            for (int i = 0; i < users.getLength(); i++) {
                Node node = users.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    org.w3c.dom.Element element = (org.w3c.dom.Element) node;
                    String userId = XmlUtils.readString(element, "user-id");
                    invitees.add(server.createJID(userId, null));
                }
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return invitees;
    }

    public IQ getRooms(IQ packet) {
        IQ replyPacket = IQ.createResultIQ(packet);
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("packet-type").addText("user-list");
        queryResult.addElement("mode").addText("create-room-success");
        Element item = queryResult.addElement("rooms");
        MultiUserChatService service = server.getMultiUserChatManager().getMultiUserChatServices().get(0);
        List<MUCRoom> rooms = service.getChatRooms();
        for (MUCRoom room : rooms) {
            Element roomElement = item.addElement("room");
            Element roomId = roomElement.addElement("room-jid");

            roomId.addText(room.getJID().toString());
            Element name = roomElement.addElement("room-name");
            name.addText(room.getName());
            Element sub = roomElement.addElement("room-subject");
            sub.addText(room.getSubject());
        }
        replyPacket.setChildElement(queryResult);

        return replyPacket;
    }

    /**
     * get hold of admins
     * @param packet
     * @return
     */
    public IQ getAdmins(IQ packet) {
        IQ replyPacket = IQ.createResultIQ(packet);
        Collection<JID> admins = server.getAdmins();
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));

        queryResult.addElement("packet-type").addText("user-list");
        queryResult.addElement("mode").addText("admins");
        Element item = queryResult.addElement("users");

        for (JID admin : admins) {

            Element userElement = item.addElement("user");
            Element userId = userElement.addElement("user-id");
            userId.addText(admin.toBareJID());

        }
        replyPacket.setChildElement(queryResult);

        return replyPacket;
    }

    public IQ makeInstructor(IQ packet) {
        IQ replyPacket = IQ.createResultIQ(packet);
        Collection<User> users = server.getUserManager().getUsers();
        Element queryResult = DocumentHelper.createElement(QName.get("iq", Constants.NAME_SPACE));

        queryResult.addElement("packet-type").addText("user-list");
        queryResult.addElement("mode").addText("request-user-list");
        Element item = queryResult.addElement("users");

        for (User user : users) {
            Element userElement = item.addElement("user");
            Element userId = userElement.addElement("user-id");
            user.getProperties().put("is-instructor", "true");
            userId.addText(user.getUsername());
            Element name = userElement.addElement("name");
            name.addText(user.getName());
            Map<String, String> props = user.getProperties();
            Element inst = userElement.addElement("is-instructor");
            inst.addText(props.get("is-instructor"));
        }
        replyPacket.setChildElement(queryResult);
        return replyPacket;
    }

    public Element initUsers() {

        Collection<User> users = server.getUserManager().getUsers();
        Element queryResult = DocumentHelper.createElement(QName.get("realtime-ext2", Constants.NAME_SPACE));

        queryResult.addElement("packet-type").addText("user-list");
        queryResult.addElement("mode").addText("request-user-list");
        Element item = queryResult.addElement("users");

        for (User user : users) {
            Element userElement = item.addElement("user");
            Element userId = userElement.addElement("user-id");
            userId.addText(user.getUsername());
            Element name = userElement.addElement("name");
            name.addText(user.getName());
            Map<String, String> props = user.getProperties();
            Element inst = userElement.addElement("is-instructor");
            String val = props.get("is-instructor") == null ? "false" : props.get("is-instructor");
            inst.addText(val);
        }
        return queryResult;
    }

    private String getFormatedUsers() {
        Collection<User> users = server.getUserManager().getUsers();
        StringBuilder sb = new StringBuilder();

        for (User user : users) {
            String name = "N/A";
            if (user.getName() != null) {
                if (!user.getName().trim().equals("")) {
                    name = user.getName();
                }
            }
            sb.append("<user>");
            sb.append("<userid>").append(user.getUsername()).append("</userid>");
            sb.append("<username>").append(name).append("</username>");
            Map<String, String> props = user.getProperties();
            String val = props.get("is-instructor") == null ? "false" : props.get("is-instructor");
            sb.append("<instructor>").append(val).append("</instructor>");
            sb.append("</user>");
        }


        return sb.toString();
    }

    public IQ getUsers(IQ packet, String mode) {
        IQ replyPacket = IQ.createResultIQ(packet);
        String replyMode = "";
        if (mode.equals(Mode.REQUEST_USER_PROPERTIES)) {
            replyMode = Mode.USER_PROPERTIES;
        }
        if (mode.equals(Mode.REQUEST_USER_LIST)) {
            replyMode = Mode.USERLIST_ACCESSLEVEL_REPLY;
        }
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));


        queryResult.addElement("mode").addText(replyMode);
        Element item = queryResult.addElement("content");
        item.addText(getFormatedUsers());
        replyPacket.setChildElement(queryResult);

        return replyPacket;
    }

    public void updateUserProperties(IQ packet) {
        try {
            DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();
            String xmlContents = packet.toXML();

            DocumentBuilder documentBuilder = documentBuilderFactory.newDocumentBuilder();
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));

            NodeList usersNodeList = doc.getElementsByTagName("user");
            for (int i = 0; i < usersNodeList.getLength(); i++) {
                Node node = usersNodeList.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    try {
                        org.w3c.dom.Element element = (org.w3c.dom.Element) node;
                        String userId = XmlUtils.readString(element, "userid");
                        JID jid = server.createJID(userId, null);
                        String isInstructor = XmlUtils.readString(element, "instructor");
                        server.getUserManager().getUser(jid.toString()).getProperties().put("is-instructor", isInstructor);
                    } catch (UserNotFoundException ex) {
                        ex.printStackTrace();
                    }
                }
            }

        } catch (Exception ex) {
            ex.printStackTrace();
        }

    }

    public IQ initCreateRoom(IQ packet, Document doc) {
        IQ copy = packet.createCopy();
        String roomName = XmlUtils.readString(doc, "room-name");
        if (createRoom(roomName, getInvitees(packet), "Join class for " + roomName, packet.getFrom())) {
            return getRooms(packet);
        } else {
            IQ replyPacket = IQ.createResultIQ(copy);
            Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
            queryResult.addElement("packet-type").addText("user-list");
            queryResult.addElement("mode").addText(Mode.CREATE_ROOM_ERROR);
            replyPacket.setError(new PacketError(PacketError.Condition.not_allowed));
            replyPacket.setChildElement(queryResult);

            return pl.getDefaultPacketProcessor().getFileView(packet, "questions", "teacher",roomName);
        }
    }

    private boolean createRoom(String chatroomName, Collection<JID> invitees, String inviteReason, JID owner) {
        try {
            if (server.getMultiUserChatManager().getMultiUserChatServicesCount() == 0) {
                server.getMultiUserChatManager().createMultiUserChatService("conference", null, false);
            }

            MultiUserChatService service = server.getMultiUserChatManager().getMultiUserChatServices().get(0);

            LocalMUCRoom room = (LocalMUCRoom) service.getChatRoom(chatroomName, owner);
            room.unlock(room.getRole());
            for (JID j : invitees) {

                room.sendInvitation(j, inviteReason, room.getRole(), null);
            }
            return true;
        } catch (Exception e) {
            e.printStackTrace();
        }
        return false;
    }
}
