/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * MainFrame.java
 *
 * Created on 2009/03/21, 04:16:43
 */
package org.avoir.realtime.gui.main;

import chrriis.dj.nativeswing.swtimpl.NativeInterface;
import java.awt.event.FocusEvent;
import javax.swing.JProgressBar;
import chrriis.dj.nativeswing.swtimpl.components.JWebBrowser;
import com.l2fprod.common.swing.JOutlookBar;
import com.l2fprod.common.swing.JTaskPane;
import com.l2fprod.common.swing.JTaskPaneGroup;
//import com.l2fprod.common.swing.
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.GridLayout;
import java.awt.Rectangle;
import java.awt.Robot;
import java.awt.Toolkit;
import java.awt.event.FocusListener;
import java.awt.image.BufferedImage;
import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Timer;
import javax.imageio.ImageIO;
import javax.swing.ImageIcon;
import javax.swing.JDesktopPane;
import javax.swing.JFileChooser;
import javax.swing.JFrame;
import javax.swing.JInternalFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JSplitPane;
import javax.swing.JTabbedPane;
import javax.swing.JTextArea;
import javax.swing.JToolBar;
import javax.swing.SwingUtilities;
import javax.swing.event.ChangeEvent;
import javax.swing.event.ChangeListener;
import javax.swing.event.InternalFrameEvent;
import javax.swing.event.InternalFrameListener;
import javax.swing.text.Document;
import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.RealtimeFile;
import org.avoir.realtime.common.filetransfer.FileManager;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.gui.whiteboard.WhiteboardPanel;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.gui.PointerListPanel;
import org.avoir.realtime.gui.PresentationFilter;
import org.avoir.realtime.gui.QuestionNavigator;
import org.avoir.realtime.gui.RealtimeFileChooser;
import org.avoir.realtime.gui.RoomResourceNavigator;
import org.avoir.realtime.gui.RoomToolsPanel;
import org.avoir.realtime.gui.SlidesNavigator;
import org.avoir.realtime.gui.WebpresentNavigator;
import org.avoir.realtime.gui.whiteboard.WhiteboardToolsPanel;
import org.avoir.realtime.gui.room.CreateRoomDialog;
import org.avoir.realtime.gui.room.InviteParticipants;
import org.avoir.realtime.gui.room.RoomMemberListFrame;
import org.avoir.realtime.gui.userlist.ParticipantListTree;
import org.avoir.realtime.gui.webbrowser.WebBrowserManager;
import org.avoir.realtime.gui.userlist.UserListFrame;
import org.avoir.realtime.net.RPacketListener;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.avoir.realtime.notepad.JNotepad;

import org.jivesoftware.smack.util.Base64;

/**
 *
 * @author developer
 */
public class MainFrame extends javax.swing.JFrame {

    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    //private ParticipantListPanel userListPanel;
    private WhiteboardPanel whiteboardPanel;
    private RoomToolsPanel roomToolsPanel;
    private ChatRoomManager chatRoomManager;
    private String defaultRoomName = "No Room";
    private ImageIcon chatIcon = ImageUtil.createImageIcon(this, "/images/chat_on.gif");
    private ImageIcon delIcon = ImageUtil.createImageIcon(this, "/images/delete.gif");
    private ImageIcon logo = ImageUtil.createImageIcon(this, "/images/intro_logo.jpg");
    private SlidesNavigator slidesNavigator;
    private QuestionNavigator questionsNavigator;
    private RealtimeFileChooser realtimeFileChooser = new RealtimeFileChooser("images");
    private int currentRoomIndex = 0;
    private int notepadCount = 0;
    private UserListFrame userListFrame;
    private boolean expand = false;
    private WebBrowserManager webbrowserManager;
    private JTabbedPane toolBarTabbedPane = new JTabbedPane();
    private boolean slidesPopulated = false;
    private boolean questionsPopulated = false;
    //private JWebBrowser generalWebBrowser = new JWebBrowser();
    private Timer tabTimer = new Timer();
    //private DefaultListModel roomResourceModel = new DefaultListModel();
    //private JList roomResourceList = new JList(roomResourceModel);
    private RoomResourceNavigator roomResourceNavigator = new RoomResourceNavigator();
    private JSplitPane slidesSplitPane = new JSplitPane(JSplitPane.VERTICAL_SPLIT);
    private WebpresentNavigator webPresentNavigator;
    private ArrayList<Speaker> speakers = new ArrayList<Speaker>();
    int speakerRows = 2;
    int speakerCols = 2;
    private JPanel speakersPanel = new JPanel(new GridLayout(speakerRows, speakerCols));
    private JFileChooser presentationFC = new JFileChooser();
    private SlideScroller slideScroller = new SlideScroller();
    private JTabbedPane scrollerTabbedPane = new JTabbedPane();
    private JTaskPane taskPane = new JTaskPane();
    private JTaskPane chatRoomTaskPane = new JTaskPane();
    private JDesktopPane desktop = new JDesktopPane();
    private JTaskPane slideScrollerPane = new JTaskPane();
    private JTaskPaneGroup participantsPanel = new JTaskPaneGroup();
    private JTaskPaneGroup toolsPanel = new JTaskPaneGroup();
    private JTaskPaneGroup wbToolsPanel = new JTaskPaneGroup();
    private JTaskPaneGroup roomResourcesPanel = new JTaskPaneGroup();
    private JTaskPaneGroup chatRoomPanel = new JTaskPaneGroup();
    private JTaskPaneGroup audioVideoPanel = new JTaskPaneGroup();
    private JTaskPaneGroup slidesScrollerPanel = new JTaskPaneGroup();
    private JTaskPaneGroup resourcesPanel = new JTaskPaneGroup();
    private ParticipantListTree participantListTree;
    private WhiteboardToolsPanel whiteboardToolsPanel;
    private JTabbedPane roomResourcesTabbedPane = new JTabbedPane();

    public static void main(String[] args) {
        StandAloneManager.isAdmin = true;
        ConnectionManager.audioVideoUrlReady = true;
        ConnectionManager.flashUrlReady = true;
        NativeInterface.open();
        String audioVideoUrl = "localhost:7070/red5";
        if (ConnectionManager.init(args[0], Integer.parseInt(args[1]), args[2])) {
            String roomName = "admin";
            String username = "admin";
            if (ConnectionManager.login(username, "admin", roomName)) {
                MainFrame fr = new MainFrame(roomName);
                fr.setTitle(username + "@" + roomName + ": Realtime Virtual Classroom");
                fr.setSize(Toolkit.getDefaultToolkit().getScreenSize());
                fr.setVisible(true);

            } else {
                System.out.println("cant login");
            }
        } else {
            System.out.println("cant connect to server");
        }

    }

    /** Creates new form MainFrame */
    public MainFrame(String roomName) {

        initComponents();
        GUIAccessManager.setMf(this);
        chatRoomManager = new ChatRoomManager(ConnectionManager.getRoomName());
        roomToolsPanel = new RoomToolsPanel();
        mainSplitPane.setDividerLocation((ss.width / 4));
        mainSplitPane.setLeftComponent(taskPane);
        desktop.setBackground(Color.WHITE);
        desktop.setDragMode(JDesktopPane.OUTLINE_DRAG_MODE);

        JInternalFrame fr = new JInternalFrame("Room: " + roomName);
        fr.setSize(800, 600);
        fr.addInternalFrameListener(new InternalFrameListener() {

            public void internalFrameOpened(InternalFrameEvent e) {
            }

            public void internalFrameClosing(InternalFrameEvent e) {
            }

            public void internalFrameClosed(InternalFrameEvent e) {
            }

            public void internalFrameIconified(InternalFrameEvent e) {
            }

            public void internalFrameDeiconified(InternalFrameEvent e) {
            }

            public void internalFrameActivated(InternalFrameEvent e) {
                chatRoomPanel.setExpanded(false);
            }

            public void internalFrameDeactivated(InternalFrameEvent e) {
                chatRoomPanel.setExpanded(true);
            }
        });
        fr.setLocation(0, 80);
        fr.setContentPane(surfacePanel);
        fr.setMaximizable(true);
        fr.setResizable(true);
        fr.setIconifiable(true);
        fr.setVisible(true);
        desktop.add(fr);

        mainSplitPane.setRightComponent(desktop);
        whiteboardPanel = new WhiteboardPanel();

        tabbedPane.addTab("Whiteboard", whiteboardPanel);
        tabbedPane.add("Speakers", speakersPanel);
        slidesNavigator = new SlidesNavigator(this);
        questionsNavigator = new QuestionNavigator(this);
        add(statusBar, BorderLayout.SOUTH);
        taskPane.setName("leftPanelTaskPane");
        chatRoomTaskPane.setName("chatRoomTaskPane");
        participantListTree = new ParticipantListTree();
        participantListTree.setPreferredSize(new Dimension(ss.width / 6, ss.height / 4));
        participantsPanel.setTitle("Participants");
        participantsPanel.add(participantListTree);
        taskPane.add(participantsPanel);

        toolsPanel.setTitle("Room Tools");
        toolsPanel.setName("toolsPanel");

        toolsPanel.add(roomToolsPanel);
        taskPane.add(toolsPanel);


        wbToolsPanel.setTitle("Whiteboard Tools");
        whiteboardToolsPanel = new WhiteboardToolsPanel();
        wbToolsPanel.add(whiteboardToolsPanel);
        taskPane.add(wbToolsPanel);

        resourcesPanel.setTitle("Room Resources");
        resourcesPanel.setExpanded(false);
        webPresentNavigator = new WebpresentNavigator();
        JTextArea inf = new JTextArea("Help");
        inf.setEnabled(false);
        roomResourcesTabbedPane.addTab("Info", new JScrollPane(inf));
        roomResourcesTabbedPane.addTab("Slides", webPresentNavigator);
        roomResourcesTabbedPane.addChangeListener(new ChangeListener() {

            public void stateChanged(ChangeEvent e) {
                if (roomResourcesTabbedPane.getSelectedIndex() == 1) {
                    if (!slidesPopulated) {
                        webPresentNavigator.populateWithRoomResources();

                        slidesPopulated =
                                true;
                        slideScroller.setStartIndex(0);
                        int slideCount = resetSlideCount();
                        slideScroller.setEndIndex(slideCount > 6 ? 6 : slideCount);
                        slideScroller.refresh();

                        adjustSize();

                    }
                }
            }
        });
        resourcesPanel.add(roomResourcesTabbedPane);
        taskPane.add(resourcesPanel);

        chatRoomPanel.setTitle("Chat Room");
        chatRoomManager.getChatRoom().setSize(new Dimension(ss.width / 6, ss.height / 3));

        chatRoomPanel.add(chatRoomManager.getChatRoom());

        chatRoomTaskPane.add(chatRoomPanel);
        audioVideoPanel.setTitle("Audio Video");
        chatRoomTaskPane.add(audioVideoPanel);
        chatRoomManager.getChatRoom().setOpaque(false);
        chatRoomTaskPane.setOpaque(false);
        audioVideoPanel.setOpaque(false);
        chatRoomPanel.setOpaque(false);


        JInternalFrame control = new JInternalFrame("Chat Room", true, false, true);
        control.setSize(ss.width / 4, (ss.height / 4) * 2);
        control.setContentPane(chatRoomTaskPane);
        control.setName("chatRoom");
        control.setOpaque(false);
        control.setVisible(true);
        control.setIconifiable(true);
        desktop.add(control, new Integer(2));
        control.setLocation((fr.getWidth() / 4) * 3, (fr.getHeight() / 8));
        try {
            control.setSelected(true);
            control.toFront();
            fr.toBack();
        } catch (Exception ex) {
            ex.printStackTrace();
        }



        slidesSplitPane.setOneTouchExpandable(true);
        tabbedPane.addChangeListener(new ChangeListener() {

            public void stateChanged(ChangeEvent e) {
                if (GeneralUtil.isInstructor()) {
                    RealtimePacket p = new RealtimePacket(RealtimePacket.Mode.CHANGE_TAB);
                    StringBuilder sb = new StringBuilder();
                    sb.append("<index>").append(tabbedPane.getSelectedIndex()).append("</index>");
                    sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
                    p.setContent(sb.toString());
                    ConnectionManager.sendPacket(p);
                }
            }
        });
        whiteboardPanel.getWhiteboard().setDefaultRoom(true);
        for (int i = 0; i < speakerCols; i++) {
            for (int j = 0; j < speakerRows; j++) {
                String speakerName = "free";
                JWebBrowser browser = new JWebBrowser();
                browser.setMenuBarVisible(false);
                browser.setBarsVisible(false);
                browser.setButtonBarVisible(false);
                Speaker speaker = new Speaker(browser, speakerName);
                speakers.add(speaker);
//                speakersPanel.add(browser);

            }
        }
        presentationFC.addChoosableFileFilter(new PresentationFilter());
        presentationFC.setMultiSelectionEnabled(true);

        doInitRoomJoin(roomName);
//        userListPanel.getStartAudioVideoButton().setEnabled(!ConnectionManager.useEC2);
        displayAvator();

    }

    public boolean chatRoomShowing() {
        boolean showing = false;
        JInternalFrame[] frs = desktop.getAllFrames();
        for (JInternalFrame fr : frs) {
            if (fr.getName().equals("chatRoom")) {
                showing = fr.isSelected();
            }
        }
        return showing;
    }

    public void doGUIAccess() {

        for (int i = 0; i < roomResourcesTabbedPane.getTabCount(); i++) {
            roomResourcesTabbedPane.setEnabledAt(i, GeneralUtil.isMyRoom());
        }
    }

    public WhiteboardToolsPanel getWhiteboardToolsPanel() {
        return whiteboardToolsPanel;
    }

    public ParticipantListTree getParticipantListTree() {
        return participantListTree;
    }

    public boolean addSpeaker(final String url, String speakerName) {
        //speakerName = new String(Base64.decode(speakerName));
        int index = 0;
        //first, check same guy already there, if so, simply update instead
        //of creating new window

        for (Speaker speaker : speakers) {
            if (speaker.getSpeaker().equals(speakerName)) {
                final JWebBrowser browser = speaker.getWebBrowser();
                SwingUtilities.invokeLater(new Runnable() {

                    public void run() {
                        browser.navigate(url);
                    }
                });
                tabbedPane.setSelectedIndex(2);
                speaker.setSpeaker(speakerName);
                speakers.set(index, speaker);
//                userListPanel.getUserListTree().setUserHasMIC(speakerName, speakerName, true);
                return true;
            }
        }
        //if we come here, which we often will, means this is a new user
        for (Speaker speaker : speakers) {

            if (speaker.getSpeaker().equals("free")) {
                final JWebBrowser browser = speaker.getWebBrowser();
                SwingUtilities.invokeLater(new Runnable() {

                    public void run() {
                        browser.navigate(url);
                    }
                });
                tabbedPane.setSelectedIndex(2);
                speaker.setSpeaker(speakerName);
                speakers.set(index, speaker);
//                userListPanel.getUserListTree().setUserHasMIC(speakerName, speakerName, true);
                return true;
            }
            index++;
        }
        return false;
    }

    public void removeSpeaker(final String speakerName) {
        //final String speakerName = new String(Base64.decode(xspeakerName));
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                for (Speaker speaker : speakers) {
                    //     System.out.println("testing " + speakerName + " against " + speaker.getSpeaker());
                    if (speaker.getSpeaker().equals(speakerName)) {
                        //  System.out.println("Match");

                        final JWebBrowser browser = speaker.getWebBrowser();
                        SwingUtilities.invokeLater(new Runnable() {

                            public void run() {
                                browser.setHTMLContent("<html>Free<html>");
                            }
                        });
                        speaker.setSpeaker("free");
                        // System.out.println("#########removing " + speakerName);
//                        userListPanel.getUserListTree().setUserHasMIC(speakerName, speakerName, false);
                        break;
                    } else {
                        // System.out.println("no match");
                    }
                }
            }
        });
    }

    class Speaker {

        private JWebBrowser webBrowser;
        private String speaker;

        public Speaker(JWebBrowser webBrowser, String speaker) {
            this.webBrowser = webBrowser;
            this.speaker = speaker;
        }

        public String getSpeaker() {
            return speaker;
        }

        public void setSpeaker(String speaker) {
            this.speaker = speaker;
        }

        public JWebBrowser getWebBrowser() {
            return webBrowser;
        }

        public void setWebBrowser(JWebBrowser webBrowser) {
            this.webBrowser = webBrowser;
        }
    }

    public void updateURL(final String url) {
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                String decodedUrl = new String(Base64.decode(url));

                if (!decodedUrl.equals("null")) {
//                    generalWebBrowser.navigate(decodedUrl);
                }

            }
        });
    }

    public void setWebBrowserEnabled(final boolean isInstructor) {
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                /*                generalWebBrowser.setBarsVisible(isInstructor);
                generalWebBrowser.setButtonBarVisible(isInstructor);
                generalWebBrowser.setMenuBarVisible(false);
                generalWebBrowser.setLocationBarVisible(isInstructor);
                generalWebBrowser.setEnabled(isInstructor);
                 */            }
        });
    }

    public void insertPresentation() {

        if (presentationFC.showOpenDialog(MainFrame.this) == JFileChooser.APPROVE_OPTION) {
            final File[] selectedFiles = presentationFC.getSelectedFiles();
            for (final File file : selectedFiles) {
                FileManager.transferFile(file.getAbsolutePath(), "slideshows", "jodconvert");
            }
        }
    }

    private void displayAvator() {
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                ConnectionManager.displayAvatar();
            }
        });
    }

    private void doInitRoomJoin(final String roomName) {

        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                chatRoomManager.doActualJoin(roomName, true);
            }
        });
    }

    public SlideScroller getSlideScroller() {
        return slideScroller;
    }

    public JTabbedPane getTabbedPane() {
        return tabbedPane;
    }

    public JLabel getWbInfoField() {
        return wbInfoField;
    }

    public JProgressBar getWbProgressBar() {
        return wbProgressBar;
    }

    /*    public JWebBrowser getGeneralWebBrowser() {
    return generalWebBrowser;
    }
     */
    public int resetSlideCount() {
        /*
        int slideCount = 0;
        String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
        String files[] = new File(resourceDir + "/" + WebpresentNavigator.selectedPresentation.replaceAll("\n", "")).list();
        if (files != null) {
            slideCount = 0;
            for (int i = 0; i < files.length; i++) {

                if (!files[i].endsWith(".tr") && !files[i].endsWith(".txt")) {
                    slideCount++;

                }
            }
        }*/
        return 0;
    }

    public void showInstructorToolbar() {
        toolBarTabbedPane.addTab("Toolbar", instToolbar);
        toolBarTabbedPane.addTab("Whiteboard Toolbox", whiteboardPanel.getWbToolbar());
        toolBarTabbedPane.addChangeListener(new ChangeListener() {

            public void stateChanged(ChangeEvent e) {
                if (toolBarTabbedPane.getSelectedIndex() == 1) {
                    tabbedPane.setSelectedIndex(0);
                }

            }
        });
        /*        for (int i = 2; i <
        userListPanel.getUserTabbedPane().getTabCount(); i++) {
        userListPanel.getUserTabbedPane().removeTabAt(i);
        }
         */
        JTabbedPane tp = new JTabbedPane();


        /*        userListPanel.getUserTabbedPane().addTab("Slides", webPresentNavigator);
        userListPanel.getUserTabbedPane().addChangeListener(new ChangeListener() {

        public void stateChanged(ChangeEvent e) {
        if (userListPanel.getUserTabbedPane().getSelectedIndex() == 2) {
        if (!slidesPopulated) {
        webPresentNavigator.populateWithRoomResources();
        slidesPopulated =
        true;
        slideScroller.setStartIndex(0);
        int slideCount = resetSlideCount();
        slideScroller.setEndIndex(slideCount > 6 ? 6 : slideCount);
        slideScroller.refresh();

        adjustSize();

        }
        }
        }
        });
         * */
        //surfacePanel.add(slideScroller, BorderLayout.EAST);
        adjustSize();
    /* } else {
    slidesSplitPane.setTopComponent(slidesNavigator);
    slidesSplitPane.setDividerLocation(180);
    // userListPanel.getUserTabbedPane().setFont(new Font("Dialog", 0, 12));

    Color bg = new Color(244, 247, 203);
    //sp.getViewport().setBackground(bg);
    //roomResourceList.setBackground(bg);
    JPanel p = new JPanel(new BorderLayout());
    p.add(roomResourceNavigator, BorderLayout.CENTER);
    tp.addTab("Room Resources", p);
    slidesSplitPane.setBottomComponent(tp);
    slidesSplitPane.setDividerSize(3);
    userListPanel.getUserTabbedPane().addTab("Slides", roomResourceNavigator);
    userListPanel.getUserTabbedPane().addTab("Questions", questionsNavigator);
    userListPanel.getUserTabbedPane().addChangeListener(new ChangeListener() {

    public void stateChanged(ChangeEvent e) {
    if (userListPanel.getUserTabbedPane().getSelectedIndex() == 2) {
    if (!slidesPopulated) {
    roomResourceNavigator.populateWithRoomResources();
    slidesPopulated =
    true;
    adjustSize();

    }


    }
    if (userListPanel.getUserTabbedPane().getSelectedIndex() == 3) {
    if (!questionsPopulated) {
    questionsNavigator.populateNodes("questions");
    questionsPopulated =
    true;
    }

    }
    }
    });
    slidesSplitPane.repaint();
    adjustSize();

    }*/


    }

    public RoomResourceNavigator getRoomResourceNavigator() {
        return roomResourceNavigator;
    }

    public WebpresentNavigator getWebPresentNavigator() {
        return webPresentNavigator;
    }

    public void adjustSize() {
        int w = getWidth();
        int h = getHeight();
        setSize(w + 1, h + 1);
        setSize(w - 1, h - 1);
    }

    public void showParticipantToolbar() {
        toolBarTabbedPane.addTab("Toolbar", partiToolbar);

        adjustSize();

    }

    public RoomToolsPanel getRoomToolsPanel() {
        return roomToolsPanel;
    }

    public void setRoomToolsPanel(RoomToolsPanel roomToolsPanel) {
        this.roomToolsPanel = roomToolsPanel;
    }

    public void stopAnimateTabTitle(int index) {
        if (tabTimer != null) {
            tabTimer.cancel();
        }

    }

    public void animateTabTitle(JTabbedPane tab, int index) {
        if (tabTimer != null) {
            tabTimer.cancel();
        }

        tabTimer = new Timer();
        tabTimer.scheduleAtFixedRate(new TabTitleAnimator(index, tab, tab.getBackground()), 0, 1000);
    }

    public ImageIcon getLogo() {
        return logo;
    }

    public int getCurrentRoomIndex() {
        return currentRoomIndex;
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        titlePanel = new javax.swing.JPanel();
        jScrollPane1 = new javax.swing.JScrollPane();
        titleField = new javax.swing.JTextArea();
        instToolbar = new javax.swing.JToolBar();
        newRoomButton = new javax.swing.JButton();
        pointerButton = new javax.swing.JButton();
        deskShareButton = new javax.swing.JButton();
        imagesButton = new javax.swing.JButton();
        notepadButton = new javax.swing.JButton();
        wbButtonGroup = new javax.swing.ButtonGroup();
        partiToolbar = new javax.swing.JToolBar();
        notepadButton1 = new javax.swing.JButton();
        topPanel = new javax.swing.JPanel();
        statusBar = new javax.swing.JPanel();
        jLabel1 = new javax.swing.JLabel();
        wbProgressBar = new javax.swing.JProgressBar();
        wbInfoField = new javax.swing.JLabel();
        surfacePanel = new javax.swing.JPanel();
        tabbedPane = new javax.swing.JTabbedPane();
        mainSplitPane = new javax.swing.JSplitPane();
        screenShareItem = new javax.swing.JMenuBar();
        fileMenutem = new javax.swing.JMenu();
        exitMenuItem = new javax.swing.JMenuItem();
        actionsMenu = new javax.swing.JMenu();
        insertGraphicMenuItem = new javax.swing.JMenuItem();
        insertPresentationMenuItem = new javax.swing.JMenuItem();
        updateRoomResourcesMenuItem = new javax.swing.JMenuItem();
        jSeparator7 = new javax.swing.JSeparator();
        requestMicMenuItem = new javax.swing.JMenuItem();
        meetingsMenuItem = new javax.swing.JMenu();
        inviteMenuItem = new javax.swing.JMenuItem();
        invitationLinkMenuItem = new javax.swing.JMenuItem();
        jSeparator4 = new javax.swing.JSeparator();
        roomListMenuItem = new javax.swing.JMenuItem();
        createRoomMenuItem = new javax.swing.JMenuItem();
        addRoomMembersMenuItem = new javax.swing.JMenuItem();
        joinRoomMenuItem = new javax.swing.JMenuItem();
        jSeparator5 = new javax.swing.JSeparator();
        banUserMenuItem = new javax.swing.JMenuItem();
        toolsMenu = new javax.swing.JMenu();
        screenShareMenuItem = new javax.swing.JMenuItem();
        screenViewerMenuItem = new javax.swing.JMenuItem();
        jSeparator2 = new javax.swing.JSeparator();
        jMenu1 = new javax.swing.JMenu();
        thisAppMenuItem = new javax.swing.JMenuItem();
        desktopMenuItem = new javax.swing.JMenuItem();
        jSeparator3 = new javax.swing.JSeparator();
        privateChatMenuItem = new javax.swing.JMenuItem();
        jSeparator1 = new javax.swing.JSeparator();
        optionsMenuItem = new javax.swing.JMenuItem();
        helpMenu = new javax.swing.JMenu();
        aboutMenuItem = new javax.swing.JMenuItem();

        titlePanel.setLayout(new java.awt.BorderLayout());

        titleField.setColumns(20);
        titleField.setEditable(false);
        titleField.setFont(new java.awt.Font("Dialog", 1, 24));
        titleField.setForeground(new java.awt.Color(244, 214, 117));
        titleField.setRows(2);
        titleField.setText("Realtime Classroom");
        jScrollPane1.setViewportView(titleField);

        titlePanel.add(jScrollPane1, java.awt.BorderLayout.CENTER);

        instToolbar.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        instToolbar.setRollover(true);

        newRoomButton.setFont(new java.awt.Font("Dialog", 0, 11));
        newRoomButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/virtualroom.png"))); // NOI18N
        newRoomButton.setText("New Room");
        newRoomButton.setBorderPainted(false);
        newRoomButton.setContentAreaFilled(false);
        newRoomButton.setFocusable(false);
        newRoomButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        newRoomButton.setName("newRoom"); // NOI18N
        newRoomButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        newRoomButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                newRoomButtonActionPerformed(evt);
            }
        });
        newRoomButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                newRoomButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                newRoomButtonMouseExited(evt);
            }
        });
        instToolbar.add(newRoomButton);

        pointerButton.setFont(new java.awt.Font("Dialog", 0, 11));
        pointerButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/arrow_side32.png"))); // NOI18N
        pointerButton.setText("Pointer");
        pointerButton.setBorderPainted(false);
        pointerButton.setContentAreaFilled(false);
        pointerButton.setFocusable(false);
        pointerButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        pointerButton.setName("pointer"); // NOI18N
        pointerButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        pointerButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                pointerButtonActionPerformed(evt);
            }
        });
        pointerButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                pointerButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                pointerButtonMouseExited(evt);
            }
        });
        instToolbar.add(pointerButton);

        deskShareButton.setFont(new java.awt.Font("Dialog", 0, 11));
        deskShareButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/desktopsharing.png"))); // NOI18N
        deskShareButton.setText("Desktop Share");
        deskShareButton.setBorderPainted(false);
        deskShareButton.setContentAreaFilled(false);
        deskShareButton.setFocusable(false);
        deskShareButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        deskShareButton.setName("deskShare"); // NOI18N
        deskShareButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        deskShareButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                deskShareButtonActionPerformed(evt);
            }
        });
        deskShareButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                deskShareButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                deskShareButtonMouseExited(evt);
            }
        });
        instToolbar.add(deskShareButton);

        imagesButton.setFont(new java.awt.Font("Dialog", 0, 11));
        imagesButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/image.png"))); // NOI18N
        imagesButton.setText("Graphics");
        imagesButton.setBorderPainted(false);
        imagesButton.setContentAreaFilled(false);
        imagesButton.setFocusable(false);
        imagesButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        imagesButton.setName("images"); // NOI18N
        imagesButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        imagesButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                imagesButtonActionPerformed(evt);
            }
        });
        imagesButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                imagesButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                imagesButtonMouseExited(evt);
            }
        });
        instToolbar.add(imagesButton);

        notepadButton.setFont(new java.awt.Font("Dialog", 0, 11));
        notepadButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/kedit32.png"))); // NOI18N
        notepadButton.setText("Notepad");
        notepadButton.setBorderPainted(false);
        notepadButton.setContentAreaFilled(false);
        notepadButton.setFocusable(false);
        notepadButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        notepadButton.setName("notepad"); // NOI18N
        notepadButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        notepadButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                notepadButtonActionPerformed(evt);
            }
        });
        notepadButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                notepadButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                notepadButtonMouseExited(evt);
            }
        });
        instToolbar.add(notepadButton);

        partiToolbar.setRollover(true);

        notepadButton1.setFont(new java.awt.Font("Dialog", 0, 11));
        notepadButton1.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/kedit32.png"))); // NOI18N
        notepadButton1.setText("Notepad");
        notepadButton1.setBorderPainted(false);
        notepadButton1.setContentAreaFilled(false);
        notepadButton1.setFocusable(false);
        notepadButton1.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        notepadButton1.setName("notepad"); // NOI18N
        notepadButton1.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        notepadButton1.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                notepadButton1MouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                notepadButton1MouseExited(evt);
            }
        });
        notepadButton1.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                notepadButton1ActionPerformed(evt);
            }
        });
        partiToolbar.add(notepadButton1);

        topPanel.setLayout(new java.awt.BorderLayout());

        statusBar.setLayout(new java.awt.GridLayout(1, 3));

        jLabel1.setText("Ready");
        jLabel1.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        statusBar.add(jLabel1);
        statusBar.add(wbProgressBar);

        wbInfoField.setText("Ready");
        wbInfoField.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        statusBar.add(wbInfoField);

        surfacePanel.setLayout(new java.awt.BorderLayout());
        surfacePanel.add(tabbedPane, java.awt.BorderLayout.CENTER);

        setDefaultCloseOperation(javax.swing.WindowConstants.DO_NOTHING_ON_CLOSE);
        setTitle("Realtime Virtual Classroom");
        addWindowListener(new java.awt.event.WindowAdapter() {
            public void windowClosing(java.awt.event.WindowEvent evt) {
                formWindowClosing(evt);
            }
        });

        mainSplitPane.setDividerSize(5);
        getContentPane().add(mainSplitPane, java.awt.BorderLayout.CENTER);

        screenShareItem.setName("mainMenubar"); // NOI18N

        fileMenutem.setText("File");

        exitMenuItem.setText("Exit");
        exitMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                exitMenuItemActionPerformed(evt);
            }
        });
        fileMenutem.add(exitMenuItem);

        screenShareItem.add(fileMenutem);

        actionsMenu.setText("Actions");
        actionsMenu.setName(""); // NOI18N

        insertGraphicMenuItem.setText("Insert Graphic");
        insertGraphicMenuItem.setName("insertGraphic"); // NOI18N
        insertGraphicMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                insertGraphicMenuItemActionPerformed(evt);
            }
        });
        actionsMenu.add(insertGraphicMenuItem);

        insertPresentationMenuItem.setText("Insert Presentation");
        insertPresentationMenuItem.setName("insertPresentation"); // NOI18N
        insertPresentationMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                insertPresentationMenuItemActionPerformed(evt);
            }
        });
        actionsMenu.add(insertPresentationMenuItem);

        updateRoomResourcesMenuItem.setText("Room Resources");
        updateRoomResourcesMenuItem.setName("roomResources"); // NOI18N
        updateRoomResourcesMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                updateRoomResourcesMenuItemActionPerformed(evt);
            }
        });
        actionsMenu.add(updateRoomResourcesMenuItem);
        actionsMenu.add(jSeparator7);

        requestMicMenuItem.setText("Request MIC");
        requestMicMenuItem.setName("requestMIC"); // NOI18N
        requestMicMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                requestMicMenuItemActionPerformed(evt);
            }
        });
        actionsMenu.add(requestMicMenuItem);

        screenShareItem.add(actionsMenu);

        meetingsMenuItem.setText("Meetings");

        inviteMenuItem.setText("Invite Users");
        inviteMenuItem.setEnabled(false);
        inviteMenuItem.setName("schedule"); // NOI18N
        inviteMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                inviteMenuItemActionPerformed(evt);
            }
        });
        meetingsMenuItem.add(inviteMenuItem);

        invitationLinkMenuItem.setText("Invitation Link");
        invitationLinkMenuItem.setEnabled(false);
        invitationLinkMenuItem.setName("invitationLink"); // NOI18N
        invitationLinkMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                invitationLinkMenuItemActionPerformed(evt);
            }
        });
        meetingsMenuItem.add(invitationLinkMenuItem);
        meetingsMenuItem.add(jSeparator4);

        roomListMenuItem.setText("Room List");
        roomListMenuItem.setEnabled(false);
        roomListMenuItem.setName("roomList"); // NOI18N
        roomListMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                roomListMenuItemActionPerformed(evt);
            }
        });
        meetingsMenuItem.add(roomListMenuItem);

        createRoomMenuItem.setText("Create New Room");
        createRoomMenuItem.setEnabled(false);
        createRoomMenuItem.setName("createRoom"); // NOI18N
        createRoomMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                createRoomMenuItemActionPerformed(evt);
            }
        });
        meetingsMenuItem.add(createRoomMenuItem);

        addRoomMembersMenuItem.setText("Room Members");
        addRoomMembersMenuItem.setEnabled(false);
        addRoomMembersMenuItem.setName("addroommembers"); // NOI18N
        addRoomMembersMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                addRoomMembersMenuItemActionPerformed(evt);
            }
        });
        meetingsMenuItem.add(addRoomMembersMenuItem);

        joinRoomMenuItem.setText("Join Unlisted Room");
        joinRoomMenuItem.setEnabled(false);
        joinRoomMenuItem.setName("joinRoom"); // NOI18N
        joinRoomMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                joinRoomMenuItemActionPerformed(evt);
            }
        });
        meetingsMenuItem.add(joinRoomMenuItem);
        meetingsMenuItem.add(jSeparator5);

        banUserMenuItem.setText("Ban User");
        banUserMenuItem.setEnabled(false);
        banUserMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                banUserMenuItemActionPerformed(evt);
            }
        });
        meetingsMenuItem.add(banUserMenuItem);

        screenShareItem.add(meetingsMenuItem);

        toolsMenu.setText("Tools");

        screenShareMenuItem.setText("Screen Share");
        screenShareMenuItem.setEnabled(false);
        screenShareMenuItem.setName("screenshare"); // NOI18N
        screenShareMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                screenShareMenuItemActionPerformed(evt);
            }
        });
        toolsMenu.add(screenShareMenuItem);

        screenViewerMenuItem.setText("Screen Viewer");
        screenViewerMenuItem.setEnabled(false);
        screenViewerMenuItem.setName("screenviewer"); // NOI18N
        screenViewerMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                screenViewerMenuItemActionPerformed(evt);
            }
        });
        toolsMenu.add(screenViewerMenuItem);
        toolsMenu.add(jSeparator2);

        jMenu1.setText("Screen Shot");
        jMenu1.setEnabled(false);
        jMenu1.setName("screenShot"); // NOI18N

        thisAppMenuItem.setText("This Application");
        thisAppMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                thisAppMenuItemActionPerformed(evt);
            }
        });
        jMenu1.add(thisAppMenuItem);

        desktopMenuItem.setText("Desktop");
        desktopMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                desktopMenuItemActionPerformed(evt);
            }
        });
        jMenu1.add(desktopMenuItem);

        toolsMenu.add(jMenu1);
        toolsMenu.add(jSeparator3);

        privateChatMenuItem.setText("Private Chat");
        privateChatMenuItem.setEnabled(false);
        privateChatMenuItem.setName("privatechat"); // NOI18N
        privateChatMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                privateChatMenuItemActionPerformed(evt);
            }
        });
        toolsMenu.add(privateChatMenuItem);
        toolsMenu.add(jSeparator1);

        optionsMenuItem.setText("Options");
        optionsMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                optionsMenuItemActionPerformed(evt);
            }
        });
        toolsMenu.add(optionsMenuItem);

        screenShareItem.add(toolsMenu);

        helpMenu.setText("Help");

        aboutMenuItem.setText("About");
        aboutMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                aboutMenuItemActionPerformed(evt);
            }
        });
        helpMenu.add(aboutMenuItem);

        screenShareItem.add(helpMenu);

        setJMenuBar(screenShareItem);

        pack();
    }// </editor-fold>//GEN-END:initComponents

    public ChatRoomManager getChatRoomManager() {
        return chatRoomManager;
    }

    public WhiteboardPanel getWhiteboardPanel() {
        return whiteboardPanel;
    }

    public JToolBar getToolbar() {
        return instToolbar;
    }

    public UserListFrame getUserListFrame() {
        return userListFrame;
    }

    private void close() {
        try {
            RealtimePacket p = null;
            /* if (GeneralUtil.isInstructor()) {
            p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.CLEAR_LAST_SESSION);
            StringBuilder sb = new StringBuilder();
            sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
            p.setContent(sb.toString());
            ConnectionManager.sendPacket(p);


            p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.TERMINATE_INSTANCE);
            sb = new StringBuilder();
            sb.append("<username>").append(ConnectionManager.getUsername()).append("</username>");
            p.setContent(sb.toString());
            if (ConnectionManager.oldConnection != null) {
            try {
            ConnectionManager.oldConnection.sendPacket(p);
            ConnectionManager.oldConnection.disconnect();
            } catch (Exception ex) {
            ex.printStackTrace();
            }

            } else {
            ConnectionManager.sendPacket(p);
            }
            }*/
            ConnectionManager.getConnection().disconnect();
        } catch (Exception ex) {
            ex.printStackTrace();
        }

        System.exit(0);

    }

    private void inviteParticipants() {
        InviteParticipants fr = new InviteParticipants();
        fr.setSize((ss.width / 3) * 2, (ss.height / 6) * 5);
        fr.setLocationRelativeTo(this);
        fr.setVisible(true);
    }

    private void createRoom() {
        CreateRoomDialog fr = new CreateRoomDialog();
        fr.setSize((ss.width / 2) * 1, (ss.height / 2) * 1);
        fr.setLocationRelativeTo(this);
        fr.setVisible(true);
    }
    private void exitMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_exitMenuItemActionPerformed
        close();
    }//GEN-LAST:event_exitMenuItemActionPerformed

    private void createRoomMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_createRoomMenuItemActionPerformed
        createRoom();
    }//GEN-LAST:event_createRoomMenuItemActionPerformed

    private void notepadButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_notepadButtonMouseEntered
        notepadButton.setContentAreaFilled(true);
        notepadButton.setBorderPainted(true);
    // notepadButton.setText("Notepad");
    }//GEN-LAST:event_notepadButtonMouseEntered

    private void notepadButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_notepadButtonMouseExited
        notepadButton.setContentAreaFilled(false);
        notepadButton.setBorderPainted(false);
    //   notepadButton.setText("");
    }//GEN-LAST:event_notepadButtonMouseExited

    private void imagesButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_imagesButtonActionPerformed
        showImageFileChooser();
}//GEN-LAST:event_imagesButtonActionPerformed

    private void imagesButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_imagesButtonMouseEntered
        imagesButton.setContentAreaFilled(true);
        imagesButton.setBorderPainted(true);
    // imagesButton.setText("Insert Image");
    }//GEN-LAST:event_imagesButtonMouseEntered

    private void imagesButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_imagesButtonMouseExited
        imagesButton.setContentAreaFilled(false);
        imagesButton.setBorderPainted(false);
    //  imagesButton.setText("");
    }//GEN-LAST:event_imagesButtonMouseExited

    private void pointerButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_pointerButtonActionPerformed
}//GEN-LAST:event_pointerButtonActionPerformed

    private void pointerButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_pointerButtonMouseEntered
        pointerButton.setContentAreaFilled(true);
        pointerButton.setBorderPainted(true);
    // pointerButton.setText("Pointer");
    }//GEN-LAST:event_pointerButtonMouseEntered

    private void pointerButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_pointerButtonMouseExited
        pointerButton.setContentAreaFilled(false);
        pointerButton.setBorderPainted(false);
    //  pointerButton.setText("");
    }//GEN-LAST:event_pointerButtonMouseExited

    private void notepadButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_notepadButtonActionPerformed
        showNotepad();
    }//GEN-LAST:event_notepadButtonActionPerformed

    private void newRoomButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_newRoomButtonActionPerformed
        createRoom();
}//GEN-LAST:event_newRoomButtonActionPerformed

    private void newRoomButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_newRoomButtonMouseEntered
        newRoomButton.setContentAreaFilled(true);
        newRoomButton.setBorderPainted(true);
    //  newRoomButton.setText("Create Room");

    }//GEN-LAST:event_newRoomButtonMouseEntered

    private void newRoomButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_newRoomButtonMouseExited
        newRoomButton.setContentAreaFilled(false);
        newRoomButton.setBorderPainted(false);
    //  newRoomButton.setText("");

    }//GEN-LAST:event_newRoomButtonMouseExited

    private void notepadButton1MouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_notepadButton1MouseEntered
        notepadButton1.setContentAreaFilled(true);
        notepadButton1.setBorderPainted(true);
    // notepadButton1.setText("Notepad");
    }//GEN-LAST:event_notepadButton1MouseEntered

    private void notepadButton1MouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_notepadButton1MouseExited
        notepadButton1.setContentAreaFilled(false);
        notepadButton1.setBorderPainted(false);
    //  notepadButton1.setText("");
    }//GEN-LAST:event_notepadButton1MouseExited

    private void notepadButton1ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_notepadButton1ActionPerformed
        showNotepad();
    }//GEN-LAST:event_notepadButton1ActionPerformed

    private void privateChatMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_privateChatMenuItemActionPerformed
//        userListPanel.getUserListTree().initPrivateChat(ConnectionManager.getUsername(), ConnectionManager.roomOwner, "Room Moderator");
}//GEN-LAST:event_privateChatMenuItemActionPerformed

    private void formWindowClosing(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_formWindowClosing
        close();
    }//GEN-LAST:event_formWindowClosing

    private void screenShareMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_screenShareMenuItemActionPerformed
        initScreenShare();
}//GEN-LAST:event_screenShareMenuItemActionPerformed

    private void screenViewerMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_screenViewerMenuItemActionPerformed
        if (webbrowserManager == null) {
            webbrowserManager = new WebBrowserManager();
        }

        webbrowserManager.showScreenShareViewer(800, 600, "Remote Desktop", true);
    }//GEN-LAST:event_screenViewerMenuItemActionPerformed

    private void deskShareButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_deskShareButtonActionPerformed
        initScreenShare();
    }//GEN-LAST:event_deskShareButtonActionPerformed

    private void deskShareButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_deskShareButtonMouseEntered
        deskShareButton.setContentAreaFilled(true);
        deskShareButton.setBorderPainted(true);
    // deskShareButton.setText("Desktop Share");
    }//GEN-LAST:event_deskShareButtonMouseEntered

    private void deskShareButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_deskShareButtonMouseExited
        deskShareButton.setContentAreaFilled(false);
        deskShareButton.setBorderPainted(false);
    // deskShareButton.setText("");
    }//GEN-LAST:event_deskShareButtonMouseExited

    private void optionsMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_optionsMenuItemActionPerformed
        LoginFrame.showOptionsFrame();
}//GEN-LAST:event_optionsMenuItemActionPerformed

    private void aboutMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_aboutMenuItemActionPerformed
        JOptionPane.showMessageDialog(this, GeneralUtil.about);
    }//GEN-LAST:event_aboutMenuItemActionPerformed

    private void inviteMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_inviteMenuItemActionPerformed
        inviteParticipants();
    }//GEN-LAST:event_inviteMenuItemActionPerformed

    public void resetGUIccess() {
        /*        if (userListPanel.getUserTabbedPane().getTabCount() == 3) {
        userListPanel.getUserTabbedPane().remove(2);
        }
        toolBarTabbedPane.removeAll();
        adjustSize();*/
    }

    private void doScreenShot(boolean desktop) {
        try {
            if (desktop) {
                setExtendedState(JFrame.ICONIFIED);
                Thread.sleep(1000);
            }

            Robot r = new Robot();
            BufferedImage img = r.createScreenCapture(new Rectangle(Toolkit.getDefaultToolkit().getScreenSize()));
            String tmpDir = GeneralUtil.getTmpDir();
            try {
                ImageIO.write(img, "jpg", new File(tmpDir + "/tmp.jpg"));

            } catch (IOException ex) {
                ex.printStackTrace();
            }

            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.BROADCAST_IMAGE_DATA);
            StringBuilder sb = new StringBuilder();
            sb.append("<image-data>").append(Base64.encodeFromFile(tmpDir + "/tmp.jpg")).append("</image-data>");
            sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
            p.setContent(sb.toString());
            ConnectionManager.sendPacket(p);
            if (desktop) {
                setExtendedState(JFrame.MAXIMIZED_BOTH);
                Thread.sleep(1000);
            }

        } catch (Exception ex) {
            ex.printStackTrace();
        }

    }

    private void banUserMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_banUserMenuItemActionPerformed
        String jid = JOptionPane.showInputDialog(this, "Enter the user jid, or search", "Ban", JOptionPane.INFORMATION_MESSAGE);
        if (jid != null) {
            chatRoomManager.ban(jid);
        }

    }//GEN-LAST:event_banUserMenuItemActionPerformed

    private void roomListMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_roomListMenuItemActionPerformed
        roomToolsPanel.showRoomList(null);
    }//GEN-LAST:event_roomListMenuItemActionPerformed

    private void thisAppMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_thisAppMenuItemActionPerformed
        doScreenShot(false);
    }//GEN-LAST:event_thisAppMenuItemActionPerformed

    private void desktopMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_desktopMenuItemActionPerformed
        doScreenShot(true);
    }//GEN-LAST:event_desktopMenuItemActionPerformed

    private void insertGraphicMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_insertGraphicMenuItemActionPerformed
        showImageFileChooser();
    }//GEN-LAST:event_insertGraphicMenuItemActionPerformed

    private void insertPresentationMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_insertPresentationMenuItemActionPerformed
        insertPresentation();
    }//GEN-LAST:event_insertPresentationMenuItemActionPerformed

    private void updateRoomResourcesMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_updateRoomResourcesMenuItemActionPerformed
        showRoomResourcesNavigator();
    }//GEN-LAST:event_updateRoomResourcesMenuItemActionPerformed

    private void invitationLinkMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_invitationLinkMenuItemActionPerformed
        JOptionPane.showInputDialog("Invitation Link", WebPresentManager.finalInviteURL);
    }//GEN-LAST:event_invitationLinkMenuItemActionPerformed

    private void joinRoomMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_joinRoomMenuItemActionPerformed
        String roomName = JOptionPane.showInputDialog("Enter room name: ");
        if (roomName != null) {
            chatRoomManager.joinAsParticipant(roomName.trim());
        }
    }//GEN-LAST:event_joinRoomMenuItemActionPerformed

    private void requestMicMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_requestMicMenuItemActionPerformed
        if (WebPresentManager.isPresenter || StandAloneManager.isAdmin) {
            JOptionPane.showMessageDialog(null, "You are a room moderator already. You dont need to request microphone");
            return;
        }
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.REQUEST_MIC);
        StringBuilder sb = new StringBuilder();
        sb.append("<mic-requester>").append(ConnectionManager.getUsername()).append("</mic-requester>");
        sb.append("<mic-requester-name>").append(ConnectionManager.fullnames).append("</mic-requester-name>");
        sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
        sb.append("<room-owner>").append(GeneralUtil.getThisRoomOwner()).append("</room-owner>");
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);
    }//GEN-LAST:event_requestMicMenuItemActionPerformed

    private void addRoomMembersMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_addRoomMembersMenuItemActionPerformed
        roomToolsPanel.showRoomMemberList();
    }//GEN-LAST:event_addRoomMembersMenuItemActionPerformed

    private void showRoomResourcesNavigator() {
        JFrame fr = new JFrame("Room Resources");
        slidesSplitPane.setTopComponent(slidesNavigator);
        slidesSplitPane.setDividerLocation(180);
        slidesSplitPane.setBottomComponent(roomResourceNavigator);
        slidesSplitPane.setDividerSize(3);

        fr.setContentPane(slidesSplitPane);
        fr.setSize((ss.width / 4) * 3, (ss.height / 4) * 3);
        fr.setVisible(true);
        slidesNavigator.populateNodes("slideshows");
        roomResourceNavigator.populateWithRoomResources();
    }

    public void initScreenShare() {
        if (webbrowserManager == null) {
            webbrowserManager = new WebBrowserManager();
        }

        webbrowserManager.showScreenShareFrame();
    }

    private void showUserList() {
        if (userListFrame == null) {
            userListFrame = new UserListFrame();
        }

        userListFrame.setSize(500, 400);
        userListFrame.setLocationRelativeTo(this);
        userListFrame.setVisible(true);
    }

    /**
     * displays the notepad
     */
    public void showNotepad() {
        JNotepad notepad = new JNotepad();
        notepad.setSize(400, 300);
        int x = ss.width - notepad.getWidth();
        int y = 10;
        notepad.setLocation(x, y + (notepadCount++ * 30));
        notepad.setTitle("Untitled " + notepadCount);
        notepad.setAlwaysOnTop(true);
        notepad.setVisible(true);
    }

    public void showNotepad(Document doc, String title) {
        JNotepad notepad = new JNotepad(doc, title);
        notepad.setSize(400, 300);
        int x = ss.width - notepad.getWidth();
        int y = 10;

        notepad.setLocation(x, y + (notepadCount++ * 30));

        notepad.setAlwaysOnTop(true);
        notepad.setVisible(true);
    }

    public void showImageFileChooser() {
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.REQUEST_FILE_VIEW);
        StringBuilder sb = new StringBuilder();
        sb.append("<username>").append(ConnectionManager.getUsername()).append("</username>");
        sb.append("<file-type>").append("images").append("</file-type>");
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);
        if (realtimeFileChooser.showDialog() == RealtimeFileChooser.APPROVE_OPTION) {
            RealtimeFile file = realtimeFileChooser.getSelectedFile();
            p =
                    new RealtimePacket();
            p.setMode(RealtimePacket.Mode.BROADCAST_IMAGE);
            StringBuilder buf = new StringBuilder();
            buf.append("<image-path>").append(file.getFilePath()).append("</image-path>");
            buf.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
            p.setContent(buf.toString());
            ConnectionManager.sendPacket(p);
        }

    }

    private void doRefresh() {
        int width = getWidth();
        int height = getHeight();
        if (expand) {
            setSize(width + 1, height + 1);
            expand =
                    false;
        } else {
            setSize(width - 1, height - 1);
            expand =
                    true;
        }

    }

    public WebBrowserManager getWebbrowserManager() {
        if (webbrowserManager == null) {
            webbrowserManager = new WebBrowserManager();
        }

        return webbrowserManager;
    }

    public SlidesNavigator getSlideNavigator() {
        return slidesNavigator;
    }

    public void removeNavigator() {
        surfacePanel.removeAll();
//        surfacePanel.add(xtopPanel, java.awt.BorderLayout.PAGE_START);
        surfacePanel.add(tabbedPane, java.awt.BorderLayout.CENTER);
        slidesNavigator =
                null;
        int width = getWidth();
        int height = getHeight();
        setSize(width + 1, height + 1);
        setSize(width - 1, height - 1);
        RPacketListener.removeNavigatorFileVewListener(slidesNavigator);
        RPacketListener.removeSlideShowListener(slidesNavigator);
    }
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JMenuItem aboutMenuItem;
    private javax.swing.JMenu actionsMenu;
    private javax.swing.JMenuItem addRoomMembersMenuItem;
    private javax.swing.JMenuItem banUserMenuItem;
    private javax.swing.JMenuItem createRoomMenuItem;
    private javax.swing.JButton deskShareButton;
    private javax.swing.JMenuItem desktopMenuItem;
    private javax.swing.JMenuItem exitMenuItem;
    private javax.swing.JMenu fileMenutem;
    private javax.swing.JMenu helpMenu;
    private javax.swing.JButton imagesButton;
    private javax.swing.JMenuItem insertGraphicMenuItem;
    private javax.swing.JMenuItem insertPresentationMenuItem;
    private javax.swing.JToolBar instToolbar;
    private javax.swing.JMenuItem invitationLinkMenuItem;
    private javax.swing.JMenuItem inviteMenuItem;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JMenu jMenu1;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JSeparator jSeparator1;
    private javax.swing.JSeparator jSeparator2;
    private javax.swing.JSeparator jSeparator3;
    private javax.swing.JSeparator jSeparator4;
    private javax.swing.JSeparator jSeparator5;
    private javax.swing.JSeparator jSeparator7;
    private javax.swing.JMenuItem joinRoomMenuItem;
    private javax.swing.JSplitPane mainSplitPane;
    private javax.swing.JMenu meetingsMenuItem;
    private javax.swing.JButton newRoomButton;
    private javax.swing.JButton notepadButton;
    private javax.swing.JButton notepadButton1;
    private javax.swing.JMenuItem optionsMenuItem;
    private javax.swing.JToolBar partiToolbar;
    private javax.swing.JButton pointerButton;
    private javax.swing.JMenuItem privateChatMenuItem;
    private javax.swing.JMenuItem requestMicMenuItem;
    private javax.swing.JMenuItem roomListMenuItem;
    private javax.swing.JMenuBar screenShareItem;
    private javax.swing.JMenuItem screenShareMenuItem;
    private javax.swing.JMenuItem screenViewerMenuItem;
    private javax.swing.JPanel statusBar;
    private javax.swing.JPanel surfacePanel;
    private javax.swing.JTabbedPane tabbedPane;
    private javax.swing.JMenuItem thisAppMenuItem;
    private javax.swing.JTextArea titleField;
    private javax.swing.JPanel titlePanel;
    private javax.swing.JMenu toolsMenu;
    private javax.swing.JPanel topPanel;
    private javax.swing.JMenuItem updateRoomResourcesMenuItem;
    private javax.swing.ButtonGroup wbButtonGroup;
    private javax.swing.JLabel wbInfoField;
    private javax.swing.JProgressBar wbProgressBar;
    // End of variables declaration//GEN-END:variables
}
