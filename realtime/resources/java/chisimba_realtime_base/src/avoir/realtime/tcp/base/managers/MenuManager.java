/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.managers;

import avoir.realtime.tcp.base.RealtimeBase;
import avoir.realtime.tcp.base.Version;
import avoir.realtime.tcp.base.appsharing.AppShareFrame;
import avoir.realtime.tcp.common.Constants;
import avoir.realtime.tcp.common.ImageFilter;
import avoir.realtime.tcp.common.PresentationFilter;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.File;
import javax.swing.JFileChooser;
import javax.swing.JMenu;
import javax.swing.JMenuBar;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;

/**
 *
 * @author developer
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
    private AppShareFrame appShareFrame;

    public MenuManager(RealtimeBase base) {
        this.base = base;
        presentationFC.addChoosableFileFilter(new PresentationFilter());
        graphicFC.addChoosableFileFilter(new ImageFilter());
        JMenu fileMenu = createMenu("File");
        JMenu insertMenu = createMenu("Insert");
        JMenu toolsMenu = createMenu("Tools");
        JMenu helpMenu = createMenu("Help");

        createMenuItem(toolsMenu, "Application Sharing", "appshare", base.getControl());
        createMenuItem(toolsMenu, "Filters", "filter", true);
        createMenuItem(toolsMenu, "Audio Setup", "audiosetup", true);
        createMenuItem(toolsMenu, "Options", "options", true);
        createMenuItem(helpMenu, "About", "about", true);

        createMenuItem(fileMenu, "New Whiteboard", "newWhiteboard", false);
        createMenuItem(fileMenu, "New Room", "newRoom", false);

        fileMenu.addSeparator();
        createMenuItem(fileMenu, "Exit", "exit", true);
        createMenuItem(insertMenu, "Insert Graphic", "insertGraphic", base.getControl());
        createMenuItem(insertMenu, "Insert Presentation", "insertPresentation", base.getControl());
        createMenuItem(insertMenu, "Insert Flash", "insertFlash", false);
        menuBar.add(fileMenu);
        menuBar.add(insertMenu);
        //  }
        menuBar.add(toolsMenu);
        menuBar.add(helpMenu);

    }

    public JMenuBar getMenuBar() {
        return menuBar;
    }

    public void actionPerformed(ActionEvent e) {
        if (e.getActionCommand().equals("appshare")) {
            if (appShareFrame == null) {
                appShareFrame = new AppShareFrame(base);
            }
            appShareFrame.setSize(400, 300);
            appShareFrame.setLocationRelativeTo(null);
            appShareFrame.setVisible(true);
        }
        if (e.getActionCommand().equals("insertGraphic")) {
            if (graphicFC.showOpenDialog(base) == JFileChooser.APPROVE_OPTION) {
                final File f = graphicFC.getSelectedFile();
                Thread t = new Thread() {

                    public void run() {
                        base.getFileUploader().transferFile(f.getAbsolutePath(), Constants.IMAGE);
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
            base.showFilterFrame();
        }
        if (e.getActionCommand().equals("audiosetup")) {
            MenuManager.this.base.showAudioWizardFrame();
        }
        if (e.getActionCommand().equals("about")) {
            JOptionPane.showMessageDialog(null, aboutText + "<br><center>Version 1.0.2" +
                    " Build " + Version.version + "</center>");

        }
    }

    private JMenu createMenu(String txt) {
        JMenu menu = new JMenu(txt);
        return menu;
    }

    private void createMenuItem(JMenu menu, String txt, String action, boolean enabled) {
        JMenuItem item = new JMenuItem(txt);
        item.setEnabled(enabled);
        item.addActionListener(this);
        item.setActionCommand(action);
        menu.add(item);
    }
}
