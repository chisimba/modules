/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.launcher;

/**
 *
 * @author developer
 */
public class Constants {

    public static final String WIN_REALTIME_HOME = "c:\\avoir-realtime-0.1\\";
    public static final String REALTIME_HOME = System.getProperty("user.home") + "/avoir-realtime-0.1";
    public static int APPLET = 0;
    public static int WEBSTART = 1;

    public static String getRealtimeHome() {
        if (System.getProperty("os.name").toUpperCase().startsWith("WINDOWS")) {
            return WIN_REALTIME_HOME;
        } else {
            return REALTIME_HOME;
        }
    }
}
