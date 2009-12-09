/*
 * MainFrame.java
 *
 * Created on 2009/03/21, 04:16:43
 */
package org.avoir.realtime.gui.main;

import java.awt.event.WindowEvent;
import javax.swing.JProgressBar;
import org.avoir.realtime.gui.webbrowser.RWebBrowserListener;
import chrriis.dj.nativeswing.swtimpl.components.JWebBrowser;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.GridLayout;
import java.awt.Rectangle;
import java.awt.Robot;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.WindowStateListener;
import java.awt.image.BufferedImage;
import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Timer;
import java.util.TimerTask;
import javax.imageio.ImageIO;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JCheckBoxMenuItem;
import javax.swing.JComponent;
import javax.swing.JDialog;
import javax.swing.JFileChooser;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JSpinner;
import javax.swing.JSplitPane;
import javax.swing.JTabbedPane;
import javax.swing.JTextArea;
import javax.swing.JToolBar;
import javax.swing.SpinnerNumberModel;
import javax.swing.SwingUtilities;
import javax.swing.event.ChangeEvent;
import javax.swing.event.ChangeListener;
import javax.swing.text.Document;
import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.RealtimeFile;
import org.avoir.realtime.common.filetransfer.FileManager;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.common.util.RTimer;
import org.avoir.realtime.gui.Magnifier;
import org.avoir.realtime.gui.userlist.ParticipantListPanel;
import org.avoir.realtime.gui.whiteboard.WhiteboardPanel;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.gui.PointerListPanel;
import org.avoir.realtime.gui.PresentationFilter;
import org.avoir.realtime.gui.RealtimeFileChooser;
import org.avoir.realtime.gui.RoomResourceNavigator;
import org.avoir.realtime.gui.SlidesNavigator;
import org.avoir.realtime.gui.WebpresentNavigator;
import org.avoir.realtime.gui.room.CreateRoomDialog;
import org.avoir.realtime.gui.room.InviteParticipants;
import org.avoir.realtime.gui.room.RoomListFrame;
import org.avoir.realtime.gui.room.RoomMemberListFrame;
import org.avoir.realtime.gui.room.RoomResourcesList;
import org.avoir.realtime.gui.screenviewer.webstart.screen.CaptureScreen;
import org.avoir.realtime.gui.webbrowser.WebBrowserManager;
import org.avoir.realtime.gui.userlist.UserListFrame;
import org.avoir.realtime.net.RPacketListener;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.avoir.realtime.notepad.JNotepad;

import org.jivesoftware.smack.util.Base64;
import org.avoir.realtime.gui.tips.*;
import org.avoir.realtime.sound.AudioVideoTest;
import org.avoir.realtime.sound.SoundMonitor;
import snoozesoft.systray4j.SysTrayMenu;
import snoozesoft.systray4j.SysTrayMenuEvent;
import snoozesoft.systray4j.SysTrayMenuIcon;
import snoozesoft.systray4j.SysTrayMenuListener;
import org.jivesoftware.smack.packet.Packet;

/**
 *
 * @author developer
 */
public class MainFrame extends javax.swing.JFrame implements SysTrayMenuListener {

    private JDialog magnifierDialog;
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private ParticipantListPanel userListPanel;
    private WhiteboardPanel whiteboardPanel = new WhiteboardPanel();
    private ChatRoomManager chatRoomManager;
    private String defaultRoomName = "Chat";
    private ImageIcon chatIcon = ImageUtil.createImageIcon(this, "/images/chat_on.gif");
    private ImageIcon logo = ImageUtil.createImageIcon(this, "/images/intro_logo.jpg");
    private ImageIcon alertIcon = ImageUtil.createImageIcon(this, "/images/application.png");
    private SlidesNavigator slidesNavigator;
    private ImageIcon appIcon = ImageUtil.createImageIcon(this, "/images/application_delete.png");
    private RealtimeFileChooser realtimeFileChooser = new RealtimeFileChooser("images");
    private int currentRoomIndex = 0;
    private int notepadCount = 0;
    private UserListFrame userListFrame;
    private PointerListPanel pointerListPanel;
    private boolean expand = false;
    private WebBrowserManager webbrowserManager;
    private JWebBrowser generalWebBrowser = new JWebBrowser();
    private Timer tabTimer = new Timer();
    private RoomResourceNavigator roomResourceNavigator = new RoomResourceNavigator();
    private JSplitPane slidesSplitPane = new JSplitPane(JSplitPane.VERTICAL_SPLIT);
    private WebpresentNavigator webPresentNavigator;
    private ArrayList<Speaker> speakers = new ArrayList<Speaker>();
    int speakerRows = 2;
    int speakerCols = 2;
    private JPanel speakersPanel = new JPanel(new GridLayout(speakerRows, speakerCols));
    private RoomListFrame roomListFrame;
    private RoomResourcesList roomResourcesList;
    private JFileChooser presentationFC = new JFileChooser();
    private RoomMemberListFrame roomMemberListFrame;
    private boolean slidesPopulated = false;
    private Timer messageTimer = new Timer();
    private JComponent glass = new Magnifier();
    private double zoomFactor = 100.0;
    private boolean zoomControl = true;
    final JCheckBoxMenuItem handItem = new JCheckBoxMenuItem("Raise Hand");
    private boolean handRaised = false;
    private SoundMonitor soundMonitor = new SoundMonitor();
    final JFrame shareSizeFr = new JFrame("Share Desktop Size Setting");
    final JSpinner sizeOption = new JSpinner(new SpinnerNumberModel(0, 0, 100, 10));
    private JButton okButton = new JButton("Set");
    private JLabel percLbl = new JLabel(" % ");
    private JTextArea screenOptionText = new JTextArea();
    private JPanel cPanel = new JPanel();
    private static final String[] toolTips = {
        "SysTray for Java rules!",
        "brought to you by\nSnoozeSoft 2004"
    };
    private RealtimeSysTray realtimeSysTray = new RealtimeSysTray();
    // create icons
    static final SysTrayMenuIcon[] icons = {
        // the extension can be omitted
      
        new SysTrayMenuIcon("icons/duke"),
        new SysTrayMenuIcon("icons/duke_up")
    };
    SysTrayMenu menu;
    int currentIndexIcon;
    int currentIndexTooltip;
    //private Item item = new Item();

    /** Creates new form MainFrame */
    public MainFrame(String roomName) {
        this.setGlassPane(glass);
        initComponents();
        userListPanel = new ParticipantListPanel();
        leftSplitPane.setDividerLocation((ss.height / 2));
        mainSplitPane.setDividerLocation((ss.width / 4) + 60);
        if (GUIAccessManager.skinClass == null) {
            leftSplitPane.setTopComponent(userListPanel);
        }
        tabbedPane.addTab("Whiteboard", whiteboardPanel);
        if (GUIAccessManager.skinClass == null) {
            tabbedPane.add("Browser", generalWebBrowser);
        }
        tabbedPane.add("Speakers", speakersPanel);
        //toolsPanel.add(whiteboardPanel.getWbToolbar(), BorderLayout.SOUTH);
        webPresentNavigator = new WebpresentNavigator();


        tabbedPane.addTab("ScreenShare", cPanel);
        screenShareMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                if (tabbedPane.getSelectedIndex() == 3){
                   GUIAccessManager.mf.getWebbrowserManager().showScreenShareViewerAsEmbbededTab1(shareSizeFr);
                 }
            }
        });
        

    

        userListPanel.getUserTabbedPane().addTab("Presentations", webPresentNavigator);
        userListPanel.getUserTabbedPane().addChangeListener(new ChangeListener() {

            public void stateChanged(ChangeEvent e) {
                if (userListPanel.getUserTabbedPane().getSelectedIndex() == 1) {
                    if (!userListPanel.isAudioEnabled()) {
                        userListPanel.enableAudio();
                    }
                }
                if (userListPanel.getUserTabbedPane().getSelectedIndex() == 2) {
                    webPresentNavigator.populateWithRoomResources();
                    adjustSize();
                    GUIAccessManager.mf.getWebbrowserManager().showScreenShareViewerAsEmbbededTab1(shareSizeFr);
                }
               }
        });


        generalWebBrowser.addWebBrowserListener(new RWebBrowserListener(generalWebBrowser));
        slidesNavigator = new SlidesNavigator(this);
        add(statusBar, BorderLayout.SOUTH);

        GUIAccessManager.setMf(this);
        chatRoomManager = new ChatRoomManager(ConnectionManager.getRoomName());
        chatTabbedPane.addTab(defaultRoomName, chatIcon, chatRoomManager.getChatRoom());


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
        if (GUIAccessManager.skinClass == null) {
            for (int i = 0; i < speakerCols; i++) {
                for (int j = 0; j < speakerRows; j++) {
                    String speakerName = "free";
                    final JWebBrowser browser = new JWebBrowser();
                    browser.setMenuBarVisible(false);
                    browser.setBarsVisible(false);
                    browser.setButtonBarVisible(false);
                    JButton refreshButton = new JButton("Reload");
                    Speaker speaker = new Speaker(browser, speakerName, refreshButton);
                    speakers.add(speaker);
                    JPanel p = new JPanel(new BorderLayout());
                    p.add(browser, BorderLayout.CENTER);

                    refreshButton.setVisible(false);
                    refreshButton.addActionListener(new ActionListener() {

                        public void actionPerformed(ActionEvent arg0) {
                            SwingUtilities.invokeLater(new Runnable() {

                                public void run() {
                                    browser.reloadPage();
                                }
                            });
                        }
                    });
                    JPanel p2 = new JPanel();
                    p2.add(refreshButton);
                    p.add(p2, BorderLayout.SOUTH);
                    speakersPanel.add(p);

                }
            }
        }
        presentationFC.addChoosableFileFilter(new PresentationFilter());
        presentationFC.setMultiSelectionEnabled(true);
        doRealRoomJoin(roomName);
        userListPanel.getStartAudioVideoButton().setEnabled(!ConnectionManager.useEC2);
        //GUIAccessManager.mf.getWebbrowserManager().showScreenShareViewerAsEmbbededTab(t);
        displayAvator();
        setSize(ss);
        if (GUIAccessManager.skinClass == null) {
            setVisible(true);
        }
        this.addWindowStateListener(new WindowStateListener() {

            public void windowStateChanged(WindowEvent e) {
                if (MainFrame.this.isActive()) {
                    MainFrame.this.setIconImage(appIcon.getImage());

                }
            }
        });



//        addCustomComponents();
        userListPanel.showRoomOwnerAudioVideoWindow();
        applySkin();
        RTimer.init();

        //initialise share screen option frame
        screenOptionText.setText("Select the percentage size of the desktop screen thumb nail.");
        screenOptionText.setBounds(110, 30, 150, 160);
        screenOptionText.setLineWrap(true);
        screenOptionText.setBackground(Color.WHITE);
        sizeOption.setBounds(28, 30, 60, 30);
        okButton.setBounds(28, 100, 80, 25);
        percLbl.setBounds(90, 30, 40, 30);
        okButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                CaptureScreen.resetImgSize();
                Object value = sizeOption.getValue();
                int perc = (int) (Double.valueOf(value.toString()) * 1.2);
                CaptureScreen.changImgSize(perc * 4, perc * 4);
                sizeOption.setValue(value);
                shareSizeFr.setVisible(false);
            }
        });
        shareSizeFr.getContentPane().setLayout(null);
        shareSizeFr.getContentPane().setBackground(Color.WHITE);
        shareSizeFr.add(screenOptionText);
        shareSizeFr.add(sizeOption);
        shareSizeFr.add(percLbl);
        shareSizeFr.add(okButton);
        shareSizeFr.pack();
        shareSizeFr.setSize(300, 180);
        shareSizeFr.setLocationRelativeTo(GUIAccessManager.mf);
        String javaVersion=System.getProperty("java.version");
        
        String major=javaVersion.substring(0,1);
        String minorStr=javaVersion.substring(2,3);
        int minorInt=Integer.parseInt(minorStr);
        if(minorInt < 6){
            String warnOpt=GeneralUtil.getProperty("show.java.warning");
            
            if(warnOpt == null){
                warnOpt="true";
            }
            boolean doNotShowJavaVersionWarning=new Boolean(warnOpt);
            if(!doNotShowJavaVersionWarning){
                JavaVersionWarningDialog javaVersionWarningDialog=new JavaVersionWarningDialog(this, true);
                javaVersionWarningDialog.setSize(400, 200);
                javaVersionWarningDialog.setLocationRelativeTo(this);
                javaVersionWarningDialog.setVisible(true);
            }
        }else{
           realtimeSysTray.init(userListPanel);
        }
     
    }



    void createMenu() {

      /*  // create some labeled menu items
        SysTrayMenuItem subItem1 = new SysTrayMenuItem("Windows 98", "windows 98");
        subItem1.addSysTrayMenuListener(this);
        // disable this item
        subItem1.setEnabled(false);

        SysTrayMenuItem subItem2 = new SysTrayMenuItem("Windows 2000", "windows 2000");
        subItem2.addSysTrayMenuListener(this);
        SysTrayMenuItem subItem3 = new SysTrayMenuItem("Windows XP", "windows xp");
        subItem3.addSysTrayMenuListener(this);

        SysTrayMenuItem subItem4 = new SysTrayMenuItem("GNOME", "gnome");
        subItem4.addSysTrayMenuListener(this);
        subItem4.setEnabled(false);

        SysTrayMenuItem subItem5 = new SysTrayMenuItem("KDE 3", "kde 3");
        subItem5.addSysTrayMenuListener(this);

        Vector items = new Vector();
        items.add(subItem1);
        items.add(subItem2);
        items.add(subItem3);
        items.add(subItem4);
        items.add(subItem5);

        // create a submenu and insert the previously created items
        SubMenu subMenu = new SubMenu("Supported", items);

        // create some checkable menu items
        CheckableMenuItem chItem1 = new CheckableMenuItem("IPC", "ipc");
        chItem1.addSysTrayMenuListener(this);

        CheckableMenuItem chItem2 = new CheckableMenuItem("Sockets", "sockets");
        chItem2.addSysTrayMenuListener(this);

        CheckableMenuItem chItem3 = new CheckableMenuItem("JNI", "jni");
        chItem3.addSysTrayMenuListener(this);

        // check this item
        chItem2.setState(true);
        chItem3.setState(true);

        // create another submenu and insert the items through addItem()
        SubMenu chSubMenu = new SubMenu("Communication");
        // disable this submenu
        chSubMenu.setEnabled(false);

        chSubMenu.addItem(chItem1);
        chSubMenu.addItem(chItem2);
        chSubMenu.addItem(chItem3);

        // create an exit item
        SysTrayMenuItem itemExit = new SysTrayMenuItem("Exit", "exit");
        itemExit.addSysTrayMenuListener(this);

        // create an about item
        SysTrayMenuItem itemAbout = new SysTrayMenuItem("About...", "about");
        itemAbout.addSysTrayMenuListener(this);

        // create the main menu
        menu = new SysTrayMenu(icons[ 0], toolTips[ 0]);

        // insert items
        menu.addItem(itemExit);
        menu.addSeparator();
        menu.addItem(itemAbout);
        menu.addSeparator();
        menu.addItem(subMenu);
        menu.addItem(chSubMenu);*/
    }

    private void applySkin() {
        String skinName = GUIAccessManager.skinClass;
        if (skinName != null) {

            try {
                Class cl = Class.forName(skinName);
                SkinManager skinManager = (SkinManager) cl.newInstance();
                skinManager.init();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
        adjustSize();
    }

    private void xaddCustomComponents() {

        handItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                if (ConnectionManager.isOwner) {
                    JOptionPane.showMessageDialog(null, "You are room owner, no need of raising hand.");
                    return;
                }
                if (handItem.isSelected()) {
                    userListPanel.getParticipantListTable().raiseHand();
                } else {
                    userListPanel.getParticipantListTable().lowerHand();
                }
            }
        });
        actionsMenu.add(handItem);

        //This code generates the menu item for generating a new question, and opens it when clicking on the item.
        final JMenuItem presentnew = new JMenuItem("Present New Question");
        presentnew.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                if (!ConnectionManager.isOwner) {
                    JOptionPane.showMessageDialog(null, "You do not have permission to perform this action in this room.");
                    return;
                } else {
                    org.avoir.realtime.questions.QuestionFrame fr = new org.avoir.realtime.questions.QuestionFrame();
                    fr.setVisible(true);
                }
            }
        });
        //dwaf temporarily disabled this
        //actionsMenu.add(presentnew);
    }

    public void removeAllSpeakers() {

        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                for (Speaker speaker : speakers) {
                    String speakerName = speaker.getSpeaker();
                    final JWebBrowser browser = speaker.getWebBrowser();
                    SwingUtilities.invokeLater(new Runnable() {

                        public void run() {
                            browser.setHTMLContent("<html>Free<html>");
                        }
                    });
                    speaker.setSpeaker("free");

                    break;
                }

            }
        });
    }

    public void runMessageAlerter() {
        messageTimer.cancel();
        messageTimer = new Timer();
        messageTimer.scheduleAtFixedRate(new MessageAlerter(), 1000, 1000);
    }

    class MessageAlerter extends TimerTask {

        boolean blink = false;

        public void run() {
            if (blink) {
                MainFrame.this.setIconImage(appIcon.getImage());
            } else {
                MainFrame.this.setIconImage(alertIcon.getImage());
            }
            blink = !blink;
            System.out.println("blinking : " + blink);
        }
    }

    public boolean addSpeaker(final String url, String speakerUsername) {
        //speakerName = new String(Base64.decode(speakerName));
        int index = 0;
        //first, check same guy already there, if so, simply update instead
        //of creating new window

        for (Speaker speaker : speakers) {
            if (speaker.getSpeaker().equals(speakerUsername)) {
                final JWebBrowser browser = speaker.getWebBrowser();
                speaker.getButton().setVisible(true);
                SwingUtilities.invokeLater(new Runnable() {

                    public void run() {
                        browser.navigate(url);
                    }
                });
                tabbedPane.setSelectedIndex(2);
                speaker.setSpeaker(speakerUsername);
                speakers.set(index, speaker);

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
                tabbedPane.setSelectedIndex(tabbedPane.getTabCount() - 1);
                speaker.setSpeaker(speakerUsername);
                speakers.set(index, speaker);
                //userListPanel.getParticipantListTable().setUserHasMIC(speakerUsername, true);
                return true;
            }
            index++;
        }
        return false;
    }

    public void removeSpeaker(final String speakerUsername) {

        //final String speakerName = new String(Base64.decode(xspeakerName));
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                for (Speaker speaker : speakers) {

                    if (speaker.getSpeaker().equalsIgnoreCase(speakerUsername)) {
                        final JWebBrowser browser = speaker.getWebBrowser();
                        speaker.getButton().setVisible(false);
                        SwingUtilities.invokeLater(new Runnable() {

                            public void run() {
                                browser.setHTMLContent("<html>Free<html>");
                            }
                        });
                        speaker.setSpeaker("free");
                        tabbedPane.setSelectedIndex(0);
                        break;
                    } else {
                    }
                }
            }
        });
    }

    public void updateURL(final String url) {
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                String decodedUrl = new String(Base64.decode(url));

                if (!decodedUrl.equals("null")) {
                    generalWebBrowser.navigate(decodedUrl);
                }

            }
        });
    }

    public void setWebBrowserEnabled(final boolean isInstructor) {
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                generalWebBrowser.setBarsVisible(isInstructor);
                generalWebBrowser.setButtonBarVisible(isInstructor);
                generalWebBrowser.setMenuBarVisible(false);
                generalWebBrowser.setLocationBarVisible(isInstructor);
                generalWebBrowser.setEnabled(isInstructor);
            }
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

    private void doRealRoomJoin(final String roomName) {

        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                chatRoomManager.doActualJoin(ConnectionManager.getUsername(), roomName, true);
            }
        });
    }

    public JTabbedPane getTabbedPane() {
        return tabbedPane;
    }

    public JLabel getWbInfoField() {
        return infoField;
    }

    public JProgressBar getWbProgressBar() {
        return wbProgressBar;
    }

    public JWebBrowser getGeneralWebBrowser() {
        return generalWebBrowser;
    }

    public int resetSlideCount() {
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
        }
        return slideCount;
    }

    public JToolBar getRoomToolsToolbar() {
        return roomToolsToolbar;
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

    public RealtimeSysTray getRealtimeSysTray() {
        return realtimeSysTray;
    }

    /*  public void showParticipantToolbar() {
    toolBarTabbedPane.addTab("Toolbar", partiToolbar);

    adjustSize();

    }*/
    public JTabbedPane getChatTabbedPane() {
        return chatTabbedPane;
    }

    public RoomListFrame getRoomListFrame() {
        return roomListFrame;
    }

    public void showRoomList(InviteParticipants inviteParticipants) {
        if (roomListFrame == null) {
            roomListFrame = new RoomListFrame(this, false);

        }
        roomListFrame.setInviteParticipants(inviteParticipants);
        roomListFrame.setSize((ss.width / 4) * 3, (ss.height / 4) * 3);
        roomListFrame.setLocationRelativeTo(null);
        roomListFrame.requestRoomList();
        roomListFrame.setVisible(true);

    }

    public void showRoomResourceList() {
        if (roomResourcesList == null) {
            roomResourcesList = new RoomResourcesList(this, false);

        }

        roomResourcesList.setSize((ss.width / 4) * 3, (ss.height / 4) * 3);
        roomResourcesList.setLocationRelativeTo(null);
        roomResourcesList.requestRoomResorcesList();
        roomResourcesList.setVisible(true);

    }

    public void showRoomMemberList() {
        if (roomMemberListFrame == null) {
            roomMemberListFrame = new RoomMemberListFrame(this, false);

        }

        roomMemberListFrame.setSize((ss.width / 4) * 3, (ss.height / 4) * 3);
        roomMemberListFrame.setLocationRelativeTo(null);
        roomMemberListFrame.requestRoomMemberList();
        roomMemberListFrame.setVisible(true);

    }

    public RoomMemberListFrame getRoomMemberListFrame() {
        return roomMemberListFrame;
    }

    public void setRoomListFrame(RoomListFrame roomListFrame) {
        this.roomListFrame = roomListFrame;
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

        zoomInButton = new javax.swing.JButton();
        zoomOutButton = new javax.swing.JButton();
        zoomOriginalButton = new javax.swing.JButton();
        titlePanel = new javax.swing.JPanel();
        jScrollPane1 = new javax.swing.JScrollPane();
        titleField = new javax.swing.JTextArea();
        wbButtonGroup = new javax.swing.ButtonGroup();
        partiToolbar = new javax.swing.JToolBar();
        notepadButton1 = new javax.swing.JButton();
        changeRoomButton1 = new javax.swing.JButton();
        topPanel = new javax.swing.JPanel();
        statusBar = new javax.swing.JPanel();
        timerField = new javax.swing.JLabel();
        wbProgressBar = new javax.swing.JProgressBar();
        infoField = new javax.swing.JLabel();
        mainSplitPane = new javax.swing.JSplitPane();
        leftSplitPane = new javax.swing.JSplitPane();
        chatTabbedPane = new javax.swing.JTabbedPane();
        surfacePanel = new javax.swing.JPanel();
        tabbedPane = new javax.swing.JTabbedPane();
        surfaceTopTabbedPane = new javax.swing.JTabbedPane();
        toolsPanel = new javax.swing.JPanel();
        roomToolsToolbar = new javax.swing.JToolBar();
        changeRoomButton = new javax.swing.JButton();
        pointerButton = new javax.swing.JButton();
        deskShareButton = new javax.swing.JButton();
        imagesButton = new javax.swing.JButton();
        notepadButton = new javax.swing.JButton();
        screenShareItem = new javax.swing.JMenuBar();
        fileMenutem = new javax.swing.JMenu();
        exitMenuItem = new javax.swing.JMenuItem();
        editMenu = new javax.swing.JMenu();
        undoMenuItem = new javax.swing.JMenuItem();
        viewMenu = new javax.swing.JMenu();
        fullScreenMenuItem = new javax.swing.JMenuItem();
        escMenuItem = new javax.swing.JMenuItem();
        actionsMenu = new javax.swing.JMenu();
        insertGraphicMenuItem = new javax.swing.JMenuItem();
        insertPresentationMenuItem = new javax.swing.JMenuItem();
        nextPrevMenuItem = new javax.swing.JMenuItem();
        prevslideMenuItem = new javax.swing.JMenuItem();
        jSeparator7 = new javax.swing.JSeparator();
        raiseHandMenuItem = new javax.swing.JMenuItem();
        meetingsMenuItem = new javax.swing.JMenu();
        jSeparator4 = new javax.swing.JSeparator();
        roomListMenuItem = new javax.swing.JMenuItem();
        createRoomMenuItem = new javax.swing.JMenuItem();
        joinRoomMenuItem = new javax.swing.JMenuItem();
        jSeparator5 = new javax.swing.JSeparator();
        banUserMenuItem = new javax.swing.JMenuItem();
        toolsMenu = new javax.swing.JMenu();
        screenShareMenuItem = new javax.swing.JMenuItem();
        screenViewerMenuItem = new javax.swing.JMenuItem();
        screenShareSizeMenuItem = new javax.swing.JMenuItem();
        jSeparator2 = new javax.swing.JSeparator();
        roomResourcesMenuItem = new javax.swing.JMenuItem();
        questionManagerMenuItem = new javax.swing.JMenuItem();
        slideBuilderMenuItem = new javax.swing.JMenuItem();
        jSeparator10 = new javax.swing.JSeparator();
        jMenu1 = new javax.swing.JMenu();
        thisAppMenuItem = new javax.swing.JMenuItem();
        desktopMenuItem = new javax.swing.JMenuItem();
        privateChatMenuItem = new javax.swing.JMenuItem();
        tipsMenuItem = new javax.swing.JMenuItem();
        magnifierMenuitem = new javax.swing.JMenuItem();
        jSeparator1 = new javax.swing.JSeparator();
        whiteboardToolsMenuItem = new javax.swing.JMenuItem();
        audioVideoTestMenuItem = new javax.swing.JMenuItem();
        jSeparator9 = new javax.swing.JSeparator();
        optionsMenuItem = new javax.swing.JMenuItem();
        jSeparator3 = new javax.swing.JSeparator();
        cleanMicsMenuItem = new javax.swing.JMenuItem();
        helpMenu = new javax.swing.JMenu();
        aboutMenuItem = new javax.swing.JMenuItem();

        zoomInButton.setFont(new java.awt.Font("Dialog", 0, 11));
        zoomInButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/kedit32.png"))); // NOI18N
        zoomInButton.setText("+");
        zoomInButton.setBorderPainted(false);
        zoomInButton.setContentAreaFilled(false);
        zoomInButton.setFocusable(false);
        zoomInButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        zoomInButton.setName("notepad"); // NOI18N
        zoomInButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        zoomInButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                zoomInButtonActionPerformed(evt);
            }
        });
        zoomInButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                zoomInButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                zoomInButtonMouseExited(evt);
            }
        });

        zoomOutButton.setFont(new java.awt.Font("Dialog", 0, 11));
        zoomOutButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/kedit32.png"))); // NOI18N
        zoomOutButton.setText("-");
        zoomOutButton.setBorderPainted(false);
        zoomOutButton.setContentAreaFilled(false);
        zoomOutButton.setFocusable(false);
        zoomOutButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        zoomOutButton.setName("notepad"); // NOI18N
        zoomOutButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        zoomOutButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                zoomOutButtonActionPerformed(evt);
            }
        });
        zoomOutButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                zoomOutButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                zoomOutButtonMouseExited(evt);
            }
        });

        zoomOriginalButton.setFont(new java.awt.Font("Dialog", 0, 11));
        zoomOriginalButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/kedit32.png"))); // NOI18N
        zoomOriginalButton.setText("100%");
        zoomOriginalButton.setBorderPainted(false);
        zoomOriginalButton.setContentAreaFilled(false);
        zoomOriginalButton.setFocusable(false);
        zoomOriginalButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        zoomOriginalButton.setName("notepad"); // NOI18N
        zoomOriginalButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        zoomOriginalButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                zoomOriginalButtonActionPerformed(evt);
            }
        });
        zoomOriginalButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                zoomOriginalButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                zoomOriginalButtonMouseExited(evt);
            }
        });

        titlePanel.setLayout(new java.awt.BorderLayout());

        titleField.setColumns(20);
        titleField.setEditable(false);
        titleField.setFont(new java.awt.Font("Dialog", 1, 24));
        titleField.setForeground(new java.awt.Color(244, 214, 117));
        titleField.setRows(2);
        titleField.setText("Realtime Classroom");
        jScrollPane1.setViewportView(titleField);

        titlePanel.add(jScrollPane1, java.awt.BorderLayout.CENTER);

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

        changeRoomButton1.setFont(new java.awt.Font("Dialog", 0, 11));
        changeRoomButton1.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/join_room.png"))); // NOI18N
        changeRoomButton1.setText("Change Room");
        changeRoomButton1.setBorderPainted(false);
        changeRoomButton1.setContentAreaFilled(false);
        changeRoomButton1.setFocusable(false);
        changeRoomButton1.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        changeRoomButton1.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        changeRoomButton1.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                changeRoomButton1MouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                changeRoomButton1MouseExited(evt);
            }
        });
        changeRoomButton1.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                changeRoomButton1ActionPerformed(evt);
            }
        });
        partiToolbar.add(changeRoomButton1);

        topPanel.setLayout(new java.awt.BorderLayout());

        statusBar.setLayout(new java.awt.GridLayout(1, 3));

        timerField.setText("Ready");
        timerField.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        statusBar.add(timerField);
        statusBar.add(wbProgressBar);

        infoField.setText("Ready");
        infoField.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        statusBar.add(infoField);

        setDefaultCloseOperation(javax.swing.WindowConstants.DO_NOTHING_ON_CLOSE);
        setTitle("Realtime Virtual Classroom");
        addWindowListener(new java.awt.event.WindowAdapter() {
            public void windowClosing(java.awt.event.WindowEvent evt) {
                formWindowClosing(evt);
            }
        });

        mainSplitPane.setOneTouchExpandable(true);

        leftSplitPane.setOrientation(javax.swing.JSplitPane.VERTICAL_SPLIT);
        leftSplitPane.setBottomComponent(chatTabbedPane);

        mainSplitPane.setLeftComponent(leftSplitPane);

        surfacePanel.setLayout(new java.awt.BorderLayout());

        tabbedPane.setAutoscrolls(true);
        surfacePanel.add(tabbedPane, java.awt.BorderLayout.CENTER);

        toolsPanel.setLayout(new java.awt.BorderLayout());

        roomToolsToolbar.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        roomToolsToolbar.setRollover(true);

        changeRoomButton.setFont(new java.awt.Font("Dialog", 0, 11));
        changeRoomButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/join_room.png"))); // NOI18N
        changeRoomButton.setText("Room List");
        changeRoomButton.setBorderPainted(false);
        changeRoomButton.setContentAreaFilled(false);
        changeRoomButton.setFocusable(false);
        changeRoomButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        changeRoomButton.setName("roomList"); // NOI18N
        changeRoomButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        changeRoomButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                changeRoomButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                changeRoomButtonMouseExited(evt);
            }
        });
        changeRoomButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                changeRoomButtonActionPerformed(evt);
            }
        });
        roomToolsToolbar.add(changeRoomButton);

        pointerButton.setFont(new java.awt.Font("Dialog", 0, 11));
        pointerButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/arrow_side32.png"))); // NOI18N
        pointerButton.setText("Pointer");
        pointerButton.setBorderPainted(false);
        pointerButton.setContentAreaFilled(false);
        pointerButton.setFocusable(false);
        pointerButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        pointerButton.setName("pointer"); // NOI18N
        pointerButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        pointerButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                pointerButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                pointerButtonMouseExited(evt);
            }
        });
        pointerButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                pointerButtonActionPerformed(evt);
            }
        });
        roomToolsToolbar.add(pointerButton);

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
        roomToolsToolbar.add(deskShareButton);

        imagesButton.setFont(new java.awt.Font("Dialog", 0, 11));
        imagesButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/image.png"))); // NOI18N
        imagesButton.setText("Graphics");
        imagesButton.setBorderPainted(false);
        imagesButton.setContentAreaFilled(false);
        imagesButton.setFocusable(false);
        imagesButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        imagesButton.setName("images"); // NOI18N
        imagesButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        imagesButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                imagesButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                imagesButtonMouseExited(evt);
            }
        });
        imagesButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                imagesButtonActionPerformed(evt);
            }
        });
        roomToolsToolbar.add(imagesButton);

        notepadButton.setFont(new java.awt.Font("Dialog", 0, 11));
        notepadButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/kedit32.png"))); // NOI18N
        notepadButton.setText("Notepad");
        notepadButton.setBorderPainted(false);
        notepadButton.setContentAreaFilled(false);
        notepadButton.setFocusable(false);
        notepadButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        notepadButton.setName("notepad"); // NOI18N
        notepadButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        notepadButton.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                notepadButtonMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                notepadButtonMouseExited(evt);
            }
        });
        notepadButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                notepadButtonActionPerformed(evt);
            }
        });
        roomToolsToolbar.add(notepadButton);

        toolsPanel.add(roomToolsToolbar, java.awt.BorderLayout.CENTER);

        surfaceTopTabbedPane.addTab("Tools", toolsPanel);

        surfacePanel.add(surfaceTopTabbedPane, java.awt.BorderLayout.PAGE_START);

        mainSplitPane.setRightComponent(surfacePanel);

        getContentPane().add(mainSplitPane, java.awt.BorderLayout.CENTER);

        fileMenutem.setText("File");

        exitMenuItem.setText("Exit");
        exitMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                exitMenuItemActionPerformed(evt);
            }
        });
        fileMenutem.add(exitMenuItem);

        screenShareItem.add(fileMenutem);

        editMenu.setText("Edit");

        undoMenuItem.setAccelerator(javax.swing.KeyStroke.getKeyStroke(java.awt.event.KeyEvent.VK_Z, java.awt.event.InputEvent.CTRL_MASK));
        undoMenuItem.setText("Undo");
        undoMenuItem.setEnabled(false);
        undoMenuItem.setName("undo"); // NOI18N
        undoMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                undoMenuItemActionPerformed(evt);
            }
        });
        editMenu.add(undoMenuItem);

        screenShareItem.add(editMenu);

        viewMenu.setText("View");

        fullScreenMenuItem.setAccelerator(javax.swing.KeyStroke.getKeyStroke(java.awt.event.KeyEvent.VK_F5, 0));
        fullScreenMenuItem.setText("Full Screen");
        fullScreenMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                fullScreenMenuItemActionPerformed(evt);
            }
        });
        viewMenu.add(fullScreenMenuItem);

        escMenuItem.setAccelerator(javax.swing.KeyStroke.getKeyStroke(java.awt.event.KeyEvent.VK_ESCAPE, 0));
        escMenuItem.setText("Esc");
        escMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                escMenuItemActionPerformed(evt);
            }
        });
        viewMenu.add(escMenuItem);

        screenShareItem.add(viewMenu);

        actionsMenu.setText("Actions");
        actionsMenu.setName(""); // NOI18N

        insertGraphicMenuItem.setAccelerator(javax.swing.KeyStroke.getKeyStroke(java.awt.event.KeyEvent.VK_F3, 0));
        insertGraphicMenuItem.setText("Insert Graphic");
        insertGraphicMenuItem.setEnabled(false);
        insertGraphicMenuItem.setName("insertGraphic"); // NOI18N
        insertGraphicMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                insertGraphicMenuItemActionPerformed(evt);
            }
        });
        actionsMenu.add(insertGraphicMenuItem);

        insertPresentationMenuItem.setAccelerator(javax.swing.KeyStroke.getKeyStroke(java.awt.event.KeyEvent.VK_F4, 0));
        insertPresentationMenuItem.setText("Insert Presentation");
        insertPresentationMenuItem.setEnabled(false);
        insertPresentationMenuItem.setName("insertPresentation"); // NOI18N
        insertPresentationMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                insertPresentationMenuItemActionPerformed(evt);
            }
        });
        actionsMenu.add(insertPresentationMenuItem);

        nextPrevMenuItem.setAccelerator(javax.swing.KeyStroke.getKeyStroke(java.awt.event.KeyEvent.VK_RIGHT, 0));
        nextPrevMenuItem.setText("Next Slide");
        nextPrevMenuItem.setEnabled(false);
        nextPrevMenuItem.setName("nextSlide"); // NOI18N
        nextPrevMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                nextPrevMenuItemActionPerformed(evt);
            }
        });
        actionsMenu.add(nextPrevMenuItem);

        prevslideMenuItem.setAccelerator(javax.swing.KeyStroke.getKeyStroke(java.awt.event.KeyEvent.VK_LEFT, 0));
        prevslideMenuItem.setText("Previous Slide");
        prevslideMenuItem.setEnabled(false);
        prevslideMenuItem.setName("prevSlide"); // NOI18N
        prevslideMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                prevslideMenuItemActionPerformed(evt);
            }
        });
        actionsMenu.add(prevslideMenuItem);
        actionsMenu.add(jSeparator7);

        raiseHandMenuItem.setAccelerator(javax.swing.KeyStroke.getKeyStroke(java.awt.event.KeyEvent.VK_F1, 0));
        raiseHandMenuItem.setText("Raise Hand");
        raiseHandMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                raiseHandMenuItemActionPerformed(evt);
            }
        });
        actionsMenu.add(raiseHandMenuItem);

        screenShareItem.add(actionsMenu);

        meetingsMenuItem.setText("Meetings");
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
        toolsMenu.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                toolsMenuActionPerformed(evt);
            }
        });

        screenShareMenuItem.setAccelerator(javax.swing.KeyStroke.getKeyStroke(java.awt.event.KeyEvent.VK_F10, 0));
        screenShareMenuItem.setText("Screen Share");
        screenShareMenuItem.setEnabled(false);
        screenShareMenuItem.setName("screenshare"); // NOI18N
        screenShareMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                screenShareMenuItemActionPerformed(evt);
            }
        });
        toolsMenu.add(screenShareMenuItem);

        screenViewerMenuItem.setAccelerator(javax.swing.KeyStroke.getKeyStroke(java.awt.event.KeyEvent.VK_F7, 0));
        screenViewerMenuItem.setText("Screen Viewer");
        screenViewerMenuItem.setEnabled(false);
        screenViewerMenuItem.setName("screenviewer"); // NOI18N
        screenViewerMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                screenViewerMenuItemActionPerformed(evt);
            }
        });
        toolsMenu.add(screenViewerMenuItem);

        screenShareSizeMenuItem.setAccelerator(javax.swing.KeyStroke.getKeyStroke(java.awt.event.KeyEvent.VK_F11, 0));
        screenShareSizeMenuItem.setText("Screen Share Options");
        screenShareSizeMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                screenShareSizeMenuItemActionPerformed(evt);
            }
        });
        toolsMenu.add(screenShareSizeMenuItem);
        toolsMenu.add(jSeparator2);

        roomResourcesMenuItem.setText("Room Resources");
        roomResourcesMenuItem.setEnabled(false);
        roomResourcesMenuItem.setName("roomResources"); // NOI18N
        roomResourcesMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                roomResourcesMenuItemActionPerformed(evt);
            }
        });
        toolsMenu.add(roomResourcesMenuItem);

        questionManagerMenuItem.setText("Question Manager");
        questionManagerMenuItem.setEnabled(false);
        questionManagerMenuItem.setName("questionBuilder"); // NOI18N
        questionManagerMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                questionManagerMenuItemActionPerformed(evt);
            }
        });
        toolsMenu.add(questionManagerMenuItem);

        slideBuilderMenuItem.setText("Slide Builder");
        slideBuilderMenuItem.setEnabled(false);
        slideBuilderMenuItem.setName("slideBuilder"); // NOI18N
        slideBuilderMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                slideBuilderMenuItemActionPerformed(evt);
            }
        });
        toolsMenu.add(slideBuilderMenuItem);
        toolsMenu.add(jSeparator10);

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

        privateChatMenuItem.setText("Private Chat");
        privateChatMenuItem.setEnabled(false);
        privateChatMenuItem.setName("privatechat"); // NOI18N
        privateChatMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                privateChatMenuItemActionPerformed(evt);
            }
        });
        toolsMenu.add(privateChatMenuItem);

        tipsMenuItem.setText("Tips");
        tipsMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                tipsMenuItemActionPerformed(evt);
            }
        });
        toolsMenu.add(tipsMenuItem);

        magnifierMenuitem.setAccelerator(javax.swing.KeyStroke.getKeyStroke(java.awt.event.KeyEvent.VK_F9, 0));
        magnifierMenuitem.setText("Maginifier");
        magnifierMenuitem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                magnifierMenuitemActionPerformed(evt);
            }
        });
        toolsMenu.add(magnifierMenuitem);
        toolsMenu.add(jSeparator1);

        whiteboardToolsMenuItem.setText("Whiteboard Tools");
        whiteboardToolsMenuItem.setEnabled(false);
        whiteboardToolsMenuItem.setName("whiteboardtools"); // NOI18N
        whiteboardToolsMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                whiteboardToolsMenuItemActionPerformed(evt);
            }
        });
        toolsMenu.add(whiteboardToolsMenuItem);

        audioVideoTestMenuItem.setText("Audio/Video Test");
        audioVideoTestMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                audioVideoTestMenuItemActionPerformed(evt);
            }
        });
        toolsMenu.add(audioVideoTestMenuItem);
        toolsMenu.add(jSeparator9);

        optionsMenuItem.setText("Options");
        optionsMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                optionsMenuItemActionPerformed(evt);
            }
        });
        toolsMenu.add(optionsMenuItem);
        toolsMenu.add(jSeparator3);

        cleanMicsMenuItem.setText("Reset");
        cleanMicsMenuItem.setEnabled(false);
        cleanMicsMenuItem.setName("clearMics"); // NOI18N
        cleanMicsMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                cleanMicsMenuItemActionPerformed(evt);
            }
        });
        toolsMenu.add(cleanMicsMenuItem);

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

    public ParticipantListPanel getUserListPanel() {
        return userListPanel;
    }

    public ChatRoomManager getChatRoomManager() {
        return chatRoomManager;
    }

    public WhiteboardPanel getWhiteboardPanel() {
        return whiteboardPanel;
    }

    public JToolBar getToolbar() {
        return roomToolsToolbar;
    }

    public UserListFrame getUserListFrame() {
        return userListFrame;
    }

    public void releaseMIC() {
        if (ConnectionManager.getRoomName() != null &&
                GUIAccessManager.amIHoldingMic()) {

            userListPanel.getParticipantListTable().takeMic(ConnectionManager.getUsername());
        }
    }

    private void close() {
        try {
            releaseMIC();
            /*RealtimePacket p = null;
            if (GeneralUtil.isInstructor()) {
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
            }*/

            ConnectionManager.getConnection().disconnect();
        } catch (Exception ex) {
            ex.printStackTrace();
        }

        System.exit(
                0);

    }

    private void inviteParticipants() {
        InviteParticipants fr = new InviteParticipants();
        fr.setSize((ss.width / 3) * 2, (ss.height / 6) * 5);
        fr.setLocationRelativeTo(this);
        fr.setVisible(true);
    }

    public void setRoomMemberListFrame(RoomMemberListFrame roomMemberListFrame) {
        this.roomMemberListFrame = roomMemberListFrame;
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
        if (pointerListPanel == null) {
            pointerListPanel = new PointerListPanel();
        }

        pointerListPanel.setSize(300, 150);
        pointerListPanel.setLocationRelativeTo(this);
        pointerListPanel.setVisible(true);
        whiteboardPanel.getWhiteboard().setCurrentPointer(pointerListPanel.getSelectedPointer());

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

    private void changeRoomButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_changeRoomButtonActionPerformed
        showRoomList(null);
    }//GEN-LAST:event_changeRoomButtonActionPerformed

    private void changeRoomButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_changeRoomButtonMouseEntered
        changeRoomButton.setContentAreaFilled(true);
        changeRoomButton.setBorderPainted(true);
        // changeRoomButton.setText("Change Room");
    }//GEN-LAST:event_changeRoomButtonMouseEntered

    private void changeRoomButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_changeRoomButtonMouseExited
        changeRoomButton.setContentAreaFilled(false);
        changeRoomButton.setBorderPainted(false);
        // changeRoomButton.setText("");
    }//GEN-LAST:event_changeRoomButtonMouseExited

    private void privateChatMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_privateChatMenuItemActionPerformed
        // userListPanel.getUserListTree().initPrivateChat(ConnectionManager.getUsername(),GeneralUtil.getThisRoomOwner(), "Room Moderator");
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
        showRoomList(null);
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

    private void roomResourcesMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_roomResourcesMenuItemActionPerformed
        showRoomResourceList();
}//GEN-LAST:event_roomResourcesMenuItemActionPerformed

    private void joinRoomMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_joinRoomMenuItemActionPerformed
        String roomName = JOptionPane.showInputDialog("Enter room name: ");
        if (roomName != null) {
            chatRoomManager.joinAsParticipant(ConnectionManager.getUsername(), roomName.trim());
        }
    }//GEN-LAST:event_joinRoomMenuItemActionPerformed
    private void raiseHandActionPerformed(java.awt.event.ActionEvent evt) {
        GUIAccessManager.mf.getUserListPanel().getParticipantListTable().raiseHand();

    }
    private void changeRoomButton1MouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_changeRoomButton1MouseEntered
        // TODO add your handling code here:
    }//GEN-LAST:event_changeRoomButton1MouseEntered

    private void changeRoomButton1MouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_changeRoomButton1MouseExited
        // TODO add your handling code here:
    }//GEN-LAST:event_changeRoomButton1MouseExited

    private void changeRoomButton1ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_changeRoomButton1ActionPerformed
        showRoomList(null);
    }//GEN-LAST:event_changeRoomButton1ActionPerformed

    private void tipsMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_tipsMenuItemActionPerformed
        showTipOfDay();
    }//GEN-LAST:event_tipsMenuItemActionPerformed

    private void magnifierMenuitemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_magnifierMenuitemActionPerformed
        /*final JCheckBox onSwitch = new JCheckBox("Turn magnifier on");
        onSwitch.addActionListener(new ActionListener() {

        public void actionPerformed(ActionEvent evt) {*/
        glass.setVisible(!glass.isVisible());
        /*}
        });
        if (magnifierDialog == null) {

        magnifierDialog = new JDialog(this, "Magnifier", false);
        magnifierDialog.getContentPane().add(onSwitch);
        magnifierDialog.setSize(100, 50);
        magnifierDialog.pack();
        //magnifierDialog.setLocation(this.getX() + this.getWidth(), this.getY());
        magnifierDialog.addWindowListener(new WindowAdapter() {

        @Override
        public void windowClosing(WindowEvent e) {
        glass.setVisible(false);
        onSwitch.setSelected(false);
        }
        });
        }
        magnifierDialog.setVisible(true);*/
    }//GEN-LAST:event_magnifierMenuitemActionPerformed

    private void zoomInButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_zoomInButtonMouseEntered
        zoomInButton.setContentAreaFilled(true);
        zoomInButton.setBorderPainted(true);
}//GEN-LAST:event_zoomInButtonMouseEntered

    private void zoomInButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_zoomInButtonMouseExited
        zoomInButton.setContentAreaFilled(false);
        zoomInButton.setBorderPainted(false);
}//GEN-LAST:event_zoomInButtonMouseExited

    private void zoomInButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_zoomInButtonActionPerformed
        whiteBoardZoomIn();
}//GEN-LAST:event_zoomInButtonActionPerformed

    private void zoomOutButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_zoomOutButtonMouseEntered
        zoomOutButton.setContentAreaFilled(true);
        zoomOutButton.setBorderPainted(true);
}//GEN-LAST:event_zoomOutButtonMouseEntered

    private void zoomOutButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_zoomOutButtonMouseExited
        zoomOutButton.setContentAreaFilled(false);
        zoomOutButton.setBorderPainted(false);
}//GEN-LAST:event_zoomOutButtonMouseExited

    private void zoomOutButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_zoomOutButtonActionPerformed
        whiteBoardZoomOut();
}//GEN-LAST:event_zoomOutButtonActionPerformed

    private void zoomOriginalButtonMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_zoomOriginalButtonMouseEntered
        zoomOriginalButton.setContentAreaFilled(true);
        zoomOriginalButton.setBorderPainted(true);
}//GEN-LAST:event_zoomOriginalButtonMouseEntered

    private void zoomOriginalButtonMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_zoomOriginalButtonMouseExited
        zoomOriginalButton.setContentAreaFilled(false);
        zoomOriginalButton.setBorderPainted(false);
}//GEN-LAST:event_zoomOriginalButtonMouseExited

    private void zoomOriginalButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_zoomOriginalButtonActionPerformed
        whiteBoardZoomOriginal();
}//GEN-LAST:event_zoomOriginalButtonActionPerformed

    private void fullScreenMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_fullScreenMenuItemActionPerformed
        setFullScreen();
    }//GEN-LAST:event_fullScreenMenuItemActionPerformed

    private void undoMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_undoMenuItemActionPerformed
        whiteboardPanel.getWhiteboard().undo();
    }//GEN-LAST:event_undoMenuItemActionPerformed

    private void escMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_escMenuItemActionPerformed
        glass.setVisible(false);
    }//GEN-LAST:event_escMenuItemActionPerformed

    private void cleanMicsMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_cleanMicsMenuItemActionPerformed
        userListPanel.getParticipantListTable().clearMics();
    }//GEN-LAST:event_cleanMicsMenuItemActionPerformed

    private void nextPrevMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_nextPrevMenuItemActionPerformed
        webPresentNavigator.moveToNextSlide();
    }//GEN-LAST:event_nextPrevMenuItemActionPerformed

    private void prevslideMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_prevslideMenuItemActionPerformed
        webPresentNavigator.moveToPrevSlide();
    }//GEN-LAST:event_prevslideMenuItemActionPerformed

    private void raiseHandMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_raiseHandMenuItemActionPerformed
        if (ConnectionManager.isOwner) {
            JOptionPane.showMessageDialog(null, "You are room owner, no need of raising hand.");
            return;
        }
        if (handRaised) {
            userListPanel.getParticipantListTable().raiseHand();
            raiseHandMenuItem.setText("Lower Hand");
        } else {
            raiseHandMenuItem.setText("Raise Hand");
            userListPanel.getParticipantListTable().lowerHand();
        }
        handRaised = !handRaised;
    }//GEN-LAST:event_raiseHandMenuItemActionPerformed

    private void audioVideoTestMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_audioVideoTestMenuItemActionPerformed
        AudioVideoTest audioVideoTest = new AudioVideoTest();
        audioVideoTest.setLocationRelativeTo(null);
        audioVideoTest.setVisible(true);
    }//GEN-LAST:event_audioVideoTestMenuItemActionPerformed

    private void whiteboardToolsMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_whiteboardToolsMenuItemActionPerformed
        whiteboardPanel.getWhiteboard().displayToolsDialog();
    }//GEN-LAST:event_whiteboardToolsMenuItemActionPerformed

    private void questionManagerMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_questionManagerMenuItemActionPerformed
        if (!ConnectionManager.isOwner) {
            JOptionPane.showMessageDialog(null, "You do not have permission to perform this action in this room.");
            return;
        } else {
            org.avoir.realtime.gui.QuestionNavigator navi = new org.avoir.realtime.gui.QuestionNavigator(this);
            JFrame QManagerFrame = new JFrame("Questions Navigator");
            QManagerFrame.setSize((int) (ss.width / 4), (int) (ss.height / 2.5));
            QManagerFrame.setLocation((int) (ss.width / 2 - ss.width / 8), (int) (ss.height / 2 - ss.height / 5));
            QManagerFrame.setContentPane(navi);
            QManagerFrame.setVisible(true);
        }
    }//GEN-LAST:event_questionManagerMenuItemActionPerformed

    private void answerManagerMenuItemActionPerformed(java.awt.event.ActionEvent evt) {
        if (!ConnectionManager.isOwner) {
            JOptionPane.showMessageDialog(null, "You do not have permission to perform this action in this room.");
            return;
        } else {
            org.avoir.realtime.gui.AnswerNavigator navi = new org.avoir.realtime.gui.AnswerNavigator(this);
            JFrame AManagerFrame = new JFrame("Answers Navigator");
            AManagerFrame.setSize((int) (ss.width / 4), (int) (ss.height / 2.5));
            AManagerFrame.setLocation((int) (ss.width / 2 - ss.width / 8), (int) (ss.height / 2 - ss.height / 5));
            AManagerFrame.setContentPane(navi);
            AManagerFrame.setVisible(true);
        }
    }

    private void slideBuilderMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_slideBuilderMenuItemActionPerformed
        // TODO add your handling code here:
    }//GEN-LAST:event_slideBuilderMenuItemActionPerformed

    private void screenShareSizeMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_screenShareSizeMenuItemActionPerformed
        shareSizeFr.setVisible(true);

}//GEN-LAST:event_screenShareSizeMenuItemActionPerformed

    private void toolsMenuActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_toolsMenuActionPerformed
        // TODO add your handling code here:
    }//GEN-LAST:event_toolsMenuActionPerformed

    public void doZoom() {
        if (zoomControl) {
            zoomControl = false;
            whiteBoardZoom();
        } else {
            whiteboardPanel.getWhiteboard().zoomEnabled = false;
            whiteboardPanel.getWhiteboard().zoomOriginal();
            zoomControl = true;
        }
    }

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

    public void whiteBoardZoomIn() {
        whiteboardPanel.getWhiteboard().zoomIn();
    }

    public void whiteBoardZoomOut() {
        whiteboardPanel.getWhiteboard().zoomOut();
    }

    public void whiteBoardZoomOriginal() {
        whiteboardPanel.getWhiteboard().zoomOriginal();
    }

    public void whiteBoardZoom() {
        whiteboardPanel.getWhiteboard().zoomPan();
    }

    public void showTipOfDay() {
        TipOfDayDialog tipOfDayDialog = new TipOfDayDialog(this, false);
        tipOfDayDialog.setSize(400, 350);
        tipOfDayDialog.setLocationRelativeTo(this);
        tipOfDayDialog.setVisible(true);
    }

    public void setFullScreen() {

        whiteboardPanel.getWhiteboard().setFullScreen();
    }

    public ImageIcon getIcon(
            String iconType) {
        if (iconType.equals("alert")) {
            return this.alertIcon;
        } else {
            return this.chatIcon;
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

    public void iconLeftClicked(SysTrayMenuEvent evt) {
    }

    public void iconLeftDoubleClicked(SysTrayMenuEvent evt) {
    }

    public void menuItemSelected(SysTrayMenuEvent evt) {
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

    public RoomResourcesList getRoomResourcesList() {
        return roomResourcesList;
    }

    


    /*** The gets **/
    public JComponent getGlass() {
        return glass;
    }

    public JLabel getInfoField() {
        return infoField;
    }

    public JLabel getTimerField() {
        return timerField;
    }

    public JCheckBoxMenuItem getHandItem() {
        return handItem;
    }

    public SoundMonitor getSoundMonitor() {
        return soundMonitor;
    }

    public JSplitPane getMainSplitPane() {
        return mainSplitPane;
    }

    public JTabbedPane getSurfaceTopTabbedPane() {
        return surfaceTopTabbedPane;
    }

    public ArrayList<Speaker> getSpeakers() {
        return speakers;
    }

    public JPanel getSpeakersPanel() {
        return speakersPanel;
    }

    public JPanel getSurfacePanel() {
        return surfacePanel;
    }
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JMenuItem aboutMenuItem;
    private javax.swing.JMenu actionsMenu;
    private javax.swing.JMenuItem audioVideoTestMenuItem;
    private javax.swing.JMenuItem banUserMenuItem;
    private javax.swing.JButton changeRoomButton;
    private javax.swing.JButton changeRoomButton1;
    private javax.swing.JTabbedPane chatTabbedPane;
    private javax.swing.JMenuItem cleanMicsMenuItem;
    private javax.swing.JMenuItem createRoomMenuItem;
    private javax.swing.JButton deskShareButton;
    private javax.swing.JMenuItem desktopMenuItem;
    private javax.swing.JMenu editMenu;
    private javax.swing.JMenuItem escMenuItem;
    private javax.swing.JMenuItem exitMenuItem;
    private javax.swing.JMenu fileMenutem;
    private javax.swing.JMenuItem fullScreenMenuItem;
    private javax.swing.JMenu helpMenu;
    private javax.swing.JButton imagesButton;
    private javax.swing.JLabel infoField;
    private javax.swing.JMenuItem insertGraphicMenuItem;
    private javax.swing.JMenuItem insertPresentationMenuItem;
    private javax.swing.JMenu jMenu1;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JSeparator jSeparator1;
    private javax.swing.JSeparator jSeparator10;
    private javax.swing.JSeparator jSeparator2;
    private javax.swing.JSeparator jSeparator3;
    private javax.swing.JSeparator jSeparator4;
    private javax.swing.JSeparator jSeparator5;
    private javax.swing.JSeparator jSeparator7;
    private javax.swing.JSeparator jSeparator9;
    private javax.swing.JMenuItem joinRoomMenuItem;
    private javax.swing.JSplitPane leftSplitPane;
    private javax.swing.JMenuItem magnifierMenuitem;
    private javax.swing.JSplitPane mainSplitPane;
    private javax.swing.JMenu meetingsMenuItem;
    private javax.swing.JMenuItem nextPrevMenuItem;
    private javax.swing.JButton notepadButton;
    private javax.swing.JButton notepadButton1;
    private javax.swing.JMenuItem optionsMenuItem;
    private javax.swing.JToolBar partiToolbar;
    private javax.swing.JButton pointerButton;
    private javax.swing.JMenuItem prevslideMenuItem;
    private javax.swing.JMenuItem privateChatMenuItem;
    private javax.swing.JMenuItem questionManagerMenuItem;
    private javax.swing.JMenuItem raiseHandMenuItem;
    private javax.swing.JMenuItem roomListMenuItem;
    private javax.swing.JMenuItem roomResourcesMenuItem;
    private javax.swing.JToolBar roomToolsToolbar;
    private javax.swing.JMenuBar screenShareItem;
    private javax.swing.JMenuItem screenShareMenuItem;
    private javax.swing.JMenuItem screenShareSizeMenuItem;
    private javax.swing.JMenuItem screenViewerMenuItem;
    private javax.swing.JMenuItem slideBuilderMenuItem;
    private javax.swing.JPanel statusBar;
    private javax.swing.JPanel surfacePanel;
    private javax.swing.JTabbedPane surfaceTopTabbedPane;
    private javax.swing.JTabbedPane tabbedPane;
    private javax.swing.JMenuItem thisAppMenuItem;
    private javax.swing.JLabel timerField;
    private javax.swing.JMenuItem tipsMenuItem;
    private javax.swing.JTextArea titleField;
    private javax.swing.JPanel titlePanel;
    private javax.swing.JMenu toolsMenu;
    private javax.swing.JPanel toolsPanel;
    private javax.swing.JPanel topPanel;
    private javax.swing.JMenuItem undoMenuItem;
    private javax.swing.JMenu viewMenu;
    private javax.swing.ButtonGroup wbButtonGroup;
    private javax.swing.JProgressBar wbProgressBar;
    private javax.swing.JMenuItem whiteboardToolsMenuItem;
    private javax.swing.JButton zoomInButton;
    private javax.swing.JButton zoomOriginalButton;
    private javax.swing.JButton zoomOutButton;
    // End of variables declaration//GEN-END:variables
}
