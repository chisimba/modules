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
import javax.swing.JOptionPane;
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
        addURL(f.toURI().toURL());
    }// end method

    public static void addURL(URL u) throws IOException {
        URLClassLoader sysloader = (URLClassLoader) ClassLoader.getSystemClassLoader();
        Class sysclass = URLClassLoader.class;
        try {
            Method method = sysclass.getDeclaredMethod("addURL", parameters);
            method.setAccessible(true);
            method.invoke(sysloader, new Object[]{u});
        //  Thread.currentThread().setContextClassLoader(aUrlCL);
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

    public static void loadAudioCodecPlugin(TCPClient client,
            String userName,
            String sessionId,
            String slideServerId,
            String resourcesPath) {
        try {
            String[] urls = {REALTIME_HOME + "/lib/jspeex.jar",
                REALTIME_HOME + "/lib/realtime-audio-0.1.jar",
                REALTIME_HOME + "/lib/realtime-common-0.1.jar"
            };
            NetworkClassLoader classLoader = new NetworkClassLoader();
            for (int i = 0; i < urls.length; i++) {
                classLoader.addURL(new URL("file://" + urls[i]));
            }
            avoir.realtime.plugin.common.RealtimePluginInf plugin =
                    (avoir.realtime.plugin.common.RealtimePluginInf) classLoader.loadClass(
                    "avoir.realtime.tcp.audio.AudioPlugin", true).newInstance();

            if (plugin != null) {
                plugin.createAudioWizard(client, userName, sessionId, slideServerId, resourcesPath);
            } else {
                JOptionPane.showMessageDialog(null, "Cannot find suitable audio decoder. Aborting.");
            }

        } catch (IOException e) {
            e.printStackTrace();
        } catch (IllegalAccessException e) {
            e.printStackTrace();
        } catch (SecurityException e) {
            e.printStackTrace();
        } catch (IllegalArgumentException e) {
            e.printStackTrace();
        } catch (InstantiationException e) {
            e.printStackTrace();
        } catch (ClassNotFoundException e) {
            e.printStackTrace();
        }

    }

    public static boolean detectJSpeex(TCPClient client, String resourcesPath,
            String slideServerId, String username) {
        try {
            addFile(REALTIME_HOME + "/lib/jspeex.jar");
            System.out.print("Detecting jspeex...");
            Class cl = Class.forName("org.xiph.speex.SpeexEncoder");
            cl = Class.forName("org.xiph.speex.SpeexDecoder");
            System.out.println("OK");
            return true;
        } catch (Exception ex) {
            System.out.println("FAIL");
            String filepath = resourcesPath + "/jspeex.jar";
            dowloadLib(client, "jspeex.jar", filepath, slideServerId, username);
            System.out.println("Send download request for jspeex.jar");
            return false;
        }
    }

    /***
     * 
     * @param client
     * @param resourcesPath
     * @param slideServerId
     * @param username
     * @return
     */
    public static boolean detectRealtimeAudio(TCPClient client, String resourcesPath,
            String slideServerId, String username) {
        try {
            //String before=System.getProperty("java.class.path");
            System.out.print("Detecting realtime audio...");
            NetworkClassLoader classLoader = new NetworkClassLoader();
            String[] files = {
                REALTIME_HOME + "/lib/realtime-common-0.1.jar",
                REALTIME_HOME + "/lib/realtime-audio-0.1.jar",
            };
             /* for (int i = 0; i < files.length; i++) {
                 classLoader.addURL(new URL("file://" + files[i]));
             }*/
            xeus.jcl.JarClassLoader ccl = new xeus.jcl.JarClassLoader(files);
            Class cl = ccl.loadClass("avoir.realtime.plugin.common.RealtimePluginInf");
            cl=ccl.loadClass("avoir.realtime.tcp.audio.AudioPlugin");
            // classLoader.loadClass("avoir.realtime.plugin.common.RealtimePluginInf", true);
            //Class cl = classLoader.loadClass("avoir.realtime.tcp.audio.AudioPlugin", true);
            avoir.realtime.plugin.common.RealtimePluginInf real =
                    (avoir.realtime.plugin.common.RealtimePluginInf) cl.newInstance();
            System.out.println("OK");
            return true;
        } catch (Exception ex) {
            ex.printStackTrace();
            System.out.println("FAIL fff");
            String filepath = resourcesPath + "/realtime-audio-0.1.jar";
            dowloadLib(client, "realtime-audio-0.1.jar", filepath, slideServerId, username);
            System.out.println("Send download request for realtime-audio-0.1.jar");
            return false;
        }
    }
}
