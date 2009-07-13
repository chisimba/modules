/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.test.net;

import chrriis.dj.nativeswing.swtimpl.NativeInterface;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.MainFrame;
import org.avoir.realtime.gui.main.WebPresentManager;
import org.avoir.realtime.net.ConnectionManager;
import org.junit.AfterClass;
import org.junit.BeforeClass;
import static org.junit.Assert.*;

/**
 *
 * @author david
 */
public class ChisimbaRealtimeTest {

    /*    protected String roomName = "rtest";
    protected String host = "localhost";
    protected int port = 5222;
     */
    protected ConnectionManager connect2=ConnectionManager.getInstance();
    protected GUIAccessManager gUIAccessManager2=GUIAccessManager.getInstance();
    protected String username = "dwaf";
    protected String password = "a";
    protected String[] args = {
        "localhost",
        "5222",
        "localhost:7070/red5",
        "default",
        "u1",
        "/",
        "yes",
        "12",
        "popo",
        "David Wafula",
        "davidwaf@gmail.com",
        "localhost/presentation",
        "jumped",
        "true",
        "1 "
    };

    public ChisimbaRealtimeTest() {

        connect();
    }

    @BeforeClass
    public static void setUpClass() throws Exception {
    }

    @AfterClass
    public static void tearDownClass() throws Exception {
    }

        public void connect() {

        ///  NativeInterface.open();
        String host = args[0];
        int port = 5222;
        try {
            port = Integer.parseInt(args[1]);
        } catch (NumberFormatException ex) {
            ex.printStackTrace();
        }

        String audioVideoUrl = args[2];
        String defaultRoomName = args[3];

        String slidesDir = args[5];
        WebPresentManager.isPresenter = args[6].equals("yes");
        WebPresentManager.presentationId = args[7];
        WebPresentManager.presentationName = args[8];

        String names = args[9];
        //if you are presenter, then room name is your names by default
        if (WebPresentManager.isPresenter) {
            defaultRoomName = GeneralUtil.formatStr(names, " ");
            System.out.println("You are presenter, default room name set to " + defaultRoomName);
        }

        String email = args[10];
        ConnectionManager.inviteUrl = args[11];
        String undefined = args[12];
        String xuseEc2 = args[13].trim().toLowerCase();
        String joinMeetingId = args[14];


        NativeInterface.open();

        if (ConnectionManager.init(host, port, audioVideoUrl)) {
            if (ConnectionManager.login(username, password, defaultRoomName)) {

                GUIAccessManager.setMf(new MainFrame(defaultRoomName));

            } else {
                assertFalse("Login Failed", true);
            }
        } else {
            assertFalse("Connection to server failed", true);
        }
    }
    public void connect2() {

        ///  NativeInterface.open();
        String host = args[0];
        int port = 5222;
        try {
            port = Integer.parseInt(args[1]);
        } catch (NumberFormatException ex) {
            ex.printStackTrace();
        }

        String audioVideoUrl = args[2];
        String defaultRoomName = args[3];

        String slidesDir = args[5];
        WebPresentManager.isPresenter = args[6].equals("yes");
        WebPresentManager.presentationId = args[7];
        WebPresentManager.presentationName = args[8];

        String names = args[9];
        //if you are presenter, then room name is your names by default
        if (WebPresentManager.isPresenter) {
            defaultRoomName = GeneralUtil.formatStr(names, " ");
            System.out.println("You are presenter, default room name set to " + defaultRoomName);
        }

        String email = args[10];
        connect2.inviteUrl = args[11];
        String undefined = args[12];
        String xuseEc2 = args[13].trim().toLowerCase();
        String joinMeetingId = args[14];


        NativeInterface.open();

        if (connect2.init(host, port, audioVideoUrl)) {
            if (connect2.login(username, password, defaultRoomName)) {

                gUIAccessManager2.setMf(new MainFrame(defaultRoomName));

            } else {
                assertFalse("Login Failed", true);
            }
        } else {
            assertFalse("Connection to server failed", true);
        }
    }
}