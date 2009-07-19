/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.net;

import java.net.MalformedURLException;
import java.net.URL;
import java.util.Map;
import java.util.Timer;
import java.util.TimerTask;
import javax.swing.ImageIcon;
import javax.swing.JOptionPane;
import javax.swing.JWindow;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.LoginFrame;
import org.avoir.realtime.gui.main.Main;
import org.jivesoftware.smack.ConnectionListener;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.ConnectionConfiguration;

import org.jivesoftware.smack.XMPPException;
import org.jivesoftware.smack.packet.IQ;
import org.jivesoftware.smack.packet.Message;
import org.jivesoftware.smack.proxy.ProxyInfo;
import org.jivesoftware.smack.proxy.ProxyInfo.ProxyType;
import org.jivesoftware.smackx.muc.InvitationListener;
import org.jivesoftware.smackx.muc.MultiUserChat;
import org.jivesoftware.smackx.packet.VCard;

/**
 * this class contains functions for initiating connection to
 * xmpp transport framework
 * @author david wafula
 */
public class ConnectionManager {

    public static String server = "localhost";
    private static String resource = "smack";
    public static int PORT = 5222;
    public static String PROXY_HOST = "localhost";
    public static int PROXY_PORT = 80;
    public static String AUDIO_VIDEO_URL = "localhost";
    private static XMPPConnection connection = null;
    private static String username;
    private static String roomName;
    public static String myEmail = "";
    
    private static boolean admin = false;
    private static JWindow banner = new JWindow();
    private static ProxyInfo proxy;
    private static String email;
    public static String inviteUrl = "";
    public static String fullnames = "anonymous";
    public static boolean useEC2 = false;
    public static Map<String, String> userProps;
    public static XMPPConnection oldConnection;
    public static boolean audioVideoUrlReady = false;
    public static boolean flashUrlReady = false;
    public static String FLASH_URL = "";
    
    private static Timer connectionTimer = new Timer();
    private static int connectionCount = 0;
    private static int maxConnectionCount = 3;
    private static int connectionType = Constants.Proxy.NO_PROXY;
    public static String roomOwner;

    /**
     * This initiates connection to openfire server
     * @param server
     * @param port
     * @param mediaUrl
     * @return
     */
    public static boolean init(String server, int port, String mediaUrl) {

        PORT = port;
        AUDIO_VIDEO_URL = mediaUrl;
        System.out.println("connecting to " + server + ":" + PORT);
        ConnectionManager.server = server;

        boolean doconnect = true;
        int max = 12;
        int count = 0;
        while (doconnect) {
            //acquire connection type
            acquireCurrentConnectionType();
            if (connectionType == Constants.Proxy.NO_PROXY) {
                if (connectDirect()) {
                    return true;
                } else {
                    alertTimeOut();
                }

            } else if (connectionType == Constants.Proxy.HTTP_PROXY) {
                if (connectViaHttpProxy()) {
                    return true;
                } else {
                    alertTimeOut();
                }
            }
            count++;
            if (count > max) {
                break;
            }
        }
        return false;

    }

    public static String getFullnames() {
        return fullnames;
    }

    /**
     * This method get the current connection type. Any type you display
     * options dialog to the user, call this method before attempting 
     * further connections. The user might have changed connection options
     */
    private static void acquireCurrentConnectionType() {
        try {
            connectionType = Integer.parseInt(GeneralUtil.getProperty("connection.type"));
        } catch (NumberFormatException ex) {
            ex.printStackTrace();
        }
    }

    /**
     * Alert the user of failed connection. Give them a change to change connection
     * options. If they choose no, then just display a failed connection dialog
     */
    private static void alertTimeOut() {
        Main.disposeBanner();

        int n = JOptionPane.showConfirmDialog(null, "Internal Error occured. Cannot connect to server.\n" +
                "Would you like to adjust settings and try again?", "Settings",
                JOptionPane.YES_NO_OPTION);
        if (n == JOptionPane.YES_OPTION) {
            LoginFrame.showOptionsFrame();
            acquireCurrentConnectionType();
            Main.startBanner();
        } else {

            ConnectionFailedDialog dl = new ConnectionFailedDialog(new javax.swing.JFrame(), true);
            dl.setSize(400, 300);
            dl.setLocationRelativeTo(null);
            dl.setVisible(true);

        }
    }

    private static boolean connectDirect() {
        System.out.println("Attempting direct connection");
        ConnectionConfiguration config = new ConnectionConfiguration(server, PORT, resource);
        connection = new XMPPConnection(config);
        return connect();
    }

    private static boolean connectViaHttpProxy() {
        System.out.println("Attempting connection via proxy");
        ConnectionConfiguration config = new ConnectionConfiguration(server, PORT, resource);

        String proxyUsername = null;
        String proxyPassword = null;
        try {
            PROXY_HOST = GeneralUtil.getProperty("proxy.host");
            String portStr = GeneralUtil.getProperty("proxy.port");
            //System.out.println("PORT STR: = "+portStr);
            PROXY_PORT = Integer.parseInt(portStr);
            System.out.println("Proxy identified as "+PROXY_HOST+":"+PROXY_PORT);
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        if (new Boolean(GeneralUtil.getProperty("proxy.require.auth"))) {
            proxyUsername = GeneralUtil.getProperty("proxy.username");
            proxyPassword = GeneralUtil.getProperty("proxy.password");

            if (proxyUsername != null) {
                proxyUsername.trim();
            }
            if (proxyPassword != null) {
                proxyPassword.trim();
            }
        }

        proxy = new ProxyInfo(ProxyType.HTTP, PROXY_HOST, PROXY_PORT, proxyUsername, proxyPassword);
        config = new ConnectionConfiguration(server, PORT, resource, proxy);
        connection = new XMPPConnection(config);
        return connect();
    }

    public static ConnectionManager getInstance() {
        return new ConnectionManager();
    }

    private static boolean connect() {
        try {
            connectionTimer.cancel();
            connectionTimer = new Timer();
            connectionTimer.scheduleAtFixedRate(new ConnectionTimer(), 1000, 1000);
            connection.connect();
            System.out.println("Successfully connected to "+connection.getHost());
            connectionTimer.cancel();
            return true;
        } catch (XMPPException e) {

            e.printStackTrace();
            connectionTimer.cancel();
            return false;
        }
    }

    static class ConnectionTimer extends TimerTask {

        int count = 0;

        public void run() {
            if (count < 60) {
                System.out.println("Attempt: " + count);
            } else {
                if (useEC2) {
                    EC2Manager.dispose();
                }
                Main.disposeBanner();
                JOptionPane.showMessageDialog(null, "Connection to server failed. Make sure you have correct network settings.");
                LoginFrame.showOptionsFrame();
                if (useEC2) {
                    EC2Manager.showLoginProgress();
                } else {
                    Main.startBanner();
                }
                init(server, PORT, AUDIO_VIDEO_URL);
                connectionCount++;
                if (connectionCount > maxConnectionCount) {
                    JOptionPane.showMessageDialog(null, "Max connection attempts reached. Consult system administrator");
                    System.exit(0);
                }
                cancel();
            }
            count++;
        }
    }

    public static void setRoomName(String roomName) {
        ConnectionManager.roomName = roomName;
    }

    public static void createUser(String username, String password, Map<String, String> props) {
        try {
            connection.getAccountManager().createAccount(username, password, props);
        } catch (XMPPException ex) {
            ex.printStackTrace();
        }
    }

    public static void setAUDIO_VIDEO_URL(String AUDIO_VIDEO_URL) {
        ConnectionManager.AUDIO_VIDEO_URL = AUDIO_VIDEO_URL;
    }


    public static void setPORT(int PORT) {
        ConnectionManager.PORT = PORT;
    }

    public static boolean isAdmin() {
        return admin;
    }

    public static void setAdmin(boolean admin) {
        ConnectionManager.admin = admin;
    }

    public static XMPPConnection getConnection() {

        return connection;
    }

    public static String getUsername() {
        return username;
    }

    public static String getRoomName() {
        /** We user lower case...cause Linux/Unix and whatever else is case sensitive with folder names**/
        return roomName;
    }

    public static void logout() {
        connection.disconnect();
    }

    public static boolean login(String xusername, String password, String roomName) {

        try {
            ConnectionManager.username = xusername;
            ConnectionManager.roomName = roomName;
            if (!connection.isAuthenticated()) {
                connection.login(xusername, password);
                System.out.println("Login succeeded");
            }
            /*connection.getChatManager().addChatListener(new ChatManagerListener() {

                public void chatCreated(Chat chat, boolean createdLocally) {
                    chat.addMessageListener(new MessageListener() {

                        public void processMessage(Chat chat, Message message) {
                            JOptionPane.showMessageDialog(null, message.getBody());
                        }
                    });
                }
            });*/
            initPacketListener();
            initConnectionListener();



            return true;
        } catch (XMPPException ex) {
            ex.printStackTrace();

        }
        return false;
    }

    public static void setAvatar(String path) {
        VCard vcard = new VCard();
        try {
            vcard.load(connection);
            URL url = new URL("file://" + path);
            vcard.setAvatar(url);
            vcard.save(connection);
            displayAvatar();

        } catch (MalformedURLException ex) {
            JOptionPane.showMessageDialog(null, "Error reading image file");
            ex.printStackTrace();
        } catch (XMPPException ex) {
            JOptionPane.showMessageDialog(null, "Error setting avator");
            ex.printStackTrace();
        }

    }

    public static String getEmail() {
        return email;
    }

    public static void displayAvatar() {
        /*    try {
        VCard vcard = new VCard();
        vcard.load(connection);
        email = vcard.getEmailHome();

        byte[] avatarBytes = vcard.getAvatar();
        if (avatarBytes != null) {
        ImageIcon icon = new ImageIcon(avatarBytes);
        icon = new ImageIcon(GeneralUtil.getScaledImage(icon.getImage(), 48, 48));
        GUIAccessManager.mf.getUserListPanel().getPhotoButton().setIcon(icon);
        }
        } catch (XMPPException ex) {
        ex.printStackTrace();
        }*/
    }

    public static String getEmail(String jid) {
        /*
        try {
        VCard vcard = new VCard();
        vcard.load(connection, jid);
        return vcard.getEmailHome();
        } catch (XMPPException ex) {
        ex.printStackTrace();
        }*/
        return "*";
    }

    public static ImageIcon getAvatar(String jid) {
        try {

            VCard vcard = new VCard();
            vcard.load(connection, jid);
            byte[] avatarBytes = vcard.getAvatar();
            if (avatarBytes != null) {
                ImageIcon icon = new ImageIcon(avatarBytes);
                return new ImageIcon(GeneralUtil.getScaledImage(icon.getImage(), 48, 48));
            }
        } catch (XMPPException ex) {
            ex.printStackTrace();
        }
        return null;
    }

    public static VCard getVCard(String jid) {
        try {
            VCard vcard = new VCard();
            vcard.load(connection, jid);
            return vcard;
        } catch (XMPPException ex) {
            ex.printStackTrace();
        }
        return null;
    }

    public static void sendPacket(IQ packet) {
        connection.sendPacket(packet);
    }

    private static void initInvitationListener() {

        MultiUserChat.addInvitationListener(connection, new InvitationListener() {

            public void invitationReceived(XMPPConnection conn, String room, String inviter,
                    String reason, String password, Message message) {
                MultiUserChat muc2 = new MultiUserChat(connection, room);
                try {
                    muc2.join(username);
                } catch (XMPPException ex) {
                    ex.printStackTrace();
                }
            }
        });

    }

    private static void initConnectionListener() {
        connection.addConnectionListener(new ConnectionListener() {

            public void connectionClosed() {

                GUIAccessManager.mf.getChatRoomManager().getChatRoom().insertErrorMessage("" +
                        "CONNECTION TO SERVER BROKEN!!!\n");

            }

            public void connectionClosedOnError(Exception arg0) {

                JOptionPane.showMessageDialog(null, "Connection to server closed.",
                        "Connection Closed", JOptionPane.ERROR_MESSAGE);
                if (GUIAccessManager.mf != null) {
                    GUIAccessManager.mf.getChatRoomManager().getChatRoom().insertErrorMessage("" +
                            "AN ERROR OCCURRED. CONNECTION TO SERVER BROKEN!!!\n");
                }

            }

            public void reconnectingIn(int arg0) {
            }

            public void reconnectionSuccessful() {
            }

            public void reconnectionFailed(Exception arg0) {
            }
        });
    }

    public static void initPacketListener() {

        connection.addPacketListener(new RPacketListener(), null);

    }
}
