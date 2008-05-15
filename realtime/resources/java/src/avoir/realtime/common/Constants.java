/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common;

/**
 *
 * @author developer
 */
public class Constants {

    public static final String USER_LIST_COMMAND = "USERLIST";
    public static final String USER_LIST_SEPARATOR = "\"USER\"";
    public static final String PUBLISH_COMMAND = "PUBLISH";
    public static final int FULL_NAME_INDEX = 9;
    public static final int USER_ID_INDEX = 7;
    public static final int USER_IP_INDEX = 7;
    public static final String JAVA_HOME = System.getProperty("java.home");
    public static final String REALTIME_HOME = System.getProperty("user.home") +
            "/avoir-realtime-0.1/";
    public static final String REALTIME_APPLET = "realtime-applet-0.1.jar";
    public static final String REALTIME_SPLASH = "realtime-splash-0.1.jar";
    public static final String REALTIME_JMF = "realtime-jmf-0.1.jar";
    public static final String REALTIME_MEDIA = "realtime-media-0.1.jar";
    public static final String REALTIME_WHITEBOARD = "realtime-whiteboard-0.1.jar";
    public static final String VERSION = "0.0001";
    public static String SUPERNODE_HOST = "196.21.45.85";
    public static int SUPERNODE_PORT = 80;
    public static final String[] realtimelib = {
        REALTIME_APPLET,
        REALTIME_WHITEBOARD,
        REALTIME_JMF,
        REALTIME_MEDIA
    };
    public static final String[] realtimeVerlib = {
        "avoir.realtime.tcp.client.applet.TCPTunnellingApplet",
        "avoir.realtime.tcp.client.whiteboard.Whiteboard",
        "avoir.realtime.tcp.media.MediaWizardFrame",
        REALTIME_MEDIA,
    };
    public static final String[] linuxFileList = {
        "restartfirefox.sh",
        "jmf.properties",
        "libjmcvid.so",
        "libjmdaud.so",
        "libjmfjawt.so",
        "libjmg723.so",
        "libjmgsm.so",
        "libjmh261.so",
        "libjmh263enc.so",
        "libjmjpeg.so",
        "libjmmpa.so",
        "libjmmpegv.so",
        "libjmmpx.so",
        "libjmutil.so",
        "libjmv4l.so",
        "libjmxlib.so",
        "mediaplayer.jar",
        "multiplayer.jar",
        "mp3plugin.jar",
        "jmf.jar",
    };
    public static final String[] winFileList = {"jmf-2_1_1e-windows-i586.exe"};
}
