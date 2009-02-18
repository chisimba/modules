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

/*
 * ClassroomMainFrame.java
 *
 * Created on 2008/10/30, 01:03:02
 */
package avoir.realtime.classroom;

import avoir.realtime.audio.client.AudioChatClient;
import avoir.realtime.chat.ChatRoom;

import avoir.realtime.classroom.packets.RemoveUserPacket;
import avoir.realtime.classroom.tcp.TCPConnector;

import avoir.realtime.classroom.whiteboard.WhiteboardSurface;
import avoir.realtime.common.Flash;
import avoir.realtime.common.ImageUtil;
import avoir.realtime.common.WebPage;
import avoir.realtime.common.packet.ChatLogPacket;
import avoir.realtime.common.packet.ChatPacket;
import avoir.realtime.common.user.User;
import avoir.realtime.common.user.UserLevel;

import avoir.realtime.filetransfer.FileReceiverManager;

import avoir.realtime.common.TCPSocket;
import avoir.realtime.common.packet.QuestionPacket;
import avoir.realtime.survey.AnsweringFrame;
import avoir.realtime.survey.SurveyFrame;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.GradientPaint;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.LayoutManager;
import java.awt.Rectangle;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.util.ArrayList;
import java.util.Vector;
import javax.swing.BorderFactory;
import javax.swing.ButtonGroup;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JSplitPane;
import javax.swing.JTabbedPane;
import javax.swing.JTable;
import javax.swing.JTextField;
import javax.swing.JToggleButton;
import javax.swing.JToolBar;

/**
 *
 * @author developer
 */
public class ClassroomMainFrame extends javax.swing.JFrame {

    protected String host;
    protected int port;
    protected String username;
    protected String fullnames;
    protected boolean isPresenter;
    protected String sessionId;
    protected String sessionTitle;
    protected String userLevel;
    protected String slidesDir;
    protected String siteRoot;
    protected String slidesServerId;
    protected String resourcesPath;
    protected JFrame parent;
    protected boolean webPresent;
    protected User user;
    protected TCPSocket tcpConnector;
    protected UserListManager userListManager;
    protected UserInteractionManager userInteractionManager;
    protected ChatRoom chatRoom;
    protected JTabbedPane userListTabbedPane = new JTabbedPane();
    protected JTabbedPane dockTabbedPane = new JTabbedPane();
    protected JPanel userListPanel = new JPanel(new BorderLayout());
    protected Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    protected String chatLogFile = "ChatLog.txt";
    protected TabbedPanePlaf docTabbedPaneUI = new TabbedPanePlaf();
    protected JTabbedPane mainTabbedPane = new CloseableTabbedPane();
    protected Vector<Flash> flashFiles = new Vector<Flash>();
    protected Vector<WebPage> webPages = new Vector<WebPage>();
    protected JToolBar userItemsPanel = new JToolBar();
    protected JScrollPane surfaceScrollPane;
    protected WhiteboardSurface whiteBoardSurface;
    protected RealtimeOptions realtimeOptions = new RealtimeOptions();
    protected FileReceiverManager fileRecieverManager;
    protected JPanel centerPanel = new JPanel(new BorderLayout());
    private String mediaServerHost;
    private int audioMICPort;
    private int audioSpeakerPort;
    //  protected RealtimeToolBar toolBar;
    protected ClassicToolbar toolBar;
    private AudioChatClient audioChatClient;
    private InfoPanel infoPanel;
    private UserListHeaderPanel userListHeaderPanel = new UserListHeaderPanel();
    private JTextField headerField;// = new JLabel("Class password: xxxx; Class Name: yyyyyy");
    final protected JPanel leftBottonNorthPanel = new JPanel(new BorderLayout());
    final protected JPanel ttop = new JPanel();
    protected ButtonGroup chatButtonGroup = new ButtonGroup();
    protected JToggleButton chatButton = new JToggleButton("Chat");

    public ClassroomMainFrame(
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
            boolean webPresent, String mediaServerHost,
            int audioMICPort,
            int audioSpeakerPort,
            JFrame parent) {
        this();
        this.host = host;
        this.port = port;
        this.username = username;
        this.fullnames = fullnames;
        this.isPresenter = isPresenter;
        this.sessionId = sessionId;
        this.sessionTitle = sessionTitle;
        this.userLevel = userLevel;
        this.slidesDir = slidesDir;
        this.siteRoot = siteRoot;
        this.slidesServerId = slidesServerId;
        this.resourcesPath = resourcesPath;
        this.parent = parent;
        this.webPresent = webPresent;
        this.mediaServerHost = mediaServerHost;
        this.audioMICPort = audioMICPort;
        this.audioSpeakerPort = audioSpeakerPort;
        headerField = new JTextField("Class password: " + sessionId + " | Class Name:  " + sessionTitle);
        headerField.setFont(new Font("Dialog", 1, 12));
        headerField.setEditable(false);
        user = createUser();
        userListManager = new UserListManager(this);
        userInteractionManager = new UserInteractionManager(this);
        whiteBoardSurface = new WhiteboardSurface(this);
        fileRecieverManager = new FileReceiverManager(this);
        surfaceScrollPane = new JScrollPane(whiteBoardSurface);
        tcpConnector = new TCPConnector(this);
        tcpConnector.setName("student");
        chatRoom = new ChatRoom(this, user, chatLogFile, sessionId, false, null, sessionId);
        chatRoom.setTcpSocket(tcpConnector);

        initCustomComponents();

    }

    public void initToolbar() {
        toolBar = new ClassicToolbar(this);
        addToolbarButtons();
        JPanel pp = new JPanel(new BorderLayout());
        pp.add(headerField, BorderLayout.NORTH);
        pp.add(toolBar, BorderLayout.CENTER);
        centerPanel.add(pp, BorderLayout.NORTH);
    }

    private void pause(long time) {
        try {
            Thread.sleep(time);
        } catch (Exception ex) {
        }
    }

    public void initAudio() {
        audioChatClient = new AudioChatClient(this);

        Thread t = new Thread() {

            public void run() {
                getAudioChatClient().connect();
                pause(2000);
                getAudioChatClient().signIn(getUser().getUserName());
            }
        };

        t.start();
    }

    private void addToolbarButtons() {
        toolBar.add("/icons/micro.png", "mic", "Microphone");
        toolBar.add("/icons/speaker.png", "speaker", "Speaker");
        toolBar.add("/icons/kedit.png", "notepad", "Notepad");
        toolBar.add("/icons/global_config.png", "config", "Settings");
    //toolBar.add("/icons/media-record.png", "record", "Record");
    //toolBar.add("/icons/player_play.png", "play", "Play");
    }

    protected void initCustomComponents() {
        centerPanel.add(mainTabbedPane, BorderLayout.CENTER);
        mainSplitPane.setRightComponent(centerPanel);
        mainTabbedPane.addTab("Default", surfaceScrollPane);
        userListTabbedPane.setFont(new Font("Dialog", 0, 11));

        dockTabbedPane.setUI(docTabbedPaneUI);

        docTabbedPaneUI.setBase(this, "/icons/popout.png", "Chat", "Popout");
        dockTabbedPane.setBorder(BorderFactory.createEtchedBorder());
        dockTabbedPane.setFont(new Font("Dialog", 0, 11));
        dockTabbedPane.addTab("Chat", chatRoom);
        dockTabbedPane.setSelectedIndex(0);
        chatButton.setIcon(ImageUtil.createImageIcon(this, "/icons/chat32.png"));

        chatButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {
                leftBottonNorthPanel.removeAll();
                leftBottonNorthPanel.add(ttop, BorderLayout.NORTH);
                leftBottonNorthPanel.add(chatRoom, BorderLayout.CENTER);
                leftSplitPane.repaint();

            }
        });
        chatButtonGroup.add(chatButton);
        chatButton.setSelected(true);
        ttop.add(chatButton);
        leftBottonNorthPanel.add(ttop, BorderLayout.NORTH);
        leftBottonNorthPanel.add(chatRoom, BorderLayout.CENTER);
        leftBottomPanel.add(leftBottonNorthPanel, BorderLayout.CENTER);
        infoPanel = new InfoPanel();
        infoPanel.setPreferredSize(new Dimension(150, 80));
        leftBottomPanel.add(infoPanel, BorderLayout.NORTH);


        JTable table = userListManager.getUserList();
        JScrollPane sp = new JScrollPane(table);
        sp.getViewport().setBackground(Color.WHITE);
        table.setShowGrid(false);
        table.setBackground(Color.WHITE);
        table.setOpaque(true);

        userListPanel.setLayout(new BorderLayout());
        //JLabel allLabel=new JLabel("Participants");
        userListHeaderPanel.setPreferredSize(new Dimension(150, 50));
        userListPanel.add(userListHeaderPanel, BorderLayout.NORTH);
        userListPanel.add(sp, BorderLayout.CENTER);
        userListPanel.add(userItemsPanel, BorderLayout.SOUTH);
        userListPanel.setBackground(Color.WHITE);
        /* userItemsPanel.add(userInteractionManager.getStepOutButton());
        userItemsPanel.add(userInteractionManager.getLaughterButton());
        userItemsPanel.add(userInteractionManager.getApplaudButton());
        userItemsPanel.add(userInteractionManager.getHandButton());
        userItemsPanel.setBorder(BorderFactory.createTitledBorder("Interact"));
         */
        userItemsPanel.setFont(new Font("Dialog", 0, 11));

        userListPanel.setPreferredSize(new Dimension(ss.width / 4, (ss.height / 4) * 3));
        mainSplitPane.setDividerLocation(ss.width / 4);
        leftSplitPane.setDividerLocation((ss.height / 2) - 80);

        //userListTabbedPane.addTab("Participants", userListPanel);
        topLeftPanel.add(userListPanel, BorderLayout.CENTER);
        parent.addWindowListener(new WindowAdapter() {

            @Override
            public void windowClosing(WindowEvent e) {
                tcpConnector.sendPacket(new RemoveUserPacket(user));
                System.exit(0);
            }
        });

    }

    public UserListHeaderPanel getUserListHeaderPanel() {
        return userListHeaderPanel;
    }

    public AudioChatClient getAudioChatClient() {
        return audioChatClient;
    }

    public int getAudioMICPort() {
        return audioMICPort;
    }

    public int getAudioSpeakerPort() {
        return audioSpeakerPort;
    }

    public String getMediaServerHost() {
        return mediaServerHost;
    }

    public ClassicToolbar getToolBar() {
        return toolBar;
    }

    public JSplitPane getLeftSplitPane() {
        return leftSplitPane;
    }

    public JSplitPane getMainSplitPane() {
        return mainSplitPane;
    }

    public JTabbedPane getMainTabbedPane() {
        return mainTabbedPane;
    }

    public FileReceiverManager getFileRecieverManager() {
        return fileRecieverManager;
    }

    public JLabel getTimerField() {
        return timerField;
    }

    public boolean isWebPresent() {
        return webPresent;
    }

    public void setWebPresent(boolean webPresent) {
        this.webPresent = webPresent;
    }

    public RealtimeOptions getRealtimeOptions() {
        return realtimeOptions;
    }

    public JScrollPane getSurfaceScrollPane() {
        return surfaceScrollPane;
    }

    public WhiteboardSurface getWhiteBoardSurface() {
        return whiteBoardSurface;
    }

    public ChatRoom getChatRoom() {
        return chatRoom;
    }

    public Vector<Flash> getFlashFiles() {
        return flashFiles;
    }

    public Vector<WebPage> getWebPages() {
        return webPages;
    }

    public void setTcpConnector(TCPSocket tcpConnector) {
        this.tcpConnector = tcpConnector;
    }

    protected void setChatTCPConnector(TCPConnector con) {
        chatRoom.setTcpSocket(tcpConnector);
    }

    public void setWhiteBoardSurface(WhiteboardSurface whiteBoardSurface) {
        this.whiteBoardSurface = whiteBoardSurface;
    }

    public JButton getAlertButton() {
        return alertButton;
    }

    /**
     * Diplays the chat room (frame)
     */
    public void showChatRoom() {
        final JPanel panel = (JPanel) dockTabbedPane.getComponentAt(0);

        JFrame fr = new JFrame("Chat - Close to dock");
        fr.addWindowListener(new WindowAdapter() {

            @Override
            public void windowClosing(WindowEvent e) {
                dockTabbedPane.removeAll();
                dockTabbedPane.add(panel, "Chat");
            }
        });
        fr.setLayout(new BorderLayout());
        fr.setAlwaysOnTop(true);
        fr.add(panel, BorderLayout.CENTER);
        fr.setSize(400, 300);
        fr.setLocationRelativeTo(null);
        fr.setVisible(true);
        dockTabbedPane.remove(0);
    }

    public InfoPanel getInfoPanel() {
        return infoPanel;
    }

    public void showSurveyFrame(ArrayList<QuestionPacket> questions, String title) {
        SurveyFrame fr = new SurveyFrame(this);
        fr.setQuestions(questions, title);
        fr.setTitle(title);
        fr.setAlwaysOnTop(true);
        fr.setSize((ss.width / 8) * 5, (ss.height / 8) * 5);
        fr.setLocationRelativeTo(null);
        fr.setVisible(true);
    }

    public void showQuestionFrame(QuestionPacket questions, String title) {
        AnsweringFrame fr = new AnsweringFrame(questions, this);

        fr.setTitle(title);
        fr.setAlwaysOnTop(true);
        fr.setSize((ss.width / 8) * 5, (ss.height / 8) * 5);
        fr.setLocationRelativeTo(null);
        fr.setVisible(true);
    }

    /**
     * Connect to server
     */
    public void connect() {
        Thread t = new Thread() {

            @Override
            public void run() {
                if (tcpConnector.connect(host, port)) {
                    chatRoom.setTcpSocket(tcpConnector);
                } else {
                    String msg = "Cannot connect to server";
                    JOptionPane.showMessageDialog(ClassroomMainFrame.this, msg);
                    showErrorMessage(msg);
                }

            }
        };
        t.start();
    }

    public TCPSocket getTcpConnector() {
        return tcpConnector;
    }

    public User getUser() {
        return user;
    }

    public JFrame getParentFrame() {
        return parent;
    }

    public void showInfoMessage(String msg) {
        infoField.setForeground(Color.BLACK);
        infoField.setText(msg);
    }

    public void showErrorMessage(String msg) {
        infoField.setForeground(Color.RED);
        infoField.setText(msg);
    }

    /** Creates new form ClassroomMainFrame */
    public ClassroomMainFrame() {
        initComponents();
    }

    public UserListManager getUserListManager() {
        return userListManager;
    }

    public JLabel getParticipantsField() {
        return participantsField;
    }

    class AudioButtonsPanel extends JPanel {

        public AudioButtonsPanel(LayoutManager lm) {
            super(lm);
            setBorder(BorderFactory.createEtchedBorder());
        }

        @Override
        public void paintComponent(Graphics g) {
            super.paintComponent(g);

            Graphics2D g2 = (Graphics2D) g;
            g2.setPaint(new GradientPaint(0, 0, Color.LIGHT_GRAY, getWidth(),
                    getHeight(), Color.WHITE, false));

            Rectangle r = new Rectangle(0, 0, getWidth(), getHeight());
            g2.fill(r);
        }
    }

    /**
     * set the sessions chat log. normally for a newly logged in user
     * @param chatLogPacket
     */
    public void updateChat(ChatLogPacket chatLogPacket) {
        if (chatRoom != null) {
            chatRoom.update(chatLogPacket);
        }

    }

    /**
     * Add new chat line
     * @param chatPacket
     */
    public void updateChat(ChatPacket chatPacket) {
        chatRoom.update(chatPacket);
    }

    /**
     * Create a user
     * @return
     */
    protected User createUser() {

        //if not logged in, then assign random number
        if (username == null || username.startsWith("Language")) { //not logging on through KEWL for dev, so give random user name

            username = "Guest" + new java.util.Random().nextInt(200);
            fullnames =
                    username;
        }

        int xuserLevel = UserLevel.GUEST; //assume guest unless set differently in applet param

        xuserLevel =
                UserLevel.getUserLevel(userLevel.toUpperCase());

        return new User(xuserLevel, fullnames, username, host, 22225, isPresenter,
                sessionId, sessionTitle, slidesDir, false, siteRoot, slidesServerId);

    }

    private void showAbout() {
        String title =
                "<h1>Realtime Communication Tools</h1><br>";
        String owner = "University of Western Cape<br>" +
                "Free Software Innovation Unit<br>";
        String developers = "Developers: <b>David Waf, Feroz Zaidi</b>";
        String cc = "<b>(c) 2008 AVOIR<br></b><br>";
        String aboutText = "<html><center>" +
                title +
                owner +
                cc +
                developers +
                "</center>";
        JOptionPane.showMessageDialog(null, aboutText);
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        footerPanel = new javax.swing.JPanel();
        infoField = new javax.swing.JLabel();
        participantsField = new javax.swing.JLabel();
        timerField = new javax.swing.JLabel();
        alertButton = new javax.swing.JButton();
        mainSplitPane = new javax.swing.JSplitPane();
        leftSplitPane = new javax.swing.JSplitPane();
        leftBottomPanel = new javax.swing.JPanel();
        topLeftPanel = new javax.swing.JPanel();
        menuBar = new javax.swing.JMenuBar();
        fileMenuItem = new javax.swing.JMenu();
        exitMenuItem = new javax.swing.JMenuItem();
        jMenu1 = new javax.swing.JMenu();
        aboutMenuItem = new javax.swing.JMenuItem();

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);

        footerPanel.setLayout(new java.awt.GridLayout(1, 5, 0, 1));

        infoField.setText("Ready");
        infoField.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        footerPanel.add(infoField);

        participantsField.setText("0 participants");
        participantsField.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        footerPanel.add(participantsField);

        timerField.setText("0.00 ");
        timerField.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        footerPanel.add(timerField);

        alertButton.setBorder(javax.swing.BorderFactory.createEtchedBorder());
        alertButton.setContentAreaFilled(false);
        footerPanel.add(alertButton);

        getContentPane().add(footerPanel, java.awt.BorderLayout.PAGE_END);

        leftSplitPane.setDividerLocation(200);
        leftSplitPane.setOrientation(javax.swing.JSplitPane.VERTICAL_SPLIT);

        leftBottomPanel.setLayout(new java.awt.BorderLayout());
        leftSplitPane.setRightComponent(leftBottomPanel);

        topLeftPanel.setLayout(new java.awt.BorderLayout());
        leftSplitPane.setLeftComponent(topLeftPanel);

        mainSplitPane.setLeftComponent(leftSplitPane);

        getContentPane().add(mainSplitPane, java.awt.BorderLayout.CENTER);

        fileMenuItem.setText("File");

        exitMenuItem.setText("Exit");
        exitMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                exitMenuItemActionPerformed(evt);
            }
        });
        fileMenuItem.add(exitMenuItem);

        menuBar.add(fileMenuItem);

        jMenu1.setText("Help");

        aboutMenuItem.setText("About");
        aboutMenuItem.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                aboutMenuItemActionPerformed(evt);
            }
        });
        jMenu1.add(aboutMenuItem);

        menuBar.add(jMenu1);

        setJMenuBar(menuBar);

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void aboutMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_aboutMenuItemActionPerformed
        showAbout();
    }//GEN-LAST:event_aboutMenuItemActionPerformed

    private void exitMenuItemActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_exitMenuItemActionPerformed
        System.exit(0);
    }//GEN-LAST:event_exitMenuItemActionPerformed

    /**
     * @param args the command line arguments
     */
    public static void main(String args[]) {
        java.awt.EventQueue.invokeLater(new Runnable() {

            public void run() {
                new ClassroomMainFrame().setVisible(true);
            }
        });
    }
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JMenuItem aboutMenuItem;
    private javax.swing.JButton alertButton;
    private javax.swing.JMenuItem exitMenuItem;
    private javax.swing.JMenu fileMenuItem;
    private javax.swing.JPanel footerPanel;
    private javax.swing.JLabel infoField;
    private javax.swing.JMenu jMenu1;
    private javax.swing.JPanel leftBottomPanel;
    protected javax.swing.JSplitPane leftSplitPane;
    private javax.swing.JSplitPane mainSplitPane;
    private javax.swing.JMenuBar menuBar;
    private javax.swing.JLabel participantsField;
    private javax.swing.JLabel timerField;
    private javax.swing.JPanel topLeftPanel;
    // End of variables declaration//GEN-END:variables
}
