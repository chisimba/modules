/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.test.room.gui;

import javax.swing.JTextPane;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.gui.main.Main;
import org.uispec4j.Trigger;
import org.uispec4j.UISpecTestCase;
import org.uispec4j.Window;
import org.uispec4j.interception.MainClassAdapter;
import org.uispec4j.interception.WindowInterceptor;

/**
 *
 * @author david
 */
public class ParticipantTreeListTest extends UISpecTestCase {

    @Override
    protected void setUp() throws Exception {
        super.setUp();
        String[] args = {
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

        setAdapter(new MainClassAdapter(Main.class, args));

    }

    public void testRoomActions() throws Exception {
        final String roomName = "David_Wafula";
        String username = "popo";
        String title1 = "Virtual Meeting: " + roomName;

        Window window = WindowInterceptor.run(new Trigger() {

            public void run() throws Exception {
                String title1 = "Virtual Meeting: " + roomName;

                Window window = WindowInterceptor.run(new Trigger() {

                    public void run() throws Exception {
                        Window banner = getMainWindow();
                    }
                });
            }
        });
        Thread.sleep(1000);
        assertEquals(title1, window.getTitle());
        Thread.sleep(1000);
        // test the chat
        String txt = GeneralUtil.generateRandomStr(10);
        window.getTextBox("chatInput").appendText(txt);
        window.getButton("enterButton").click();
        Thread.sleep(1000);

        String tr = window.getTextBox("chatTranscript").getText();
        System.out.println("tr:  " + tr);
        assertTrue(tr.indexOf(txt) > -1);
    }
}