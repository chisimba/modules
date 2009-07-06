/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.startup;

import com.sun.javafx.functions.Function0;
import java.io.File;
import java.util.HashMap;
import java.util.Map;
import javafx.async.RunnableFuture;
import javafx.lang.FX;
import javax.swing.JOptionPane;
import javax.swing.SwingUtilities;
import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.RealtimeFxInt;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.gui.main.WebPresentManager;
import org.avoir.realtime.net.ConnectionManager;

/**
 * This class gets params from the fx script, inits connection to openfire server,
 * logs into the server and sets everything in motion
 *
 * @author david
 */
public class Startup implements RunnableFuture {

    public static RealtimeFxInt realtimeFxInt;
    public static String[] args;

    public Startup(RealtimeFxInt realtimeFxInt, String[] args) {
        Startup.realtimeFxInt = realtimeFxInt;
        Startup.args = args;
    }

    public void run() throws Exception {
        init();
    }

    /**
     * @param args
     */
    public void init() {
        //  XMPPConnection.DEBUG_ENABLED = true;
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                for (String arg : args) {
                    System.out.println(arg);
                }
                boolean browserProxyRequired = new Boolean(GeneralUtil.getProperty("browser.proxy.required"));
                if (browserProxyRequired) {
                    String proxHost = System.getProperty("proxy.host");
                    String proxyPort = System.getProperty("proxy.port");
                    System.setProperty("network.proxy_host", proxHost);
                    System.setProperty("network.proxy_port", proxyPort);

                }
                ///  NativeInterface.open();
                String host = args[0];
                int port = 5222;
                try {
                    port = Integer.parseInt(args[1]);
                } catch (NumberFormatException ex) {
                    ex.printStackTrace();
                }

                String audioVideoUrl = args[2];
                String defaultRoomName = args[3];
                String username = args[4];
                String slidesDir = args[5];
                WebPresentManager.isPresenter = args[6].equals("yes");
                WebPresentManager.presentationId = args[7];
                WebPresentManager.presentationName = args[8];

                String names = args[9];
                //chisimba bug..room name could be invalid if user not logged in
                if (GeneralUtil.isInvalidRoomName(defaultRoomName)) {
                    System.out.println(defaultRoomName + " is invalid, fixing ...");
                    defaultRoomName = GeneralUtil.formatStr(names, " ");
                    System.out.println("Fixed, new room name: " + defaultRoomName);
                }

                String email = args[10];
                ConnectionManager.inviteUrl = args[11];
                String undefined = args[12];
                String xuseEc2 = args[13].trim().toLowerCase();
                String joinMeetingId = args[14];
                ConnectionManager.useEC2 = new Boolean(xuseEc2);
                WebPresentManager.slidesDir = slidesDir;
                WebPresentManager.hasBeenLaunchedAsWebPresent = true;

                WebPresentManager.username = username;
                GeneralUtil.saveProperty("port", port + "");
                GeneralUtil.saveProperty("audio.video.url", audioVideoUrl);
                GeneralUtil.saveProperty("server", host);
                Map<String, String> props = new HashMap<String, String>();
                props.put("email", email);
                props.put("name", names);
                ConnectionManager.userProps = props;
                ConnectionManager.myEmail = email;
                ConnectionManager.fullnames = names;
                String presenterRoom = GeneralUtil.formatStr(names, " ");
                String roomName = WebPresentManager.isPresenter ? presenterRoom : defaultRoomName;
                ConnectionManager.setRoomName(roomName);
                if (ConnectionManager.init(host, port, audioVideoUrl)) {
                    ConnectionManager.createUser(username, "--LDAP--", props);
                    if (ConnectionManager.login(username, "--LDAP--", roomName)) {
                        startServices(roomName);

                    } else {
                        JOptionPane.showMessageDialog(null, "Severe error. Cannot log into the server. Contact system adminstrator");
                        System.exit(0);
                    }
                } else {
                    JOptionPane.showMessageDialog(null, "Connection to server failed miserably. Contact system adminstrator");
                    System.exit(0);
                }
            }
        });
    }

    private void startServices(String roomName) {
        startRoomService(roomName, ConnectionManager.getUsername(),
                ConnectionManager.fullnames, "email", true);
    }

    public static void startRoomService(
            final String roomName,
            final String username,
            final String names,
            final String email, final boolean fisrtTime) {
        FX.deferAction(new Function0<Void>() {

            public Void invoke() {
                if (fisrtTime) {
                    realtimeFxInt.showMainScreen(roomName, username, names, email);
                } else {
                    realtimeFxInt.joinRoom(roomName, username, names, email);
                }
                populateRoomWithLocalResources();
                return null;
            }
        });

    }

    private static void populateRoomWithLocalResources() {

        String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
        String files[] = new File(resourceDir).list();
        String standByPresentationId = "";
        java.util.Arrays.sort(files);
        if (files != null) {
            for (int i = 0; i < 1; i++) {
                String path = resourceDir + "/" + files[i];
                String[] contents = new File(path).list();
                java.util.Arrays.sort(contents);
                if (contents != null) {
                    boolean firstOne = true;
                    for (String content : contents) {
                        String filePath = path + "/" + content;
                        if (!filePath.endsWith(".tr") && !filePath.endsWith(".txt")) {

                            realtimeFxInt.addSlide("file://" + filePath);
                              if (firstOne) {
                                realtimeFxInt.getWhiteboard().setSlideImage("file://" + filePath);
                                firstOne=false;
                            }
                        }
                    }

                }
            }
        }
    }
}
