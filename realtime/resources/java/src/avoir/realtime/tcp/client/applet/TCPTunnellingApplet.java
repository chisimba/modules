/*
 * TCPTunnellingApplet.java
 *
 * Created on 06 April 2008, 01:53
 */
package avoir.realtime.tcp.client.applet;

import avoir.realtime.tcp.client.applet.survey.SurveyFrame;
import avoir.realtime.tcp.client.applet.survey.SurveyManagerFrame;
import avoir.realtime.tcp.common.user.User;
import avoir.realtime.tcp.common.user.UserObject;
import avoir.realtime.tcp.common.user.UserLevel;
import avoir.realtime.common.*;
import avoir.realtime.tcp.common.packet.ChatLogPacket;
import avoir.realtime.tcp.common.packet.ChatPacket;
import java.io.*;
import javax.swing.*;
import java.awt.event.*;
import javax.swing.JButton;
import java.util.Vector;
import java.awt.Color;
import java.awt.Component;
import static avoir.realtime.common.Constants.*;
import avoir.realtime.tcp.audio.AudioWizardFrame;

/**
 *
 * @author  developer
 */
public class TCPTunnellingApplet extends javax.swing.JApplet {

    private Surface surface;
    public DefaultListModel listModel = new DefaultListModel();
    public MButton firstSlideButton,  surveyButton,  nextSlideButton,  backSlideButton,  lastSlideButton;
    private SurveyManagerFrame surveyManagerFrame;
    private TButton sessionButton;
    private SurveyFrame surveyFrame;
    private JLabel logo = new JLabel();
    private int slideIndex = 0;
    private int slideCount = 0;
    private TCPClient client;
    private User user;
    private ChatRoom chatRoom;
    private String chatLogFile;
    private boolean connected = false;
    private JFrame chatFrame = new JFrame();
    private boolean chatFrameShowing = false;
    private JPopupMenu popup = new JPopupMenu();
    private JMenu menu = new JMenu("Help");
    private JMenuItem aboutMenu = new JMenuItem("About");
    private JMenuItem allowControlItem = new JMenuItem("Allow Control");
    private JMenuItem stopControlItem = new JMenuItem("Stop Control");
    // private JMenuItem callItem = new JMenuItem("Give Microphone");
    private JMenuItem sendPrivateMessageItem = new JMenuItem("Send Private Message");
    private boolean hasControl;
    private boolean surveyOn = false;
    private boolean privateVote = false;
    private JMenuBar menubar = new JMenuBar();
    private UserManager userManager;
    private String userName = "Anonymous";
    private String fullName = "Anonymous";
    private String sessionId = "xx";
    private String slidesDir = "";
    private String slideServerId = "";
    private String resourcesPath = "";
    private String siteRoot = "";
    private String host;
    private boolean isPresenter = false;
    private DefaultListModel agendaListModel = new DefaultListModel();
    private JList agendaList = new JList(agendaListModel);
    private OutlineTree outlineTree;
    private AudioWizardFrame audioWizardFrame;

    public TCPTunnellingApplet() {
        init();
    }

    /** Initializes the applet TCPTunnellingApplet */
    @Override
    public void init() {
        try {
            java.awt.EventQueue.invokeAndWait(new Runnable() {

                public void run() {
                    RealtimeOptions.init();
                    initUser();
                    initComponents();
                    initCustomComponents();
                    enableButtons(false);
                }
            });
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    private void initUser() {
        userName = getParameter("userName");
        fullName = getParameter("fullName");
        sessionId = getParameter("sessionId");
        slidesDir = getParameter("slidesDir");
        siteRoot = getParameter("siteRoot");
        resourcesPath = getParameter("resourcesPath");
        slideServerId = getParameter("slideServerId");
        String levelString = getParameter("userLevel");
        isPresenter = new Boolean(getParameter("isSessionPresenter")).booleanValue();
        //if not logged in, then assign random number
        if (userName == null || userName.startsWith("Language")) { //not logging on through KEWL for dev, so give random user name

            userName = "Guest" + new java.util.Random().nextInt(200);
            fullName = userName;
        }
        //the host from which this applet originated
        host = getCodeBase().getHost();

        UserLevel userLevel = UserLevel.GUEST; //assume guest unless set differently in applet param

        if (levelString != null) {
            userLevel = UserLevel.valueOf(levelString.toUpperCase());
        }
        user = new User(userLevel, fullName, userName, host, 22225, isPresenter,
                sessionId, slidesDir, false, siteRoot, slideServerId);

    }

    /**
     * Add our own GUI customizations here
     */
    private void initCustomComponents() {
        outlineTree = new OutlineTree(this);
        surveyManagerFrame = new SurveyManagerFrame(this);
        surveyFrame = new SurveyFrame(this);
        userManager = new UserManager(this);
        surface = new Surface(this);
        client = new TCPClient(surface, this);
        chatRoom = new ChatRoom(client, user.getFullName(), chatLogFile, user.getSessionId());
        chatLogFile = "chatLogFile";
        usersList.setModel(listModel);
        usersList.setCellRenderer(new CustomCellRenderer());

        leftSplitPane.setTopComponent(mediaPanel);
        leftSplitPane.setBottomComponent(tabbedPane);


        tabbedPane.addTab("Participants", usersListSP);
        tabbedPane.addTab("Agenda", outlineTree);
        refreshButton.setIcon(ImageUtil.createImageIcon(this, "/icons/refresh.png"));
        optionsButton.setIcon(ImageUtil.createImageIcon(this, "/icons/options.png"));

        firstSlideButton = new MButton(ImageUtil.createImageIcon(this, "/icons/first.png"));
        firstSlideButton.setText("First");
        backSlideButton = new MButton(ImageUtil.createImageIcon(this, "/icons/back.png"));
        backSlideButton.setText("Back");
        nextSlideButton = new MButton(ImageUtil.createImageIcon(this, "/icons/next.png"));
        nextSlideButton.setText("Next");
        lastSlideButton = new MButton(ImageUtil.createImageIcon(this, "/icons/last.png"));
        lastSlideButton.setText("Last");
        surveyButton = new MButton(ImageUtil.createImageIcon(this, "/icons/survey.png"));
        surveyButton.setText("Survey");
        sessionButton = new TButton(ImageUtil.createImageIcon(this, "/icons/session_off.png"));
        sessionButton.setText("Start");
        surveyButton.setToolTipText("Conduct Survey");

        chatButton.setIcon(ImageUtil.createImageIcon(this, "/icons/chat.png"));
        handButton.setIcon(ImageUtil.createImageIcon(this, "/icons/hand.png"));
        yesButton.setIcon(ImageUtil.createImageIcon(this, "/icons/yes.png"));
        noButton.setIcon(ImageUtil.createImageIcon(this, "/icons/no.png"));
        voiceOptionsButton.setIcon(ImageUtil.createImageIcon(this, "/icons/voice.png"));
        yesButton.setEnabled(false);
        noButton.setEnabled(false);
        chatButton.setToolTipText("Chat");

        toolBar.addSeparator();
        toolBar.setBorder(BorderFactory.createEtchedBorder());

        sessionButton.setEnabled(false);
        slidesToolBar.setBorder(BorderFactory.createEtchedBorder());
        if (user.isPresenter()) {
            slidesToolBar.add(sessionButton);
        }
        slidesToolBar.add(firstSlideButton);
        slidesToolBar.add(backSlideButton);
        slidesToolBar.add(nextSlideButton);
        slidesToolBar.add(lastSlideButton);
        if (user.isPresenter()) {
            whiteboardToolbar.add(surveyButton);
            popup.add(allowControlItem);
            popup.add(stopControlItem);
            popup.addSeparator();
        }
        popup.add(sendPrivateMessageItem);

        stopControlItem.setEnabled(false);
        addActionListeners();
        logo.setIcon(ImageUtil.createImageIcon(this, "/icons/realtime128.png"));
        mediaPanel.add(logo);
        chatFrame.setResizable(true);
        setJMenuBar(menubar);
        buildMenu();

        splitPane.setRightComponent(surface);
        splitPane.setLeftComponent(leftSplitPane);
        splitPane.setDividerLocation(180);

        agendaList.addMouseListener(new MouseAdapter() {

            @Override
            public void mouseClicked(MouseEvent e) {
                if (hasControl || isPresenter) {
                    slideIndex = agendaList.getSelectedIndex();
                    client.requestNewSlide(user.getSiteRoot(), slideIndex, user.isPresenter(), user.getSessionId(), user.getUserName(), hasControl);

                    if (e.getClickCount() == 2) {
                        String old = (String) agendaListModel.elementAt(agendaList.getSelectedIndex());
                        String newV = JOptionPane.showInputDialog("Edit Item", old);
                        if (newV != null) {
                            agendaListModel.set(agendaList.getSelectedIndex(), newV);
                            client.sendAgendItem(newV, sessionId, agendaList.getSelectedIndex(), slideCount);
                        }
                    }
                }
            }
        });
        add(splitPane, java.awt.BorderLayout.CENTER);

    }

    public void renameOutlineItem(String item, int index) {
        client.sendAgendItem(item, sessionId, index, slideCount);

    }

    public void updateSlide(int index) {
        slideIndex = index;
        client.requestNewSlide(user.getSiteRoot(), slideIndex, user.isPresenter(), user.getSessionId(), user.getUserName(), hasControl);

    }

    public String getResoursesPath() {
        return resourcesPath;
    }

    public void setAgenda(String[] outline) {

        if (outline == null) {
            return;
        }
        outlineTree.clear();
        for (int i = 0; i < outline.length; i++) {
            if (outline[i] == null) {
                outlineTree.addObject(null, "Item " + (i + 1), true);
            } else {
                outlineTree.addObject(null, outline[i], true);

            }
        }
    }

    public int getUserCount() {
        return listModel.getSize();
    }

    private void buildMenu() {
        menubar.add(menu);
        menu.add(aboutMenu);
    }

    /**
     * Add actions to components
     */
    private void addActionListeners() {
        sessionButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                if (sessionButton.isSelected()) {
                    sessionButton.setIcon(ImageUtil.createImageIcon(this, "/icons/session_on.png"));
                    sessionButton.setToolTipText("Pause Session");
                    sessionButton.setText("Pause");
                } else {
                    sessionButton.setIcon(ImageUtil.createImageIcon(this, "/icons/session_off.png"));
                    sessionButton.setToolTipText("Start Session");
                    sessionButton.setText("Start");

                }
            //   client.setSessionEnabled(sessionId, sessionButton.isSelected());
            }
        });
        aboutMenu.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                JOptionPane.showMessageDialog(null, "UWC, FSIU (c) 2008\n" +
                        "Beta build " + avoir.realtime.common.Version.version);
            }
        });
        surveyButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {


                showSurveyManagerFrame();
            }
        });
        sendPrivateMessageItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
            }
        });
        chatFrame.addWindowListener(new WindowAdapter() {

            @Override
            public void windowClosing(WindowEvent e) {
                chatFrameShowing = false;
                chatFrame.setVisible(false);
            }
        });
        stopControlItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                int index = usersList.getSelectedIndex();
                UserObject usr = (UserObject) listModel.elementAt(index);
                client.sendAttentionPacket(usr.getUser().getUserName(), user.getSessionId(),
                        false, false, false, false, false, false);
                stopControlItem.setEnabled(false);

            }
        });
        allowControlItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                int index = usersList.getSelectedIndex();
                UserObject usr = (UserObject) listModel.elementAt(index);
                client.sendAttentionPacket(usr.getUser().getUserName(), user.getSessionId(),
                        false, true, false, false, false, false);
                allowControlItem.setEnabled(false);
                stopControlItem.setEnabled(true);
            }
        });
        usersList.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        usersList.addMouseListener(new MouseAdapter() {

            @Override
            public void mousePressed(MouseEvent e) {
                if (e.isPopupTrigger()) {
                    int index = usersList.getSelectedIndex();
                    UserObject usr = (UserObject) listModel.elementAt(index);
                    allowControlItem.setEnabled(usr.isRaisedHand());
                    popup.show(usersList, e.getX(), e.getY());
                }
            }
        });
        nextSlideButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                if (slideIndex < slideCount - 1) {
                    slideIndex++;
                    client.requestNewSlide(user.getSiteRoot(), slideIndex, user.isPresenter(), user.getSessionId(), user.getUserName(), hasControl);
                }
            }
        });

        backSlideButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                if (slideIndex > 0) {
                    slideIndex--;
                    client.requestNewSlide(user.getSiteRoot(), slideIndex, user.isPresenter(), user.getSessionId(), user.getUserName(), hasControl);
                }
            }
        });
        firstSlideButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {

                slideIndex = 0;
                client.requestNewSlide(user.getSiteRoot(), slideIndex, user.isPresenter(), user.getSessionId(), user.getUserName(), hasControl);
            }
        });
        lastSlideButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {

                slideIndex = slideCount - 1;
                client.requestNewSlide(user.getSiteRoot(), slideIndex, user.isPresenter(), user.getSessionId(), user.getUserName(), hasControl);
            }
        });

    }

    /**
     * Returns the TCP client
     * @return TCPClient
     */
    public TCPClient getTCPClient() {
        return client;
    }

    /**
     * Diplays the chat room (frame)
     */
    public void showChatRoom() {
        if (!chatFrameShowing) {
            chatFrameShowing = true;
            chatFrame.setTitle(user.getFullName() + ": Chat Session id " + user.getSessionId());
            chatFrame.setAlwaysOnTop(true);
            chatFrame.add(chatRoom);
            chatFrame.setSize(400, 350);
            chatFrame.setLocationRelativeTo(null);
            chatFrame.setVisible(true);
        }
    }

    public int getSlideCount() {
        return slideCount;
    }

    private void setAgenda() {
        agendaListModel.clear();
        /*for (int i = 0; i < slideCount; i++) {
        agendaListModel.addElement("Item " + (i + 1));
        }*/

        outlineTree.clear();
        for (int i = 0; i < slideCount; i++) {
            outlineTree.addObject(null, "Item " + (i + 1), true);
        }
    }

    /**
     * Inform user of how many slides are present
     * @param slideCount
     */
    public void setSlideCount(int slideCount) {
        this.slideCount = slideCount;
        setAgenda();
        statusBar.setText("Detected " + this.slideCount + " slides");
    }

    /**
     * Makes the status bar display this message
     * @param txt
     */
    public void setStatusBarMessage(String txt) {
        statusBar.setText(txt);
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

    public String getSlideServerId() {
        return user.getSlideServerId();
    }

    public void checkSession() {
        final File f = new File(System.getProperty("user.home") + "/avoir-realtime-0.1/presentations/" + user.getSessionId());
        //first check if it exist in local cache, if so dont bother with downloads
        //unless the 'UseCache option is off, so it forces downloads every time

        if (f.exists() && f.list().length > 0) {
            if (RealtimeOptions.useCache()) {
                slideCount = f.list().length;
                setAgenda();
                client.requestNewSlide(user.getSiteRoot(), slideIndex, user.isPresenter(), user.getSessionId(), user.getUserName(), hasControl);
                return;
            }
        } else {
            //  System.out.println("Nothing in cache..requesting for slides");
            f.mkdirs();
            client.requestForSlides(user.getSessionId(), user.getSlideServerId(), user.getSlidesDir(), user.getUserName());
        }
    }

    public String getSiteRoot() {
        return user.getSiteRoot();
    }

    public boolean getControl() {
        return hasControl;
    }

    public void setConnecting(boolean state) {
        surface.setConnecting(state);
    }

    public void setConnectingString(String str) {
        surface.setConnectingString(str);
    }

    /**
     * Get this user
     * @return
     */
    public User getUser() {
        return user;
    }

    public void publish() {
        client.publish(user);
        surface.showMessage("Waiting for reply from slide server ...", false);
        surface.setStatusMessage("Waiting for reply from slide server ...");
        checkSession();

    }

    /**
     * Connects to the super node
     */
    public void connect() {
        Thread t = new Thread() {

            @Override
            public void run() {
                surface.setConnectingString("Connecting...This might take some" +
                        " few minutes, please wait...");
                surface.setConnecting(true);
                if (client.connect()) {
//                    MediaManager.detectJMF(client, resourcesPath, slideServerId,
                    //                          userName);
                    LibManager.detectJSpeex(client, resourcesPath, slideServerId, userName);
                    surface.setShowSplashScreen(false);
                    voiceOptionsButton.setEnabled(true);
                    sessionButton.setEnabled(true);
                    firstSlideButton.setEnabled(user.isPresenter());
                    backSlideButton.setEnabled(user.isPresenter());
                    nextSlideButton.setEnabled(user.isPresenter());
                    lastSlideButton.setEnabled(user.isPresenter());
                    yesButton.setEnabled(user.isPresenter());
                    noButton.setEnabled(user.isPresenter());
                    surface.setConnecting(false);
                    statusBar.setText("Connected");
                    connected = true;
                    client.publish(user);
                    // surface.showMessage("Waiting for reply from slide server ...", false);
                    surface.setStatusMessage("Waiting for reply from server ...");
                    checkSession();
                } else {
                    String msg = "Cannot Connect to the server.";
                    statusBar.setText(msg);
                    surface.setConnectingString(msg);
                    surface.setConnecting(false);
                    connected = false;
                //surface.setStatusMessage(msg);
                }
            }
        };
        t.start();
    }

    public Surface getSurface() {
        return surface;
    }

    @Override
    public void destroy() {
        if (client != null) {
            client.removeUser(user);
        }
    }

    public UserManager getUserManager() {
        return userManager;
    }

    /**
     * Updates the current slide and index to these values. The slide is picked from
     * the preloaded local cache
     * @param slide
     * @param slideIndex
     */
    public void setCurrentSlide(int slideIndex, boolean fromPresenter) {
        this.slideIndex = slideIndex;
        if (!isPresenter) {
            outlineTree.setSelectedRow(slideIndex + 1);
        }
        String slidePath = REALTIME_HOME + "/presentations/" + user.getSessionId() + "/img" + slideIndex + ".jpg";
        try {
            java.awt.Image img = javax.imageio.ImageIO.read(new File(slidePath));
            ImageIcon slide = new ImageIcon(img);
            surface.setCurrentSlide(slide, this.slideIndex + 1, slideCount, fromPresenter);
            if (DEBUG_ENGINE.debug_level > 3) {
                DEBUG_ENGINE.print(getClass(), "Setting current slide as " + slide);
            }
            surface.setStatusMessage(" ");
            statusBar.setText("Slide " + (this.slideIndex + 1) + " of " + slideCount);
            enableButtons(true);
        } catch (Exception ex) {
            ex.printStackTrace();
            surface.setStatusMessage("FATAL: Cannot find requested slide!");
        }
    }

    /**
     * Paint the status message on the slide...no longer used???
     * @param msg
     */
    public void setStatusMessage(String msg) {
        surface.setStatusMessage(msg);
    }

    /**
     * Starts or stops vote session
     * @param status if true starts vote session
     * @param privateVote if vote result is visible to all or not
     */
    public void setVoteSessionEnabled(boolean status, boolean privateVote) {
        //as long as voting is on, all hands should be down! :)
        handButton.setEnabled(false);
        yesButton.setEnabled(status);
        noButton.setEnabled(status);
        this.privateVote = privateVote;
    }

    public boolean isPresenter() {
        return user.isPresenter();
    }

    /**
     * Update the user lsit
     * @param list the complete user list ...normally send to a newly logged in user
     */
    public void updateUserList(Vector<User> list) {
        listModel.clear();
        for (int i = 0; i < list.size(); i++) {
            User usr = list.elementAt(i);
            userManager.addUser(usr, i);
        }
    }

    /**
     * Update date the status of this user
     * @param userName the user to be updated
     * @param raiseHand if has raised hand or not
     * @param allowControl if user has control to manipulate session or not
     * @param order the order in which the user raised the hand
     */
    public void updateUserList(String userName, boolean raiseHand, boolean allowControl,
            int order, boolean yes, boolean no, boolean isYesNoSession) {
        for (int i = 0; i < listModel.getSize(); i++) {

            UserObject usr = (UserObject) listModel.elementAt(i);
            if (usr.getUser().getUserName().equals(userName)) {
                userManager.setUser(usr.getUser(), i, raiseHand, allowControl, order, yes, no, isYesNoSession);
            //  break;
            }
        }
    }

    private final void enableButtons(boolean state) {

        chatButton.setEnabled(state);
        handButton.setEnabled(state);
        voiceOptionsButton.setEnabled(state);
        surveyButton.setEnabled(state);
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

    /**
     * Clears any votes
     */
    public void clearVote() {
        for (int i = 0; i < listModel.getSize(); i++) {
            UserObject usr = (UserObject) listModel.elementAt(i);
            userManager.setUser(usr.getUser(), i);
        }
        //ok, we can now play with our hands :)
        handButton.setEnabled(true);
    }

    public void showSurveyFrame(Vector<String> questions, String title) {
        surveyFrame.setQuestions(questions);
        surveyFrame.setTitle(title);
        surveyFrame.setSize(500, 400);
        surveyFrame.setLocationRelativeTo(null);
        // surveyFrame.setAlwaysOnTop(true);
        surveyFrame.setVisible(true);
    }

    /**
     * Refreshes screen with current settings 
     */
    public void refresh() {
        surface.repaint();
    }

    /**
     * Displays the options frame
     */
    public void showOptionsFrame(int tabIndex) {
        RealtimeOptionsFrame optionsFrame = new RealtimeOptionsFrame(this, tabIndex);
        optionsFrame.setSize(500, 500);
        optionsFrame.setLocationRelativeTo(null);
        optionsFrame.setVisible(true);

    }

    public SurveyManagerFrame getSurveyManager() {
        return surveyManagerFrame;
    }

    public void setControl(boolean control) {
        this.hasControl = control;
    }

    public void showSurveyManagerFrame() {
        surveyManagerFrame.setTitle("Survey Wizard  - Untitled");
        surveyManagerFrame.setSize(560, 500);
        surveyManagerFrame.setLocationRelativeTo(null);
        surveyManagerFrame.setVisible(true);

    }

    public String getSessionId() {
        return user.getSessionId();
    }

    /**
     * For user list. Users are added as a JPAnel
     */
    class CustomCellRenderer implements ListCellRenderer {

        public Component getListCellRendererComponent(JList list, Object value, int index,
                boolean isSelected, boolean cellHasFocus) {
            Component component = (Component) value;
            component.setBackground(isSelected ? Color.GRAY : Color.white);
            component.setForeground(isSelected ? Color.white : Color.GRAY);
            return component;
        }
    }

    /**
     * Our own button behavoir
     */
    class MButton extends JButton {

        public MButton(ImageIcon icon) {
            super(icon);
            setBorderPainted(false);
            setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
            setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
            setFont(new java.awt.Font("Dialog", 0, 9));
            setEnabled(false);
            this.addMouseListener(new  

                  MouseAdapter( ) {

                    @Override
                public void mouseEntered(MouseEvent e) {
                    setContentAreaFilled(true);
                }

                @Override
                public void mouseExited(MouseEvent e) {
                    setContentAreaFilled(false);
                }
            });
        }
    }

    /**
     * Our own button behavoir
     */
    class TButton extends JToggleButton {

        public TButton(ImageIcon icon) {
            super(icon);
            setBorderPainted(false);
            setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
            setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
            setFont(new java.awt.Font("Dialog", 0, 9));
            setEnabled(false);
            this.addMouseListener(new  

                  MouseAdapter( ) {

                    @Override
                public void mouseEntered(MouseEvent e) {
                    setContentAreaFilled(true);
                }

                @Override
                public void mouseExited(MouseEvent e) {
                    setContentAreaFilled(false);
                }
            });
        }
    }

    /** This method is called from within the init() method to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    // <editor-fold defaultstate="collapsed" desc="Generated Code">                          
    private void initComponents() {

        actionsBG = new javax.swing.ButtonGroup();
        splitPane = new javax.swing.JSplitPane();
        leftSplitPane = new javax.swing.JSplitPane();
        usersListSP = new javax.swing.JScrollPane();
        usersList = new javax.swing.JList();
        mediaPanel = new javax.swing.JPanel();
        tabbedPane = new javax.swing.JTabbedPane();
        mainToolBar = new javax.swing.JToolBar();
        slidesToolBar = new javax.swing.JToolBar();
        whiteboardToolbar = new javax.swing.JToolBar();
        handButton = new javax.swing.JToggleButton();
        yesButton = new javax.swing.JToggleButton();
        noButton = new javax.swing.JToggleButton();
        toolBar = new javax.swing.JToolBar();
        chatButton = new javax.swing.JButton();
        voiceOptionsButton = new javax.swing.JButton();
        refreshButton = new javax.swing.JButton();
        optionsButton = new javax.swing.JButton();
        statusBar = new javax.swing.JLabel();


        leftSplitPane.setOrientation(javax.swing.JSplitPane.VERTICAL_SPLIT);

        usersList.setModel(new javax 

               .swing.AbstractListModel() {

            String[] strings = {"Item 1", "Item 2", "Item 3", "Item 4", "Item 5"};

            public int getSize() {
                return strings.length;
            }

            public Object getElementAt(int i) {
                return strings[i];
            }
        });
        usersListSP.setViewportView(usersList);

        leftSplitPane.setRightComponent(usersListSP);

        mediaPanel.setBorder(javax.swing.BorderFactory.createTitledBorder("Media Streaming"));
        mediaPanel.setPreferredSize(new java.awt.Dimension(160, 120));
        mediaPanel.setLayout(new java.awt.BorderLayout());
        leftSplitPane.setTopComponent(mediaPanel);
        leftSplitPane.setRightComponent(tabbedPane);


        mainToolBar.setRollover(true);

        slidesToolBar.setRollover(true);
        slidesToolBar.setPreferredSize(new java.awt.Dimension(18, 25));
        mainToolBar.add(slidesToolBar);

        whiteboardToolbar.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        whiteboardToolbar.setRollover(true);

        handButton.setFont(new java.awt.Font("Dialog", 0, 9));
        handButton.setText("Hand");
        handButton.setToolTipText("Raise Hand");
        handButton.setBorderPainted(false);
        handButton.setFocusable(false);
        handButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        handButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        handButton.addActionListener(new java.awt 

              .event.ActionListener  
                () 
            
        
          {

            public  void actionPerformed(java.awt.event.ActionEvent evt) {
                handButtonActionPerformed(evt);
            }

                
                
            }
        );
        handButton.addMouseListener(
                
        new java.awt.event.MouseAdapter() {

                    @Override
                    public void mouseEntered(java.awt.event.MouseEvent evt) {
                        handButtonMouseEntered(evt);
                    }

                    @Override
                    public void mouseExited(java.awt.event.MouseEvent evt) {
                        handButtonMouseExited(evt);
                    }
                });
        whiteboardToolbar.add(handButton);

        yesButton.setFont(new java.awt.Font("Dialog", 0, 9));
        yesButton.setText("Yes");
        yesButton.setToolTipText("Say Yes");
        yesButton.setBorderPainted(false);
        yesButton.setEnabled(false);
        yesButton.setFocusable(false);
        yesButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        yesButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        yesButton.addActionListener(new java.awt 

              .event.ActionListener  
                () 
            
        
        
         {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                yesButtonActionPerformed(evt);
            }
        });
        whiteboardToolbar.add(yesButton);

        noButton.setFont(new java.awt.Font("Dialog", 0, 9));
        noButton.setText("No");
        noButton.setToolTipText("Say No");
        noButton.setBorderPainted(false);
        noButton.setEnabled(false);
        noButton.setFocusable(false);
        noButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        noButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        noButton.addActionListener(new java.awt 

              .event.ActionListener  
                () 
            
        
        
        
        {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                noButtonActionPerformed(evt);
            }
        });
        whiteboardToolbar.add(noButton);

        mainToolBar.add(whiteboardToolbar);

        toolBar.setRollover(true);
        toolBar.setPreferredSize(new java.awt.Dimension(66, 25));

        chatButton.setFont(new java.awt.Font("Dialog", 0, 9));
        chatButton.setText("Chat");
        chatButton.setBorderPainted(false);
        chatButton.setContentAreaFilled(false);
        chatButton.setFocusable(false);
        chatButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        chatButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        chatButton.addMouseListener(new java.awt 

              .event.MouseAdapter  
                () 
            

                {

                
            
        
          

            @ 
             Override  
                
            
        
        
         public void mouseEntered(java.awt.event.MouseEvent evt) {
                chatButtonMouseEntered(evt);
            }

            @Override
            public void mouseExited(java.awt.event.MouseEvent evt) {
                chatButtonMouseExited(evt);
            }
        });
        chatButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                chatButtonActionPerformed(evt);
            }
        });
        toolBar.add(chatButton);

        voiceOptionsButton.setFont(new java.awt.Font("Dialog", 0, 9));
        voiceOptionsButton.setText("Voice");
        voiceOptionsButton.setBorderPainted(false);
        voiceOptionsButton.setContentAreaFilled(false);
        voiceOptionsButton.setFocusable(false);
        voiceOptionsButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        voiceOptionsButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        voiceOptionsButton.addMouseListener(new java.awt 

              .event.MouseAdapter  
                () 
            

                {

                
            
        
          

            @ 
             Override  
                
            
        
        
         public void mouseEntered(java.awt.event.MouseEvent evt) {
                voiceOptionsButtonMouseEntered(evt);
            }

            @Override
            public void mouseExited(java.awt.event.MouseEvent evt) {
                voiceOptionsButtonMouseExited(evt);
            }
        });
        voiceOptionsButton.addActionListener(new java.awt.event.ActionListener() {

            public void actionPerformed(java.awt.event.ActionEvent evt) {
                voiceOptionsButtonActionPerformed(evt);
            }
        });
        toolBar.add(voiceOptionsButton);

        refreshButton.setFont(new java.awt.Font("Dialog", 0, 9));
        refreshButton.setText("Refresh");
        refreshButton.setToolTipText("Reload");
        refreshButton.setBorderPainted(false);
        refreshButton.setContentAreaFilled(false);
        refreshButton.setFocusable(false);
        refreshButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        refreshButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        refreshButton.addActionListener(new java.awt 

              .event.ActionListener  
                () 
            
        
          {

            public  void actionPerformed(java.awt.event.ActionEvent evt) {
                refreshButtonActionPerformed(evt);
            }

                
                
            }
        );
        refreshButton.addMouseListener(
                
        new java.awt.event.MouseAdapter() {

                    @Override
                    public void mouseEntered(java.awt.event.MouseEvent evt) {
                        refreshButtonMouseEntered(evt);
                    }

                    @Override
                    public void mouseExited(java.awt.event.MouseEvent evt) {
                        refreshButtonMouseExited(evt);
                    }
                });
        toolBar.add(refreshButton);

        optionsButton.setFont(new java.awt.Font("Dialog", 0, 9));
        optionsButton.setText("Config");
        optionsButton.setToolTipText("Options");
        optionsButton.setBorderPainted(false);
        optionsButton.setContentAreaFilled(false);
        optionsButton.setFocusable(false);
        optionsButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        optionsButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        optionsButton.addActionListener(new java.awt 

              .event.ActionListener  
                () 
            
        
          {

            public  void actionPerformed(java.awt.event.ActionEvent evt) {
                optionsButtonActionPerformed(evt);
            }

                
                
            }
        );
        optionsButton.addMouseListener(
                
        
         
        new java.awt.event.MouseAdapter() {

                    @Override
                    public void mouseEntered(java.awt.event.MouseEvent evt) {
                        optionsButtonMouseEntered(evt);
                    }

                    @Override
                    public void mouseExited(java.awt.event.MouseEvent evt) {
                        optionsButtonMouseExited(evt);
                    }
                });
        toolBar.add(optionsButton);

        mainToolBar.add(toolBar);

        add(mainToolBar, java.awt.BorderLayout.PAGE_START);

        statusBar.setText("Ready");
        statusBar.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        add(statusBar, java.awt.BorderLayout.PAGE_END);
    }// </editor-fold>                        

    private void optionsButtonActionPerformed(java.awt.event.ActionEvent evt) {
        showOptionsFrame(0);
    }

    private void optionsButtonMouseEntered(java.awt.event.MouseEvent evt) {
        optionsButton.setContentAreaFilled(true);
    }

    private void optionsButtonMouseExited(java.awt.event.MouseEvent evt) {
        optionsButton.setContentAreaFilled(false);
    }

    private void refreshButtonMouseEntered(java.awt.event.MouseEvent evt) {
        refreshButton.setContentAreaFilled(true);
    }

    private void refreshButtonMouseExited(java.awt.event.MouseEvent evt) {
        refreshButton.setContentAreaFilled(false);
    }

    private void refreshButtonActionPerformed(java.awt.event.ActionEvent evt) {

        refreshConnection();
    }

    public void refreshConnection() {
        client.removeUser(user);
        checkSession();
        connect();
    }

    private void chatButtonActionPerformed(java.awt.event.ActionEvent evt) {
        // TODO add your handling code here:
        if (connected) {
            showChatRoom();
        }
    }

    public void setSurvey(boolean status) {
        this.surveyOn = status;

        noButton.setEnabled(status);
        yesButton.setEnabled(status);
    }

    public void showAudioWizardFrame() {
        //File f = new File(REALTIME_HOME + "/conf/devicesdetected");
        //if (!f.exists()) {
        //MediaManager.setupJMF();
        // }
        //avoir.realtime.tcp.media.MediaWizardFrame frame =
        //      new avoir.realtime.tcp.media.MediaWizardFrame(client, sessionId);
        if (audioWizardFrame == null) {
            audioWizardFrame = new AudioWizardFrame(client, userName, sessionId,
                    slideServerId, resourcesPath);
            audioWizardFrame.setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
            audioWizardFrame.setSize(500, 500);
            audioWizardFrame.setLocationRelativeTo(null);
        }
        audioWizardFrame.setVisible(true);
    }

    private void handButtonMouseEntered(java.awt.event.MouseEvent evt) {
        // handButton.setContentAreaFilled(true);
    }

    private void handButtonMouseExited(java.awt.event.MouseEvent evt) {
        //handButton.setContentAreaFilled(false);
    }

    private void handButtonActionPerformed(java.awt.event.ActionEvent evt) {

        client.sendAttentionPacket(user.getUserName(), user.getSessionId(), handButton.isSelected(),
                false, false, false, false, false);
    }

    private void yesButtonActionPerformed(java.awt.event.ActionEvent evt) {
        if (noButton.isSelected()) {
            noButton.setSelected(false);
        }
        client.sendAttentionPacket(user.getUserName(), user.getSessionId(), handButton.isSelected(),
                false, yesButton.isSelected(), noButton.isSelected(), true, privateVote);

    }

    private void noButtonActionPerformed(java.awt.event.ActionEvent evt) {
        if (yesButton.isSelected()) {
            yesButton.setSelected(false);
        }
        client.sendAttentionPacket(user.getUserName(), user.getSessionId(), handButton.isSelected(),
                false, yesButton.isSelected(), noButton.isSelected(), true, privateVote);

    }

    private void voiceOptionsButtonActionPerformed(java.awt.event.ActionEvent evt) {
        showAudioWizardFrame();
    }

    private void voiceOptionsButtonMouseEntered(java.awt.event.MouseEvent evt) {
        voiceOptionsButton.setContentAreaFilled(true);
    // TODO add your handling code here:
    }

    private void voiceOptionsButtonMouseExited(java.awt.event.MouseEvent evt) {
        voiceOptionsButton.setContentAreaFilled(false);
    }

    private void chatButtonMouseEntered(java.awt.event.MouseEvent evt) {
        chatButton.setContentAreaFilled(true);
    }

    private void chatButtonMouseExited(java.awt.event.MouseEvent evt) {
        chatButton.setContentAreaFilled(false);
    }

    // Variables declaration - do not modify                     
    private javax.swing.ButtonGroup actionsBG;
    private javax.swing.JButton chatButton;
    private javax.swing.JToggleButton handButton;
    private javax.swing.JSplitPane leftSplitPane;
    private javax.swing.JToolBar mainToolBar;
    private javax.swing.JPanel mediaPanel;
    private javax.swing.JToggleButton noButton;
    private javax.swing.JButton optionsButton;
    private javax.swing.JButton refreshButton;
    private javax.swing.JToolBar slidesToolBar;
    private javax.swing.JSplitPane splitPane;
    private javax.swing.JLabel statusBar;
    private javax.swing.JTabbedPane tabbedPane;
    private javax.swing.JToolBar toolBar;
    private javax.swing.JList usersList;
    private javax.swing.JScrollPane usersListSP;
    private javax.swing.JButton voiceOptionsButton;
    private javax.swing.JToolBar whiteboardToolbar;
    private javax.swing.JToggleButton yesButton;
    // End of variables declaration                   
}
