/**
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
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
package avoir.realtime.instructor.whiteboard;

import avoir.realtime.appsharing.AppShareFrame;
import avoir.realtime.appsharing.Java2ScreenScraper;
import avoir.realtime.appsharing.tcp.StartScreenSharing;
import avoir.realtime.appsharing.tcp.StopScreenSharing;
import avoir.realtime.classroom.RealtimeOptionsFrame;
import avoir.realtime.common.Constants;
import avoir.realtime.common.FlashFilter;
import avoir.realtime.common.ImageFilter;
import avoir.realtime.common.PresentationFilter;
import avoir.realtime.common.packet.ResumeWhiteboardSharePacket;
import chrriis.dj.nativeswing.swtimpl.components.JWebBrowser;
import java.awt.Component;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.Point;
import java.awt.Rectangle;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.File;
import java.util.Timer;
import java.util.TimerTask;
import javax.swing.JCheckBoxMenuItem;
import javax.swing.JFileChooser;
import javax.swing.JMenu;
import javax.swing.JMenuBar;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;
import javax.swing.JTabbedPane;
import javax.swing.JTextField;

/**
 * This managers the menu system
 * @author David Wafula
 */
public class MenuManager implements ActionListener {

    private FinalGlassPane glassPane;
    private JMenuBar menuBar = new JMenuBar();
    private String title = "<font color=\"orange\">" +
            "<h1>Realtime Communication Tools</h1></font><br>";
    private String owner = "University of Western Cape<br>" +
            "Free Software Innovation Unit<br>";
    private String developers = "Developers: <b>David Waf, Feroz Zaidi</b>";
    private String cc = "<b>(c) 2008 AVOIR<br></b><br>";
    private String aboutText = "<html><center>" +
            title +
            owner +
            cc +
            developers +
            "</center>";
    private Classroom mf;
    private JFileChooser presentationFC = new JFileChooser();
    private JFileChooser graphicFC = new JFileChooser();
    private JFileChooser flashFC = new JFileChooser();
    private AppShareFrame appShareFrame;
    private boolean shareWhiteboard = true;
    private boolean shareWebPage = false;
    private Java2ScreenScraper screenScraper;
    private Java2ScreenScraper recordScreen;
    private Timer shareAlertTimer = new Timer();
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private boolean appSharing = true;

    public MenuManager(Classroom mf) {
        this.mf = mf;
        glassPane = new FinalGlassPane(mf);
        presentationFC.addChoosableFileFilter(new PresentationFilter());
        graphicFC.addChoosableFileFilter(new ImageFilter());
        flashFC.addChoosableFileFilter(new FlashFilter());
        try {
            screenScraper = new Java2ScreenScraper(mf.getConnector(), mf.getUser().getSessionId(), false);
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        try {
            recordScreen = new Java2ScreenScraper(mf.getConnector(), mf.getUser().getSessionId(), true);
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        JMenu fileMenu = createMenu("File");
        JMenu insertMenu = createMenu("Insert");
        JMenu screenShareMenu = createMenu("Screen Share");
        JMenu toolsMenu = createMenu("Tools");
        JMenu helpMenu = createMenu("Help");

        createMenuItem(toolsMenu, "Session Details", "sessionDetails", true);
        toolsMenu.addSeparator();
        createMenuItem(toolsMenu, "File Sharing", "fileSharing", true);
        createMenuItem(toolsMenu, "Survey", "survey", true);
        createMenuItem(toolsMenu, "Filters", "filter", true);
        createMenuItem(toolsMenu, "Audio Setup", "audiosetup", true);
        createMenuItem(toolsMenu, "Options", "options", true);
        createMenuItem(toolsMenu, "Web Browser Settings", "webBrowserSettings", true);
        createMenuItem(helpMenu, "About", "about", true);


        createCBMenuItem(screenShareMenu, "Share Whiteboard", "shareWhiteboard", true);
        createCBMenuItem(screenShareMenu, "Share Web Page", "shareWebPage", true);
        screenShareMenu.addSeparator();
        createMenuItem(screenShareMenu, "Application Sharing", "appshare", true);

        createMenuItem(fileMenu, "New Whiteboard", "newWhiteboard", false);
        createMenuItem(fileMenu, "New Room", "newRoom", false);

        fileMenu.addSeparator();
        createMenuItem(fileMenu, "Exit", "exit", true);
        createMenuItem(insertMenu, "Insert Presentation", "insertPresentation", true);
       // createMenuItem(insertMenu, "Insert Webpage", "insertWebpage", true);
        createMenuItem(insertMenu, "Insert Graphic", "insertGraphic", true);
        createMenuItem(insertMenu, "Insert Flash", "insertFlash", true);
        menuBar.add(fileMenu);
        menuBar.add(screenShareMenu);
        menuBar.add(insertMenu);

        menuBar.add(toolsMenu);
        menuBar.add(helpMenu);

    }

    public boolean shareWebPage() {
        return shareWebPage;
    }

    public Java2ScreenScraper getScreenScraper() {
        return screenScraper;
    }

    public void setShareWebPage(boolean shareWebPage) {
        this.shareWebPage = shareWebPage;
    }

    public boolean shareWhiteboard() {
        return shareWhiteboard;
    }

    public void setShareWhiteboard(boolean shareWhiteboard) {
        this.shareWhiteboard = shareWhiteboard;
    }

    public JMenuBar getMenuBar() {
        return menuBar;
    }

    public void initAppshare() {
        /* if (appShareFrame == null) {
        appShareFrame = new AppShareFrame(mf, mf.getUser().getSessionId());
        }
        appShareFrame.setVisible(true);
        appShareFrame.setSize(400, 300);
        appShareFrame.setLocationRelativeTo(null);*/
        if (appSharing) {
            screenScraper.startScraping();
            mf.getInfoPanel().setCenterMessage("Application Sharing On");
        } else {
            screenScraper.stopScraping();
            mf.getTcpConnector().sendPacket(new StopScreenSharing(false));
            mf.getInfoPanel().setCenterMessage("Quick Info");

        }
        appSharing = !appSharing;
    }

    public void stopAppshare() {
        screenScraper.stopScraping();
    }

    public Java2ScreenScraper getRecordScreen() {
        return recordScreen;
    }

    public void setRecordScreen(Java2ScreenScraper recordScreen) {
        this.recordScreen = recordScreen;
    }

    public void initRecord() {
        mf.getConnector().sendPacket(new StartScreenSharing(true));
        recordScreen.startScraping();
//        mf.getToolBar().setRecording(true);
    }

    public void stopRecord() {
        recordScreen.stopScraping();
        mf.getConnector().sendPacket(new StopScreenSharing(true));
    //      mf.getToolBar().setRecording(false);

    }

    public void insertWebpage() {
        new Thread() {

            public void run() {
               // mf.getClassroomManager().showWebpage();
            }
        }.start();
    }

    public void actionPerformed(ActionEvent e) {
        if (e.getActionCommand().equals("sessionDetails")) {
            JTextField infoField = new JTextField(mf.getUser().getSessionId());
            JOptionPane.showMessageDialog(mf.getParentFrame(), infoField);

        }
        if (e.getActionCommand().equals("webBrowserSettings")) {
            BrowserSettingsFrame fr = new BrowserSettingsFrame(mf.getRealtimeOptions());
            fr.setLocationRelativeTo(null);
            fr.setVisible(true);
        }
        if (e.getActionCommand().equals("fileSharing")) {
            //mf.showFileTransferFrame();
        }
        if (e.getActionCommand().equals("survey")) {
            // mf.showSurveyManagerFrame();
        }
        if (e.getActionCommand().equals("insertWebpage")) {
            insertWebpage();
        }
        if (e.getActionCommand().equals("shareWhiteboard")) {
            JCheckBoxMenuItem cb = (JCheckBoxMenuItem) e.getSource();
            shareWhiteboard = cb.isSelected();
            if (shareWhiteboard) {
                mf.getConnector().sendPacket(new ResumeWhiteboardSharePacket());
            }
        }
        if (e.getActionCommand().equals("shareWebPage")) {
            JCheckBoxMenuItem cb = (JCheckBoxMenuItem) e.getSource();
            shareWebPage = cb.isSelected();
            Component selectedTab = mf.getMainTabbedPane().getSelectedComponent();
            if (!(selectedTab instanceof JWebBrowser)) {
                JOptionPane.showMessageDialog(null, "Please insert a webpage. Then select it to share");
                shareWebPage = false;
                cb.setSelected(false);
                return;
            }
            if (shareWebPage) {
                /*shareAlertTimer.cancel();
                shareAlertTimer = new Timer();
                shareAlertTimer.scheduleAtFixedRate(new ScreenShareAlerter(), 0, 1000);
                 */
                JTabbedPane pane = mf.getMainTabbedPane();
                Point location = pane.getSelectedComponent().getLocationOnScreen();

                int width = mf.getWhiteboard().getWidth();
                int height = mf.getWhiteboard().getHeight();
                screenScraper.setFullScrapeRect(new Rectangle(location.x, location.y, width, height));
                screenScraper.startScraping();
                glassPane.setMf(mf);
                glassPane.repaint();
                mf.getParentFrame().setGlassPane(glassPane);
                glassPane.setVisible(true);
            } else {
                shareAlertTimer.cancel();

                screenScraper.stopScraping();
                mf.getConnector().sendPacket(new StopScreenSharing(false));
                glassPane.setVisible(false);

            }
        }
        if (e.getActionCommand().equals("appshare")) {
            initAppshare();

        }
        if (e.getActionCommand().equals("insertGraphic")) {
            if (graphicFC.showOpenDialog(mf) == JFileChooser.APPROVE_OPTION) {
                final File f = graphicFC.getSelectedFile();
                Thread t = new Thread() {

                    public void run() {
                        if (mf.getMainTabbedPane().getSelectedIndex() != 0) {
                            mf.getClassroomManager().animateTabTitle(mf.getMainTabbedPane(), 0);
                        }
                        mf.getFileUploader().transferFile(f.getAbsolutePath(), Constants.IMAGE);
                        mf.setSelectedFile(f.getName());


                    }
                };
                t.start();
            }
        }
        if (e.getActionCommand().equals("insertFlash")) {
            if (flashFC.showOpenDialog(mf) == JFileChooser.APPROVE_OPTION) {

                final File f = flashFC.getSelectedFile();
                Thread t = new Thread() {

                    public void run() {

                        mf.getFileUploader().transferFile(f.getAbsolutePath(), Constants.FLASH);
                        mf.setSelectedFile(f.getName());
                    }
                };
                t.start();

            }
        }

        if (e.getActionCommand().equals("insertPresentation")) {
            if (presentationFC.showOpenDialog(mf) == JFileChooser.APPROVE_OPTION) {
                final File f = presentationFC.getSelectedFile();
                WhiteboardUtil.showStatusWindow("Processing ... please wait", false);
                Thread t = new Thread() {

                    public void run() {

                        if (mf.getMainTabbedPane().getSelectedIndex() != 0) {
                            mf.getClassroomManager().animateTabTitle(mf.getMainTabbedPane(), 0);
                        }

                        mf.getFileUploader().transferFile(f.getAbsolutePath(), Constants.PRESENTATION);
                        mf.setSelectedFile(f.getName());

                    }
                };
                t.start();
            }
        }
        if (e.getActionCommand().equals("exit")) {
            System.exit(0);
        }
        if (e.getActionCommand().equals("options")) {
            RealtimeOptionsFrame fr = new RealtimeOptionsFrame(mf, 0);
            fr.setSize((ss.width / 4) * 3, (ss.height / 4) * 3);
            fr.setLocationRelativeTo(null);
            fr.setVisible(true);
        }
        if (e.getActionCommand().equals("filter")) {
            //          mf.getBaseManager().showFilterFrame();
        }
        if (e.getActionCommand().equals("audiosetup")) {
            //        MenuManager.this.mf.showAudioWizardFrame();
        }
        if (e.getActionCommand().equals("about")) {
            JOptionPane.showMessageDialog(null, aboutText + "<br><center>Version 1.1 Beta</center>");

        }
    }

    private JMenu createMenu(String txt) {
        JMenu menu = new JMenu(txt);
        menu.setFont(new Font("Dialog", 0, 11));

        return menu;
    }

    private void createCBMenuItem(JMenu menu, String txt, String action, boolean enabled) {
        JCheckBoxMenuItem item = new JCheckBoxMenuItem(txt);
        item.setFont(new Font("Dialog", 0, 11));
        if (txt.equals("Share Whiteboard")) {
            item.setSelected(true);
        }
        item.setEnabled(enabled);
        item.addActionListener(this);
        item.setActionCommand(action);
        menu.add(item);
    }

    private void createMenuItem(JMenu menu, String txt, String action, boolean enabled) {
        JMenuItem item = new JMenuItem(txt);
        item.setFont(new Font("Dialog", 0, 11));

        item.setEnabled(enabled);
        item.addActionListener(this);
        item.setActionCommand(action);
        menu.add(item);
    }

    private class ScreenShareAlerter extends TimerTask {

        private boolean drawrect = false;

        public void run() {
            if (drawrect) {
                glassPane.setDrawRect(true);
                drawrect = false;
            } else {
                glassPane.setDrawRect(false);
                drawrect = true;
            }
            glassPane.repaint();
        }
    }
}
