/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.classroom;

import avoir.realtime.chat.PrivateChatFrame;
import avoir.realtime.common.ImageUtil;


import avoir.realtime.common.user.User;
import avoir.realtime.common.user.UserLevel;
import avoir.realtime.common.user.UserObject;
import avoir.realtime.common.PresenceConstants;
import avoir.realtime.common.packet.PresencePacket;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Component;
import java.awt.Font;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.io.Serializable;
import java.util.EventObject;
import java.util.Map;
import java.util.Timer;
import java.util.TimerTask;
import java.util.Vector;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JCheckBoxMenuItem;
import javax.swing.JComponent;
import javax.swing.JLabel;
import javax.swing.JMenuItem;
import javax.swing.JPanel;
import javax.swing.JPopupMenu;
import javax.swing.JTable;
import javax.swing.JTree;
import javax.swing.ListSelectionModel;
import javax.swing.SwingUtilities;
import javax.swing.event.CellEditorListener;
import javax.swing.event.ChangeEvent;
import javax.swing.event.EventListenerList;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;
import javax.swing.table.AbstractTableModel;
import javax.swing.table.DefaultTableCellRenderer;
import javax.swing.table.TableCellEditor;
import javax.swing.table.TableCellRenderer;
import javax.swing.table.TableColumn;
import javax.swing.tree.TreeCellEditor;

/**
 *
 * @author developer
 */
public class UserListManager {

    private JPopupMenu popup = new JPopupMenu();
    private JMenuItem giveMicItem = new JMenuItem("Give Microphone");
    private JMenuItem removeMicItem = new JMenuItem("Deny Microphone");
    private JMenuItem allowControlItem = new JMenuItem("Allow Control");
    private JMenuItem stopControlItem = new JMenuItem("Stop Control");
    private JCheckBoxMenuItem chatControlItem = new JCheckBoxMenuItem("Disable Text Chat");
    private JMenuItem callItem = new JMenuItem("Give Microphone");
    private JMenuItem sendPrivateMessageItem = new JMenuItem("Send Private Message");
    ImageIcon speakerIcon = ImageUtil.createImageIcon(this, "/icons/mute_off.png");
    ImageIcon chatOnIcon = ImageUtil.createImageIcon(this, "/icons/chat_enabled.png");
    ImageIcon chatOffIcon = ImageUtil.createImageIcon(this, "/icons/chat_disabled.png");
    ImageIcon micIcon = ImageUtil.createImageIcon(this, "/icons/mic.png");
    ImageIcon editIcon = ImageUtil.createImageIcon(this, "/icons/edit.png");
    ImageIcon handIcon = ImageUtil.createImageIcon(this, "/icons/hand.png");
    ImageIcon yesIcon = ImageUtil.createImageIcon(this, "/icons/yes.png");
    ImageIcon noIcon = ImageUtil.createImageIcon(this, "/icons/no.png");
    ImageIcon blankIcon = ImageUtil.createImageIcon(this, "/icons/blank.png");
    ImageIcon laughIcon = ImageUtil.createImageIcon(this, "/icons/laugh.jpeg");
    ImageIcon applaudIcon = ImageUtil.createImageIcon(this, "/icons/applaud.jpeg");
    ImageIcon editWBIcon = ImageUtil.createImageIcon(this, "/icons/editwhiteboard.png");
    ImageIcon userActiveIcon = ImageUtil.createImageIcon(this, "/icons/user_green.png");
    ImageIcon userOrangeIcon = ImageUtil.createImageIcon(this, "/icons/user_orange.png");
    ImageIcon userRedIcon = ImageUtil.createImageIcon(this, "/icons/user_red.png");
    ImageIcon soundIcon = ImageUtil.createImageIcon(this, "/icons/sound.png");
    ImageIcon correctAnswerIcon = ImageUtil.createImageIcon(this, "/icons/tick.png");
    ImageIcon wrongAnswerIcon = ImageUtil.createImageIcon(this, "/icons/cross16.png");
    ParticipantListingTableModel model;
    int selectedRow = 0;
    int DEFAULT_SIZE = 0;
    Timer timer = new Timer();
    boolean blink = false;
    boolean blinking = false;
    JTable table = new JTable();
    Vector<UserObject> userList = new Vector<UserObject>();
    private ClassroomMainFrame mf;
    private Timer blinkingTimer = new Timer();
    int fontSize = 16;
    private String currentPrivateChatUser = "";
    private String chatId = "";

    public UserListManager(final ClassroomMainFrame mf) {
        this.mf = mf;
        try {
            model = new ParticipantListingTableModel();
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        sendPrivateMessageItem.setEnabled(false);
        table.setTableHeader(null);
        table.addMouseListener(new MouseAdapter() {

            @Override
            public void mousePressed(MouseEvent e) {
                if (e.isPopupTrigger()) {
                    if (selectedRow < userList.size() && userList.size() > 0) {
                        UserObject usr = userList.elementAt(selectedRow);
                        allowControlItem.setEnabled(usr.isHandRaised());
                        chatControlItem.setSelected(!usr.getUser().isChatEnabled());
                        sendPrivateMessageItem.setEnabled(usr.getUser().isPresenter() && !usr.getUser().equals(mf.getUser()));
                        popup.show(table, e.getX(), e.getY());
                    }
                }
            }
        });
        table.setFont(new Font("Dialog", 0, 18));
        table.setDefaultRenderer(JComponent.class, new JComponentCellRenderer());
        table.setDefaultEditor(JComponent.class, new JComponentCellEditor());
        sendPrivateMessageItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                showPrivateChatFrame(userList.elementAt(selectedRow).getUser());
            }
        });


        chatControlItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                if (selectedRow < userList.size() && userList.size() > 0) {
                    /* UserObject usr = userList.elementAt(selectedRow);
                    UserListManager.this.mf.getTcpConnector().sendPacket(new PresencePacket(usr.getUser().getSessionId(),
                    PresenceConstants.CONTROL_ICON, chatControlItem.isSelected() ? PresenceConstants.CHAT_DISABLED : PresenceConstants.CHAT_ENABLED,
                    usr.getUser().getUserName()));
                     */

                    UserObject usr = userList.elementAt(selectedRow);
                    PresencePacket p = new PresencePacket(usr.getUser().getSessionId(),
                            PresenceConstants.CONTROL_ICON, chatControlItem.isSelected() ? PresenceConstants.CHAT_DISABLED : PresenceConstants.CHAT_ENABLED,
                            usr.getUser().getUserName());
                    mf.getTcpConnector().sendPacket(p);
                }
            }
        });
        stopControlItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                if (selectedRow < userList.size() && userList.size() > 0) {
                    UserObject usr = userList.elementAt(selectedRow);
                    UserListManager.this.mf.getTcpConnector().sendPacket(new PresencePacket(usr.getUser().getSessionId(),
                            PresenceConstants.CONTROL_ICON, PresenceConstants.CONTROL_DISABLED,
                            usr.getUser().getUserName()));
                    stopControlItem.setEnabled(false);
                }
            }
        });


        removeMicItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
            }
        });
        giveMicItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
            }
        });
        allowControlItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                UserObject usr = userList.elementAt(selectedRow);
                UserListManager.this.mf.getTcpConnector().sendPacket(new PresencePacket(usr.getUser().getSessionId(),
                        PresenceConstants.CONTROL_ICON, PresenceConstants.CONTROL_ENABLED,
                        usr.getUser().getUserName()));
                allowControlItem.setEnabled(false);
                stopControlItem.setEnabled(true);
            }
        });

        table.setGridColor(new Color(238, 238, 238));
        if (mf.getUser().isPresenter()) {
            //popup.add(allowControlItem);
            // popup.add(stopControlItem);
            popup.add(giveMicItem);
            popup.add(removeMicItem);
            popup.add(chatControlItem);
            popup.addSeparator();
        }
        //Ask to be notified of selection changes.
        ListSelectionModel rowSM = table.getSelectionModel();
        rowSM.addListSelectionListener(new ListSelectionListener() {

            public void valueChanged(ListSelectionEvent e) {
                //Ignore extra messages.
                if (e.getValueIsAdjusting()) {
                    return;
                }

                ListSelectionModel lsm =
                        (ListSelectionModel) e.getSource();
                if (lsm.isSelectionEmpty()) {
                } else {
                    selectedRow = lsm.getMinSelectionIndex();

                }
            }
        });
        if (model != null) {
            table.setModel(model);
        }
        popup.add(sendPrivateMessageItem);
        table.setShowHorizontalLines(false);
        stopControlItem.setEnabled(false);
        // table.setDefaultRenderer(UserObject.class, new LRenderer());
        table.setDefaultRenderer(JComponent.class, new JComponentCellRenderer());
        table.setDefaultEditor(JComponent.class, new JComponentCellEditor());

    }

    private void showPrivateChatFrame(User usr) {
        if (currentPrivateChatUser.equals("") || !currentPrivateChatUser.equals(usr.getUserName())) {
            chatId = String.valueOf((long) (Long.MIN_VALUE *
                    Math.random()));
            currentPrivateChatUser = usr.getUserName();
        }
        Map<String, PrivateChatFrame> privateChats = mf.getTcpConnector().getPrivateChats();
        PrivateChatFrame privateChatFrame = privateChats.get(chatId);
        if (privateChatFrame == null) {
            privateChatFrame = new PrivateChatFrame(usr, mf, chatId);
            privateChats.put(chatId, privateChatFrame);
        }

        privateChatFrame.show();
    }

    /**
     * Get user object based on user name
     * @param username
     * @return {@link User}
     */
    public User getUser(String username) {
        for (int i = 0; i < userList.size(); i++) {
            UserObject usr = userList.elementAt(i);

            if (usr.getUser().getUserName().equals(username)) {
                return usr.getUser();
            }
        }

        return null;
    }

    private void decorateTable() {
        //   table.setDefaultRenderer(UserObject.class, new LRenderer());
        table.setDefaultRenderer(JComponent.class, new JComponentCellRenderer());
        table.setDefaultEditor(JComponent.class, new JComponentCellEditor());

        TableColumn column = null;
        if (model != null) {
            for (int i = 0; i < model.getColumnCount(); i++) {
                column = table.getColumnModel().getColumn(i);
                if (i == 4) {
                    column.setPreferredWidth(280);
                } else if (i == 3) {
                    column.setPreferredWidth(100);
                } else {
                    column.setPreferredWidth(20);
                }
            }
        }
        table.setDefaultRenderer(JComponent.class, new JComponentCellRenderer());
        table.setDefaultEditor(JComponent.class, new JComponentCellEditor());

        // table.setDefaultRenderer(UserObject.class, new LRenderer());
        mf.getParticipantsField().setText(userList.size() + " " + (userList.size() > 1 ? "Participants" : "Partcipant"));

    }
    public void addMobileUserList(Vector<User> list) {
     
        for (int i = 0; i < list.size(); i++) {
            User usr = list.elementAt(i);
            usr.setUserName(usr.getUserName()+ "[Mobile]");
            usr.setOnline(true);
            addUser(usr, i);
        }
        //     table.setDefaultRenderer(UserObject.class, new LRenderer());
        table.setDefaultRenderer(JComponent.class, new JComponentCellRenderer());
        table.setDefaultEditor(JComponent.class, new JComponentCellEditor());

        model = new ParticipantListingTableModel();
        table.setModel(model);
        decorateTable();
    }

    public void updateUserList(Vector<User> list) {
        userList.clear();
        for (int i = 0; i < list.size(); i++) {
            User usr = list.elementAt(i);
            usr.setOnline(true);
            addUser(usr, i);
        }
        //     table.setDefaultRenderer(UserObject.class, new LRenderer());
        table.setDefaultRenderer(JComponent.class, new JComponentCellRenderer());
        table.setDefaultEditor(JComponent.class, new JComponentCellEditor());

        model = new ParticipantListingTableModel();
        table.setModel(model);
        decorateTable();
    }

    public JTable getUserList() {
        return table;
    }
    //TableCellRenderer r = new DefaultTableCellRenderer();
    class LRenderer extends DefaultTableCellRenderer {

        public LRenderer() {
            super();
            this.setFont(new java.awt.Font("Dialog", 1, fontSize));
            setOpaque(true); //MUST do this for background to show up.

        }

        @Override
        public void setValue(Object val) {
            System.out.println("A " + val.getClass());
            if (val instanceof ImageIcon) {
                this.setIcon((ImageIcon) val);
            }
            if (val instanceof Score) {
                Score score = (Score) val;
                this.setIcon(score.getIcon());
                this.setText(score.getText());
            }
            if (val instanceof UserObject) {
                UserObject userObject = (UserObject) val;
                if (userObject.isOnline()) {
                    if (userObject.isActive()) {

                        setFont(new java.awt.Font("Dialog", 0, fontSize));
                        this.setText(userObject.getDisplay());
                        this.setForeground(userObject.getColor());
                    } else {
                        setFont(new java.awt.Font("Dialog", 3, fontSize));
                        setForeground(Color.YELLOW);
                        this.setText(userObject.getDisplay() + "-Away");
                    }
                } else {

                    setFont(new java.awt.Font("Dialog", 3, fontSize));
                    setForeground(Color.RED);
                    this.setText(userObject.getDisplay() + "-Offline");
                }
            }

        }

        @Override
        public Component getTableCellRendererComponent(JTable table, Object val,
                boolean isSelected,
                boolean hasFocus,
                int row,
                int column) {

            if (isSelected) {

                setBackground(Color.yellow);
            } else {
                setBackground(table.getBackground());
            }

            setFont(new java.awt.Font("Dialog", 0, fontSize));
            this.setHorizontalAlignment(javax.swing.SwingConstants.LEFT);
            if (val instanceof ImageIcon) {
                this.setIcon((ImageIcon) val);
            }
            if (val instanceof Score) {
                Score score = (Score) val;
                this.setIcon(score.getIcon());
                this.setText(score.getText());
            }
            if (val instanceof UserObject) {
                UserObject userObject = (UserObject) val;
                if (userObject.isOnline()) {
                    if (userObject.isActive()) {
                        setFont(new java.awt.Font("Dialog", 0, fontSize));
                        this.setForeground(userObject.getColor());
                        this.setText(userObject.getDisplay());

                    } else {
                        setFont(new java.awt.Font("Dialog", 3, fontSize));
                        setForeground(Color.RED);
                        this.setText(userObject.getDisplay() + "-Away");

                    }
                } else {

                    setFont(new java.awt.Font("Dialog", 3, fontSize));
                    setForeground(Color.LIGHT_GRAY);
                    this.setText(userObject.getDisplay() + "- Offline");



                }

                setToolTipText(userObject.getUser().getUserDetails());

            }
            return this;
        }
    }

    public int getUserCount() {
        return userList.size();
    }

    /**
     * Clears any votes
     */
    public void clearVote() {
        for (int i = 0; i < userList.size(); i++) {
            UserObject usr = userList.elementAt(i);
            UserListManager.this.mf.getTcpConnector().sendPacket(new PresencePacket(usr.getUser().getSessionId(),
                    PresenceConstants.YES_ICON, PresenceConstants.DONT_SHOW_YES,
                    usr.getUser().getUserName()));
            UserListManager.this.mf.getTcpConnector().sendPacket(new PresencePacket(usr.getUser().getSessionId(),
                    PresenceConstants.NO_ICON, PresenceConstants.DONT_SHOW_NO,
                    usr.getUser().getUserName()));
        }

    }

    /**
     * adds new user at the bottom of the list
     * @param user
     */
    public void addNewUser(User user) {
        for (int i = 0; i < userList.size(); i++) {
            UserObject usr = (UserObject) userList.elementAt(i);

            if (usr.getUser().getUserName().equals(user.getUserName())) {
                return;
            }
        }
        addUser(user, userList.size());
        model = new ParticipantListingTableModel();
        table.setModel(model);
        decorateTable();
        mf.getParticipantsField().setText(userList.size() + " Participants");
        mf.getUserListHeaderPanel().setCount(userList.size());
        Color idleColor = Color.ORANGE;
        JLabel display = new JLabel();
        JPanel pp = new JPanel(new BorderLayout());
        pp.add(display, BorderLayout.CENTER);
        display.setFont(new Font("Dialog", 1, 18));
        display.setBackground(Color.WHITE);
        ImageIcon icon = userActiveIcon;
        pp.setBackground(Color.WHITE);

        if (user.isOrangleIdle()) {
            icon = userOrangeIcon;
            display.setFont(new Font("Dialog", 2, 18));

        }
        if (user.isRedIdle()) {
            idleColor = Color.RED;
            icon = userRedIcon;
            display.setFont(new Font("Dialog", 2, 18));

        }
        display.setForeground(idleColor);
        display.setText(user.getUserName());
        model.setValueAt(pp, user.isPresenter() ? 0 : model.getRowCount() - 1, 4);
        model.setValueAt(icon, user.isPresenter() ? 0 : model.getRowCount() - 1, 0);

    }

    private UserObject decorateUser(UserObject userObject, User usr) {

        String presenter = usr.isPresenter() ? "Presenter" : "";
        String display = "";

        if (usr.getLevel() == (UserLevel.ADMIN)) {
            if (usr.isPresenter()) {

                userObject.setColor(Color.ORANGE);
                if (usr.getUserName().trim().equals(mf.getUser().getUserName().trim())) {
                    presenter += " [Me]";
                    userObject.setColor(Color.ORANGE);

                }
                display = usr.getUserName() + " (A) " + presenter;// ");//[" + usr.getIpAddress() + "]");

            } else {
                userObject.setColor(new Color(0, 131, 0));
                if (usr.getUserName().trim().equals(mf.getUser().getUserName().trim())) {
                    presenter += " [Me]";
                    userObject.setColor(Color.ORANGE);

                }
                display = usr.getUserName() + " (A) " + presenter;
            }

        }
        if (usr.getLevel() == (UserLevel.STUDENT)) {
            if (usr.isPresenter()) {
                userObject.setColor(Color.ORANGE);
                if (usr.getUserName().trim().equals(mf.getUser().getUserName().trim())) {
                    presenter += " [Me]";
                    userObject.setColor(Color.ORANGE);

                }
                display = usr.getUserName() + " (S) " + presenter;
            } else {
                userObject.setColor(Color.BLACK);
                if (usr.getUserName().trim().equals(mf.getUser().getUserName().trim())) {
                    presenter += " [Me]";
                    userObject.setColor(Color.ORANGE);

                }
                display = usr.getUserName() + " (S) " + presenter;
            }

        }
        if (usr.getLevel() == (UserLevel.LECTURER)) {
            if (usr.isPresenter()) {
                userObject.setColor(Color.ORANGE);
                if (usr.getUserName().trim().equals(mf.getUser().getUserName().trim())) {
                    presenter += " [Me]";
                    userObject.setColor(Color.ORANGE);

                }
                display = usr.getUserName() + " (L) " + presenter;
            } else {
                userObject.setColor(Color.ORANGE);
                if (usr.getUserName().trim().equals(mf.getUser().getUserName().trim())) {
                    presenter += " [Me]";
                    userObject.setColor(Color.ORANGE);

                }
                display = usr.getUserName() + " (L) " + presenter;
            }

        }
        if (usr.getLevel() == (UserLevel.GUEST)) {
            if (usr.isPresenter()) {
                userObject.setColor(Color.ORANGE);
                if (usr.getUserName().trim().equals(mf.getUser().getUserName().trim())) {
                    presenter += " [Me]";
                    userObject.setColor(Color.ORANGE);

                }
                display = usr.getUserName() + " (G) " + presenter;
            } else {
                userObject.setColor(Color.DARK_GRAY);
                if (usr.getUserName().trim().equals(mf.getUser().getUserName().trim())) {
                    presenter += " [Me]";
                    userObject.setColor(Color.ORANGE);

                }

                display = usr.getUserName() + " (G) " + presenter;
            }

        }
        userObject.setDisplay(display);

        return userObject;
    }

    /**
     * Adds the new user at the specified index
     * @param usr the user
     * @param index index at which the user is to be added
     */
    public void addUser(User usr, int index) {
        UserObject userObject = new UserObject(usr, Color.BLACK, true, false, usr.isOnline());
        userObject.setSpeakerIcon(usr.isSpeakerOn() ? speakerIcon : blankIcon);
        userObject.setMicIcon(usr.isMicOn() ? micIcon : blankIcon);
        userObject.setPresenceIcon(usr.isEditOn() ? editIcon : blankIcon);
        userObject.setChatIcon(usr.isChatEnabled() ? chatOnIcon : chatOffIcon);
        if (usr.isOrangleIdle()) {
            userObject.setActiveIcon(userOrangeIcon);
        }
        if (usr.isRedIdle()) {
            userObject.setActiveIcon(userRedIcon);
        }

        userObject.setSpeakingIcon(usr.isSpeaking() ? soundIcon : blankIcon);
        if (usr.isPresenter()) {
            userList.add(0, decorateUser(userObject, usr));
        } else {
            userList.addElement(decorateUser(userObject, usr));
        }
        mf.getParticipantsField().setText(userList.size() + " Participants");
        mf.getUserListHeaderPanel().setCount(userList.size());

    }

    public void updateUser(User usr) {

        for (int i = 0; i < userList.size(); i++) {
            UserObject userObject = userList.elementAt(i);
            if (userObject.getUser().getUserName().equals(usr.getUserName())) {
                userObject.setUser(usr);
                userList.set(i, userObject);
            }
        }
    }

    public void updateUserAnswerStatus(User usr, boolean state) {

        for (int i = 0; i < userList.size(); i++) {
            UserObject userObject = userList.elementAt(i);

            if (userObject.getUser().getUserName().equals(usr.getUserName())) {
                userObject.setResultIcon(state ? correctAnswerIcon : wrongAnswerIcon);
                userObject.setUser(usr);
                int correctScores = userObject.getCorrectScores();
                int wrongScrores = userObject.getWrongScores();
                if (state) {
                    correctScores++;
                } else {
                    wrongScrores++;
                }
                userObject.setCorrectScores(correctScores);
                userObject.setWrongScores(wrongScrores);
                userList.set(i, userObject);
                setUser(i, PresenceConstants.ANSWER_ICON, state, true);
            }
        }
    }

    public void setAllUsersOnline(boolean state) {

        for (int i = 0; i < userList.size(); i++) {
            UserObject userObject = userList.elementAt(i);
            String username = userObject.getUser().getUserName();
            JLabel display = new JLabel();
            JPanel pp = new JPanel(new BorderLayout());
            pp.add(display, BorderLayout.CENTER);
            display.setBackground(Color.WHITE);
            pp.setBackground(Color.WHITE);
            //   display.setBorderPainted(false);
            //  display.setContentAreaFilled(false);
            display.setFont(new Font("Dialog", 0, 18));

            if (username.equals(mf.getUser().getUserName())) {
                display.setText(username + "-ME");
                display.setForeground(Color.ORANGE);

            } else {
                if (userObject.getUser().isPresenter()) {
                    display.setForeground(Color.ORANGE);
                } else {
                    display.setForeground(new Color(0, 131, 0));
                }
                display.setText(username);
            }
            userObject.setOnline(state);
            model.setValueAt(display, i, 3);
            userList.set(i, decorateUser(userObject, userObject.getUser()));
        }
    }

    public int getUserIndex(String username) {
        for (int i = 0; i < userList.size(); i++) {
            UserObject userObject = userList.elementAt(i);
            if (userObject.getUser().getUserName().equals(username)) {
                return i;
            }
        }
        return -1;
    }

    /**
     * Updates this user
     * @param usr the user
     * @param index index to be updated
     * @param raiseHand if has raised hand
     * @param allowControl if user has control over the session
     * @param order order in which user raised hand relative to others..if 
     * the user raised hand
     */
    public void setUser(int index, int iconType, boolean show, boolean online) {
        table.setDefaultRenderer(JComponent.class, new JComponentCellRenderer());
        table.setDefaultEditor(JComponent.class, new JComponentCellEditor());

        //table.setDefaultRenderer(UserObject.class, new LRenderer());
        UserObject userObject;
        try {
            userObject = userList.elementAt(index);

        } catch (Exception ex) {
            return;
        }
        userObject.setOnline(online);
        User usr = userObject.getUser();
        usr.setOnline(online);
        String username = userObject.getUser().getUserName();
        JLabel display = new JLabel();
        JPanel pp = new JPanel(new BorderLayout());
        pp.setBackground(Color.WHITE);
        pp.add(display, BorderLayout.CENTER);
        display.setBackground(Color.WHITE);
        pp.setBackground(Color.WHITE);
        display.setFont(new Font("Dialog", 0, 18));

        if (username.equals(mf.getUser().getUserName())) {
            display.setText(username + "-ME");
            display.setForeground(Color.ORANGE);

        } else {
            if (userObject.getUser().isPresenter()) {
                display.setForeground(Color.ORANGE);
            } else {
                display.setForeground(new Color(0, 131, 0));
            }
            display.setText(username);
        }

        ImageIcon icon = blankIcon;
        if (iconType == PresenceConstants.USER_IDLE_ICON) {
            Color idleColor = Color.ORANGE;
            if (show) {
                icon = userOrangeIcon;
            } else {
                idleColor = Color.RED;
                icon = userRedIcon;
            }
            display.setForeground(idleColor);
            display.setFont(new Font("Dialog", 2, 18));
            model.setValueAt(pp, index, 4);
            model.setValueAt(icon, index, 0);
        }
        if (iconType == PresenceConstants.SOUND_ICON) {
            if (show) {
                icon = soundIcon;
            } else {
                icon = blankIcon;
            }

            model.setValueAt(pp, index, 4);
            model.setValueAt(userActiveIcon, index, 0);
            model.setValueAt(icon, index, 2);
        }
        if (iconType == PresenceConstants.ANSWER_ICON) {
            if (show) {
                icon = correctAnswerIcon;
            } else {
                icon = wrongAnswerIcon;
            }
            icon = correctAnswerIcon;
            String txt = "<html><font color =\"green\"/>" + userObject.getCorrectScores() + " <font color =\"red\"/>" + userObject.getWrongScores();
            if (userObject.getCorrectScores() == 0 && userObject.getWrongScores() == 0) {
                txt = "";
                icon = blankIcon;
            }
            icon = userObject.getCorrectScores() > userObject.getWrongScores() ? correctAnswerIcon : wrongAnswerIcon;
            JButton button = new JButton(icon);
            button.setContentAreaFilled(false);
            button.setBorderPainted(false);
            button.setText(txt);
            button.setFont(new Font("Dialog", 0, 10));
            model.setValueAt(pp, index, 4);

            model.setValueAt(userActiveIcon, index, 0);
            model.setValueAt(button, index, 3);
        }
        /*
        if (iconType == PresenceConstants.SPEAKER_ICON) {
        if (show) {
        icon = speakerIcon;
        } else {
        icon = blankIcon;
        }
        model.setValueAt(icon, index, 0);
        }

        if (iconType == PresenceConstants.MIC_ICON) {
        if (show) {
        icon = micIcon;
        } else {
        icon = blankIcon;
        }
        model.setValueAt(icon, index, 1);
        }*/

        if (iconType == PresenceConstants.EDIT_ICON) {
            if (show) {
                icon = editIcon;
            } else {
                icon = usr.isChatEnabled() ? chatOnIcon : chatOffIcon;
            }
            model.setValueAt(pp, index, 4);

            model.setValueAt(userActiveIcon, index, 0);
            model.setValueAt(icon, index, 1);
        }
        if (iconType == PresenceConstants.EDIT_WB_ICON) {
            if (show) {
                icon = editWBIcon;
            } else {
                icon = usr.isChatEnabled() ? chatOnIcon : chatOffIcon;
            }
            model.setValueAt(pp, index, 4);

            model.setValueAt(userActiveIcon, index, 0);
            model.setValueAt(icon, index, 1);
        }

        if (iconType == PresenceConstants.HAND_ICON) {
            userObject.setHandRaised(show);
            if (usr.getUserName().equals(mf.getUser().getUserName())) {
                // mf.setControl(show);
            }
            if (show) {
                icon = handIcon;
                blinking = true;
            } else {
                icon = blankIcon;
                blinking = false;
            }
            model.setValueAt(pp, index, 4);

            model.setValueAt(userActiveIcon, index, 0);
            doBlinking(index, iconType, 500);
//            model.setValueAt(icon, index, 2);
        }
        if (iconType == PresenceConstants.LAUGHTER_ICON) {

            userObject.setHandRaised(show);
            if (show) {
                blinking = true;
                icon = laughIcon;

            } else {
                blinking = false;
                icon = blankIcon;
            }
            model.setValueAt(pp, index, 4);

            model.setValueAt(userActiveIcon, index, 0);
            doBlinking(index, iconType, 500);
        //          model.setValueAt(icon, index, 2);
        }
        if (iconType == PresenceConstants.APPLAUD_ICON) {
            userObject.setHandRaised(show);
            if (show) {
                icon = applaudIcon;
                blinking = true;

            } else {
                blinking = false;
                icon = blankIcon;
            }
            model.setValueAt(pp, index, 4);

            model.setValueAt(userActiveIcon, index, 0);
            doBlinking(index, iconType, 500);
        //        model.setValueAt(icon, index, 2);
        }


        if (iconType == PresenceConstants.YES_ICON) {

            if (show) {
                icon = yesIcon;

            } else {
                icon = blankIcon;
            }
            model.setValueAt(pp, index, 4);

            model.setValueAt(userActiveIcon, index, 0);
        //      model.setValueAt(icon, index, 2);
        }

        if (iconType == PresenceConstants.NO_ICON) {
            if (show) {
                icon = noIcon;

            } else {
                icon = blankIcon;
            }

            model.setValueAt(pp, index, 4);
            model.setValueAt(userActiveIcon, index, 0);
        //    model.setValueAt(icon, index, 2);
        }


        if (iconType == PresenceConstants.CONTROL_ICON) {


            if (show) {
                //fullNames = "**" + fullNames;
                icon = chatOnIcon;
            } else {
                //fullNames = "" + fullNames;
                icon = chatOffIcon;

            }
            if (mf.getUser().getUserName().equals(usr.getUserName())) {
                mf.getChatRoom().getChatIn().setEditable(show);
                mf.getChatRoom().getChatSubmit().setEnabled(show);
                String txt = show ? "" : "YOUR TEXT CHAT HAS BEEN DISABLED BY MODERATOR";
                mf.getChatRoom().getChatIn().setText(txt);

            }
            model.setValueAt(pp, index, 4);

            model.setValueAt(icon, index, 1);
        }


        if (iconType == PresenceConstants.STEP_OUT_ICON) {
            userObject.setActive(!show);
            model.setValueAt(display, index, 4);
        }
        if (iconType == PresenceConstants.ONLINE_STATUS_ICON) {

            userObject.setOnline(online);
            model.setValueAt(display, index, 3);
        }

        userList.set(index, decorateUser(userObject, usr));
//table.setModel(model);

    }
    ImageIcon icon;

    private void doBlinking(final int index, int iconType, final int delay) {

        switch (iconType) {
            case PresenceConstants.HAND_ICON: {
                icon = handIcon;
                break;
            }
            case PresenceConstants.LAUGHTER_ICON: {
                icon = laughIcon;
                break;
            }
            case PresenceConstants.APPLAUD_ICON: {
                icon = applaudIcon;
                break;
            }
        }
        blinkingTimer.cancel();
        blinkingTimer = new Timer();
        blinkingTimer.scheduleAtFixedRate(new BlinkIcon(icon, index), 0, delay);

    /*   Thread t = new Thread() {

    public void run() {
    int count = 0;
    while (blinking && count++ < 20) {
    if (blink) {

    model.setValueAt(icon, index, 2);
    blink = false;
    } else {

    model.setValueAt(blankIcon, index, 2);
    blink = true;

    }
    delay(this, delay);
    }

    model.setValueAt(blankIcon, index, 2);

    }
    };
    t.start();*/
    }

    private void delay(Thread t, long time) {
        try {
            t.sleep(time);
        } catch (Exception ex) {
        }
    }

    class BlinkIcon extends TimerTask {

        ImageIcon icon;
        int index = -1;
        int count = 0;

        public BlinkIcon(ImageIcon icon, int index) {
            this.icon = icon;
            this.index = index;
        }

        public void run() {
            if (index > -1 && icon != null) {
                if (blink) {

                    model.setValueAt(icon, index, 2);
                    blink = false;
                } else {
                    model.setValueAt(blankIcon, index, 2);
                    blink = true;
                }
            }
            if (count > 20) {
                cancel();
            }
            count++;
        }
    }

    /**
     * Removes this user from the list
     * @param user
     */
    public void removeUser(User user) {
        for (int i = 0; i < userList.size(); i++) {
            UserObject usr = userList.elementAt(i);
            if (usr.getUser().getUserName().equals(user.getUserName())) {
                userList.remove(i);
            }
        }
        model = new ParticipantListingTableModel();
        table.setModel(model);
        decorateTable();
        mf.getParticipantsField().setText(userList.size() + " Participants");

    }

    class ParticipantListingTableModel extends AbstractTableModel {

        private String[] columnNames = {
            "U",
            "C", //0
            "S",
            "P",
            "Name"
        };
        private Object[][] data = new Object[DEFAULT_SIZE][columnNames.length];

        public ParticipantListingTableModel() {
            if (userList.size() > DEFAULT_SIZE) {
                data = new Object[userList.size()][columnNames.length];
            } else {
                data = new Object[DEFAULT_SIZE][columnNames.length];
            }
            for (int i = 0; i < userList.size(); i++) {
                UserObject userObject = userList.elementAt(i);
                String username = userObject.getUser().getUserName();
                JLabel display = new JLabel();
                JPanel pp = new JPanel(new BorderLayout());
                pp.add(display, BorderLayout.CENTER);
                pp.setBackground(Color.WHITE);
                display.setBackground(Color.WHITE);
                pp.setBackground(Color.WHITE);
                display.setFont(new Font("Dialog", 0, 18));
                ImageIcon icon = userActiveIcon;
                if (username.equals(mf.getUser().getUserName())) {
                    display.setText(username + "-ME");
                    display.setForeground(Color.ORANGE);

                } else {
                    if (userObject.getUser().isPresenter()) {
                        display.setForeground(Color.ORANGE);
                    } else {
                        if (userObject.getUser().isPresenter()) {
                            display.setForeground(Color.ORANGE);
                        } else {
                            display.setForeground(new Color(0, 131, 0));
                        }
                    }

                    display.setText(username);
                }
                if (userObject.getUser().isOrangleIdle()) {
                    display.setForeground(Color.ORANGE);
                    display.setFont(new Font("Dialog", 2, 18));
                    icon = userOrangeIcon;
                }

                if (userObject.getUser().isRedIdle()) {
                    display.setForeground(Color.RED);
                    display.setFont(new Font("Dialog", 2, 18));
                    icon = userRedIcon;
                }
                Object[] row = {icon, userObject.getChatIcon(),
                    userObject.getSpeakingIcon(), userObject.getScore(), pp
                };
                data[i] = row;
            }
        }

        public int getColumnCount() {
            return columnNames.length;
        }

        public int getRowCount() {
            return data.length;
        }

        @Override
        public String getColumnName(int col) {
            return columnNames[col];
        }

        public Object getValueAt(int row, int col) {
            return data[row][col];

        }

        @Override
        public void setValueAt(Object value, int row, int col) {

            data[row][col] = value;
            fireTableCellUpdated(row, col);
        }

        /*
         * JTable uses this method to determine the default renderer/
         * editor for each cell.  If we didn't implement this method,
         * then the last column would contain text ("true"/"false"),
         * rather than a check box.
         */
        @Override
        public Class getColumnClass(int c) {

            Object obj = getValueAt(0, c);
            if (obj != null) {
                return getValueAt(0, c).getClass();
            } else {
                return new Object().getClass();
            }
        }
    }

    // End of variables declaration
    class JComponentCellRenderer implements TableCellRenderer {

        public Component getTableCellRendererComponent(JTable table, Object value,
                boolean isSelected, boolean hasFocus, int row, int column) {
            return (JComponent) value;
        }
    }

    class JComponentCellEditor implements TableCellEditor, TreeCellEditor,
            Serializable {

        protected EventListenerList listenerList = new EventListenerList();
        transient protected ChangeEvent changeEvent = null;
        protected JComponent editorComponent = null;
        protected JComponent container = null;		// Can be tree or table

        public Component getComponent() {
            return editorComponent;
        }

        public Object getCellEditorValue() {
            return editorComponent;
        }

        public boolean isCellEditable(EventObject anEvent) {
            return true;
        }

        public boolean shouldSelectCell(EventObject anEvent) {
            if (editorComponent != null && anEvent instanceof MouseEvent && ((MouseEvent) anEvent).getID() == MouseEvent.MOUSE_PRESSED) {
                Component dispatchComponent = SwingUtilities.getDeepestComponentAt(editorComponent, 3, 3);
                MouseEvent e = (MouseEvent) anEvent;
                MouseEvent e2 = new MouseEvent(dispatchComponent, MouseEvent.MOUSE_RELEASED,
                        e.getWhen() + 100000, e.getModifiers(), 3, 3, e.getClickCount(),
                        e.isPopupTrigger());
                dispatchComponent.dispatchEvent(e2);
                e2 = new MouseEvent(dispatchComponent, MouseEvent.MOUSE_CLICKED,
                        e.getWhen() + 100001, e.getModifiers(), 3, 3, 1,
                        e.isPopupTrigger());
                dispatchComponent.dispatchEvent(e2);
            }
            return false;
        }

        public boolean stopCellEditing() {
            fireEditingStopped();
            return true;
        }

        public void cancelCellEditing() {
            fireEditingCanceled();
        }

        public void addCellEditorListener(CellEditorListener l) {
            listenerList.add(CellEditorListener.class, l);
        }

        public void removeCellEditorListener(CellEditorListener l) {
            listenerList.remove(CellEditorListener.class, l);
        }

        protected void fireEditingStopped() {
            Object[] listeners = listenerList.getListenerList();
            // Process the listeners last to first, notifying
            // those that are interested in this event
            for (int i = listeners.length - 2; i >= 0; i -= 2) {
                if (listeners[i] == CellEditorListener.class) {
                    // Lazily create the event:
                    if (changeEvent == null) {
                        changeEvent = new ChangeEvent(this);
                    }
                    ((CellEditorListener) listeners[i + 1]).editingStopped(changeEvent);
                }
            }
        }

        protected void fireEditingCanceled() {
            // Guaranteed to return a non-null array
            Object[] listeners = listenerList.getListenerList();
            // Process the listeners last to first, notifying
            // those that are interested in this event
            for (int i = listeners.length - 2; i >= 0; i -= 2) {
                if (listeners[i] == CellEditorListener.class) {
                    // Lazily create the event:
                    if (changeEvent == null) {
                        changeEvent = new ChangeEvent(this);
                    }
                    ((CellEditorListener) listeners[i + 1]).editingCanceled(changeEvent);
                }
            }
        }

        // implements javax.swing.tree.TreeCellEditor
        public Component getTreeCellEditorComponent(JTree tree, Object value,
                boolean isSelected, boolean expanded, boolean leaf, int row) {
            String stringValue = tree.convertValueToText(value, isSelected,
                    expanded, leaf, row, false);

            editorComponent = (JComponent) value;
            container = tree;
            return editorComponent;
        }

        // implements javax.swing.table.TableCellEditor
        public Component getTableCellEditorComponent(JTable table, Object value,
                boolean isSelected, int row, int column) {

            editorComponent = (JComponent) value;
            container = table;
            return editorComponent;
        }
    } // End of class JComponentCellEditor

    class Score {

        private ImageIcon icon;
        private String text;

        public Score(ImageIcon icon, String text) {
            this.icon = icon;
            this.text = text;
        }

        public ImageIcon getIcon() {
            return icon;
        }

        public void setIcon(ImageIcon icon) {
            this.icon = icon;
        }

        public String getText() {
            return text;
        }

        public void setText(String text) {
            this.text = text;
        }
    }
}
