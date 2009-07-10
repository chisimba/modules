/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.test.room;

import org.avoir.realtime.chat.ChatRoomManager;
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
public class DestroyRoom extends ChisimbaRealtimeTest {

    public DestroyRoom() {
        super();
    }

    @BeforeClass
    public static void setUpClass() throws Exception {
    }

    @AfterClass
    public static void tearDownClass() throws Exception {
    }

    @Test
    public void destroyRoom() {
        try {
            String roomname = "testroom0001";

            ChatRoomManager chatRoomManager = new ChatRoomManager(roomname);
            chatRoomManager.createRoom(roomname, roomname, false, null);
            chatRoomManager.doActualJoin(username, roomname, false);
            assertEquals(true, chatRoomManager.destroyRoom(roomname));
        } catch (Exception ex) {
            ex.printStackTrace();
            fail(ex.getMessage());
        }
    }

    /**
     * Make sure you create room testroom0003 using a different user
     */
    @Test
    public void destroyRoomNotOwnedByMe() {
        try {
            String roomname = "testroom003";
            ChatRoomManager chatRoomManager = new ChatRoomManager(roomname);
            chatRoomManager.doActualJoin(username, roomname, false);
            assertEquals(true, chatRoomManager.destroyRoom(roomname));
        } catch (Exception ex) {
            ex.printStackTrace();
            fail(ex.getMessage());
        }
    }
}