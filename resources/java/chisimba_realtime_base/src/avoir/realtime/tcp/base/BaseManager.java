/*
 *
 *  Copyright (C) GNU/GPL AVOIR 2008
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if notfchat, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
package avoir.realtime.tcp.base;

import avoir.realtime.tcp.base.appsharing.DesktopUtil;
import avoir.realtime.tcp.base.user.User;
import avoir.realtime.tcp.common.Flash;
import avoir.realtime.tcp.common.packet.DesktopPacket;
import avoir.realtime.tcp.whiteboard.item.Img;
import chrriis.dj.nativeswing.swtimpl.components.JFlashPlayer;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.io.File;
import java.util.Timer;
import javax.swing.ImageIcon;
import javax.swing.JFrame;
import javax.swing.JOptionPane;
import javax.swing.JTabbedPane;
import javax.swing.SwingUtilities;

/**
 * Contains utility classes that are used by RealtimeBase
 * @version 1.0.2
 * @author David Wafula
 */
public class BaseManager {

    private RealtimeBase base;
    private DesktopUtil desktopUtil;
    private boolean screenCapture;
    private boolean paused = false;
    private Timer tabTimer = new Timer();

    public BaseManager(RealtimeBase base) {
        this.base = base;
        desktopUtil = new DesktopUtil();

    }

    /**
     * This initiates application sharing
     */
    public void startApplicationSharing() {
        screenCapture = true;
        Thread t = new Thread() {

            public void run() {
                while (screenCapture) {
                    base.getTcpClient().sendPacket(
                            new DesktopPacket(desktopUtil.getScreenShot(), base.getSessionId()));
                }
            }
        };
        t.start();
    }

    public boolean isScreenCapture() {
        return screenCapture;
    }

    public void stopApplicationSharing() {
        screenCapture = false;
    }

    public void setScreenCapture(boolean screenCapture) {
        this.screenCapture = screenCapture;
    }

    public void showFlashPlayer(final String filepath, final String id, final String sessionId) {

        SwingUtilities.invokeLater(new Runnable() {

            public void run() {

                JFlashPlayer flashPlayer = new JFlashPlayer();
                flashPlayer.setControlBarVisible(true);

                flashPlayer.load(filepath);
                flashPlayer.pause();
                String filename = new File(filepath).getName();
                base.getFlashFiles().add(new Flash(filename, id, sessionId));
                base.getMainTabbedPane().add(flashPlayer, filename);
                int count = base.getMainTabbedPane().getTabCount();
                base.getMainTabbedPane().setSelectedIndex(count - 1);
            }
        });
    }

    /**
     * When the application is close..remove user from the list
     * @param frame
     * @param user
     */
    public void setApplicationClosedOperation(JFrame frame, final User user) {
        if (frame != null) {
            frame.addWindowListener(new WindowAdapter() {

                @Override
                public void windowClosing(WindowEvent e) {
                    base.getTcpClient().removeUser(user);

                    paused = true;
                }
            });
        }
    }

    /**
     * Previously downloaded presentations for this session...load them up
     */
    public void loadCachedSlides() {
        base.getAgendaManager().addAgenda(new String[0], "Whiteboard");
        File cacheHome = new File(avoir.realtime.tcp.common.Constants.getRealtimeHome() + "/classroom/slides/" + base.getSessionId());
        if (!cacheHome.exists()) {
            return;
        }
        String[] slides = cacheHome.list();

        if (slides != null) {
            for (int i = 0; i < slides.length; i++) {
                String presentation = cacheHome.getAbsolutePath() + "/" + slides[i];

                int[] slidesList = base.getTcpClient().getImageFileNames(presentation);

                base.getAgendaManager().addAgenda(base.getTcpClient().createSlideNames(slidesList), new File(presentation).getName());
            }
        }
    }

    /**
     * Here the session is on, and so reload the images in case you broke off before the session
     * ended
     */
    public void loadCachedImage(Img img, String path) {

        File cacheHome = new File(avoir.realtime.tcp.common.Constants.getRealtimeHome() + "/classroom/images/" + base.getSessionId());
        if (!cacheHome.exists()) {
            return;
        }
        base.getWhiteboardSurface().setImage(new ImageIcon(path));

    }

    public Timer getTabTimer() {
        return tabTimer;
    }

    public void animateTabTitle(JTabbedPane tab, int index) {
        if (tabTimer != null) {
            tabTimer.cancel();
        }

        tabTimer = new Timer();
        tabTimer.scheduleAtFixedRate(new TabTitleAnimator(index, tab, tab.getBackground()), 0, 1000);
    }

    /**
     * Display the filter frame
     */
    public void showFilterFrame() {

        String filterNames = "Guest" + new java.util.Random().nextInt(200) + " " + "RL" + new java.util.Random().nextInt(200);

        if (filterNames != null) {
            if (filterNames.trim().equals("")) {
                JOptionPane.showMessageDialog(base, "Empty spaces are not allowed. Please enter your fullname");
                return;
            }
            String[] names = filterNames.trim().split(" ");
            for (int i = 0; i < names.length; i++) {
                System.out.println(i + " " + names[i]);
            }
            if (names.length < 2) {
                JOptionPane.showMessageDialog(null, "Atleast 2 names required");
                return;
            }
            String filter = "[REALTIME: " +
                    "id=\"" + base.getSessionId() + "\" \n" +
                    "agenda=\"" + base.getSessionTitle() + "\" \n" +
                    "resourcesPath=\"" + base.getResourcesPath() + "\" \n" +
                    "appletCodeBase=\"" + base.getAppletCodeBase() + "\" \n" +
                    "slidesDir=\"" + base.getSlidesDir() + "\" \n" +
                    "username=\"" + names[0] + "\" \n" +
                    "fullnames=\"" + filterNames + "\" \n" +
                    "userLevel=\"admin\" \n" +
                    "slideServerId=\"" + base.getSlideServerId() + "\" \n" +
                    "siteRoot=\"" + base.getSiteRoot() + "\" /]";
            FilterFrame fr = new FilterFrame(filter, createEmbbedStr(names[0], filterNames));
            fr.setSize(500, 300);
            fr.setLocationRelativeTo(null);
            fr.setAlwaysOnTop(true);
            fr.setVisible(true);
        }

    }

    /**
     * this creates an embbed string than can be pasted into any container that supports html
     * then the realtime session will be played
     * @param userName
     * @param fullName
     * @return
     */
    private String createEmbbedStr(String userName, String fullName) {
        String url = "<center>\n";
        url += "<applet codebase=\"" + base.getAppletCodeBase() + "\"\n";
        url += "code=\"avoir.realtime.tcp.launcher.RealtimeLauncher\" name =\"Chisimba Realtime Applet\"\n";

        url += "archive=\"realtime-launcher-0.1.jar\" width=\"100%\" height=\"600\">\n";
        url += "<param name=userName value=\"" + userName + "\" >\n";

        url += "<param name=isLocalhost value=\"false\">\n";
        url += "<param name=fullname value=\"" + fullName + "\">\n";
        url += "<param name=userLevel value=\"" + base.getUserLevel() + "\">\n";
        url += "<param name=uploadURL value=\"uploadURL\">\n";
        url += "<param name=chatLogPath value=\"chatLogPath\">\n";
        url += "<param name=siteRoot value=\"" + base.getSiteRoot() + "\">\n";
        url += "<param name=isWebPresent value=\"true\">\n";
        url += "<param name=slidesDir value=\"" + base.getSlidesDir() + "\">\n";
        url += "<param name=uploadPath value=\"uploadPath\">\n";
        url += "<param name=resourcesPath value=\"" + base.getResourcesPath() + "\">\n";
        url += "<param name=sessionId value=\"" + base.getSessionId() + "\">\n";
        url += "<param name=sessionTitle value=\"" + base.getSessionTitle() + "\">\n";
        url += "<param name=slideServerId value=\"" + base.getSlideServerId() + "\">\n";

        url += "<param name=isSessionPresenter value=\"false\">\n";
        url += "</applet>\n";
        url += "</center>\n";
        return url;
    }
}
