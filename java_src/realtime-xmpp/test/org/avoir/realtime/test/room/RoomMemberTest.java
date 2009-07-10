/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.test.room;

import org.avoir.realtime.gui.main.MainFrame;
import org.uispec4j.MenuBar;
import org.uispec4j.MenuItem;
import org.uispec4j.PasswordField;
import org.uispec4j.TextBox;
import org.uispec4j.UISpecTestCase;
import org.uispec4j.Window;
import org.uispec4j.interception.MainClassAdapter;
import org.uispec4j.interception.WindowInterceptor;

/**
 *
 * @author david
 */
public class RoomMemberTest extends UISpecTestCase {

    public RoomMemberTest() {
    }

    @Override
    protected void setUp() throws Exception {
        String[] args = {"localhost", "5222", "locahost"};
        setAdapter(new MainClassAdapter(MainFrame.class, args));

    }

    public void testAddRoomMember() throws  Exception{
            Window mainWindow = getMainWindow();

            MenuBar menuBar = mainWindow.getMenuBar();
            //roomMembersWindow = WindowInterceptor.run(menuBar.getMenu("Meetings").getSubMenu("Room Members").triggerClick());
            
        }
        
    }
}