/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

/**
 *
 * @author developer
 */
public class Constants {

    private static DateFormat dateFormat = new SimpleDateFormat("yyy-MM-ddd-H-mm-ss");
    public static final String WIN_REALTIME_HOME = "c:\\avoir-realtime-1.0.2\\";
    public static final String REALTIME_HOME = System.getProperty("user.home") + "/avoir-realtime-1.0.2";

    public static String getRealtimeHome() {
      /*  if (System.getProperty("os.name").toUpperCase().startsWith("WINDOWS")) {
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
    public static final int NO_POINTER = 16;
    public static final int PRESENTATION = 17;
    public static final int IMAGE = 18;
    public static final int ANY=19;
    public static final boolean TEMPORARY_MESSAGE = true;
    public static final boolean LONGTERM_MESSAGE = false;
    public static final boolean INFORMATION_MESSAGE = false;
    public static final boolean ERROR_MESSAGE = true;

    public static String getDateTime() {
        Date date = new Date();
        return "[" + dateFormat.format(date) + "]";
    }
    public static int APPLET = 0;
    public static int WEBSTART = 1;
}
