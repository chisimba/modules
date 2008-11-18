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
package avoir.realtime.instructor.whiteboard;


import avoir.realtime.classroom.*;
import avoir.realtime.appsharing.DesktopUtil;
import avoir.realtime.classroom.packets.RemoveUserPacket;
import avoir.realtime.classroom.packets.WhiteboardPacket;
import avoir.realtime.common.user.User;
import avoir.realtime.common.Constants;
import avoir.realtime.common.GenerateUUID;
import avoir.realtime.common.WebPage;
import avoir.realtime.common.Flash;
import chrriis.dj.nativeswing.swtimpl.components.JWebBrowser;
import chrriis.dj.nativeswing.swtimpl.components.WebBrowserAdapter;
import chrriis.dj.nativeswing.swtimpl.components.WebBrowserEvent;
import chrriis.dj.nativeswing.swtimpl.components.WebBrowserNavigationEvent;
import chrriis.dj.nativeswing.swtimpl.components.JFlashPlayer;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.io.File;
import java.net.URL;
import java.util.Timer;
import javax.swing.JFrame;
import javax.swing.JOptionPane;
import javax.swing.JTabbedPane;
import javax.swing.SwingUtilities;

/**
 * Contains utility classes that are used by RealtimeBase
 * @version 1.0.2
 * @author David Wafula
 */
public class ClassroomManager {

    private ClassroomMainFrame mf;
    private DesktopUtil desktopUtil;
    private boolean screenCapture;
    private boolean paused = false;
    private Timer tabTimer = new Timer();
    private boolean newTab = false;
    private String oldID = "";
    int c = 0;
    private boolean scrollLeft = true;
    private int lastStartIndex = Constants.MAX_THUMBNAIL_INDEX - 1;
    private Timer scrollerTimer = new Timer();

    public ClassroomManager(ClassroomMainFrame mf) {
        this.mf = mf;
        desktopUtil = new DesktopUtil();

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

    public void showWebpage() {
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {

                newTab = true;
                final JWebBrowser webBrowser = new JWebBrowser();
                webBrowser.setMenuBarVisible(false);

                mf.getMainTabbedPane().add(webBrowser, "Web Browser " + ((mf.getWebPages().size() + 1) + c++));
                webBrowser.addWebBrowserListener(new WebBrowserAdapter() {

                    public void locationChanging(WebBrowserNavigationEvent evt) {
                        int count = mf.getMainTabbedPane().getTabCount();
                        mf.getMainTabbedPane().setTitleAt(count - 1, "Loading ...");

                    }

                    public void locationChanged(WebBrowserNavigationEvent evt) {
                        //send same to rest of the clients
                        String url = webBrowser.getResourceLocation();

                        int count = mf.getMainTabbedPane().getTabCount();
                        mf.getMainTabbedPane().setToolTipTextAt(count - 1, url);
                        String title = formatTitle(url);
                        mf.getMainTabbedPane().setTitleAt(count - 1, title);
                        String id = GenerateUUID.getId();
                        mf.getWebPages().add(new WebPage(id, url, count - 1));
                        /* if (mf.getControl()) {
                        mf.getTcpClient().sendPacket(new ClassroomFile(url,
                        Constants.WEBPAGE, mf.getSessionId(), id, newTab, oldID));
                        }*/
                        
                        newTab = false;
                        oldID = id;

                    }
                    @Override
                    public void loadingProgressChanged(WebBrowserEvent evt) {
                      super.loadingProgressChanged(evt);

                    }

                    public void statusChanged(WebBrowserEvent evt) {
                    }
                });
                int count = mf.getMainTabbedPane().getTabCount();
                mf.getMainTabbedPane().setSelectedIndex(count - 1);

            }
        });
    }

    private String formatTitle(String url) {
        try {
            URL r = new URL(url);
            if (url.length() > 10) {

                return r.getHost().substring(0, 10) + "...";
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        //return url.substring(0, 12);

        return url;
    }

    private void updateWebPageUrl(String id, String url) {
        for (int i = 0; i < mf.getWebPages().size(); i++) {
            if (mf.getWebPages().elementAt(i).getId().equals(id)) {
                WebPage p = mf.getWebPages().elementAt(i);

                p.setUrl(url);
                mf.getWebPages().set(i, p);
            }
        }

    }

    private int getWebPageTabIndex(String url) {
        for (int i = 0; i < mf.getWebPages().size(); i++) {
            if (mf.getWebPages().elementAt(i).getUrl().equals(url)) {
                return mf.getWebPages().elementAt(i).getIndex();
            }
        }
        return -1;
    }

    public void showWebpage(final String url, final String id, final boolean xnewTab) {
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                if (xnewTab) {
                    newTab = xnewTab;
                    final JWebBrowser webBrowser = new JWebBrowser();

                    webBrowser.setMenuBarVisible(false);
                    webBrowser.navigate(url);
                    String title = formatTitle(url);
                    mf.getMainTabbedPane().add(webBrowser, title);
                    int count = mf.getMainTabbedPane().getTabCount();
                    mf.getMainTabbedPane().setSelectedIndex(count - 1);
                    mf.getMainTabbedPane().setToolTipTextAt(count - 1, url);
                    mf.getWebPages().add(new WebPage(id, url, count - 1));
                    webBrowser.addWebBrowserListener(new WebBrowserAdapter() {

                        public void locationChanging(WebBrowserNavigationEvent evt) {
                            int count = mf.getMainTabbedPane().getTabCount();
                            mf.getMainTabbedPane().setTitleAt(count - 1, "Loading ...");

                        }

                        public void locationChanged(WebBrowserNavigationEvent evt) {
                            //send same to rest of the clients
                            String url = webBrowser.getResourceLocation();

                            int count = mf.getMainTabbedPane().getTabCount();
                            mf.getMainTabbedPane().setToolTipTextAt(count - 1, url);
                            String title = formatTitle(url);
                            mf.getMainTabbedPane().setTitleAt(count - 1, title);
                            newTab = false;

                            updateWebPageUrl(id, url);
                            /*                            if (mf.getControl()) {
                            mf.getTcpClient().sendPacket(new ClassroomFile(url,
                            Constants.WEBPAGE, mf.getSessionId(), id, newTab, oldID));
                            }
                             */
                            oldID = id;
                            newTab = false;
                        }

                        public void statusChanged(WebBrowserEvent evt) {
                        }
                    });

                } else {

                    updateWebPageUrl(id, url);
                    int index = getWebPageTabIndex(url);
                    if (index > 0) {
                        JWebBrowser webBrowser = (JWebBrowser) mf.getMainTabbedPane().getComponentAt(index);
                        webBrowser.navigate(url);
                    }
                }
            }
        });
    }

    public void showFlashPlayer(final String filepath, final String id, final String sessionId) {

        SwingUtilities.invokeLater(new Runnable() {

            public void run() {

                JFlashPlayer flashPlayer = new JFlashPlayer();
                flashPlayer.setControlBarVisible(true);

                flashPlayer.load(filepath);
                flashPlayer.pause();
                String filename = new File(filepath).getName();
                mf.getFlashFiles().add(new Flash(filename, id, sessionId));
                mf.getMainTabbedPane().add(flashPlayer, "Flash: " + filename);
                int count = mf.getMainTabbedPane().getTabCount();
                mf.getMainTabbedPane().setSelectedIndex(count - 1);
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
                    mf.getTcpConnector().sendPacket(new RemoveUserPacket(user));

                    paused = true;
                }
            });
        }
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
                JOptionPane.showMessageDialog(mf, "Empty spaces are not allowed. Please enter your fullname");
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
        /*            String filter = "[REALTIME: " +
        "id=\"" + mf.getSessionId() + "\" \n" +
        "agenda=\"" + mf.getSessionTitle() + "\" \n" +
        "resourcesPath=\"" + mf.getResourcesPath() + "\" \n" +
        "appletCodeBase=\"" + mf.getAppletCodeBase() + "\" \n" +
        "slidesDir=\"" + mf.getSlidesDir() + "\" \n" +
        "username=\"" + names[0] + "\" \n" +
        "fullnames=\"" + filterNames + "\" \n" +
        "userLevel=\"admin\" \n" +
        "slideServerId=\"" + mf.getSlideServerId() + "\" \n" +
        "siteRoot=\"" + mf.getSiteRoot() + "\" /]";
        FilterFrame fr = new FilterFrame(filter, createEmbbedStr(names[0], filterNames));
        fr.setSize(500, 300);
        fr.setLocationRelativeTo(null);
        fr.setAlwaysOnTop(true);
        fr.setVisible(true);
         */
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
        /*        url += "<applet codebase=\"" + mf.getAppletCodeBase() + "\"\n";
        url += "code=\"avoir.realtime.tcp.launcher.RealtimeLauncher\" name =\"Chisimba Realtime Applet\"\n";

        url += "archive=\"realtime-launcher-0.1.jar\" width=\"100%\" height=\"600\">\n";
        url += "<param name=userName value=\"" + userName + "\" >\n";

        url += "<param name=isLocalhost value=\"false\">\n";
        url += "<param name=fullname value=\"" + fullName + "\">\n";
        url += "<param name=userLevel value=\"" + mf.getUserLevel() + "\">\n";
        url += "<param name=uploadURL value=\"uploadURL\">\n";
        url += "<param name=chatLogPath value=\"chatLogPath\">\n";
        url += "<param name=siteRoot value=\"" + mf.getSiteRoot() + "\">\n";
        url += "<param name=isWebPresent value=\"true\">\n";
        url += "<param name=slidesDir value=\"" + mf.getSlidesDir() + "\">\n";
        url += "<param name=uploadPath value=\"uploadPath\">\n";
        url += "<param name=resourcesPath value=\"" + mf.getResourcesPath() + "\">\n";
        url += "<param name=sessionId value=\"" + mf.getSessionId() + "\">\n";
        url += "<param name=sessionTitle value=\"" + mf.getSessionTitle() + "\">\n";
        url += "<param name=slideServerId value=\"" + mf.getSlideServerId() + "\">\n";

        url += "<param name=isSessionPresenter value=\"false\">\n";
        url += "</applet>\n";
        url += "</center>\n";
         */ return url;
    }
}
