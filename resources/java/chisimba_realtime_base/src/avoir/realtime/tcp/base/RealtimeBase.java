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
package avoir.realtime.tcp.base;

import avoir.realtime.tcp.base.appsharing.Java2ScreenScraper;
import avoir.realtime.tcp.common.ImageUtil;
import avoir.realtime.tcp.base.chat.ChatRoom;
import avoir.realtime.tcp.base.audio.AudioWizardFrame;
import avoir.realtime.tcp.base.filetransfer.FileTransferPanel;
import avoir.realtime.tcp.base.filetransfer.FileUploader;
import avoir.realtime.tcp.base.managers.AgendaManager;
import avoir.realtime.tcp.base.managers.FileManager;
import avoir.realtime.tcp.base.managers.MenuManager;
import avoir.realtime.tcp.base.managers.ToolbarManager;
import avoir.realtime.tcp.base.managers.UserListManager;
import avoir.realtime.tcp.base.managers.SessionManager;
import avoir.realtime.tcp.base.managers.WhiteboardToolbarManager;
import avoir.realtime.tcp.base.survey.SurveyFrame;
import avoir.realtime.tcp.base.survey.SurveyManagerFrame;

import avoir.realtime.tcp.base.user.User;
import avoir.realtime.tcp.base.user.UserLevel;
import avoir.realtime.tcp.common.Constants;
import avoir.realtime.tcp.common.Flash;
import avoir.realtime.tcp.common.MessageCode;
import avoir.realtime.tcp.common.PresenceConstants;
import avoir.realtime.tcp.common.WebPage;
import avoir.realtime.tcp.common.packet.ChatLogPacket;
import avoir.realtime.tcp.common.packet.ChatPacket;
import avoir.realtime.tcp.common.packet.PresencePacket;
import avoir.realtime.tcp.common.packet.SwitchTabPacket;
import avoir.realtime.tcp.whiteboard.WhiteboardSurface;
import chrriis.common.UIUtils;
import chrriis.dj.nativeswing.swtimpl.NativeInterface;
import java.applet.AppletContext;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.Graphics;
import java.awt.Rectangle;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.util.Vector;
import javax.swing.BorderFactory;
import javax.swing.ImageIcon;
import javax.swing.JApplet;
import javax.swing.JCheckBox;
import javax.swing.JDesktopPane;
import javax.swing.JFrame;
import javax.swing.JInternalFrame;
import javax.swing.JLabel;
import javax.swing.JMenuBar;
import javax.swing.JPanel;
import javax.swing.JProgressBar;
import javax.swing.JScrollPane;
import javax.swing.JSlider;
import javax.swing.JSplitPane;
import javax.swing.JTabbedPane;
import javax.swing.JTable;
import javax.swing.JToggleButton;
import javax.swing.JToolBar;
import javax.swing.JViewport;
import javax.swing.event.ChangeEvent;
import javax.swing.event.ChangeListener;

/**
 *
 * @author  david wafula
 */
public class RealtimeBase extends javax.swing.JPanel implements ActionListener {

    private RealtimeOptions options;
    private TCPClient tcpClient;
    private String userName = "Anonymous";
    private String fullName = "DAO";
    private String sessionId = "whiteboard";
    private String slidesDir;
    private String slideServerId;
    private String resourcesPath;
    private String siteRoot;
    private String host;
    private boolean isPresenter = true;
    private String sessionTitle;
    private String appletCodeBase;
    private User user;
    private boolean localhost;
    private javax.swing.JSplitPane splitPane = new JSplitPane();
    private javax.swing.JSplitPane leftSplitPane = new JSplitPane(JSplitPane.VERTICAL_SPLIT);
    // private javax.swing.JSplitPane leftBottomSplitPane = new JSplitPane(JSplitPane.VERTICAL_SPLIT);
    private JPanel leftBottomPanel = new JPanel(new BorderLayout());
    private JTabbedPane userListTabbedPane = new JTabbedPane();
    private JTabbedPane dockTabbedPane = new JTabbedPane();//true);
    private JLabel logo = new JLabel();
    private String userLevel;
    private int port;
    private boolean isSlidesHost;
    private UserListManager userManager;
    private ToolbarManager toolbarManager;
    private Surface surface;
    private SessionManager sessionManager;
    private AgendaManager agendaManager;
    private MenuManager menuMananger;
    private WhiteboardToolbarManager whiteboardToolbar;
    private SurveyFrame surveyFrame;
    private SurveyManagerFrame surveyManagerFrame;
    private AudioWizardFrame audioWizardFrame;
    private FileTransferPanel fileTransferPanel;
    private FileManager fileManager;
    private FileUploader fileUploader;
    private boolean hasControl = false;
    private ChatRoom chatRoom;
    private String chatLogFile = "ChatLog.txt";
    private JFrame chatFrame = new JFrame();
    private boolean connected = false;
    private boolean speakerEnabled,  micEnabled;
    private JPanel mPanel = new JPanel();
    private JPanel leftPanel = new JPanel();
    private JPanel userListPanel = new JPanel();
    private JPanel agendaPanel = new JPanel();
    private JToolBar userItemsPanel = new JToolBar();
    private TButton stepOutButton = new TButton(new ImageIcon(Constants.getRealtimeHome() + "/icons/stepout.jpeg"));//new TButton(ImageUtil.createImageIcon(this, "/icons/stepout.jpeg"));
    private TButton handButton = new TButton(ImageUtil.createImageIcon(this, "/icons/hand.png"));
    private TButton laughterButton = new TButton(ImageUtil.createImageIcon(this, "/icons/laugh.jpeg"));
    private TButton applaudButton = new TButton(ImageUtil.createImageIcon(this, "/icons/applaud.jpeg"));
    private TButton whiteboardButton = new TButton(ImageUtil.createImageIcon(this, "/icons/wb_icon.png"));
    private ImageIcon micOffIcon = ImageUtil.createImageIcon(this, "/icons/mic_off.png");
    private ImageIcon micOnIcon = ImageUtil.createImageIcon(this, "/icons/mic_on.png");
    private ImageIcon muteOffIcon = ImageUtil.createImageIcon(this, "/icons/mute_off.png");
    private ImageIcon muteOnIcon = ImageUtil.createImageIcon(this, "/icons/mute_on.png");
    private TButton spkrButton = new TButton(muteOffIcon);
    private TButton micButton = new TButton(micOnIcon);
    private JFrame fileTransferFrame;
    private WhiteboardSurface whiteboardSurface;
    private JApplet glassPaneHandler;
    private JPanel surfacePanel = new JPanel(new BorderLayout());
    private JPanel centerPanel = new JPanel();
    private String selectedFile = "Presentation";
    private AppletContext appletContext;
    private JFrame parent;
    private BaseManager baseManager;
    private String userDetails;
    private String userImagePath;
    private boolean webPresent = true;
    private static JDesktopPane desktop = new JDesktopPane();
    private JInternalFrame surfaceFrame = new JInternalFrame();
    private JScrollPane surfaceScrollPane = new JScrollPane();
    private TabbedPanePlaf docTabbedPaneUI = new TabbedPanePlaf();
    private TabbedPanePlaf surfaceTabbedPaneUI = new TabbedPanePlaf();
    private static Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private CloseableTabbedPane mainTabbedPane = new CloseableTabbedPane();
    private Vector<Flash> flashFiles = new Vector<Flash>();
    private Vector<WebPage> webPages = new Vector<WebPage>();
    private JToolBar soundPanel = new JToolBar();
    private Java2ScreenScraper screenScraper;

    /**
     * Create additional components
     */
    private void initCustomComponents() {

        dockTabbedPane.setUI(docTabbedPaneUI);
        docTabbedPaneUI.setBase(this, "/icons/popout.png", "Chat", "Popout");
        dockTabbedPane.setFont(new Font("Dialog", 0, 11));

        //mainTabbedPane.setUI(surfaceTabbedPaneUI);
        surfaceTabbedPaneUI.setBase(this, "/icons/delete_edit.gif", "Surface", "Close");
        mainTabbedPane.setFont(new Font("Dialog", 0, 11));
        baseManager = new BaseManager(this);
        whiteboardSurface = new WhiteboardSurface(this);
        userManager = new UserListManager(this);
        toolbarManager = new ToolbarManager(this);
        surface = new Surface(this);

        sessionManager = new SessionManager(this);
        agendaManager = new AgendaManager(this);


        menuMananger = new MenuManager(this);
        whiteboardToolbar = new WhiteboardToolbarManager(this);

        fileUploader = new FileUploader(this);
        fileManager = new FileManager(this);
        micVolumeMeter.setMinimum(0);
        micVolumeMeter.setMaximum(128);

//        speakerVolumeMeter.setMinimum(0);
        //      speakerVolumeMeter.setMaximum(128);

        talkButton.setIcon(micOffIcon);
        talkButton.setBorderPainted(false);
        talkButton.setFont(new java.awt.Font("Dialog", 0, 8));

        mPanel.setLayout(new BorderLayout());

        leftPanel.setLayout(new BorderLayout());
        JTable table = userManager.getUserList();
        JScrollPane sp = new JScrollPane(table);
        sp.getViewport().setBackground(Color.WHITE);
        table.setShowGrid(false);
        table.setBackground(Color.WHITE);
        table.setOpaque(true);


        userListPanel.setLayout(new BorderLayout());
        userListPanel.setPreferredSize(new Dimension(250, ss.height / 2));
        userListPanel.add(sp, BorderLayout.CENTER);
        userListPanel.add(userItemsPanel, BorderLayout.SOUTH);
        userListPanel.setBackground(Color.WHITE);

        //   stepOutButton.setText("Step Out");
        stepOutButton.setActionCommand("stepout");
        stepOutButton.addActionListener(this);

        userItemsPanel.setBorder(BorderFactory.createTitledBorder("Interact"));
        userItemsPanel.setFont(new Font("Dialog", 0, 11));

        // laughterButton.setText("Laugh");
        laughterButton.addActionListener(this);
        laughterButton.setActionCommand("laughter");

        //applaudButton.setText("Applaud");
        applaudButton.addActionListener(this);
        applaudButton.setActionCommand("applaud");

        //handButton.setText("Hand");
        handButton.addActionListener(this);
        handButton.setActionCommand("hand");

        userItemsPanel.add(stepOutButton);
        userItemsPanel.add(handButton);
        userItemsPanel.add(laughterButton);
        userItemsPanel.add(applaudButton);
        userItemsPanel.add(toolbarManager.getYesButton());
        userItemsPanel.add(toolbarManager.getNoButton());


        whiteboardButton.addActionListener(this);
        whiteboardButton.setActionCommand("whiteBoard");
        whiteboardButton.setToolTipText("Use whiteboard on this slide");


        userListTabbedPane.addTab("Participants", userListPanel);

        leftPanel.setPreferredSize(new Dimension(250, 250));

        // audioPanel.setPreferredSize(new Dimension(100, 100));
        volumeSlide.setPreferredSize(new Dimension(150, 20));
//        speakerVolumeMeter.setPreferredSize(new Dimension(150, 7));

        agendaPanel.setLayout(new BorderLayout());
        agendaPanel.add(agendaManager.getAgendaTree(), BorderLayout.CENTER);
        agendaPanel.setPreferredSize(new Dimension(250, 150));

        leftBottomPanel.add(dockTabbedPane, BorderLayout.CENTER);
        soundPanel.setBorder(BorderFactory.createTitledBorder("Audio"));
        soundPanel.setFont(new Font("dialog", 0, 11));
        soundPanel.add(micButton);
        soundPanel.add(spkrButton);
        soundPanel.add(volumeSlide);
        leftBottomPanel.add(soundPanel, BorderLayout.SOUTH);
        userListTabbedPane.setFont(new Font("Dialog", 0, 11));

        leftSplitPane.setDividerLocation(ss.height / 2);
        leftSplitPane.setTopComponent(userListTabbedPane);
        leftSplitPane.setBottomComponent(leftBottomPanel);
        sessionManager.setIsPresenter(isPresenter);

        splitPane.setLeftComponent(leftSplitPane);
        splitPane.setRightComponent(mainTabbedPane);
        splitPane.setDividerLocation(300);
        //   audioPanel.setMinimumSize(new Dimension(300, 120));
        JToolBar toolbar = new JToolBar();
        toolbar.add(toolbarManager.getGeneralToolbar());
        //toolbar.add(whiteboardSurface.getMainToolbar());
        centerPanel.setLayout(new BorderLayout());
        surfaceScrollPane.setViewportView(whiteboardSurface);
        whiteboardSurface.setSize(new Dimension(1200, 1200));
        whiteboardSurface.setPreferredSize(new Dimension(500, 400));

        JViewport vport = surfaceScrollPane.getViewport();

        Dimension size = vport.getViewSize();
        int xx = (whiteboardSurface.getWidth() - size.width) / 2;
        int yy = (whiteboardSurface.getHeight() - size.height) / 2;

        Rectangle rect = new Rectangle(xx, yy, size.width, size.height);
        whiteboardSurface.scrollRectToVisible(rect);


        desktop.setLayout(new BorderLayout());
        mainTabbedPane.add(centerPanel, "Default");
        centerPanel.add(surfaceScrollPane, BorderLayout.CENTER);

        JToolBar whiteboardDefaultToolbar = new JToolBar();
        whiteboardDefaultToolbar.add(toolbarManager.getFirstSlideButton());
        whiteboardDefaultToolbar.add(toolbarManager.getBackSlideButton());
        whiteboardDefaultToolbar.add(toolbarManager.getNextSlideButton());
        whiteboardDefaultToolbar.add(toolbarManager.getLastSlideButton());

        whiteboardSurface.createToolBar();
        whiteboardDefaultToolbar.add(whiteboardSurface.getBoldButton());
        whiteboardDefaultToolbar.add(whiteboardSurface.getItalicButton());
        whiteboardDefaultToolbar.add(whiteboardSurface.getUnderButton());
        whiteboardDefaultToolbar.add(whiteboardSurface.getFontNamesField());


        JPanel p = new JPanel();
        p.setLayout(new BorderLayout());
        p.add(whiteboardSurface.getFontSizeField(), BorderLayout.WEST);
        whiteboardDefaultToolbar.add(p);
        centerPanel.add(whiteboardDefaultToolbar, BorderLayout.NORTH);


        centerPanel.add(whiteboardSurface.getToolsToolbar(), BorderLayout.EAST);


        mainPanel.add(splitPane, BorderLayout.CENTER);
        mainTabbedPane.addChangeListener(new ChangeListener() {

            public void stateChanged(ChangeEvent arg0) {
                tcpClient.sendPacket(new SwitchTabPacket(sessionId, mainTabbedPane.getSelectedIndex()));
            }
        });

    }

    public Java2ScreenScraper getScreenScraper() {
        return screenScraper;
    }

    public void setScreenScraper(Java2ScreenScraper screenScraper) {
        this.screenScraper = screenScraper;
    }

    public Vector<WebPage> getWebPages() {
        return webPages;
    }

    public Vector<Flash> getFlashFiles() {
        return flashFiles;
    }

    public JTabbedPane getMainTabbedPane() {
        return mainTabbedPane;
    }

    public static void addFrame(JInternalFrame fr, int h) {
        try {
            // fr.setLocation((ss.width - fr.getWidth()) / 2, h);
            desktop.add(fr);
            fr.setSelected(true);
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    public JScrollPane getSurfaceScrollPane() {
        return surfaceScrollPane;
    }

    public JInternalFrame getSurfaceFrame() {
        return surfaceFrame;
    }

    public BaseManager getBaseManager() {
        return baseManager;
    }

    public String getSelectedFile() {
        return selectedFile;
    }

    public void setSelectedFile(String selectedFile) {
        this.selectedFile = selectedFile;
    }

    public AppletContext getAppletContext() {
        return appletContext;
    }

    public boolean isWebPresent() {
        return webPresent;
    }

    public void setWebPresent(boolean webPresent) {
        this.webPresent = webPresent;
    }

    /**
     * own simple panel to display color
     */
    class GP extends JPanel {

        public GP() {
            setSize(100, 100);
            setBackground(Color.RED);
        }

        public void paintComponent(Graphics g) {
            super.paintComponent(g);
        }
    }

    public FileUploader getFileUploader() {
        return fileUploader;
    }

    /**
     * get the whiteboard surface
     * @return
     */
    public WhiteboardSurface getWhiteboardSurface() {
        return whiteboardSurface;
    }

    public String getUserDetails() {
        return userDetails;
    }

    public void setUserDetails(String userDetails) {
        this.userDetails = userDetails;
    }

    public String getUserImagePath() {
        return userImagePath;
    }

    public void setUserImagePath(String userImagePath) {
        this.userImagePath = userImagePath;
    }

    /**
     * React to action events
     * @param e
     */
    public void actionPerformed(ActionEvent e) {
        if (e.getActionCommand().equals("stepout")) {
            if (stepOutButton.isSelected()) {
                // stepOutButton.setText("Resume");
                tcpClient.sendPacket(new PresencePacket(sessionId,
                        PresenceConstants.STEP_OUT_ICON, true,
                        userName));
                laughterButton.setSelected(false);
                applaudButton.setSelected(false);
                handButton.setSelected(false);
            } else {
                //   stepOutButton.setText("Step Out");
                tcpClient.sendPacket(new PresencePacket(sessionId,
                        PresenceConstants.STEP_OUT_ICON, false,
                        userName));
            }
        }

        if (e.getActionCommand().equals("laughter")) {
            tcpClient.sendPacket(new PresencePacket(sessionId,
                    PresenceConstants.LAUGHTER_ICON, laughterButton.isSelected(),
                    userName));
            if (laughterButton.isSelected()) {
                stepOutButton.setSelected(false);
                applaudButton.setSelected(false);
                handButton.setSelected(false);
            }
        }
        if (e.getActionCommand().equals("applaud")) {
            tcpClient.sendPacket(new PresencePacket(sessionId,
                    PresenceConstants.APPLAUD_ICON, applaudButton.isSelected(),
                    userName));
            if (applaudButton.isSelected()) {
                stepOutButton.setSelected(false);
                laughterButton.setSelected(false);
                handButton.setSelected(false);
            }
        }
        if (e.getActionCommand().equals("hand")) {
            tcpClient.sendPacket(new PresencePacket(sessionId,
                    PresenceConstants.HAND_ICON, handButton.isSelected(),
                    userName));
            if (handButton.isSelected()) {
                stepOutButton.setSelected(false);
                laughterButton.setSelected(false);
                applaudButton.setSelected(false);
            }
        }

    }

    public TButton getMicButton() {
        return micButton;
    }

    public TButton getSpkrButton() {
        return spkrButton;
    }

    /**
     * get a glass handler. will be used in future to handle whiteboard overlays
     * for now due to perfomance problems, nothing much can be achieved from this
     * @return
     */
    public JApplet getGlassPaneHandler() {
        return glassPaneHandler;
    }

    public void setGlassPaneHandler(JApplet glassPaneHandler) {
        this.glassPaneHandler = glassPaneHandler;

    }

    /**
     * initialize realtime options object
     */
    public void initRealtimeHome() {
        options = new RealtimeOptions();

    }

    /**
     * get menu bar manager
     * @return
     */
    public JMenuBar getMenuMananger() {
        return menuMananger.getMenuBar();
    }

    /**
     * get options object
     * @return
     */
    public RealtimeOptions getRealtimeOptions() {
        return options;
    }

    /**
     * create chat room
     */
    public void initChatRoom() {
        chatRoom = new ChatRoom(this, user, chatLogFile, sessionId);
        chatRoom.setPreferredSize(new Dimension(400, 260));
    }

    /**
     * get survey manager
     * @return
     */
    public SurveyManagerFrame getSurveyManagerFrame() {
        return surveyManagerFrame;
    }

    /**
     * display survey manager: for creating survey
     */
    public void showSurveyManagerFrame() {
        if (surveyManagerFrame == null) {
            surveyManagerFrame = new SurveyManagerFrame(this);
        }
        surveyManagerFrame.setTitle("Survey Wizard (Beta) - Untitled");
        surveyManagerFrame.setSize(560, 500);
        surveyManagerFrame.setLocationRelativeTo(null);
        surveyManagerFrame.setVisible(true);

    }

    /**
     * Displays the options frame
     */
    public void showOptionsFrame() {
        RealtimeOptionsFrame optionsFrame = new RealtimeOptionsFrame(this, 0);
        optionsFrame.setSize(500, 500);
        optionsFrame.setLocationRelativeTo(null);
        optionsFrame.setVisible(true);

    }

    /**
     * display the survey frame.....for answering a survey
     * @param questions
     * @param title
     */
    public void showSurveyFrame(Vector<String> questions, String title) {
        if (surveyFrame == null) {
            surveyFrame = new SurveyFrame(this);
        }
        surveyFrame.setQuestions(questions);
        surveyFrame.setTitle(title);
        surveyFrame.setSize(500, 400);
        surveyFrame.setLocationRelativeTo(null);
        // surveyFrame.setAlwaysOnTop(true);
        surveyFrame.setVisible(true);
    }

    /**
     * Get hold of the file manager
     * @return
     */
    public FileManager getFileManager() {
        return fileManager;
    }

    /**
     * get the user manager
     * @return
     */
    public UserListManager getUserManager() {
        return userManager;
    }

    /**
     * get the session manager
     * @return
     */
    public SessionManager getSessionManager() {
        return sessionManager;
    }

    /**
     * get the toolbar manager
     * @return
     */
    public ToolbarManager getToolbarManager() {
        return toolbarManager;
    }

    /**
     * Get the agenda manager
     * @return
     */
    public AgendaManager getAgendaManager() {
        return agendaManager;
    }

    /**
     * Remove this user from the server, then release audio resources
     * @param userid
     * @param sessionid
     */
    public void removeUser(String userid, String sessionid) {
        tcpClient.removeUser(user);
        if (audioWizardFrame != null) {
            audioWizardFrame.stopAudio();
        }
    }

    /**
     * Can we use the MIc?
     * @return
     */
    public boolean isMicEnabled() {
        return micEnabled;
    }

    /**
     * If we can use mic, display an icon saying so
     * @param micEnabled
     */
    public void setMicEnabled(boolean micEnabled) {
        this.micEnabled = micEnabled;
        talkButton.setEnabled(micEnabled);
        micButton.setIcon(micEnabled ? micOnIcon : micOffIcon);
        micButton.setEnabled(micEnabled);
        tcpClient.sendPacket(new PresencePacket(sessionId,
                PresenceConstants.MIC_ICON, micEnabled,
                userName));
    }

    /**
     * can we receive sound?
     * @return
     */
    public boolean isSpeakerEnabled() {
        return speakerEnabled;

    }

    /**
     * If we can recieve sound, the display the sound-on speaker
     * @param speakerEnabled
     */
    public void setSpeakerEnabled(boolean speakerEnabled) {
        this.speakerEnabled = speakerEnabled;
        muteOpt.setEnabled(speakerEnabled);
        volumeSlide.setEnabled(speakerEnabled);
        spkrButton.setIcon(speakerEnabled ? muteOffIcon : muteOnIcon);
        tcpClient.sendPacket(new PresencePacket(sessionId,
                PresenceConstants.SPEAKER_ICON, speakerEnabled,
                userName));
    }

    /**
     * can this user control a presentation ?
     * @return
     */
    public boolean getControl() {
        return hasControl || isPresenter;
    }

    /**
     * is this a presenter?
     * @return
     */
    public boolean isPresenter() {
        return user.isPresenter();
    }

    /**
     * get slide server ID
     * @return
     */
    public String getSlideServerId() {
        return slideServerId;
    }

    /**
     * get the session ID
     * @return
     */
    public String getSessionId() {
        return sessionId;
    }

    /**
     * get the tcp connector object
     * @return
     */
    public TCPClient getTcpClient() {
        return tcpClient;
    }

    /**
     * get this user
     * @return
     */
    public User getUser() {
        return user;
    }

    /**
     * set whether this user has control
     * @param control
     */
    public void setControl(boolean control) {
        this.hasControl = control;
        whiteboardSurface.setDrawingEnabled(control);
    }

    /**
     * get the session title
     * @return
     */
    public String getSessionTitle() {
        return sessionTitle;
    }

    /**
     * set this session's title
     * @param sessionTitle
     */
    public void setSessionTitle(String sessionTitle) {
        this.sessionTitle = sessionTitle;
    }

    /**
     * sets the applet code base as an absolute path
     * @return
     */
    public String getAppletCodeBase() {
        return appletCodeBase;
    }

    /**
     * get the applet code base as an absolute path
     * @param appletCodeBase
     */
    public void setAppletCodeBase(String appletCodeBase) {
        this.appletCodeBase = appletCodeBase;
    }

    public JLabel getTimerField() {
        return timerField;
    }

    /**
     * This creates the actual realtime presentation screen. This is invoked 
     * by the launcher. We could invoke it directly, but its too heavy for an applet
     * so the trick is lauch a simple light frame for an applet, then let that
     * invoke this
     * @param userLevel
     * @param fullname
     * @param userName
     * @param host
     * @param port
     * @param isPresenter
     * @param sessionId
     * @param slidesDir
     * @param isSlidesHost
     * @param siteRoot
     * @param slideServerId
     * @param resourcesPath
     * @param localhost
     * @return
     */
    public JPanel init(
            String userLevel,
            String fullname,
            String userName,
            String host,
            int port,
            boolean isPresenter,
            String sessionId,
            String slidesDir,
            boolean isSlidesHost,
            String siteRoot,
            String slideServerId,
            String resourcesPath,
            boolean localhost,
            AppletContext appletContext) {
        this.userLevel = userLevel;
        this.fullName = fullname;
        this.userName = userName;
        this.host = host;
        this.port = port;
        this.isPresenter = isPresenter;
        this.sessionId = sessionId;
        this.selectedFile = sessionId;
        this.slidesDir = slidesDir;
        this.isSlidesHost = isSlidesHost;
        this.siteRoot = siteRoot;
        this.slideServerId = slideServerId;
        this.resourcesPath = resourcesPath;
        this.localhost = localhost;
        this.appletContext = appletContext;
        initRealtimeHome();
        initUser();
        initComponents();
        initCustomComponents();

        Thread t = new Thread() {

            @Override
            public void run() {
                initTCPCommunication();
            }
        };
        t.start();

        return this;
    }

    public JPanel initAsClassroom(
            String host,
            int port,
            String username,
            String fullnames,
            boolean isPresenter,
            String sessionId,
            String userLevel,
            String slidesDir,
            String siteRoot,
            String slidesServerId,
            String resourcesPath,
            JFrame parent) {
        this.host = host;
        this.port = port;
        this.userName = username;
        this.fullName = fullnames;
        this.isPresenter = isPresenter;
        this.sessionId = sessionId;
        this.parent = parent;
        this.userLevel = userLevel;
        this.slidesDir = slidesDir;
        this.siteRoot = siteRoot;
        this.slideServerId = slidesServerId;
        this.resourcesPath = resourcesPath;
        initRealtimeHome();
        initUser();
        initComponents();
        initCustomComponents();

        Thread t = new Thread() {

            @Override
            public void run() {
                UIUtils.setPreferredLookAndFeel();
                NativeInterface.open();
                initTCPCommunication();
                baseManager.loadCachedSlides();
            }
        };
        t.start();

        return this;
    }

    public JFrame getParentFrame() {
        return parent;
    }

    public String getUserName() {
        return userName;
    }

    public void setUserName(String userName) {
        this.userName = userName;
    }

    /**
     * for configurating the supernode port
     * @param port
     */
    public void setSupernodePort(int port) {
        tcpClient.setSuperNodePort(port);
    }

    /**
     * for configuring the super node host
     * @param host
     */
    public void setSupernodeHost(String host) {
        tcpClient.setSuperNodeHost(host);
    }

    public JTabbedPane getDockTabbedPane() {
        return dockTabbedPane;
    }

    /**
     * Initialize TCP connection. Actually, this is where you create an object
     * to connect to the server
     */
    public void initTCPCommunication() {

        tcpClient = new TCPClient(this);
        tcpClient.setSuperNodeHost(host);
        tcpClient.setSuperNodePort(port);
        tcpClient.setFileTransfer(fileTransferPanel);

        mainTabbedPane.addChangeListener(new ChangeListener() {

            public void stateChanged(ChangeEvent arg0) {
                baseManager.getTabTimer().cancel();
                mainTabbedPane.setBackgroundAt(mainTabbedPane.getSelectedIndex(), dockTabbedPane.getBackgroundAt(1));
            }
        });

        whiteboardSurface.setTCPClient(tcpClient);
        baseManager.setApplicationClosedOperation(parent, user);

        initChatRoom();
        dockTabbedPane.addTab("Chat", chatRoom);
        dockTabbedPane.addTab("Agenda", agendaPanel);
        dockTabbedPane.setSelectedIndex(0);
        sessionManager.connect();
    }

    /**
     * After connecting to server, and signing in..enable necesary buttons
     * and display correct messages
     */
    public void doPostConnectControlsCheck() {
        surface.setStatusMessage("Initializing session, please wait ...");
        surface.setShowSplashScreen(false);
        getToolbarManager().getVoiceOptionsButton().setEnabled(true);
        getToolbarManager().setButtonsEnabled(isPresenter);
        getSurface().setConnecting(false);
        setText("Connected", true);
        setConnected(true);
    }

    /**
     * create this user based on parameters from the applet
     */
    private void initUser() {

        //if not logged in, then assign random number
        if (userName == null || userName.startsWith("Language")) { //not logging on through KEWL for dev, so give random user name

            userName = "Guest" + new java.util.Random().nextInt(200);
            fullName = userName;
        }
        int xuserLevel = UserLevel.GUEST; //assume guest unless set differently in applet param

        xuserLevel = UserLevel.getUserLevel(userLevel.toUpperCase());

        user = new User(xuserLevel, fullName, userName, host, 22225, isPresenter,
                sessionId, sessionTitle, slidesDir, false, siteRoot, slideServerId);

    }

    /**
     * Create the audio wizard frame
     */
    public void initAudio() {
        audioWizardFrame = new AudioWizardFrame(RealtimeBase.this, userName, sessionId, slideServerId, resourcesPath);
        audioWizardFrame.setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
        audioWizardFrame.setSize(500, 500);
        audioWizardFrame.setAlwaysOnTop(true);
        audioWizardFrame.setLocationRelativeTo(null);

    }

    /**
     * get the mute obj
     * @return
     */
    public JCheckBox getMuteOpt() {
        return muteOpt;
    }

    /**
     * get the talk button
     * @return
     */
    public JToggleButton getTalkButton() {
        return talkButton;
    }

    /**
     * get the volume slide obj
     * @return
     */
    public JSlider getVolumeSlide() {
        return volumeSlide;
    }

    /**
     * get the slides directory path
     * @return
     */
    public String getSlidesDir() {
        return slidesDir;
    }

    /**
     * get status bar object
     * @return
     */
    public JLabel getStatusBar() {
        return statusBar;
    }

    /**
     * get surface object
     * @return
     */
    public Surface getSurface() {
        return surface;
    }

    /**
     * Test wether we are running localhost
     * @return
     */
    public boolean isLocalhost() {
        return localhost;
    }

    /**
     * Get site root of the presentation
     * @return
     */
    public String getSiteRoot() {
        return siteRoot;
    }

    public String getResourcesPath() {
        return resourcesPath;
    }

    public void setResourcesPath(String resourcesPath) {
        this.resourcesPath = resourcesPath;
    }

    public String getUserLevel() {
        return userLevel;
    }

    public void setUserLevel(String userLevel) {
        this.userLevel = userLevel;
    }

    /**
     * Displays the audio wizard frame
     */
    public void showAudioWizardFrame() {
        if (audioWizardFrame == null) {
            initAudio();
        }
        audioWizardFrame.setVisible(true);
    }

    public JFrame getChatFrame() {
        return chatFrame;
    }

    /**
     * Diplays the chat room (frame)
     */
    public void showChatRoom() {
        final JPanel panel = (JPanel) dockTabbedPane.getComponentAt(0);
        final JPanel agendaPanel = (JPanel) dockTabbedPane.getComponentAt(1);
        JFrame fr = new JFrame("Chat - Close to dock");
        fr.addWindowListener(new WindowAdapter() {

            public void windowClosing(WindowEvent e) {
                dockTabbedPane.removeAll();
                dockTabbedPane.add(panel, "Chat");
                dockTabbedPane.add(agendaPanel, "Agenda");
            }
        });
        fr.setLayout(new BorderLayout());
        fr.setAlwaysOnTop(true);
        fr.add(panel, BorderLayout.CENTER);
        fr.setSize(400, 300);
        fr.setLocationRelativeTo(null);
        fr.setVisible(true);
        dockTabbedPane.remove(0);
        dockTabbedPane.add(agendaPanel, "Agenda");
    }

    /**
     * Displays the file transfer frame
     */
    public void showFileTransferFrame() {
        if (fileTransferFrame == null) {
            fileTransferFrame = new JFrame("Realtime File Transfer ");
            fileTransferPanel = new FileTransferPanel(this);
            fileTransferFrame.getContentPane().add(fileTransferPanel);
            fileTransferFrame.setSize(600, 400);
            fileTransferFrame.setAlwaysOnTop(true);
            fileTransferFrame.setLocationRelativeTo(null);
        }
        fileTransferFrame.setVisible(true);
    }

    public FileTransferPanel getFileTransferPanel() {
        return fileTransferPanel;
    }

    /**
     * Display message both in display window and status bar.If error message, then
     * display a warning icon or in red color
     * @param txt
     * @param error
     */
    public void setText(String txt, boolean error) {
        if (error) {
            statusBar.setForeground(Color.RED);
        } else {
            statusBar.setForeground(Color.BLACK);
        }
        statusBar.setText(txt);
        surface.setConnectingString(txt);

    }

    /**
     * tests if connected to server
     * @return
     */
    public boolean isConnected() {
        return connected;
    }

    /**
     * Paint the status message on the slide...no longer used???
     * @param msg
     */
    public void setStatusMessage(String msg) {
        surface.setStatusMessage(msg);
    }

    /**
     * set the sessions chat log. normally for a newly logged in user
     * @param chatLogPacket
     */
    public void updateChat(ChatLogPacket chatLogPacket) {
        chatRoom.update(chatLogPacket);
    }

    /**
     * Add new chat line
     * @param chatPacket
     */
    public void updateChat(ChatPacket chatPacket) {
        chatRoom.update(chatPacket);
    }

    public AudioWizardFrame getAudioWizardFrame() {
        return audioWizardFrame;
    }

    public ChatRoom getChatRoom() {
        return chatRoom;
    }

    /**
     * Sets the connection status
     * @param connected
     */
    public void setConnected(boolean connected) {
        this.connected = connected;
    }

    /**
     * Send user packet for for sign in
     */
    public void publish() {
        tcpClient.publish(user);
    }

    /**
     * Pause for a specified time
     * @param time
     */
    private void sleep(long time) {
        try {
            Thread.sleep(time);
        } catch (Exception ex) {
        }
    }

    /**
     * If diconnected from server, try to auto re-connect
     */
    public void refreshConnection() {
        connected = false;
        int count = 0;
        int max = 10;
        while (!connected) {
            showMessage("Disconnected from server. Retrying " + count + " of " + max + "...", false, true, MessageCode.ALL);
            connected = tcpClient.connect();

            if (connected) {
                publish();
                sessionManager.startSession();
                break;
            }
            if (count > max) {
                break;
            }
            sleep(5000);
            count++;


        }
        if (!connected) {
            showMessage("Connection to server failed. Contact your system administrator.", false, true, MessageCode.ALL);

        }
    }

    /**
     * Displays message in the rectangle above the slide. 
     * @param msg The message to be displayed
     * @param temp if the message is to be temporarily displayed or not. Default
     * temp time is 5000 ms
     * @param isErrorMessage if is error message or not. error messages at preappended
     * with a warning icon
     */
    public void showMessage(String msg, boolean temp, boolean isErrorMessage, int messageType) {
        String prevMsg = surface.getMessage();
        if (messageType == MessageCode.ALL) {
            surface.showMessage(msg, isErrorMessage);
            whiteboardSurface.showMessage(msg, isErrorMessage);
        }
        if (messageType == MessageCode.CLASSROOM_SPECIFIC) {
            whiteboardSurface.showMessage(msg, isErrorMessage);
            return;

        }

        if (messageType == MessageCode.WEBPRESENT_SPECIFIC) {
            surface.showMessage(msg, isErrorMessage);

            return;

        }
        if (temp) {
            Thread t = new Thread() {

                public void run() {
                    try {
                        sleep(10000);
                    } catch (Exception ex) {
                    }
                    surface.showMessage("", false);
                    whiteboardSurface.showMessage("", false);

                }
            };
            t.start();
        }
    }

    public JProgressBar getMicVolumeMeter() {
        return micVolumeMeter;
    }

    /** This method is called from within the init() method to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {
        java.awt.GridBagConstraints gridBagConstraints;

        audioPanel = new javax.swing.JPanel();
        cPanel = new javax.swing.JPanel();
        talkButton = new javax.swing.JToggleButton();
        micVolumeMeter = new javax.swing.JProgressBar();
        volumeSlide = new javax.swing.JSlider();
        muteOpt = new javax.swing.JCheckBox();
        mainPanel = new javax.swing.JPanel();
        footerPanel = new javax.swing.JPanel();
        statusBar = new javax.swing.JLabel();
        jSeparator1 = new javax.swing.JSeparator();
        timerField = new javax.swing.JLabel();

        audioPanel.setBorder(javax.swing.BorderFactory.createTitledBorder(""));
        audioPanel.setMinimumSize(new java.awt.Dimension(50, 11));
        audioPanel.setPreferredSize(new java.awt.Dimension(210, 100));
        audioPanel.setLayout(new java.awt.BorderLayout());

        cPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Audio"));
        cPanel.setLayout(new java.awt.GridBagLayout());

        talkButton.setText("Talk");
        talkButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                talkButtonActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        cPanel.add(talkButton, gridBagConstraints);
        cPanel.add(micVolumeMeter, new java.awt.GridBagConstraints());

        volumeSlide.setEnabled(false);
        volumeSlide.setMinimumSize(new java.awt.Dimension(50, 40));
        volumeSlide.setPreferredSize(new java.awt.Dimension(60, 40));
        volumeSlide.addChangeListener(new javax.swing.event.ChangeListener() {
            public void stateChanged(javax.swing.event.ChangeEvent evt) {
                volumeSlideStateChanged(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        cPanel.add(volumeSlide, gridBagConstraints);

        muteOpt.setText("Mute");
        muteOpt.setEnabled(false);
        muteOpt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                muteOptActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 2;
        gridBagConstraints.gridy = 1;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        cPanel.add(muteOpt, gridBagConstraints);

        audioPanel.add(cPanel, java.awt.BorderLayout.PAGE_END);

        setPreferredSize(new java.awt.Dimension(40, 19));
        setLayout(new java.awt.BorderLayout());

        mainPanel.setLayout(new java.awt.BorderLayout());

        footerPanel.setLayout(new java.awt.BorderLayout());

        statusBar.setText("Ready");
        statusBar.setBorder(javax.swing.BorderFactory.createBevelBorder(javax.swing.border.BevelBorder.LOWERED));
        statusBar.setPreferredSize(new java.awt.Dimension(641, 19));
        footerPanel.add(statusBar, java.awt.BorderLayout.LINE_START);

        jSeparator1.setOrientation(javax.swing.SwingConstants.VERTICAL);
        footerPanel.add(jSeparator1, java.awt.BorderLayout.CENTER);

        timerField.setBorder(javax.swing.BorderFactory.createBevelBorder(javax.swing.border.BevelBorder.LOWERED));
        timerField.setPreferredSize(new java.awt.Dimension(304, 4));
        footerPanel.add(timerField, java.awt.BorderLayout.LINE_END);

        mainPanel.add(footerPanel, java.awt.BorderLayout.PAGE_END);

        add(mainPanel, java.awt.BorderLayout.CENTER);
    }// </editor-fold>//GEN-END:initComponents

    /**
     * Controls the start/end talk sessions. it is also used for switching on/off recording
     * @param evt
     */
private void talkButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_talkButtonActionPerformed
    if (audioWizardFrame != null) {
        if (talkButton.isSelected()) {
            talkButton.setIcon(micOnIcon);
            audioWizardFrame.talk();
            if (sessionTitle == null) {
                sessionTitle = "general";
            }
            //start an xml file to be later used for synchronizing
            String filename = sessionTitle + "_" + Constants.getDateTime();
            audioWizardFrame.getMicInput().setAudioClipFileName(filename);
            long startTime = System.currentTimeMillis();
            audioWizardFrame.getMicInput().setAudioTimeStamp(startTime);
            audioWizardFrame.getMicInput().setAudioRecordStart(startTime);
            audioWizardFrame.getMicInput().saveXML("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n");
            audioWizardFrame.getMicInput().saveXML("<session>\n");
        } else {
            audioWizardFrame.getMicInput().saveXML("</session>");
        }
    /* else {
    talkButton.setIcon(micOffIcon);
    audioWizardFrame.stopCapture();
    String filename = "lastSlide" + Constants.getDateTime();
    if (audioWizardFrame.isTesting()) {
    filename = "testing";
    }
    audioWizardFrame.getMicInput().setAudioClipFileName(filename);
    audioWizardFrame.getMicInput().recordAudioClip();
    
    }*/
    }
}//GEN-LAST:event_talkButtonActionPerformed

    /**
     * this generates an xml file that can be used for playback, when a session has been recorded
     * @param slideIndex
     */
    public void recordXml(int slideIndex) {
        String slideName = "slide" + slideIndex + ".jpg";
        long currentTime = System.currentTimeMillis();
        long prevTime = getAudioWizardFrame().getMicInput().getAudioTimeStamp();
        long duration = currentTime - prevTime;
        long startTime = prevTime - getAudioWizardFrame().getMicInput().getAudioRecordStart();
        long endTime = startTime + duration;
        getAudioWizardFrame().getMicInput().setAudioTimeStamp(currentTime);
        getAudioWizardFrame().getMicInput().saveXML(
                "<slide>\n" +
                "\t<slideName>" + slideName + "</slideName>\n" +
                "\t<start>" + startTime + "</start>\n" +
                "\t<end>" + endTime + "</end>\n" +
                "</slide>\n");
        getAudioWizardFrame().getMicInput().recordAudioClip();
        getAudioWizardFrame().getMicInput().setAudioTimeStamp(currentTime);
    }

    /**
     * respond to mute actions
     * @param evt
     */
private void muteOptActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_muteOptActionPerformed
    if (audioWizardFrame != null) {
        audioWizardFrame.mute(muteOpt.isSelected());
    }
}//GEN-LAST:event_muteOptActionPerformed
    /**
     * Our own button behavoir
     */
    class TButton extends JToggleButton {

        public TButton(ImageIcon icon) {
            super(icon);

            setBorderPainted(false);
            setContentAreaFilled(false);
            setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
            setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
            setFont(new java.awt.Font("Dialog", 0, 8));
            this.addMouseListener(new MouseAdapter() {

                @Override
                public void mouseEntered(MouseEvent e) {
                    setContentAreaFilled(true);
                }

                @Override
                public void mouseExited(MouseEvent e) {
                    setContentAreaFilled(false);
                    if (isSelected()) {
                        setContentAreaFilled(true);
                    }

                }
            });
        }
    }
private void volumeSlideStateChanged(javax.swing.event.ChangeEvent evt) {//GEN-FIRST:event_volumeSlideStateChanged
    if (audioWizardFrame != null) {
        audioWizardFrame.setVolume(volumeSlide.getValue());
    }
}//GEN-LAST:event_volumeSlideStateChanged
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JPanel audioPanel;
    private javax.swing.JPanel cPanel;
    private javax.swing.JPanel footerPanel;
    private javax.swing.JSeparator jSeparator1;
    private javax.swing.JPanel mainPanel;
    private javax.swing.JProgressBar micVolumeMeter;
    private javax.swing.JCheckBox muteOpt;
    private javax.swing.JLabel statusBar;
    private javax.swing.JToggleButton talkButton;
    private javax.swing.JLabel timerField;
    private javax.swing.JSlider volumeSlide;
    // End of variables declaration//GEN-END:variables
}
