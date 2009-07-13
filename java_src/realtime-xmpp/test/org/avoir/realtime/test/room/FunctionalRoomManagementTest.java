/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.test.room;

import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.test.net.ChisimbaRealtimeTest;
import org.junit.AfterClass;
import org.junit.BeforeClass;
import org.junit.Test;
import static org.junit.Assert.*;

/**
 *
 * @author david
 */
public class FunctionalRoomManagementTest extends ChisimbaRealtimeTest {

    public FunctionalRoomManagementTest() {
        super();
    }

    @BeforeClass
    public static void setUpClass() throws Exception {
    }

    @AfterClass
    public static void tearDownClass() throws Exception {
    }

    /**
     *create and destroy room
     */
    @Test
    public void createDestroyRoom() {
        try {
            String roomname = "testroom0001";

            ChatRoomManager chatRoomManager = new ChatRoomManager(roomname);
            chatRoomManager.createRoom(roomname, roomname, false, null);
            chatRoomManager.doActualJoin(roomname, false);
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
            chatRoomManager.doActualJoin(roomname, false);
            assertEquals(false, chatRoomManager.destroyRoom(roomname));
        } catch (Exception ex) {
            ex.printStackTrace();
            fail(ex.getMessage());
        }
    }
}