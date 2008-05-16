/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.client.applet;

import avoir.realtime.tcp.common.packet.ModuleFileRequestPacket;
import java.io.File;
import java.io.IOException;
import java.lang.reflect.Method;
import java.net.URL;
import java.net.URLClassLoader;
import static avoir.realtime.common.Constants.*;

/**
 *
 * @author developer
 */
public class LibManager {

    private static final Class[] parameters = new Class[]{URL.class};

    public static void addFile(String s) throws IOException {
        File f = new File(s);
        addFile(f);
    }// end method

    public static void addFile(File f) throws IOException {
        addURL(f.toURL());
    }// end method

    public static void addURL(URL u) throws IOException {
        URLClassLoader sysloader = (URLClassLoader) ClassLoader.getSystemClassLoader();
        Class sysclass = URLClassLoader.class;
        try {
            Method method = sysclass.getDeclaredMethod("addURL", parameters);
            method.setAccessible(true);
            method.invoke(sysloader, new Object[]{u});
        } catch (Throwable t) {
            t.printStackTrace();
            throw new IOException(
                    "Error, could not add URL to system classloader");
        }// end try catch

    }// end method

    private static void dowloadLib(TCPClient client, String filename, String filepath,
            String slideServerId, String username) {
        ModuleFileRequestPacket p =
                new avoir.realtime.tcp.common.packet.ModuleFileRequestPacket(null,
                filename, filepath, slideServerId, username);

        client.sendPacket(p);
    }

    public static void detectJSpeex(TCPClient client, String resourcesPath,
            String slideServerId, String username) {
        try {
            System.out.println("Detecting jspeex...");
            File f = new File(REALTIME_HOME + "/lib/jspeex.jar");
            if (!f.exists()) {
                System.out.println(f.getAbsolutePath() + " NOT FOUND");
            }
            LibManager.addFile(REALTIME_HOME + "/lib/jspeex.jar");
            System.out.println("jspeex loaded");
            Class cl = Class.forName("org.xiph.speex.SpeexEncoder");
            cl = Class.forName("org.xiph.speex.SpeexDecoder");
            System.out.println("Classes found !!1");
        } catch (Exception ex) {
            String filepath = resourcesPath + "/jspeex.jar";
            dowloadLib(client, "jspeex.jar", filepath, slideServerId, username);
            System.out.println("Send request for jspeex.jar");
        }
    }
}
