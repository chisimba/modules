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
import java.awt.Dimension;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.KeyListener;
import java.awt.event.KeyEvent;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.io.IOException;
import java.net.URI;
import java.net.URISyntaxException;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JMenu;
import javax.swing.JMenuItem;
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
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private int fullX = (int) ss.getWidth();
    private int fullY = (int) ss.getHeight();
    private String zoomScript = "document.write(\"hello\")";
    private double zoomFactor = 1.0;

    //for java 1.6 above
    public void showTestView() {
        SwingUtilities.invokeLater(new Runnable() {

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
        });
    }

    public void showScreenShareViewer(final int w, final int h, final String title, final boolean centerScreen) {
       
        final JMenu menu = new JMenu("Tools");
        final JMenuItem zoomIn = new JMenuItem("Zoom In");
        final JMenuItem zoomOut = new JMenuItem("Zoom Out");

        zoomIn.addActionListener(new ActionListener (){
            public void actionPerformed(ActionEvent e){
                CaptureScreen.changImgSize(100, 100);
            }
        });
        zoomOut.addActionListener(new ActionListener (){
            public void actionPerformed(ActionEvent e){
                CaptureScreen.changImgSize(-100, -100);
            }
        });
        menu.add(zoomIn);
        menu.add(zoomOut);

        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                final ZoomBrowser webBrowser = new ZoomBrowser();
                frame.addKeyListener(webBrowser);
                frame.setTitle(title);
                frame.setAlwaysOnTop(true);
                frame.setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
                frame.getContentPane().add(webBrowser, BorderLayout.CENTER);
                frame.setSize(w, h);
                frame.setLocationByPlatform(centerScreen);
                frame.setVisible(true);
                //webBrowser.setMenuBarVisible(false);
                //webBrowser.getFileMenu().add(zoomIn);
                //webBrowser.getFileMenu().add(zoomOut);
                webBrowser.getMenuBar().add(menu);
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
                GUIAccessManager.mf.getChatTabbedPane().addTab("Preview", mPanel);
                GUIAccessManager.mf.getChatTabbedPane().setSelectedComponent(mPanel);
            }
        });
        NativeInterface.runEventPump();

    }

    public void showScreenShareFrame() {
        String host = ConnectionManager.AUDIO_VIDEO_URL + "/ScreenServlet";
        StartScreen ss = new StartScreen(host, "test1", "default", "default", "test1", "test1");
        ss.initMainFrame();


    }

    class ZoomBrowser extends JWebBrowser implements KeyListener,MouseListener{

        public ZoomBrowser() {
        }

        public void mouseClicked(MouseEvent e) {

        }

        public void mouseEntered(MouseEvent e) {

        }

        public void mouseExited(MouseEvent e) {

        }

        public void mousePressed(MouseEvent e) {
           this.requestFocusInWindow();
        }

        public void mouseReleased(MouseEvent e) {
            throw new UnsupportedOperationException("Not supported yet.");
        }

        @Override
        public void paintComponent(Graphics g) {
            super.paintComponent(g);
            Graphics2D g2 = (Graphics2D) g;

            g2.scale(zoomFactor, zoomFactor);
        }

        public void keyPressed(KeyEvent e) {
            if (e.getKeyCode() == KeyEvent.VK_F12){
                //zoomIn();
                System.out.println("+");
                zoomFactor += 0.1;
                repaint();
            }
            if (e.getKeyCode() == KeyEvent.VK_F11){
                //zoomOut();
                System.out.println("-");
                zoomFactor -= 0.1;
                repaint();
            }
        }

        public void keyReleased(KeyEvent e) {
        }

        public void keyTyped(KeyEvent e) {
        }
    }

    class ZoomListener implements KeyListener {

        public ZoomListener() {
        }

        public void keyPressed(KeyEvent e) {
            if (e.getKeyCode() == KeyEvent.VK_F12){
                //zoomIn();
                System.out.println("+");
                zoomFactor += 0.1;
            }
            if (e.getKeyCode() == KeyEvent.VK_F11){
                //zoomOut();
                System.out.println("-");
                zoomFactor -= 0.1;
            }
        }

        public void keyReleased(KeyEvent e) {
        }

        public void keyTyped(KeyEvent e) {
        }
    }
}
