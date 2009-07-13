/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.test.whiteboard;

import java.util.ArrayList;
import javax.swing.JPopupMenu;
import javax.swing.JTextField;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.Main;
import org.avoir.realtime.gui.whiteboard.items.Item;
import org.uispec4j.Key;
import org.uispec4j.MenuItem;
import org.uispec4j.Mouse;
import org.uispec4j.Panel;
import org.uispec4j.Trigger;
import org.uispec4j.UISpecTestCase;
import org.uispec4j.Window;
import org.uispec4j.interception.MainClassAdapter;
import org.uispec4j.interception.PopupMenuInterceptor;
import org.uispec4j.interception.WindowInterceptor;
import org.uispec4j.utils.KeyUtils;

/**
 *
 * @author david
 */
public class WhiteboardTest extends UISpecTestCase {

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

    }

    public void testRoomActions() throws Exception {
        final String roomName = "David_Wafula";
        String title1 = "Virtual Room: " + roomName;

        Window window = WindowInterceptor.run(new Trigger() {

            public void run() throws Exception {
                String title1 = "Virtual Room: " + roomName;

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
        // test whiteboard tools: line, click
        window.getPanel("whiteboardToolsPanel").getToggleButton("lineButton").click();

        final Panel whiteBoard = window.getPanel("whiteboard");
        Mouse.pressed(whiteBoard, 100, 100);
        Mouse.drag(whiteBoard, 400, 100);
        Mouse.released(whiteBoard, 600, 100);
        String itemId = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getLastItemId();
        Thread.sleep(1000);
        ArrayList<Item> items = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getItems();
        assertEquals(itemId, items.get(items.size() - 1).getId());

        window.getPanel("whiteboardToolsPanel").getToggleButton("drawOval").click();
        Mouse.pressed(whiteBoard, 100, 100);
        Mouse.drag(whiteBoard, 400, 600);
        Mouse.released(whiteBoard, 600, 600);
        itemId = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getLastItemId();
        Thread.sleep(1000);
        items = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getItems();
        assertEquals(itemId, items.get(items.size() - 1).getId());

        window.getPanel("whiteboardToolsPanel").getToggleButton("fillOval").click();
        Mouse.pressed(whiteBoard, 200, 200);
        Mouse.drag(whiteBoard, 300, 300);
        Mouse.released(whiteBoard, 400, 400);
        itemId = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getLastItemId();
        Thread.sleep(1000);
        items = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getItems();
        assertEquals(itemId, items.get(items.size() - 1).getId());


        window.getPanel("whiteboardToolsPanel").getToggleButton("fillRect").click();
        Mouse.pressed(whiteBoard, 300, 300);
        Mouse.drag(whiteBoard, 500, 500);
        Mouse.released(whiteBoard, 550, 550);
        itemId = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getLastItemId();
        Thread.sleep(1000);
        items = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getItems();
        assertEquals(itemId, items.get(items.size() - 1).getId());

        window.getPanel("whiteboardToolsPanel").getToggleButton("drawRect").click();
        Mouse.pressed(whiteBoard, 10, 10);
        Mouse.drag(whiteBoard, 50, 50);
        Mouse.released(whiteBoard, 150, 150);
        itemId = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getLastItemId();
        Thread.sleep(1000);
        items = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getItems();
        assertEquals(itemId, items.get(items.size() - 1).getId());


        window.getPanel("whiteboardToolsPanel").getToggleButton("addText").click();

        final MenuItem menu = PopupMenuInterceptor.run(new Trigger() {

            public void run() throws Exception {
                Mouse.pressed(whiteBoard, 250, 250);

            }
        });
        JPopupMenu pp = ((JPopupMenu) menu.getAwtComponent());
        JTextField tf = (JTextField) pp.getComponent(0);
        Mouse.released(whiteBoard, 350, 350);
        tf.setText("popo lipo");
        GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().sendText();
        itemId = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getLastItemId();
        Thread.sleep(1000);
        items = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getItems();
        assertEquals(itemId, items.get(items.size() - 1).getId());


        window.getPanel("whiteboardToolsPanel").getToggleButton("scribble").click();
        Mouse.pressed(whiteBoard, 10, 10);
        Mouse.drag(whiteBoard, 50, 50);
        Mouse.drag(whiteBoard, 150, 150);
        Mouse.released(whiteBoard, 400, 150);
        itemId = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getLastItemId();
        Thread.sleep(1000);
        items = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getItems();
        assertEquals(itemId, items.get(items.size() - 1).getId());
        Thread.sleep(1000);
        items = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getItems();
        //undo
        while (items.size() > 0) {
            window.getPanel("whiteboardToolsPanel").getButton("undo").click();
        }

        items = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getItems();

        assertEquals(0, items.size());

    }
}