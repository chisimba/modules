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

   
    public static final String REALTIME_HOME = System.getProperty("user.home") + "/avoir-realtime-1.0.2";
    public static int APPLET = 0;
    public static int WEBSTART = 1;

    public static String getRealtimeHome() {
        return REALTIME_HOME;
    }
}
