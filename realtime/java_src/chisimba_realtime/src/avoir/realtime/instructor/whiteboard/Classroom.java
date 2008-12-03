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
package avoir.realtime.instructor.whiteboard;

import avoir.realtime.classroom.ClassroomMainFrame;
import avoir.realtime.classroom.whiteboard.item.Img;
import avoir.realtime.filetransfer.FileUploader;
import chrriis.common.UIUtils;
import chrriis.dj.nativeswing.swtimpl.NativeInterface;
import java.awt.BorderLayout;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.io.File;
import javax.swing.ImageIcon;
import javax.swing.JFrame;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JToolBar;

/**
 *
 * @author developer
 */
public class Classroom extends ClassroomMainFrame {

    private String selectedFile;
    private SessionManager sessionManager;
    private TCPConnector connector;
    private ArchiveManager archiveManager;
    private Whiteboard whiteboard;
    private FileUploader fileUploader;
    private MenuManager menuManager;
    private ToolbarManager toolbarManager;
    private ClassroomManager classroomManager;

    public Classroom(
            String host,
            int port,
            String username,
            String fullnames,
            boolean isPresenter,
            String sessionId,
            String sessionTitle,
            String userLevel,
            String slidesDir,
            String siteRoot,
            String slidesServerId,
            String resourcesPath,
            boolean webPresent,
            String mediaServerHost,
            int audioMICPort, int audioSpeakerPort,
            JFrame parent) {
        super(host, port, username, fullnames, isPresenter, sessionId, sessionTitle,
                userLevel, slidesDir, siteRoot, slidesServerId, resourcesPath,
                webPresent, mediaServerHost,
                audioMICPort, audioSpeakerPort, parent);
        
        sessionManager = new SessionManager(this);
        connector = new TCPConnector(this);
        connector.setName("Instructor");
        whiteboard = new Whiteboard(this);
        fileUploader = new FileUploader(this);
        menuManager = new MenuManager(this);
        archiveManager = new ArchiveManager(this);
        toolbarManager = new ToolbarManager(this);
        classroomManager = new ClassroomManager(this);
        JToolBar toolsBar = whiteboard.getToolsToolbar();
        JToolBar whiteboardToolbar = whiteboard.getMainToolbar();
        JToolBar navToolbar = toolbarManager.getSlidesNavigationToolBar();
        centerPanel.add(toolsBar, BorderLayout.EAST);
        JToolBar toolbar = new JToolBar();
       
        toolbar.add(whiteboardToolbar);
        toolbar.add(navToolbar);
        centerPanel.add(toolbar, BorderLayout.NORTH);
        surfaceScrollPane.setViewportView(whiteboard);
        setTcpConnector(connector);

        dockTabbedPane.addTab("Archive", archiveManager.getArchiveTree());
        chatRoom.setTcpSocket(connector);
        setJMenuBar(menuManager.getMenuBar());
        initNativeSwing();
       
        loadCachedSlides();
    }

    /**
     * Over ride the parent chat room method. Since they are both attached to dokTabbed pane
     * add the archive manager too. And allow undocking and re-docking of chat window
     * but everytime this clears the tabbed pane (no idea why, yet ) , so before
     * undocking it, keep a copy of archve panel, then add it back when everying
     * dissappears during the undocking process
     */
    @Override
    public void showChatRoom() {
        final JPanel panel = (JPanel) dockTabbedPane.getComponentAt(0);
        final JPanel archivePanel = (JPanel) dockTabbedPane.getComponentAt(1);
        JFrame fr = new JFrame("Chat - Close to dock");
        fr.addWindowListener(new WindowAdapter() {

            @Override
            public void windowClosing(WindowEvent e) {
                dockTabbedPane.removeAll();
                dockTabbedPane.add(panel, "Chat");
                dockTabbedPane.add(archivePanel, "Archives");
            }
        });
        fr.setLayout(new BorderLayout());
        fr.setAlwaysOnTop(true);
        fr.add(panel, BorderLayout.CENTER);
        fr.setSize(400, 300);
        fr.setLocationRelativeTo(null);
        fr.setVisible(true);
        dockTabbedPane.remove(0);
        dockTabbedPane.add(archivePanel, "Archives");
    }

    public ClassroomManager getClassroomManager() {
        return classroomManager;
    }

    /**
     * Previously downloaded presentations for this session...load them up
     */
    public void loadCachedSlides() {
        archiveManager.addArchive(new String[0], "Whiteboard");
        File cacheHome = new File(avoir.realtime.common.Constants.getRealtimeHome() + "/classroom/slides/" + user.getSessionId());
        if (!cacheHome.exists()) {
            return;
        }
        String[] slides = cacheHome.list();
        System.out.println(cacheHome);
        if (slides != null) {
            for (int i = 0; i < slides.length; i++) {
                String presentation = cacheHome.getAbsolutePath() + "/" + slides[i];
                if (i == 0) {
                    setSelectedFile(new File(presentation).getName());

                }
                int[] slidesList = connector.getImageFileNames(presentation);

                archiveManager.addArchive(connector.createSlideNames(slidesList), new File(presentation).getName());

            }
        }
    }

    public MenuManager getMenuManager() {
        return menuManager;
    }

    /**
     * Here the session is on, and so reload the images in case you broke off before the session
     * ended
     */
    public void loadCachedImage(Img img, String path) {

        File cacheHome = new File(avoir.realtime.common.Constants.getRealtimeHome() + "/classroom/images/" + user.getSessionId());
        if (!cacheHome.exists()) {
            return;
        }
        whiteboard.setImage(new ImageIcon(path));

    }

    private void initNativeSwing() {
        UIUtils.setPreferredLookAndFeel();
        NativeInterface.open();
    }

    /**
     * Connect to server
     */
    @Override
    public void connect() {
        Thread t = new Thread() {

            @Override
            public void run() {
                if (!connector.connectToServer(host, port)) {
                    String msg = "Cannot connect to server";
                    JOptionPane.showMessageDialog(Classroom.this, msg);
                    showErrorMessage(msg);
                }
            }
        };
        t.start();
    }

    public FileUploader getFileUploader() {
        return fileUploader;
    }

    public Whiteboard getWhiteboard() {
        return whiteboard;
    }

    public ArchiveManager getArchiveManager() {
        return archiveManager;
    }

    public TCPConnector getConnector() {
        return connector;
    }

    public SessionManager getSessionManager() {
        return sessionManager;
    }

    public String getSelectedFile() {
        return selectedFile;
    }

    public void setSelectedFile(String selectedFile) {
        this.selectedFile = selectedFile;
    }
}
