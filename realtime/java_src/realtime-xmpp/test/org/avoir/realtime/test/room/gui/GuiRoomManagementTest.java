/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.test.room.gui;

import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.gui.room.CreateRoomDialog;
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
public class GuiRoomManagementTest extends UISpecTestCase {

    public GuiRoomManagementTest() {
    }

    @Override
    protected void setUp() throws Exception {
        String[] args = {"localhost", "5222", "localhost"};
        setAdapter(new MainClassAdapter(CreateRoomDialog.class, args));

    }

    /**
     * This test involves:
     * 1. Running create Room Frame
     * 2. Filling in and creating three different types of rooms
     * 3. Running room list
     * 4. Veryfying the created rooms exist
     * @throws java.lang.Exception
     */
    public void testCreateDeleteRoom() throws Exception {
        //get main window
        final Window mainWindow = getMainWindow();
        final String roomName = GeneralUtil.generateRandomStr(6).toLowerCase();
        System.out.println(mainWindow.getTitle());

        mainWindow.getTextBox("roomName").setText(roomName);
        /*Window roomList = WindowInterceptor.run(new Trigger() {
        public void run() {
        mainWindow.getTextBox("roomName").setText(roomName);
        }
        });*/

        //create room and display updated room list

        Window roomList = WindowInterceptor.run(mainWindow.getButton("Create Room").triggerClick());
        Thread.sleep(2000);
        //created room should be last in list
        Table table = roomList.getTable();
        assertEquals(roomName, (String) table.getContentAt(table.getRowCount() - 1, 0));
        table.click(table.getRowCount() - 1, 0);
        //then delete/desostroy this room

        WindowInterceptor.init(roomList.getButton("Delete").triggerClick()).process(new WindowHandler() {

            public Trigger process(Window window) {
                return window.getButton("Yes").triggerClick();
            }
        }).process(new WindowHandler() {

            public Trigger process(Window window) {
                return window.getButton("OK").triggerClick();
            }
        }).run();
        Thread.sleep(2000);
        assertEquals(false, ((String) table.getContentAt(table.getRowCount() - 1, 0)).equals(roomName));

    }
}