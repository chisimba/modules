/*
 * RealtimeBase.java
 *
 * Created on 18 May 2008, 05:41
 */
package avoir.realtime.tcp.base;

import avoir.realtime.tcp.base.audio.AudioWizardFrame;
import avoir.realtime.tcp.base.managers.AgendaManager;
import avoir.realtime.tcp.base.managers.ToolbarManager;
import avoir.realtime.tcp.base.managers.UserListManager;
import avoir.realtime.tcp.base.managers.SessionManager;
import avoir.realtime.tcp.base.survey.SurveyFrame;
import avoir.realtime.tcp.base.survey.SurveyManagerFrame;
import avoir.realtime.tcp.base.user.User;
import avoir.realtime.tcp.base.user.UserLevel;
import avoir.realtime.tcp.common.packet.ChatLogPacket;
import avoir.realtime.tcp.common.packet.ChatPacket;
import java.awt.BorderLayout;
import java.awt.Color;
import java.util.Vector;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JSplitPane;
import javax.swing.JTabbedPane;

/**
 *
 * @author  developer
 */
public class RealtimeBase extends javax.swing.JPanel {

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
    private SurveyFrame surveyFrame;
    private SurveyManagerFrame surveyManagerFrame;
    private AudioWizardFrame audioWizardFrame;
    private boolean hasControl = false;
    private ChatRoom chatRoom;
    private String chatLogFile = "ChatLog.txt";
    private boolean chatFrameShowing = false;
    private JFrame chatFrame = new JFrame();
    private boolean connected = false;
    private boolean speakerEnabled,  micEnabled;

    private void initCustomComponents() {
        userManager = new UserListManager(this);
        toolbarManager = new ToolbarManager(this);
        surface = new Surface(this);
        sessionManager = new SessionManager(this);
        agendaManager = new AgendaManager(this);
        surveyFrame = new SurveyFrame(this);
        surveyManagerFrame = new SurveyManagerFrame(this);

        mediaPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Media Streaming"));
        mediaPanel.setPreferredSize(new java.awt.Dimension(160, 120));
        mediaPanel.setLayout(new java.awt.BorderLayout());
        logo.setIcon(ImageUtil.createImageIcon(this, "/icons/realtime128.png"));
        mediaPanel.add(logo);
        leftSplitPane.setTopComponent(mediaPanel);
        leftSplitPane.setBottomComponent(tabbedPane);
        tabbedPane.addTab("Participants", new JScrollPane(userManager.getUserList()));
        tabbedPane.addTab("Outline", agendaManager.getAgendaTree());

        splitPane.setLeftComponent(leftSplitPane);
        splitPane.setRightComponent(surface);
        splitPane.setDividerLocation(180);
        mainPanel.add(splitPane, BorderLayout.CENTER);
        sessionManager.setIsPresenter(isPresenter);

        mainPanel.add(toolbarManager.createToolbar(), BorderLayout.NORTH);
    }

    public void initChatRoom() {
        chatRoom = new ChatRoom(this, user, chatLogFile, sessionId);

    }

    public SurveyManagerFrame getSurveyManagerFrame() {
        return surveyManagerFrame;
    }

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

    public void showSurveyFrame(Vector<String> questions, String title) {
        surveyFrame.setQuestions(questions);
        surveyFrame.setTitle(title);
        surveyFrame.setSize(500, 400);
        surveyFrame.setLocationRelativeTo(null);
        // surveyFrame.setAlwaysOnTop(true);
        surveyFrame.setVisible(true);
    }

    public UserListManager getUserManager() {
        return userManager;
    }

    public SessionManager getSessionManager() {
        return sessionManager;
    }

    public ToolbarManager getToolbarManager() {
        return toolbarManager;
    }

    public AgendaManager getAgendaManager() {
        return agendaManager;
    }

    public void removeUser(String userid, String sessionid) {
        tcpClient.removeUser(user);
        audioWizardFrame.stopAudio();
    }

    public boolean isMicEnabled() {
        return micEnabled;
    }

    public void setMicEnabled(boolean micEnabled) {
        this.micEnabled = micEnabled;
    }

    public boolean isSpeakerEnabled() {
        return speakerEnabled;
    }

    public void setSpeakerEnabled(boolean speakerEnabled) {
        this.speakerEnabled = speakerEnabled;
    }

    public boolean getControl() {
        return hasControl;
    }

    public boolean isPresenter() {
        return user.isPresenter();
    }

    public String getSlideServerId() {
        return slideServerId;
    }

    public String getSessionId() {
        return sessionId;
    }

    public TCPClient getTcpClient() {
        return tcpClient;
    }

    public User getUser() {
        return user;
    }

    public void setControl(boolean control) {
        this.hasControl = control;
    }

    public String getSessionTitle() {
        return sessionTitle;
    }

    public void setSessionTitle(String sessionTitle) {
        this.sessionTitle = sessionTitle;
    }

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

    private void initTCPCommunication() {
        initUser();

        tcpClient = new TCPClient(this);
        initChatRoom();
        sessionManager.connect();
    }

    public void doPostConnectControlsCheck() {
        surface.setStatusMessage("Initializing session, please wait ...");
        surface.setShowSplashScreen(false);
        getToolbarManager().getVoiceOptionsButton().setEnabled(true);
        getToolbarManager().setButtonsEnabled(isPresenter);
        getSurface().setConnecting(false);
        setText("Connected", true);
        setConnected(true);
    }

    private void initUser() {

        //if not logged in, then assign random number
        if (userName == null || userName.startsWith("Language")) { //not logging on through KEWL for dev, so give random user name

            userName = "Guest" + new java.util.Random().nextInt(200);
            fullName = userName;
        }
        UserLevel xuserLevel = UserLevel.GUEST; //assume guest unless set differently in applet param

        if (userLevel != null) {
            xuserLevel = UserLevel.valueOf(userLevel.toUpperCase());
        }
        user = new User(xuserLevel, fullName, userName, host, 22225, isPresenter,
                sessionId, sessionTitle, slidesDir, false, siteRoot, slideServerId);

    }

    public String getSlidesDir() {
        return slidesDir;
    }

    public JLabel getStatusBar() {
        return statusBar;
    }

    public Surface getSurface() {
        return surface;
    }

    public boolean isLocalhost() {
        return localhost;
    }

    public String getSiteRoot() {
        return siteRoot;
    }

    public void showAudioWizardFrame() {
        if (audioWizardFrame == null) {
            audioWizardFrame = new AudioWizardFrame(this, userName, sessionId, slideServerId, resourcesPath);
            audioWizardFrame.setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
            audioWizardFrame.setSize(500, 500);
            audioWizardFrame.setLocationRelativeTo(null);
        }
        audioWizardFrame.setVisible(true);
    }

    /**
     * Diplays the chat room (frame)
     */
    public void showChatRoom() {
        if (!chatFrameShowing) {
            chatFrameShowing = true;
            chatFrame.setTitle(user.getFullName() + ": Chat Session: " + user.getSessionTitle());
            chatFrame.setAlwaysOnTop(true);
            chatFrame.add(chatRoom);
            chatFrame.setSize(400, 350);
            chatFrame.setLocationRelativeTo(null);
        }
        chatFrame.setVisible(true);

    }

    public void setText(String txt, boolean error) {
        if (error) {
            statusBar.setForeground(Color.RED);
        } else {
            statusBar.setForeground(Color.BLACK);
        }
        statusBar.setText(txt);
        surface.setConnectingString(txt);

    }

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

    public void setConnected(boolean connected) {
        this.connected = connected;
    }

    public void publish() {
        tcpClient.publish(user);
    }

    private void sleep(long time) {
        try {
            Thread.sleep(time);
        } catch (Exception ex) {
        }
    }

    public void refreshConnection() {
        connected = false;
        int count = 0;
        int max = 60;
        while (true) {
            sessionManager.connect();
            sleep(1000);
            if (connected) {
                break;
            }
            if (count > max) {
                break;
            }
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

        pb = new javax.swing.JProgressBar();
        mainPanel = new javax.swing.JPanel();
        statusBar = new javax.swing.JLabel();

        setLayout(new java.awt.BorderLayout());

        mainPanel.setLayout(new java.awt.BorderLayout());

        statusBar.setText("Ready");
        statusBar.setBorder(javax.swing.BorderFactory.createBevelBorder(javax.swing.border.BevelBorder.LOWERED));
        mainPanel.add(statusBar, java.awt.BorderLayout.PAGE_END);

        add(mainPanel, java.awt.BorderLayout.CENTER);
    }// </editor-fold>//GEN-END:initComponents
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JPanel mainPanel;
    private javax.swing.JProgressBar pb;
    private javax.swing.JLabel statusBar;
    // End of variables declaration//GEN-END:variables
}
