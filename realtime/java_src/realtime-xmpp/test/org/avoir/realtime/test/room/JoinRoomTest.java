/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.test.room;

import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.MainFrame;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.avoir.realtime.net.providers.RealtimePacketProcessor;
import org.avoir.realtime.test.net.ChisimbaRealtimeTest;
import org.jivesoftware.smack.PacketCollector;
import org.jivesoftware.smack.filter.PacketIDFilter;
import org.jivesoftware.smack.packet.Packet;
import org.junit.AfterClass;
import org.junit.BeforeClass;
import org.junit.Test;
import static org.junit.Assert.*;

/**
 *
 * @author david
 */
public class JoinRoomTest extends ChisimbaRealtimeTest {

    public JoinRoomTest() {
        super();
    }

    @BeforeClass
    public static void setUpClass() throws Exception {
    }

    @AfterClass
    public static void tearDownClass() throws Exception {
    }

    @Test
    public void requestRoomOwner() {
        try {
            String roomname = "testroom0001";

            ChatRoomManager chatRoomManager = new ChatRoomManager(roomname);
            chatRoomManager.createRoom(roomname, roomname, false, null);
            chatRoomManager.doActualJoin(username, roomname, false);


            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.REQUEST_ROOM_OWNER);
            StringBuilder sb = new StringBuilder();
            sb.append("<room-name>").append(roomname).append("</room-name>");
            p.setContent(sb.toString());
            ConnectionManager.sendPacket(p);
            PacketCollector collector = ConnectionManager.getConnection().createPacketCollector(new PacketIDFilter(p.getPacketID()));
            Packet result = collector.nextResult(2000);
            assertEquals("admin", RealtimePacketProcessor.getTag(result.toXML(), "room-owner"));
        } catch (Exception ex) {
            ex.printStackTrace();
            fail(ex.getMessage());
        }
    }
}