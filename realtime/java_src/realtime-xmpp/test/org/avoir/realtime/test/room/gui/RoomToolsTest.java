/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.test.room.gui;

import org.avoir.realtime.gui.main.Main;
import org.avoir.realtime.gui.whiteboard.Whiteboard;
import org.uispec4j.Mouse;
import org.uispec4j.Panel;
import org.uispec4j.Table;
import org.uispec4j.Trigger;
import org.uispec4j.UISpecTestCase;
import org.uispec4j.Window;
import org.uispec4j.interception.MainClassAdapter;
import org.uispec4j.interception.WindowHandler;
import org.uispec4j.interception.WindowInterceptor;

/**
 *
 * @author david
 */
public class RoomToolsTest extends UISpecTestCase {

    private Window mainWindow;
    final String roomName = "David_Wafula";

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
            "u1",
            "David Wafula",
            "davidwaf@gmail.com",
            "localhost/presentation",
            "jumped",
            "true",
            "1 "
        };

        setAdapter(new MainClassAdapter(Main.class, args));

        mainWindow = WindowInterceptor.run(new Trigger() {

            public void run() throws Exception {
                String title1 = "Virtual Room: " + roomName;

                Window window = WindowInterceptor.run(new Trigger() {

                    public void run() throws Exception {
                        Window banner = getMainWindow();
                    }
                });
            }
        });
    }

    public void testRoomTools() throws Exception {


        String title1 = "Virtual Room: " + roomName;

        Thread.sleep(1000);
        assertEquals(title1, mainWindow.getTitle());
        Thread.sleep(1000);


        //pointerz

        WindowInterceptor.init(mainWindow.getPanel("roomToolsPanel").getButton("Pointer").triggerClick()).process(new WindowHandler() {

            public Trigger process(Window pointerWindow) {
                assertEquals("Select Pointer", pointerWindow.getTitle());
                pointerWindow.getButton("arrowUp").click();
                Panel whiteBoard = mainWindow.getPanel("whiteboard");
                Mouse.click(whiteBoard);
                Whiteboard wb = ((Whiteboard) whiteBoard.getAwtComponent());
                try{Thread.sleep(1000);}catch(Exception ex){}
               // assertTrue(wb.getCurrentPointerImage().toString().endsWith("pointer-arrow_up.png" ));

                return pointerWindow.getButton("Cancel").triggerClick();
            }
        }).run();

        //Window pointerWindow = WindowInterceptor.run(mainWindow.getPanel("roomToolsPanel").getButton("Pointer").triggerClick());

        // test room tools
        Window roomListWindow = WindowInterceptor.run(mainWindow.getPanel("roomToolsPanel").getButton("Room List").triggerClick());
        assertEquals("Room List", roomListWindow.getTitle());
        Thread.sleep(3000);
        Table table = roomListWindow.getTable();
        String roomToJoin = "junit_test_room_1";
        boolean roomToJoinExists = false;
        int rowIndex = 0;
        for (int i = 0; i < table.getRowCount(); i++) {
            System.out.println("testing " + table.getContentAt(i, 0));
            if (table.getContentAt(i, 0).equals(roomToJoin)) {
                roomToJoinExists = true;
                rowIndex = i;
                System.out.println("found");
                break;
            }
        }
        assertTrue(roomToJoinExists);
        table.click(rowIndex, 0);
        roomListWindow.getButton("Join").click();
        Thread.sleep(5000);
        assertEquals(false, mainWindow.getButton("Pointer").getAwtComponent().isEnabled());




    }
}