/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.main;

import chrriis.common.UIUtils;
//import chrriis.dj.nativeswing.swtimpl.NativeInterface;
import chrriis.dj.nativeswing.swtimpl.NativeInterface;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Font;
import java.awt.Graphics;
import java.awt.Toolkit;
import java.util.HashMap;
import java.util.Map;
import java.util.Timer;
import java.util.TimerTask;
import javax.swing.ImageIcon;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JProgressBar;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.EC2Manager;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.jivesoftware.smack.XMPPConnection;

/**
 *
 * @author developer
 */
public class Main {

    public static JFrame banner = new JFrame();
    public boolean DEBUG = false;
    public static Timer ec2LauncherTimer = new Timer();

    public Main(String[] args) {
        boolean browserProxyRequired = new Boolean(System.getProperty("browser.proxy.required"));
        if (browserProxyRequired) {
            //network.proxy_host
            //network.proxy_port
            String proxHost = System.getProperty("proxy.host");
            String proxyPort = System.getProperty("proxy.port");
            System.setProperty("network.proxy_host", proxHost);
            System.setProperty("network.proxy_port", proxyPort);

        }
        int c = 0;
        for (String arg : args) {
            args[c] = args[c].trim();
            if (c != 11) {
                args[c] = GeneralUtil.formatStr(args[c], " ");
            }
            System.out.println("arg[" + c + "] = " + arg);
            c++;
        }
        UIUtils.setPreferredLookAndFeel();
        NativeInterface.open();
        LoginFrame fr = new LoginFrame();

        if (args.length >= 9) {
            String host = args[0];
            int port = 5222;
            String audioVideoUrl = "localhost";
            try {
                port = Integer.parseInt(args[1]);
            } catch (NumberFormatException ex) {
                ex.printStackTrace();
            }

            audioVideoUrl = args[2];

            //this is webpresent version
            if (args.length == 15) {
                startBanner();

                String username = args[4];
                String slidesDir = args[5];
                String xuseEc2 = args[13].trim().toLowerCase();
                String joinMeetingId = args[14];
                ConnectionManager.useEC2 = new Boolean(xuseEc2);

                String names = args[9];
                WebPresentManager.isPresenter = args[6].equals("yes");

                String presenterRoom = GeneralUtil.formatStr(names, " ");// + "_" + GeneralUtil.formatDate(new Date(), "yyyyMMdd");
                String defaultRoomName = args[3];
                //chisimba bug..room name could be invalid if user not logged in
                if (GeneralUtil.isInvalidRoomName(defaultRoomName)) {
                    System.out.println(defaultRoomName + " is invalid, fixing ...");
                    defaultRoomName = GeneralUtil.formatStr(names, " ");
                    System.out.println("Fixed, new room name: " + defaultRoomName);
                }
                String roomName = WebPresentManager.isPresenter ? presenterRoom : defaultRoomName;
                WebPresentManager.presentationId = args[7];
                WebPresentManager.presentationName = args[8];
                String email = args[10];

                WebPresentManager.slidesDir = slidesDir;
                WebPresentManager.hasBeenLaunchedAsWebPresent = true;
                ConnectionManager.inviteUrl = args[11];
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
                ConnectionManager.setRoomName(roomName);
              //  Timer connectionTimer = new Timer();
              //  connectionTimer.schedule(new ConnectionTimer(), 30 * 1000);
                if (ConnectionManager.init(host, port, audioVideoUrl)) {
                   // connectionTimer.cancel();
                    ConnectionManager.createUser(username, "--LDAP--", props);
                    if (ConnectionManager.login(username, "--LDAP--", roomName)) {
                        if (ConnectionManager.useEC2 && joinMeetingId.equals("none")) {
                            disposeBanner();
                            EC2Manager.requestLaunchEC2Instance();
                            EC2Manager.showLoginProgress();
                            ec2LauncherTimer.cancel();

                            ec2LauncherTimer = new Timer();
                            ec2LauncherTimer.schedule(new EC2Timer(), 10 * 1000);
                        } else if (!WebPresentManager.isPresenter && !joinMeetingId.equals("none")) {
                            StringBuilder sb = new StringBuilder();
                            sb.append("<joinid>").append(joinMeetingId).append("</joinid>");
                            RealtimePacket p = new RealtimePacket();
                            p.setMode(RealtimePacket.Mode.JOIN_MEETING_ID);
                            p.setContent(sb.toString());
                            ConnectionManager.sendPacket(p);
                            disposeBanner();
                            EC2Manager.showLoginProgress();
                        } else {

                            ConnectionManager.audioVideoUrlReady = true;
                            ConnectionManager.flashUrlReady = true;
                            MainFrame mf = new MainFrame(roomName);
                            mf.setSize(Toolkit.getDefaultToolkit().getScreenSize());
                            mf.setVisible(true);
                            saveEC2Urls(host, RealtimePacket.Mode.SAVE_MAIN_EC2_URL, roomName);
                            saveEC2Urls(audioVideoUrl, RealtimePacket.Mode.SAVE_AUDIO_VIDEO_EC2_URL, roomName);
                            saveEC2Urls("openfire-httpflash.wits.ac.za", RealtimePacket.Mode.SAVE_FLASH_EC2_URL, roomName);
                            disposeBanner();
                        }
                    } else {
                        disposeBanner();
                        JOptionPane.showMessageDialog(null, "Internal Error occured. Cannot connect to server.\n" +
                                "Contact your system adminstrator");
                        System.exit(0);
                    }
                } else {

                    disposeBanner();
//                    connectionTimer.cancel();
                    JOptionPane.showMessageDialog(null, "Internal Error occured. Cannot connect to server.\n" +
                            "Contact your system adminstrator");
                    System.exit(0);
                }

            }
        } else {
            StandAloneManager.isAdmin = true;
            ConnectionManager.audioVideoUrlReady = true;
            ConnectionManager.flashUrlReady = true;
            String server = args[0];
            int port = Integer.parseInt(args[1].trim());
            String audioVideoUrl = args[2];
            fr = new LoginFrame(server, port, audioVideoUrl);
            fr.setSize(400, 300);
            fr.setLocationRelativeTo(null);
            fr.setVisible(true);
        }

    }

    public static void saveEC2Urls(String host, String mode, String roomName) {
        StringBuilder sb = new StringBuilder();
        sb.append("<host>").append(host).append("</host>");
        sb.append("<username>").append(ConnectionManager.getUsername()).append("</username>");
        sb.append("<room-name>").append(roomName).append("</room-name>");
        RealtimePacket p = new RealtimePacket(mode);
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);

    }

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {

        XMPPConnection.DEBUG_ENABLED = new Boolean(GeneralUtil.getProperty("debug.enabled"));
        new Main(args);
    }

    public static void startBanner() {
        banner.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        JProgressBar pb = new JProgressBar();
        pb.setIndeterminate(true);
        banner.setSize(400, 320);
        JLabel label = new JLabel("Loading ...", JLabel.CENTER);
        label.setFont(new Font("dialog", 3, 22));
        label.setBackground(Color.WHITE);
        banner.setBackground(Color.WHITE);
        banner.setLayout(new BorderLayout());
        banner.add(label, BorderLayout.NORTH);
        banner.add(new Surface(),BorderLayout.CENTER);
        banner.add(pb, BorderLayout.SOUTH);
        banner.setLocationRelativeTo(null);
        banner.setVisible(true);

    }

    public static void disposeBanner() {
        if (banner != null) {
            banner.dispose();
        }
    }

    class EC2Timer extends TimerTask {

        public void run() {
            JOptionPane.showMessageDialog(null, "This system is programmed to " +
                    "start a server on a cloud network. However, this is failing.");
            System.exit(0);
        }
    }

    public static EC2Manager getInstance() {
        return new EC2Manager();
    }

    static class Surface extends JPanel {

        ImageIcon logo = ImageUtil.createImageIcon(getInstance(), "/images/logo.png");

        @Override
        public void paintComponent(Graphics g) {
            super.paintComponent(g);
            g.drawImage(logo.getImage(), 0, 0, this);
        }
    }

    class ConnectionTimer extends TimerTask {

        public void run() {

            JOptionPane.showMessageDialog(null, "<html>Error: Cannot connect to server.\n" +
                    "Are you using http proxy to connect to internet?. If soset proxy options in the dialog\n" +
                    "Then click on the start link to rerun the application.\n" +
                    "If this problem persists, contact your network administrator for further assistance.");
            if (WebPresentManager.hasBeenLaunchedAsWebPresent) {
                LoginFrame.showOptionsFrame();
            // System.exit(0);
            }
        }
    }
}
