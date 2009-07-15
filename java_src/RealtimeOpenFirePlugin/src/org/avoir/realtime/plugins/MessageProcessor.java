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

import java.util.ArrayList;
import java.util.Collection;
import java.util.Iterator;
import org.jivesoftware.openfire.PacketRouter;
import org.jivesoftware.openfire.PresenceManager;
import org.jivesoftware.openfire.RoutingTable;
import org.jivesoftware.openfire.XMPPServer;
import org.jivesoftware.openfire.user.User;
import org.jivesoftware.openfire.user.UserManager;
import org.xmpp.packet.IQ;
import org.xmpp.packet.JID;

/**
 *
 * @author developer
 */
public class MessageProcessor {

    private AvoirRealtimePlugin pl;
    private Collection<JID> jids;
    private PacketRouter packetRouter;

    public MessageProcessor(AvoirRealtimePlugin pl) {
        this.pl = pl;
        XMPPServer xmppServer = XMPPServer.getInstance();
        UserManager userManager = xmppServer.getUserManager();
        PresenceManager presenceManager = xmppServer.getPresenceManager();
        RoutingTable routingTable = xmppServer.getRoutingTable();
        packetRouter = xmppServer.getPacketRouter();

        // use this switch to send only to all online resources
        boolean onlineUsersOnly = false;


        // collect JIDs of active resources and offline users
        String domain = xmppServer.getServerInfo().getXMPPDomain();
        Collection<User> users = userManager.getUsers();
        jids = new ArrayList<JID>(users.size());
        Iterator<User> itrUsers = users.iterator();
        while (itrUsers.hasNext()) {
            User user = itrUsers.next();
            JID jid = new JID(user.getUsername(), domain, null, true);
            if (presenceManager.isAvailable(user)) {
                // add all active resources
                jids.addAll(routingTable.getRoutes(jid, null));
            } else if (!onlineUsersOnly) {
                // send offline message to bareJID
                jids.add(jid);
            }
        }


    }

    public void broadCastMessages(IQ packet, String from) {
        // send message to all users
        //IQRouter router=pl.getIqRouter();
        Iterator<JID> itrJIDs = jids.iterator();
       while (itrJIDs.hasNext()) {
            JID jid = itrJIDs.next();
            /*Message msgPacket = new Message();
            msgPacket.setType(Message.Type.chat);
            msgPacket.setFrom(from);
            msgPacket.setTo(jid);
            msgPacket.setBody(message);*/
            System.out.println("from " + from + " send to " + jid);
          itrJIDs.remove();
          pl.getIqRouter().route(packet);
       }
    }
}
