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

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.dom4j.DocumentHelper;
import org.dom4j.Element;
import org.dom4j.QName;
import org.jivesoftware.openfire.PacketRouter;

import org.xmpp.packet.IQ;
import org.xmpp.packet.JID;

/**
 *
 * @author developer
 */
public class PointerProcessor {

    private AvoirRealtimePlugin pl;
    ///  private ArrayList<JID> jids;
    private PacketRouter packetRouter;
    private DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();
    private DocumentBuilder documentBuilder;

    public PointerProcessor(AvoirRealtimePlugin pl) {
        this.pl = pl;
        //  jids = pl.getUsers(false);
        packetRouter = pl.getPacketRouter();
        try {
            documentBuilder = documentBuilderFactory.newDocumentBuilder();
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    private String extractPointerInfo(IQ packet) {
        String xmlContents = packet.toXML();
        return pl.getDefaultPacketProcessor().getTagText(xmlContents, "content");
    }

    public void broadCastPointer(IQ packet, String roomName) {
        IQ replyPacket = IQ.createResultIQ(packet);
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(Mode.POINTER_BROADCAST);
        queryResult.addElement("content").addText(extractPointerInfo(packet));
        replyPacket.setChildElement(queryResult);
        ArrayList<JID> jids = RUserManager.users.get(roomName);
        for (int i = 0; i < jids.size(); i++) {
            JID jid = jids.get(i);

            replyPacket.setTo(jid);
            replyPacket.setFrom(packet.getFrom());
            packetRouter.route(replyPacket);

        }
    }
}
