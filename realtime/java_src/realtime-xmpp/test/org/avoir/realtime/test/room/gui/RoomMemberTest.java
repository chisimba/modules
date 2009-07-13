/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.test.room.gui;

import org.avoir.realtime.gui.room.RoomMemberListFrame;
import org.avoir.realtime.net.ConnectionManager;
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
public class RoomMemberTest extends UISpecTestCase {

    @Override
    protected void setUp() throws Exception {
        super.setUp();
        String[] args = {"localhost", "5222", "locahost"};
        setAdapter(new MainClassAdapter(RoomMemberListFrame.class, args));

    }

    /**
     * This test involves:
     * 1. Running room member list frame
     * 2. Searching for a room member named edel
     * 3. Adding this member to the members list
     * 4. Displaying the updated list
     * @throws java.lang.Exception
     */
    public void testAddRoomMember() throws Exception {
        //get main window
        final Window mainWindow = getMainWindow();
        Thread.sleep(2000);
        assertEquals(true, mainWindow.getButton("Add").getAwtComponent().isEnabled());

        Window searchWindow = WindowInterceptor.run(mainWindow.getButton("Add").triggerClick());
        assertEquals("Search User", searchWindow.getTitle());
        String username = "u1";
        searchWindow.getTextBox("searchField").setText(username);
        Window searchResultsWindow = WindowInterceptor.run(searchWindow.getButton("searchButton").triggerClick());
        Table searchResultsTable = searchResultsWindow.getTable();
        //get the search results, username for edel should be popo 2
        assertEquals(username, searchResultsTable.getContentAt(0, 0));
        String names = (String) searchResultsTable.getContentAt(0, 1);
        //select this user
        searchResultsTable.click(0, 3);
        //then hit add user button
        searchResultsWindow.getButton("Add to Room").click();
        //then hold for few secs
        Thread.sleep(3000);
        //then test to see if user suffeccsully added
        Table memberListTable = mainWindow.getTable();
        //member should appear last on the table list
        assertEquals(names, (String) memberListTable.getContentAt(memberListTable.getRowCount() - 1, 1));
    }

    public void testDeleteRoomMember() throws Exception {
        ConnectionManager.logout();
        //get main window
        Window mainWindow = getMainWindow();
        Thread.sleep(2000);

        //select last member
        Table memberListTable = mainWindow.getTable();
        assertEquals(true, memberListTable.getRowCount() > 0);
        int lastIndex = memberListTable.getRowCount() - 1;
        System.out.println("last index = " + lastIndex);
        memberListTable.click(lastIndex, 0);
        String username = (String) memberListTable.getContentAt(lastIndex, 0);
        System.out.println("selected username = " + username);
        //member should appear last on the table list
        WindowInterceptor.init(mainWindow.getButton("Delete").triggerClick()).process(new WindowHandler() {

            public Trigger process(Window window) {
                System.out.println(window.SWING_CLASSES);
                return window.getButton("Yes").triggerClick();
            }
        }).run();

        //then hold for few secs
        Thread.sleep(3000);
        //then test to see if user suffeccsully added
        assertFalse(((String) memberListTable.getContentAt(memberListTable.getRowCount() - 1, 1)).equals(username));
    }
}