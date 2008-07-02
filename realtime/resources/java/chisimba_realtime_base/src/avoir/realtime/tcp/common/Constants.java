/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common;

/**
 *
 * @author developer
 */
public class Constants {

    public static final String WIN_REALTIME_HOME = "c:\\avoir-realtime-0.1\\";
    public static final String REALTIME_HOME = System.getProperty("user.home") + "/avoir-realtime-0.1";

    public static String getRealtimeHome() {
        /* if (System.getProperty("os.name").toUpperCase().startsWith("WINDOWS")) {
        return WIN_REALTIME_HOME;
        } else {
        return REALTIME_HOME;
        }*/
        return REALTIME_HOME;
    }
    public static final int ADD_NEW_ITEM = 1;
    public static final int REMOVE_ITEM = 2;
    public static final int REPLACE_ITEM = 3;
    public static final int CLEAR_ITEMS = 4;
    public static final int PAINT_BRUSH = 10;
    public static final int HAND_LEFT = 11;
    public static final int HAND_RIGHT = 12;
    public static final int ARROW_UP = 13;
    public static final int ARROW_SIDE = 14;
    public static final int WHITEBOARD = 15;
}
