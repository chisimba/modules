/*
 * RealtimeBase.java
 *
 * Created on 18 May 2008, 05:41
 */
package avoir.realtime.tcp.base;

import avoir.realtime.tcp.base.audio.AudioWizardFrame;
import avoir.realtime.tcp.base.filetransfer.FileTransferPanel;
import avoir.realtime.tcp.base.managers.AgendaManager;
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
import avoir.realtime.tcp.common.PresenceConstants;
import avoir.realtime.tcp.common.packet.ChatLogPacket;
import avoir.realtime.tcp.common.packet.ChatPacket;
import avoir.realtime.tcp.common.packet.PresencePacket;
import avoir.realtime.tcp.whiteboard.WhiteboardSurface;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.util.Vector;
import javax.swing.BorderFactory;
import javax.swing.ButtonGroup;
import javax.swing.ImageIcon;
import javax.swing.JApplet;
import javax.swing.JCheckBox;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JMenuBar;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JSlider;
import javax.swing.JSplitPane;
import javax.swing.JTabbedPane;
import javax.swing.JTable;
import javax.swing.JToggleButton;
import javax.swing.JToolBar;

/**
 *
 * @author  developer
 */
public class RealtimeBase extends javax.swing.JPanel implements ActionListener {

    private String defaultHome = "avoir-realtime-0.1";
    private RealtimeOptions options;
    private TCPClient tcpClient;
    private String userName;
    private String fullName;
    private String sessionId;
    private String slidesDir;
    private String slideServerId;
    private String resourcesPath;
    private String siteRoot;
    private String host;
    private boolean isPresenter;
    private String sessionTitle;
    private String appletCodeBase;
    private User user;
    private boolean localhost;
    private javax.swing.JSplitPane splitPane = new JSplitPane();
    private javax.swing.JSplitPane leftSplitPane = new JSplitPane(JSplitPane.VERTICAL_SPLIT);
    private JPanel mediaPanel = new JPanel();
    private JTabbedPane tabbedPane = new JTabbedPane();
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
    private boolean hasControl = false;
    private ChatRoom chatRoom;
    private String chatLogFile = "ChatLog.txt";
    private boolean chatFrameShowing = false;
    private JFrame chatFrame = new JFrame();
    private boolean connected = false;
    private boolean speakerEnabled,  micEnabled;
    private JPanel mPanel = new JPanel();
    private JPanel leftPanel = new JPanel();
    private JPanel userListPanel = new JPanel();
    private JPanel agendaPanel = new JPanel();
    private JToolBar userItemsPanel = new JToolBar();
    private TButton stepOutButton = new TButton(ImageUtil.createImageIcon(this, "/icons/stepout.jpeg"));
    private TButton handButton = new TButton(ImageUtil.createImageIcon(this, "/icons/hand.png"));
    private TButton laughterButton = new TButton(ImageUtil.createImageIcon(this, "/icons/laugh.jpeg"));
    private TButton applaudButton = new TButton(ImageUtil.createImageIcon(this, "/icons/applaud.jpeg"));
    private TButton handLeftButton = new TButton(ImageUtil.createImageIcon(this, "/icons/hand_left.png"));
    private TButton handRightButton = new TButton(ImageUtil.createImageIcon(this, "/icons/hand_right.png"));
    private TButton arrowUpButton = new TButton(ImageUtil.createImageIcon(this, "/icons/arrow_up.png"));
    private TButton arrowSideButton = new TButton(ImageUtil.createImageIcon(this, "/icons/arrow_side.png"));
    private TButton paintBrushButton = new TButton(ImageUtil.createImageIcon(this, "/icons/paintbrush.png"));
    private ImageIcon micOffIcon = ImageUtil.createImageIcon(this, "/icons/mic_off.png");
    private ImageIcon micOnIcon = ImageUtil.createImageIcon(this, "/icons/mic_on.png");
    private JFrame fileTransferFrame;
    private JToolBar pointerToolbar = new JToolBar(JToolBar.VERTICAL);
    private WhiteboardSurface whiteboardSurface;
    private JTabbedPane surfaceTabbedPane = new JTabbedPane();
    private JApplet glassPaneHandler;

    /**
     * Create additional components
     */
    private void initCustomComponents() {
        userManager = new UserListManager(this);
        toolbarManager = new ToolbarManager(this);
        surface = new Surface(this);
        sessionManager = new SessionManager(this);
        agendaManager = new AgendaManager(this);
        surveyFrame = new SurveyFrame(this);
        surveyManagerFrame = new SurveyManagerFrame(this);
        menuMananger = new MenuManager(this);
        whiteboardToolbar = new WhiteboardToolbarManager(this);
        whiteboardSurface = new WhiteboardSurface(this);

        talkButton.setIcon(micOffIcon);
        talkButton.setBorderPainted(false);
        talkButton.setFont(new java.awt.Font("Dialog", 0, 8));

        mediaPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Media Streaming"));
        mediaPanel.setPreferredSize(new java.awt.Dimension(300, 300));
        mediaPanel.setLayout(new java.awt.BorderLayout());
        logo.setIcon(ImageUtil.createImageIcon(this, "/icons/realtime128.png"));
        mediaPanel.add(logo);
        leftSplitPane.setDividerLocation(getHeight() / 2);
        mPanel.setLayout(new BorderLayout());

        leftPanel.setLayout(new BorderLayout());
        JTable table = userManager.getUserList();
        JScrollPane sp = new JScrollPane(table);
        sp.setBackground(Color.WHITE);
        table.setBackground(Color.WHITE);


        userListPanel.setLayout(new BorderLayout());
        userListPanel.setPreferredSize(new Dimension(250, 200));
        userListPanel.add(sp, BorderLayout.CENTER);
        userListPanel.add(userItemsPanel, BorderLayout.SOUTH);
        userListPanel.setBackground(Color.WHITE);

        stepOutButton.setText("Step Out");
        stepOutButton.setActionCommand("stepout");
        stepOutButton.addActionListener(this);

        userItemsPanel.setBorder(BorderFactory.createTitledBorder("Controls"));

        laughterButton.setText("Laugh");
        laughterButton.addActionListener(this);
        laughterButton.setActionCommand("laughter");

        applaudButton.setText("Applaud");
        applaudButton.addActionListener(this);
        applaudButton.setActionCommand("applaud");

        handButton.setText("Hand");
        handButton.addActionListener(this);
        handButton.setActionCommand("hand");

        userItemsPanel.add(stepOutButton);
        userItemsPanel.add(handButton);
        userItemsPanel.add(laughterButton);
        userItemsPanel.add(applaudButton);


        ButtonGroup bg = new ButtonGroup();

        bg.add(handRightButton);
        bg.add(handLeftButton);
        bg.add(arrowUpButton);
        bg.add(arrowSideButton);
        bg.add(paintBrushButton);

        handRightButton.setSelected(true);
        //pointerToolbar.add(paintBrushButton);
        paintBrushButton.addActionListener(this);
        paintBrushButton.setActionCommand("paintBrush");
        paintBrushButton.setToolTipText("Free Hand Paint Brush");

        pointerToolbar.add(handRightButton);
        handRightButton.addActionListener(this);
        handRightButton.setActionCommand("handRight");
        handRightButton.setToolTipText("Point right");


        pointerToolbar.add(handLeftButton);
        handLeftButton.addActionListener(this);
        handLeftButton.setActionCommand("handLeft");
        handLeftButton.setToolTipText("Point Left");

        pointerToolbar.add(arrowUpButton);
        arrowUpButton.addActionListener(this);
        arrowUpButton.setActionCommand("arrowUp");
        arrowUpButton.setToolTipText("Arrow Up");
        pointerToolbar.add(arrowSideButton);
        arrowSideButton.addActionListener(this);
        arrowSideButton.setActionCommand("arrowSide");
        arrowSideButton.setToolTipText("Arrow Side");

        tabbedPane.addTab("Participants", userListPanel);
        //tabbedPane.addTab("Files", fileTransferPanel);
        leftPanel.setPreferredSize(new Dimension(250, 300));
        leftPanel.add(tabbedPane, BorderLayout.NORTH);
        agendaPanel.setLayout(new BorderLayout());
        agendaPanel.add(agendaManager.getAgendaTree(), BorderLayout.CENTER);
        agendaPanel.setPreferredSize(new Dimension(250, 150));
        leftPanel.add(agendaPanel, BorderLayout.SOUTH);


        audioPanel.setPreferredSize(new Dimension(100, 200));
        volumeSlide.setPreferredSize(new Dimension(150, 32));
        speakerPb.setPreferredSize(new Dimension(150, 21));
        leftPanel.add(audioPanel, BorderLayout.CENTER);

        mPanel.add(leftPanel, BorderLayout.WEST);

        JPanel p = new JPanel();
        p.setLayout(new BorderLayout());

        surfaceTabbedPane.add("Presentation", surface);
        surfaceTabbedPane.add("Whiteboard", whiteboardSurface);
        p.add(surfaceTabbedPane, BorderLayout.CENTER);
        p.add(pointerToolbar, BorderLayout.WEST);
        mPanel.add(p, BorderLayout.CENTER);
        mainPanel.add(mPanel, BorderLayout.CENTER);
        sessionManager.setIsPresenter(isPresenter);

        mainPanel.add(toolbarManager.createToolbar(), BorderLayout.NORTH);
    }

    /**
     * get the whiteboard surface
     * @return
     */
    public WhiteboardSurface getWhiteboardSurface() {
        return whiteboardSurface;
    }

    public TButton getArrowSideButton() {
        return arrowSideButton;
    }

    public TButton getArrowUpButton() {
        return arrowUpButton;
    }

    public TButton getHandLeftButton() {
        return handLeftButton;
    }

    public TButton getHandRightButton() {
        return handRightButton;
    }

    /**
     * React to action events
     * @param e
     */
    public void actionPerformed(ActionEvent e) {
        if (e.getActionCommand().equals("stepout")) {
            if (stepOutButton.isSelected()) {
                stepOutButton.setText("Resume");
                tcpClient.sendPacket(new PresencePacket(sessionId,
                        PresenceConstants.STEP_OUT_ICON, true,
                        userName));
                laughterButton.setSelected(false);
                applaudButton.setSelected(false);
                handButton.setSelected(false);
            } else {
                stepOutButton.setText("Step Out");
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
        if (e.getActionCommand().equals("handLeft")) {
            surface.setPointer(Constants.HAND_LEFT);
        }
        if (e.getActionCommand().equals("handRight")) {
            surface.setPointer(Constants.HAND_RIGHT);
        }
        if (e.getActionCommand().equals("arrowUp")) {
            surface.setPointer(Constants.ARROW_UP);
        }
        if (e.getActionCommand().equals("arrowSide")) {
            surface.setPointer(Constants.ARROW_SIDE);
        }
        if (e.getActionCommand().equals("paintBrush")) {
            surface.setPointer(Constants.PAINT_BRUSH);
        }
    }

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
        surveyManagerFrame.setTitle("Survey Wizard  - Untitled");
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
        surveyFrame.setQuestions(questions);
        surveyFrame.setTitle(title);
        surveyFrame.setSize(500, 400);
        surveyFrame.setLocationRelativeTo(null);
        // surveyFrame.setAlwaysOnTop(true);
        surveyFrame.setVisible(true);
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
        tcpClient.sendPacket(new PresencePacket(sessionId,
                PresenceConstants.SPEAKER_ICON, speakerEnabled,
                userName));
    }

    /**
     * can this user control a presentation ?
     * @return
     */
    public boolean getControl() {
        return hasControl;
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
            boolean localhost) {
        this.userLevel = userLevel;
        this.fullName = fullname;
        this.userName = userName;
        this.host = host;
        this.port = port;
        this.isPresenter = isPresenter;
        this.sessionId = sessionId;

        this.slidesDir = slidesDir;
        this.isSlidesHost = isSlidesHost;
        this.siteRoot = siteRoot;
        this.slideServerId = slideServerId;
        this.resourcesPath = resourcesPath;
        this.localhost = localhost;
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

    /**
     * Initialize TCP connection. Actually, this is where you create an object
     * to connect to the server
     */
    private void initTCPCommunication() {
        initUser();
        tcpClient = new TCPClient(this);
        tcpClient.setFileTransfer(fileTransferPanel);
        whiteboardSurface.setTCPClient(tcpClient);
        initChatRoom();
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

    /**
     * Display the filter frame
     */
    public void showFilterFrame() {
        //($id,$agenda,$resourcesPath,$appletCodeBase,$slidesDir,$username,$fullnames,$userLevel,$slideServerId)
        //  String filterNames = JOptionPane.showInputDialog("Please enter your full names  separated by space.\n" +
        //        "This will be  used to create a filter to be used in live presentation");

        String filterNames = "Guest" + new java.util.Random().nextInt(200)+" "+"RL" + new java.util.Random().nextInt(200);

        if (filterNames != null) {
            if (filterNames.trim().equals("")) {
                JOptionPane.showMessageDialog(this, "Empty spaces are not allowed. Please enter your fullname");
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
            String filter = "[REALTIME: " +
                    "id=\"" + sessionId + "\" \n" +
                    "agenda=\"" + sessionTitle + "\" \n" +
                    "resourcesPath=\"" + resourcesPath + "\" \n" +
                    "appletCodeBase=\"" + appletCodeBase + "\" \n" +
                    "slidesDir=\"" + slidesDir + "\" \n" +
                    "username=\"" + names[0] + "\" \n" +
                    "fullnames=\"" + filterNames + "\" \n" +
                    "userLevel=\"admin\" \n" +
                    "slideServerId=\"" + slideServerId + "\" \n" +
                    "siteRoot=\"" + siteRoot + "\" /]";
            FilterFrame fr = new FilterFrame(filter, createEmbbedStr(names[0], filterNames));
            fr.setSize(500, 300);
            fr.setLocationRelativeTo(null);
            fr.setVisible(true);
        }

    }

    private String createEmbbedStr(String userName, String fullName) {
        String url = "<center>\n";
        url += "<applet codebase=\"" + appletCodeBase + "\"\n";
        url += "code=\"avoir.realtime.tcp.launcher.RealtimeLauncher\" name =\"Avoir Realtime Applet\"\n";

        url += "archive=\"realtime-launcher-0.1.jar\" width=\"100%\" height=\"600\">\n";
        url += "<param name=userName value=\"" + userName + "\" >\n";

        url += "<param name=isLocalhost value=\"false\">\n";
        url += "<param name=fullname value=\"" + fullName + "\">\n";
        url += "<param name=userLevel value=\"" + userLevel + "\">\n";
        url += "<param name=uploadURL value=\"uploadURL\">\n";
        url += "<param name=chatLogPath value=\"chatLogPath\">\n";
        url += "<param name=siteRoot value=\"" + siteRoot + "\">\n";
        url += "<param name=isWebPresent value=\"true\">\n";
        url += "<param name=slidesDir value=\"" + slidesDir + "\">\n";
        url += "<param name=uploadPath value=\"uploadPath\">\n";
        url += "<param name=resourcesPath value=\"" + resourcesPath + "\">\n";
        url += "<param name=sessionId value=\"" + sessionId + "\">\n";
        url += "<param name=sessionTitle value=\"" + sessionTitle + "\">\n";
        url += "<param name=slideServerId value=\"" + slideServerId + "\">\n";

        url += "<param name=isSessionPresenter value=\"false\">\n";
        url += "</applet>\n";
        url += "</center>\n";
        return url;
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

    /**
     * Diplays the chat room (frame)
     */
    public void showChatRoom() {
        chatFrame.setTitle(user.getFullName() + ": Chat Session: " + user.getSessionTitle());
        chatFrame.setAlwaysOnTop(true);
        chatFrame.add(chatRoom);
        chatFrame.setSize(400, 350);
        chatFrame.setLocationRelativeTo(null);
        chatFrame.setVisible(true);

    }

    /**
     * Displays the file transfer frame
     */
    public void showFileTransferFrame() {
        if (fileTransferFrame == null) {
            fileTransferFrame = new JFrame("Realtime File Transfer - Beta");
            fileTransferPanel = new FileTransferPanel(this);
            fileTransferFrame.getContentPane().add(fileTransferPanel);
            fileTransferFrame.setSize(600, 400);
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
            showMessage("Disconnected from server. Retrying " + count + " of " + max + "...", false, true);

        }
        if (!connected) {
            showMessage("Connection to server failed. Refresh your browser page to reconnect", false, true);

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
    public void showMessage(String msg, boolean temp, boolean isErrorMessage) {
        String prevMsg = surface.getMessage();
        surface.showMessage(msg, isErrorMessage);
        if (temp) {
            try {
                Thread.sleep(10000);
            } catch (Exception ex) {
            }
            surface.showMessage(prevMsg, false);
        }
    }

    /** This method is called from within the init() method to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        speakerPb = new javax.swing.JProgressBar();
        audioPanel = new javax.swing.JPanel();
        mAudioPanel = new javax.swing.JPanel();
        volumeSlide = new javax.swing.JSlider();
        muteOpt = new javax.swing.JCheckBox();
        cPanel = new javax.swing.JPanel();
        talkButton = new javax.swing.JToggleButton();
        pb = new javax.swing.JProgressBar();
        mainPanel = new javax.swing.JPanel();
        statusBar = new javax.swing.JLabel();

        audioPanel.setBorder(javax.swing.BorderFactory.createTitledBorder(""));
        audioPanel.setMinimumSize(new java.awt.Dimension(50, 111));
        audioPanel.setLayout(new java.awt.BorderLayout());

        mAudioPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Speaker"));

        volumeSlide.setEnabled(false);
        volumeSlide.setMinimumSize(new java.awt.Dimension(50, 40));
        volumeSlide.setPreferredSize(new java.awt.Dimension(60, 40));
        volumeSlide.addChangeListener(new javax.swing.event.ChangeListener() {
            public void stateChanged(javax.swing.event.ChangeEvent evt) {
                volumeSlideStateChanged(evt);
            }
        });
        mAudioPanel.add(volumeSlide);

        muteOpt.setText("Mute");
        muteOpt.setEnabled(false);
        muteOpt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                muteOptActionPerformed(evt);
            }
        });
        mAudioPanel.add(muteOpt);

        audioPanel.add(mAudioPanel, java.awt.BorderLayout.CENTER);

        cPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Microphone"));
        cPanel.setLayout(new java.awt.GridBagLayout());

        talkButton.setText("Talk");
        talkButton.setEnabled(false);
        talkButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                talkButtonActionPerformed(evt);
            }
        });
        cPanel.add(talkButton, new java.awt.GridBagConstraints());
        cPanel.add(pb, new java.awt.GridBagConstraints());

        audioPanel.add(cPanel, java.awt.BorderLayout.PAGE_END);

        setPreferredSize(new java.awt.Dimension(40, 19));
        setLayout(new java.awt.BorderLayout());

        mainPanel.setLayout(new java.awt.BorderLayout());

        statusBar.setText("Ready");
        statusBar.setBorder(javax.swing.BorderFactory.createBevelBorder(javax.swing.border.BevelBorder.LOWERED));
        mainPanel.add(statusBar, java.awt.BorderLayout.PAGE_END);

        add(mainPanel, java.awt.BorderLayout.CENTER);
    }// </editor-fold>//GEN-END:initComponents

private void talkButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_talkButtonActionPerformed
    if (audioWizardFrame != null) {
        if (talkButton.isSelected()) {
            talkButton.setIcon(micOnIcon);
            audioWizardFrame.talk();
        } else {
            talkButton.setIcon(micOffIcon);
            audioWizardFrame.stopCapture();
        }
    }
}//GEN-LAST:event_talkButtonActionPerformed

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
            setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
            setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
            setFont(new java.awt.Font("Dialog", 0, 8));
        /*this.addMouseListener(new MouseAdapter() {
        
        @Override
        public void mouseEntered(MouseEvent e) {
        setContentAreaFilled(true);
        }
        
        @Override
        public void mouseExited(MouseEvent e) {
        setContentAreaFilled(false);
        }
        });*/
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
    private javax.swing.JPanel mAudioPanel;
    private javax.swing.JPanel mainPanel;
    private javax.swing.JCheckBox muteOpt;
    private javax.swing.JProgressBar pb;
    private javax.swing.JProgressBar speakerPb;
    private javax.swing.JLabel statusBar;
    private javax.swing.JToggleButton talkButton;
    private javax.swing.JSlider volumeSlide;
    // End of variables declaration//GEN-END:variables
}
