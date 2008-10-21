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
package avoir.realtime.tcp.base.managers;

import avoir.realtime.tcp.base.BrowserSettingsFrame;
import avoir.realtime.tcp.base.RealtimeBase;
import avoir.realtime.tcp.base.appsharing.AppShareFrame;
import avoir.realtime.tcp.common.Constants;
import avoir.realtime.tcp.common.FlashFilter;
import avoir.realtime.tcp.common.ImageFilter;
import avoir.realtime.tcp.common.PresentationFilter;
import java.awt.Font;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.File;
import javax.swing.JFileChooser;
import javax.swing.JMenu;
import javax.swing.JMenuBar;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;

/**
 * This managers the menu system
 * @author David Wafula
 */
public class MenuManager implements ActionListener {

    private JMenuBar menuBar = new JMenuBar();
    private String title = "<font color=\"orange\">" +
            "<h1>Chisimba Realtime Communication Tools</h1></font><br>";
    private String owner = "University of Western Cape<br>" +
            "Free Software Innovation Unit<br>";
    private String cc = "<b>(c) 2008 AVOIR<br></b><br>";
    private String aboutText = "<html><center>" +
            title +
            owner +
            cc +
            // status +
            "</center>";
    private RealtimeBase base;
    private JFileChooser presentationFC = new JFileChooser();
    private JFileChooser graphicFC = new JFileChooser();
    private JFileChooser flashFC = new JFileChooser();
    private AppShareFrame appShareFrame;

    public MenuManager(RealtimeBase base) {
        this.base = base;
        presentationFC.addChoosableFileFilter(new PresentationFilter());
        graphicFC.addChoosableFileFilter(new ImageFilter());
        flashFC.addChoosableFileFilter(new FlashFilter());
        JMenu fileMenu = createMenu("File");
        JMenu insertMenu = createMenu("Insert");
        JMenu toolsMenu = createMenu("Tools");
        JMenu helpMenu = createMenu("Help");

        createMenuItem(toolsMenu, "Application Sharing", "appshare", true);
        createMenuItem(toolsMenu, "File Sharing", "fileSharing", base.getControl());
        createMenuItem(toolsMenu, "Survey", "survey", base.getControl());
        createMenuItem(toolsMenu, "Filters", "filter", true);
        createMenuItem(toolsMenu, "Audio Setup", "audiosetup", true);
        createMenuItem(toolsMenu, "Options", "options", true);
        createMenuItem(toolsMenu, "Web Browser Settings", "webBrowserSettings", true);
        createMenuItem(helpMenu, "About", "about", true);

        createMenuItem(fileMenu, "New Whiteboard", "newWhiteboard", false);
        createMenuItem(fileMenu, "New Room", "newRoom", false);

        fileMenu.addSeparator();
        createMenuItem(fileMenu, "Exit", "exit", true);
        createMenuItem(insertMenu, "Insert Webpage", "insertWebpage", base.getControl());

        createMenuItem(insertMenu, "Insert Graphic", "insertGraphic", base.getControl());
        createMenuItem(insertMenu, "Insert Presentation", "insertPresentation", base.getControl());
        createMenuItem(insertMenu, "Insert Flash", "insertFlash", base.getControl());
        menuBar.add(fileMenu);
        menuBar.add(insertMenu);

        menuBar.add(toolsMenu);
        menuBar.add(helpMenu);

    }

    public JMenuBar getMenuBar() {
        return menuBar;
    }

    public void actionPerformed(ActionEvent e) {
        if (e.getActionCommand().equals("webBrowserSettings")) {
            BrowserSettingsFrame fr = new BrowserSettingsFrame(base.getRealtimeOptions());
            fr.setLocationRelativeTo(null);
            fr.setVisible(true);
        }
        if (e.getActionCommand().equals("fileSharing")) {
            base.showFileTransferFrame();
        }
        if (e.getActionCommand().equals("survey")) {
            base.showSurveyManagerFrame();
        }
        if (e.getActionCommand().equals("insertWebpage")) {
            new Thread() {

                public void run() {
                    base.getBaseManager().showWebpage();
                }
            }.start();
        }
        if (e.getActionCommand().equals("appshare")) {
            if (base.getControl()) {
                if (appShareFrame == null) {
                    appShareFrame = new AppShareFrame(base);
                }
                appShareFrame.setSize(400, 300);
                appShareFrame.setLocationRelativeTo(null);
                appShareFrame.setVisible(true);
            } else {
                base.getTcpClient().getTCPConsumer().showRemoteDesktop();
            }
        }
        if (e.getActionCommand().equals("insertGraphic")) {
            if (graphicFC.showOpenDialog(base) == JFileChooser.APPROVE_OPTION) {
                final File f = graphicFC.getSelectedFile();
                Thread t = new Thread() {

                    public void run() {
                        if (base.getMainTabbedPane().getSelectedIndex() != 0) {
                            base.getBaseManager().animateTabTitle(base.getMainTabbedPane(), 0);
                        }
                        base.getFileUploader().transferFile(f.getAbsolutePath(), Constants.IMAGE);
                        base.setSelectedFile(f.getName());


                    }
                };
                t.start();
            }
        }
        if (e.getActionCommand().equals("insertFlash")) {
            if (flashFC.showOpenDialog(base) == JFileChooser.APPROVE_OPTION) {

                final File f = flashFC.getSelectedFile();
                Thread t = new Thread() {

                    public void run() {

                        base.getFileUploader().transferFile(f.getAbsolutePath(), Constants.FLASH);
                        base.setSelectedFile(f.getName());
                    }
                };
                t.start();

            }
        }

        if (e.getActionCommand().equals("insertPresentation")) {
            if (presentationFC.showOpenDialog(base) == JFileChooser.APPROVE_OPTION) {
                final File f = presentationFC.getSelectedFile();
                Thread t = new Thread() {

                    public void run() {

                        if (base.getMainTabbedPane().getSelectedIndex() != 0) {
                            base.getBaseManager().animateTabTitle(base.getMainTabbedPane(), 0);
                        }

                        base.getFileUploader().transferFile(f.getAbsolutePath(), Constants.PRESENTATION);
                        base.setSelectedFile(f.getName());

                    }
                };
                t.start();
            }
        }
        if (e.getActionCommand().equals("exit")) {
            System.exit(0);
        }
        if (e.getActionCommand().equals("options")) {
            base.showOptionsFrame();
        }
        if (e.getActionCommand().equals("filter")) {
            base.getBaseManager().showFilterFrame();
        }
        if (e.getActionCommand().equals("audiosetup")) {
            MenuManager.this.base.showAudioWizardFrame();
        }
        if (e.getActionCommand().equals("about")) {
            JOptionPane.showMessageDialog(null, aboutText + "<br><center>Beta Version 1.0.2</center>");

        }
    }

    private JMenu createMenu(String txt) {
        JMenu menu = new JMenu(txt);
        menu.setFont(new Font("Dialog", 0, 11));

        return menu;
    }

    private void createMenuItem(JMenu menu, String txt, String action, boolean enabled) {
        JMenuItem item = new JMenuItem(txt);
        item.setFont(new Font("Dialog", 0, 11));

        item.setEnabled(enabled);
        item.addActionListener(this);
        item.setActionCommand(action);
        menu.add(item);
    }
}
