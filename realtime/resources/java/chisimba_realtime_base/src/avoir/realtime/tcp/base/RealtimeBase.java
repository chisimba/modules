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
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.util.Vector;
import javax.swing.BorderFactory;
import javax.swing.ButtonGroup;
import javax.swing.ImageIcon;
import javax.swing.JCheckBox;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JMenuBar;
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
    private JFrame fileTransferFrame;
    JToolBar pointerToolbar = new JToolBar(JToolBar.VERTICAL);

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
        agendaPanel.setPreferredSize(new Dimension(250, 200));
        leftPanel.add(agendaPanel, BorderLayout.SOUTH);


        audioPanel.setPreferredSize(new Dimension(100, 150));
        volumeSlide.setPreferredSize(new Dimension(150, 32));
        speakerPb.setPreferredSize(new Dimension(150, 21));
        leftPanel.add(audioPanel, BorderLayout.CENTER);

        mPanel.add(leftPanel, BorderLayout.WEST);

        JPanel p = new JPanel();
        p.setLayout(new BorderLayout());

        // p.add(whiteboardToolbar, BorderLayout.NORTH);
        p.add(surface, BorderLayout.CENTER);
        p.add(pointerToolbar, BorderLayout.WEST);
        mPanel.add(p, BorderLayout.CENTER);
        mainPanel.add(mPanel, BorderLayout.CENTER);
        sessionManager.setIsPresenter(isPresenter);

        mainPanel.add(toolbarManager.createToolbar(), BorderLayout.NORTH);
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

    public void initRealtimeHome() {
        options = new RealtimeOptions();

    }

    public JMenuBar getMenuMananger() {
        return menuMananger.getMenuBar();
    }

    public RealtimeOptions getRealtimeOptions() {
        return options;
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
        if (audioWizardFrame != null) {
            audioWizardFrame.stopAudio();
        }
    }

    public boolean isMicEnabled() {
        return micEnabled;
    }

    public void setMicEnabled(boolean micEnabled) {
        this.micEnabled = micEnabled;
        talkButton.setEnabled(micEnabled);
        tcpClient.sendPacket(new PresencePacket(sessionId,
                PresenceConstants.MIC_ICON, micEnabled,
                userName));
    }

    public boolean isSpeakerEnabled() {
        return speakerEnabled;

    }

    public void setSpeakerEnabled(boolean speakerEnabled) {
        this.speakerEnabled = speakerEnabled;
        muteOpt.setEnabled(speakerEnabled);
        volumeSlide.setEnabled(speakerEnabled);
        tcpClient.sendPacket(new PresencePacket(sessionId,
                PresenceConstants.SPEAKER_ICON, speakerEnabled,
                userName));
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

    private void initTCPCommunication() {
        initUser();
        tcpClient = new TCPClient(this);
        tcpClient.setFileTransfer(fileTransferPanel);
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

    public void initAudio() {
        audioWizardFrame = new AudioWizardFrame(RealtimeBase.this, userName, sessionId, slideServerId, resourcesPath);
        audioWizardFrame.setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
        audioWizardFrame.setSize(500, 500);
        audioWizardFrame.setLocationRelativeTo(null);

    }

    public JCheckBox getMuteOpt() {
        return muteOpt;
    }

    public JToggleButton getTalkButton() {
        return talkButton;
    }

    public JSlider getVolumeSlide() {
        return volumeSlide;
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

    public void showFilterFrame() {
        String filter = "[REALTIME id=\"" + sessionId + "\" agenda=\"" + sessionTitle + "\" /]";
        FilterFrame fr = new FilterFrame(filter);
        fr.setSize(500, 300);
        fr.setLocationRelativeTo(null);
        fr.setVisible(true);

    }

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
        java.awt.GridBagConstraints gridBagConstraints;

        pb = new javax.swing.JProgressBar();
        audioPanel = new javax.swing.JPanel();
        talkButton = new javax.swing.JToggleButton();
        muteOpt = new javax.swing.JCheckBox();
        speakerPb = new javax.swing.JProgressBar();
        volumeSlide = new javax.swing.JSlider();
        mainPanel = new javax.swing.JPanel();
        statusBar = new javax.swing.JLabel();

        audioPanel.setBorder(javax.swing.BorderFactory.createTitledBorder(""));
        audioPanel.setLayout(new java.awt.GridBagLayout());

        talkButton.setText("Talk");
        talkButton.setEnabled(false);
        talkButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                talkButtonActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 4;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        gridBagConstraints.insets = new java.awt.Insets(4, 0, 0, 0);
        audioPanel.add(talkButton, gridBagConstraints);

        muteOpt.setText("Mute");
        muteOpt.setEnabled(false);
        muteOpt.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                muteOptActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        gridBagConstraints.insets = new java.awt.Insets(0, 0, 9, 0);
        audioPanel.add(muteOpt, gridBagConstraints);
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 4;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        gridBagConstraints.insets = new java.awt.Insets(5, 0, 0, 2);
        audioPanel.add(speakerPb, gridBagConstraints);

        volumeSlide.setBorder(javax.swing.BorderFactory.createTitledBorder("Volume"));
        volumeSlide.setEnabled(false);
        volumeSlide.setMinimumSize(new java.awt.Dimension(100, 40));
        volumeSlide.setPreferredSize(new java.awt.Dimension(100, 40));
        volumeSlide.addChangeListener(new javax.swing.event.ChangeListener() {
            public void stateChanged(javax.swing.event.ChangeEvent evt) {
                volumeSlideStateChanged(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 2;
        gridBagConstraints.fill = java.awt.GridBagConstraints.HORIZONTAL;
        gridBagConstraints.anchor = java.awt.GridBagConstraints.WEST;
        gridBagConstraints.insets = new java.awt.Insets(0, 0, 9, 2);
        audioPanel.add(volumeSlide, gridBagConstraints);

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
            audioWizardFrame.talk();
        } else {
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
    private javax.swing.JPanel mainPanel;
    private javax.swing.JCheckBox muteOpt;
    private javax.swing.JProgressBar pb;
    private javax.swing.JProgressBar speakerPb;
    private javax.swing.JLabel statusBar;
    private javax.swing.JToggleButton talkButton;
    private javax.swing.JSlider volumeSlide;
    // End of variables declaration//GEN-END:variables
}
