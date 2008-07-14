/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.managers;

import avoir.realtime.tcp.base.RealtimeBase;
import avoir.realtime.tcp.base.Version;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
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

    public MenuManager(RealtimeBase base) {
        this.base = base;
        JMenu toolsMenu = createMenu("Tools");
        JMenu helpMenu = createMenu("Help");

        createMenuItem(toolsMenu, "Audio Setup", "audiosetup");
        createMenuItem(toolsMenu, "Filters", "filter");
        createMenuItem(toolsMenu, "Options", "options");
        createMenuItem(helpMenu, "About", "about");
        menuBar.add(toolsMenu);
        menuBar.add(helpMenu);

    }

    public JMenuBar getMenuBar() {
        return menuBar;
    }

    public void actionPerformed(ActionEvent e) {
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
            JOptionPane.showMessageDialog(null, aboutText + "<br><center>Version 0.1" +
                    " beta Build " + Version.version + "</center>");

        }
    }

    private JMenu createMenu(String txt) {
        JMenu menu = new JMenu(txt);
        return menu;
    }

    private void createMenuItem(JMenu menu, String txt, String action) {
        JMenuItem item = new JMenuItem(txt);
        item.addActionListener(this);
        item.setActionCommand(action);
        menu.add(item);
    }
}
