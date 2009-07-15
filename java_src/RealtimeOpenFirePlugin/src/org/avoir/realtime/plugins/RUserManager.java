/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.plugins;

import java.util.ArrayList;
import java.util.Collection;
import java.util.HashMap;
import java.util.Map;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;
import org.dom4j.QName;
import org.jivesoftware.openfire.XMPPServer;
import org.xmpp.packet.IQ;
import org.xmpp.packet.JID;

/**
 *
 * @author developer
 */
public class RUserManager {

    public static Map<String,ArrayList<JID>> users =new HashMap<String,ArrayList<JID>>();
    public static boolean onlineUsersOnly = true;

    public static IQ getAdmins(IQ packet) {
        IQ replyPacket = IQ.createResultIQ(packet);
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(Mode.ADMIN_LIST);
        StringBuilder sb = new StringBuilder();
        XMPPServer server = XMPPServer.getInstance();
        Collection<JID> admins = server.getAdmins();
        for (JID admin : admins) {
            sb.append("<user>");
            sb.append("<userid>").append(admin).append("</userid>");
            sb.append("<username>").append(admin).append("</username>");
            sb.append("<admin>").append("true").append("</admin>");
            sb.append("</user>");
        }
        queryResult.addElement("content").addText(sb.toString());
        replyPacket.setChildElement(queryResult);
        return replyPacket;
    }

  /*  public static ArrayList<JID> getUsers() {
        //onlineUsersOnly = true;
        ArrayList<JID> jids = new ArrayList<JID>();
        XMPPServer server = XMPPServer.getInstance();
        PresenceManager presenceManager = server.getPresenceManager();

        RoutingTable routingTable = server.getRoutingTable();
        Collection<User> xusers = server.getUserManager().getUsers();
        Iterator<User> itrUsers = xusers.iterator();
        while (itrUsers.hasNext()) {
            User user = itrUsers.next();

            JID jid = server.createJID(user.getUsername(), server.getServerInfo().getHostname());
            Presence presence = presenceManager.getPresence(user);
            boolean online = presence == null ? false : true;

            jids.addAll(routingTable.getRoutes(jid, null));
            System.out.println("JID: " + jid + " online? " + online);

            if (online) {
                // add all active resources
                jids.addAll(routingTable.getRoutes(jid, null));
            } else if (!onlineUsersOnly) {
                // send offline message to bareJID
                jids.add(jid);
            }
        }
        return jids;
    }*/
}
