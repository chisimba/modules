/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.main;

import java.awt.Component;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import javax.swing.JButton;
import javax.swing.JMenu;
import javax.swing.JMenuBar;
import javax.swing.JMenuItem;
import javax.swing.JToolBar;
import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.net.ConnectionManager;

/**
 *
 * @author developer
 */
public class GUIAccessManager {

    public static MainFrame mf;
    public static Map<String, Boolean> saveStatus = new HashMap<String, Boolean>();
    public static ChatRoomManager chatRoomManager;
    public static boolean isLoggedInAnonymous = false;
    public static String defaultPresentationName = "";
    public static String defaultPresentationId = "";
    public static String defaultSlideName = "";
    public static String skinClass = null;//"org.avoir.realtime.skins.winxp.WinXPSkin";

    public static void setMf(MainFrame mf) {
        GUIAccessManager.mf = mf;
    }

    /**
     * enables/disables menu items in main frame. But only works for a two-level
     * menu structure. 
     * @param enabled
     * @param name
     */
    public static void setMenuEnabled(boolean enabled, String name) {
        JMenuBar menuBar = mf.getJMenuBar();
        for (int i = 0; i < menuBar.getMenuCount(); i++) {
            JMenu menu = menuBar.getMenu(i);
            if (menu != null) {
                String menuName = menu.getName();
                if (menuName != null) {
                    if (menuName.equals(name)) {
                        menu.setEnabled(enabled);
                    }
                }
                for (int j = 0; j < menu.getItemCount(); j++) {
                    JMenuItem item = menu.getItem(j);
                    if (item == null) {
                        continue;
                    }

                    String menuItemName = item.getName();

                    if (menuItemName != null) {
                        if (menuItemName.equals(name)) {
                            item.setEnabled(enabled);
                        }
                    }

                }
            }
        }
    }

    public static boolean amIHoldingMic() {
        ArrayList<Map> users = mf.getUserListPanel().getParticipantListTable().getUsers();
        for (Map user : users) {
            String username = (String) user.get("username");
            if (username.equals(ConnectionManager.getUsername())) {
                return mf.getUserListPanel().getParticipantListTable().hasMic(username);
            }
        }
        return false;
    }

    public static void setButtonEnabled(boolean enabled, String name) {
        JToolBar toolBar = mf.getToolbar();
        ArrayList<JButton> buttonsToRemove = new ArrayList<JButton>();
        for (int i = 0; i < toolBar.getComponentCount(); i++) {
            JButton b = (JButton) toolBar.getComponentAtIndex(i);
            if (b != null) {
                String buttonName = b.getName();
                if (buttonName != null) {
                    if (buttonName.equals(name)) {
                        if (enabled) {
                            b.setEnabled(enabled);
                        } else {
                            buttonsToRemove.add(b);
                        }
                    }
                }

            }
        }
        for (int i = 0; i < buttonsToRemove.size(); i++) {
            toolBar.remove(i);
        }
        toolBar.repaint();
    }

    public static void enableToolbarButtons(boolean state) {
        enableToolbarButton("roomMember", true);
        enableToolbarButton("roomList", true);
        enableToolbarButton("pointer", state);
        enableToolbarButton("deskShare", state);
        enableToolbarButton("images", state);
        enableToolbarButton("notepad", true);
    }

    public static void enableWhiteboardButtons(boolean state) {
        enableWhiteboardButton("undo", state);
        enableWhiteboardButton("pan", true);
        enableWhiteboardButton("move", state);
        enableWhiteboardButton("ovaldraw", state);
        enableWhiteboardButton("ovalfill", state);
        enableWhiteboardButton("rectdraw", state);
        enableWhiteboardButton("rectfill", state);
        enableWhiteboardButton("pen", state);
        enableWhiteboardButton("erase", state);
        enableWhiteboardButton("text", state);
        enableWhiteboardButton("line", state);
    }

    public static void enableWhiteboardButton(String name, boolean state) {
        JToolBar toolbar = GUIAccessManager.mf.getWhiteboardPanel().getWbToolbar();
        int count = toolbar.getComponentCount();
        for (int i = 0; i < count; i++) {
            Component c = toolbar.getComponentAtIndex(i);
            if (c.getName().equals(name)) {
                c.setEnabled(state);
            }
        }
    }

    public static void enableMenus(boolean enable) {
        GUIAccessManager.setMenuEnabled(enable, "screenShot");
        GUIAccessManager.setMenuEnabled(enable, "screenshare");
        GUIAccessManager.setMenuEnabled(enable, "screenviewer");
        GUIAccessManager.setMenuEnabled(enable, "schedule");
        GUIAccessManager.setMenuEnabled(/*!enable*/false, "privatechat");
        GUIAccessManager.setMenuEnabled(true, "createRoom");
        GUIAccessManager.setMenuEnabled(true, "roomList");
        GUIAccessManager.setMenuEnabled(enable, "actions");
        GUIAccessManager.setMenuEnabled(true, "joinRoom"); ///for every one
        GUIAccessManager.setMenuEnabled(enable, "invitationLink");
        GUIAccessManager.setMenuEnabled(enable, "insertGraphic");
        GUIAccessManager.setMenuEnabled(enable, "insertPresentation");
        GUIAccessManager.setMenuEnabled(enable, "roomResources");
        GUIAccessManager.setMenuEnabled(true, "requestMIC");
        GUIAccessManager.setMenuEnabled(enable, "addroommembers");
        GUIAccessManager.setMenuEnabled(enable, "cleanParticipantsList");
        GUIAccessManager.setMenuEnabled(enable, "undo");
        GUIAccessManager.setMenuEnabled(enable, "delete");
        GUIAccessManager.setMenuEnabled(enable, "clearMics");
        GUIAccessManager.setMenuEnabled(enable, "nextSlide");
        GUIAccessManager.setMenuEnabled(enable, "prevSlide");
        GUIAccessManager.setMenuEnabled(enable, "whiteboardtools");
    }

    private static void enableToolbarButton(String name, boolean state) {
        JToolBar toolbar = GUIAccessManager.mf.getRoomToolsToolbar();
        int count = toolbar.getComponentCount();
        for (int i = 0; i < count; i++) {
            Component c = toolbar.getComponentAtIndex(i);
            if (c.getName().equals(name)) {
                c.setEnabled(state);
            }
        }
    }
}
