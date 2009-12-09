/*

 *
 * Copyright (C) GNU/GPL AVOIR 2008
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

 */
package org.avoir.realtime.gui.webbrowser;

import chrriis.dj.nativeswing.swtimpl.NativeInterface;
import chrriis.dj.nativeswing.swtimpl.components.JWebBrowser;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.IOException;
import java.net.URI;
import java.net.URISyntaxException;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JPanel;
import javax.swing.SwingUtilities;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.screenviewer.webstart.gui.StartScreen;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.avoir.realtime.gui.screenviewer.webstart.screen.CaptureScreen;

/**
 *
 * @author developer
 */
public class WebBrowserManager {

    public WebBrowserManager() {
    }
    private boolean showAudioVideo = true;
    private boolean showAudioVideoBroadcast = true;
    private String warning = "Closing this will stop these functions from " +
            " working until you restart the application.\n Do you still want to close?";
    private JFrame frame = new JFrame();

    /*
     * This method is only compatible in java 1.6 or above
     */
    public void showScreenShareViewer() {
       /* SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                try {
                    try {
                        java.awt.Desktop.getDesktop().browse(new URI(ConnectionManager.AUDIO_VIDEO_URL + "/screen/screen.html?username=test1"));
                    } catch (IOException ex) {
                        ex.printStackTrace();
                    }
                } catch (URISyntaxException ex) {
                    ex.printStackTrace();
                }
            }
        });*/
    }

    public void showScreenShareViewer(final int w, final int h, final String title, final boolean centerScreen) {
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                final JWebBrowser webBrowser = new JWebBrowser();
                frame.setTitle(title);
                frame.setAlwaysOnTop(true);
                frame.setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
                frame.getContentPane().add(webBrowser, BorderLayout.CENTER);
                frame.setSize(w, h);
                frame.setLocationByPlatform(centerScreen);
                frame.setVisible(true);
                webBrowser.setMenuBarVisible(false);
                webBrowser.navigate(ConnectionManager.AUDIO_VIDEO_URL + "/screen/screen.html?username=test1");
            }
        });
        NativeInterface.runEventPump();
    }

    public void closeScreenShareViewer() {
        frame.dispose();
    }

    public void showScreenShareViewerAsEmbbededTab(final JFrame fr) {

        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                final JWebBrowser webBrowser = new JWebBrowser();
                JPanel mPanel = new JPanel(new BorderLayout());
                JPanel cPanel = new JPanel();
                JButton stopButton = new JButton("Screen Share Options");
                stopButton.setBackground(Color.RED);
                stopButton.addActionListener(new ActionListener() {

                    public void actionPerformed(ActionEvent e) {
                        for (int i = 1; i < GUIAccessManager.mf.getChatTabbedPane().getTabCount(); i++) {
                            GUIAccessManager.mf.getChatTabbedPane().remove(i);
                        }
                        fr.setVisible(true);
                    }
                });

                webBrowser.setMenuBarVisible(false);
                webBrowser.navigate(ConnectionManager.AUDIO_VIDEO_URL + "/screen/screen.html?username=test1");
                webBrowser.setBarsVisible(false);
                webBrowser.setButtonBarVisible(false);
                webBrowser.setLocationBarVisible(false);
                webBrowser.setStatusBarVisible(false);
                mPanel.add(webBrowser, BorderLayout.CENTER);
                mPanel.add(cPanel, BorderLayout.SOUTH);
                cPanel.add(stopButton);
                GUIAccessManager.mf.getTabbedPane().addTab("ScreenShare", cPanel);
                GUIAccessManager.mf.getChatTabbedPane().setSelectedComponent(cPanel);
                //GUIAccessManager.mf.getChatTabbedPane().addTab("Preview", mPanel);
                //GUIAccessManager.mf.getChatTabbedPane().setSelectedComponent(mPanel);
            }
        });
        NativeInterface.runEventPump();

    }

    public void showScreenShareViewerAsEmbbededTab1(final JFrame fr) {

        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                final JWebBrowser webBrowser = new JWebBrowser();
                JPanel mPanel = new JPanel(new BorderLayout());
                JPanel cPanel = new JPanel();
                JButton stopButton = new JButton("Screen Share Options");
                stopButton.setBackground(Color.RED);
                stopButton.addActionListener(new ActionListener() {

                    public void actionPerformed(ActionEvent e) {
                        for (int i = 1; i < GUIAccessManager.mf.getChatTabbedPane().getTabCount(); i++) {
                            GUIAccessManager.mf.getChatTabbedPane().remove(i);
                        }
                        fr.setVisible(true);
                    }
                });

                webBrowser.setMenuBarVisible(false);
                webBrowser.navigate(ConnectionManager.AUDIO_VIDEO_URL + "/screen/screen.html?username=test1");
                webBrowser.setBarsVisible(false);
                webBrowser.setButtonBarVisible(false);
                webBrowser.setLocationBarVisible(false);
                webBrowser.setStatusBarVisible(false);
                mPanel.add(webBrowser, BorderLayout.CENTER);
                mPanel.add(cPanel, BorderLayout.SOUTH);
                cPanel.add(stopButton);
                GUIAccessManager.mf.getTabbedPane().addTab("ScreenShare", cPanel);
                GUIAccessManager.mf.getTabbedPane().setSelectedComponent(cPanel);
                //GUIAccessManager.mf.getChatTabbedPane().addTab("Preview", mPanel);
                //GUIAccessManager.mf.getChatTabbedPane().setSelectedComponent(mPanel);
            }
        });
        NativeInterface.runEventPump();

    }

    public void showScreenShareFrame() {
        String host = ConnectionManager.AUDIO_VIDEO_URL + "/ScreenServlet";
        StartScreen ss = new StartScreen(host, "test1", "default", "default", "test1", "test1");
        ss.initMainFrame();


    }

   
}
