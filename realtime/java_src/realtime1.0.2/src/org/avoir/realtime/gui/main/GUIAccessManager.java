/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.main;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import javax.swing.JButton;
import javax.swing.JMenu;
import javax.swing.JMenuBar;
import javax.swing.JMenuItem;
import javax.swing.JToolBar;
import org.avoir.realtime.chat.ChatRoomManager;

/**
 *
 * @author developer
 */
public class GUIAccessManager {

    public static MainFrame mf;
    public static Map<String, Boolean> saveStatus = new HashMap<String, Boolean>();
    public static ChatRoomManager chatRoomManager;
    public static boolean isLoggedInAnonymous=false;
    public static String defaultPresentationName="" ;
    public static String defaultPresentationId="" ;
    public static String defaultSlideName="" ;

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
}
