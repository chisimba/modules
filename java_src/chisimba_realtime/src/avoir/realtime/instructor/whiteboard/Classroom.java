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

import avoir.realtime.classroom.ClassicToolbar;
import avoir.realtime.classroom.ClassroomMainFrame;
import avoir.realtime.classroom.whiteboard.item.Img;
import avoir.realtime.common.ImageUtil;
import avoir.realtime.filetransfer.FileUploader;
//import chrriis.common.UIUtils;
//import chrriis.dj.nativeswing.swtimpl.NativeInterface;
import avoir.realtime.survey.SurveyManagerFrame;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Font;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.io.File;
import javax.swing.BorderFactory;
import javax.swing.ImageIcon;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JToggleButton;
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
    //private InstructorRealtimeToolBar instructorToolbar;
    private ClassicInstructorToolbar instructorToolbar;
    private JLabel headerField;// = new JLabel("Class password: xxxx; Class Name: yyyyyy");
    private SurveyManagerFrame surveyManagerFrame;
    int surveyCount = 0;
    private InstructorFileReceiverManager instructorFileReceiverManager;
    private SlideBuilderManager slideBuilderManager;
    int slideBuilderCount = 0;

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
        headerField = new JLabel("Class password: " + sessionId + " | Class Name:  " + sessionTitle);
        headerField.setFont(new Font("Dialog", 1, 12));
        // headerField.setEnabled(false);
        //  headerField.setEditable(false);
        headerField.setBorder(BorderFactory.createEtchedBorder());
        headerField.setBackground(Color.WHITE);
        headerField.setOpaque(true);
        sessionManager = new SessionManager(this);
        connector = new TCPConnector(this);
        connector.setName("Instructor");
        whiteboard = new Whiteboard(this);
        fileUploader = new FileUploader(this);
        menuManager = new MenuManager(this);
        archiveManager = new ArchiveManager(this);
        toolbarManager = new ToolbarManager(this);
        classroomManager = new ClassroomManager(this);
        slideBuilderManager = new SlideBuilderManager(this);
//        instructorToolbar = new InstructorRealtimeToolBar(this);
        instructorToolbar = new ClassicInstructorToolbar(this);
        instructorFileReceiverManager = new InstructorFileReceiverManager(this);
        JToolBar toolsBar = whiteboard.getToolsToolbar();
        //    JToolBar whiteboardToolbar = whiteboard.getMainToolbar();
        //    JToolBar navToolbar = toolbarManager.getSlidesNavigationToolBar();
        centerPanel.add(toolsBar, BorderLayout.EAST);
        JPanel pp = new JPanel(new BorderLayout());
        pp.setBorder(BorderFactory.createEtchedBorder());
        pp.add(headerField, BorderLayout.NORTH);
        pp.add(instructorToolbar, BorderLayout.CENTER);
        centerPanel.add(pp, BorderLayout.NORTH);
        surfaceScrollPane.setViewportView(whiteboard);
        setTcpConnector(connector);

        //dockTabbedPane.addTab("Archive", archiveManager.getArchiveTree());

        JToggleButton historyButton = new JToggleButton("History");
        historyButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {
                leftBottonNorthPanel.removeAll();
                leftBottonNorthPanel.add(ttop, BorderLayout.NORTH);
                leftBottonNorthPanel.add(archiveManager.getArchiveTree(), BorderLayout.CENTER);
                leftSplitPane.repaint();

            }
        });
        // chatButtonGroup.add(chatButton);
        //chatButton.setSelected(true);
        historyButton.setIcon(ImageUtil.createImageIcon(this, "/icons/folder_yellow.png"));

        chatButtonGroup.add(historyButton);
        ttop.add(historyButton);

        chatRoom.setTcpSocket(connector);
        setJMenuBar(menuManager.getMenuBar());
        //   initNativeSwing();

        loadCachedSlides();
        addToolbarButtons();
        initAudio();
    }

    @Override
    public ClassicToolbar getToolBar() {
        return instructorToolbar;
    }

    public InstructorFileReceiverManager getInstructorFileReceiverManager() {
        return instructorFileReceiverManager;
    }

    @Override
    public void setQuestionImage(ImageIcon image) {
        JOptionPane.showMessageDialog(null, image);
    }

    public SurveyManagerFrame getSurveyManagerFrame() {
        return surveyManagerFrame;
    }

    public void showSlideBuilder() {
        int offset = 30 * slideBuilderCount;
        slideBuilderManager = new SlideBuilderManager(this);
        slideBuilderManager.setSize((ss.width / 8) * 6, (ss.height / 8) * 6);
        slideBuilderManager.setLocation(((ss.width - slideBuilderManager.getWidth()) / 2) + offset,
                ((ss.height - slideBuilderManager.getHeight()) / 2) + offset);

        slideBuilderManager.setVisible(true);
        slideBuilderCount++;
    }

    public int getSlideBuilderCount() {
        return slideBuilderCount;
    }

    public void showQuestionsManager() {
        int offset = 30 * surveyCount;
        surveyManagerFrame = new SurveyManagerFrame(this);
        ((TCPConnector) tcpConnector).setSurveyManagerFrame(surveyManagerFrame);
        surveyManagerFrame.requestForQuestions();
        surveyManagerFrame.setSize((ss.width / 8) * 6, (ss.height / 8) * 6);
        surveyManagerFrame.setLocation(((ss.width - surveyManagerFrame.getWidth()) / 2) + offset, ((ss.height - surveyManagerFrame.getHeight()) / 2) + offset);
        surveyManagerFrame.setVisible(true);
        surveyCount++;
    }

    private void addToolbarButtons() {
        //instructorToolbar.add("Chat", "/icons/chat32.png", "chat", "Private Chat", false, true);
      //  instructorToolbar.add("Mic", "/icons/micro.png", "mic", "Microphone", false, true);
        instructorToolbar.add("Pointer", "/icons/arrow_side32.png", "pointer", "Pointer");
        //instructorToolbar.add("Speaker", "/icons/speaker.png", "speaker", "Speaker", false, true);
        instructorToolbar.add("Notepad", "/icons/kedit32.png", "notepad", "Notepad");
        instructorToolbar.add("Documents", "/icons/document.png", "documents", "Documents");
        instructorToolbar.add("Slide Builder", "/icons/slidebuilder.png", "slideBuilder", "Slide Builder");
        instructorToolbar.add("Presentation", "/icons/odt.png", "presentation", "Presentation");
        instructorToolbar.add("Insert Image", "/icons/image.png", "graphic", "Insert Graphic");
        //   instructorToolbar.add("/icons/media-record.png", "record", "Record");
        instructorToolbar.add("Questions", "/icons/question32.jpg", "question", "Question Builder");
        instructorToolbar.add("App Share", "/icons/desktopshare.png", "desktopshare", "Desktop Share", false, true);
        //instructorToolbar.add("/icons/package_network.png", "webpage", "Insert Webpage");
        instructorToolbar.add("Settings", "/icons/global_config.png", "config", "Settings");

        //instructorToolbar.add("/icons/text_bold.png", "bold", "Bold", false, true);
        //instructorToolbar.add("/icons/text_under.png", "under", "Underline", false, true);
        //instructorToolbar.add("/icons/text_italic.png", "italic", "Italic", false, true);
        //instructorToolbar.add("/icons/fonts.png", "fonts", "Fonts", false, true);
    }

    /**
     * Over ride the parent chat room method. Since they are both attached to dockTabbed pane
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

    public SlideBuilderManager getSlideBuilderManager() {
        return slideBuilderManager;
    }

    private void initNativeSwing() {
//        UIUtils.setPreferredLookAndFeel();
//        NativeInterface.open();
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
