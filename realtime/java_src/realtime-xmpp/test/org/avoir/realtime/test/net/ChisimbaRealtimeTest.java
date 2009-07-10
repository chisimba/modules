/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.test.net;

import chrriis.dj.nativeswing.swtimpl.NativeInterface;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.MainFrame;
import org.avoir.realtime.net.ConnectionManager;
import org.junit.AfterClass;
import org.junit.BeforeClass;
import org.junit.Test;
import static org.junit.Assert.*;

/**
 *
 * @author david
 */
public class ChisimbaRealtimeTest {

    protected String roomName = "rtest";
    protected String host = "localhost";
    protected int port = 5222;
    protected String username = "dwaf";
    protected String password = "a";


    public ChisimbaRealtimeTest() {
        
        connect();
    }

    @BeforeClass
    public static void setUpClass() throws Exception {
    }

    @AfterClass
    public static void tearDownClass() throws Exception {
    }

    @Test
    public void connect() {
        NativeInterface.open();
        String audioVideoUrl = "localhost:7070/red5";
        if (ConnectionManager.init(host, port, audioVideoUrl)) {
            if (ConnectionManager.login(username, password, roomName)) {

                GUIAccessManager.setMf(new MainFrame(roomName));
            } else {
                assertFalse("Login Failed", true);
            }
        } else {
            assertFalse("Connection to server failed", true);
        }
    }
}