/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.managers;

import avoir.realtime.tcp.base.*;

import avoir.realtime.tcp.base.user.User;
import avoir.realtime.tcp.base.user.UserLevel;
import avoir.realtime.tcp.base.user.UserObject;
import avoir.realtime.tcp.common.PresenceConstants;
import avoir.realtime.tcp.common.packet.PresencePacket;
import java.awt.Color;
import java.awt.Component;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.util.Timer;
import java.util.TimerTask;
import java.util.Vector;
import javax.swing.ImageIcon;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;
import javax.swing.JPopupMenu;
import javax.swing.JTable;
import javax.swing.ListSelectionModel;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;
import javax.swing.table.AbstractTableModel;
import javax.swing.table.DefaultTableCellRenderer;
import javax.swing.table.TableColumn;

/**
 *
 * @author developer
 */
public class UserListManager {

    RealtimeBase base;
    private JPopupMenu popup = new JPopupMenu();
    private JMenuItem allowControlItem = new JMenuItem("Allow Control");
    private JMenuItem stopControlItem = new JMenuItem("Stop Control");
    private JMenuItem callItem = new JMenuItem("Give Microphone");
    private JMenuItem sendPrivateMessageItem = new JMenuItem("Send Private Message");
    ImageIcon speakerIcon = ImageUtil.createImageIcon(this, "/icons/mute_off.png");
    ImageIcon micIcon = ImageUtil.createImageIcon(this, "/icons/mic.png");
    ImageIcon editIcon = ImageUtil.createImageIcon(this, "/icons/edit.png");
    ImageIcon handIcon = ImageUtil.createImageIcon(this, "/icons/hand.png");
    ImageIcon yesIcon = ImageUtil.createImageIcon(this, "/icons/yes.png");
    ImageIcon noIcon = ImageUtil.createImageIcon(this, "/icons/no.png");
    ImageIcon blankIcon = ImageUtil.createImageIcon(this, "/icons/blank.png");
    ImageIcon laughIcon = ImageUtil.createImageIcon(this, "/icons/laugh.jpeg");
    ImageIcon applaudIcon = ImageUtil.createImageIcon(this, "/icons/applaud.jpeg");
    ParticipantListingTableModel model;
    int selectedRow = 0;
    int DEFAULT_SIZE = 0;
    Timer timer = new Timer();
    boolean blink = false;
    boolean blinking = false;
    JTable table = new JTable();
    Vector<UserObject> userList = new Vector<UserObject>();

    public UserListManager(RealtimeBase base) {
        this.base = base;
        try {
            model = new ParticipantListingTableModel();
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        sendPrivateMessageItem.setEnabled(false);

        table.addMouseListener(new MouseAdapter() {

            @Override
            public void mousePressed(MouseEvent e) {
                if (e.isPopupTrigger()) {
                    if (selectedRow < userList.size() && userList.size() > 0) {
                        UserObject usr = userList.elementAt(selectedRow);
                        allowControlItem.setEnabled(usr.isHandRaised());
                        popup.show(table, e.getX(), e.getY());
                    }
                }
            }
        });

        sendPrivateMessageItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
            }
        });

        stopControlItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                if (selectedRow < userList.size() && userList.size() > 0) {
                    UserObject usr = userList.elementAt(selectedRow);
                    UserListManager.this.base.getTcpClient().sendPacket(new PresencePacket(usr.getUser().getSessionId(),
                            PresenceConstants.CONTROL_ICON, PresenceConstants.CONTROL_DISABLED,
                            usr.getUser().getUserName()));
                    stopControlItem.setEnabled(false);
                }
            }
        });
        allowControlItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                UserObject usr = userList.elementAt(selectedRow);
                UserListManager.this.base.getTcpClient().sendPacket(new PresencePacket(usr.getUser().getSessionId(),
                        PresenceConstants.CONTROL_ICON, PresenceConstants.CONTROL_ENABLED,
                        usr.getUser().getUserName()));
                allowControlItem.setEnabled(false);
                stopControlItem.setEnabled(true);
            }
        });

        table.setGridColor(new Color(238, 238, 238));
        if (base.isPresenter()) {
            popup.add(allowControlItem);
            popup.add(stopControlItem);
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
        table.setDefaultRenderer(UserObject.class, new LRenderer());
    }

    private void decorateTable() {
        table.setDefaultRenderer(UserObject.class, new LRenderer());
        TableColumn column = null;
        if (model != null) {
            for (int i = 0; i < model.getColumnCount(); i++) {
                column = table.getColumnModel().getColumn(i);
                if (i == 3) {
                    column.setPreferredWidth(300);
                } else {
                    column.setPreferredWidth(10);
                }
            }
        }
        table.setDefaultRenderer(UserObject.class, new LRenderer());
    }

    public void updateUserList(Vector<User> list) {
        userList.clear();
        for (int i = 0; i < list.size(); i++) {
            User usr = list.elementAt(i);

            addUser(usr, i);
        }
        table.setDefaultRenderer(UserObject.class, new LRenderer());
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
            this.setFont(new java.awt.Font("Dialog", 1, 10));
            setOpaque(true); //MUST do this for background to show up.

        }

        @Override
        public void setValue(Object val) {
            if (val instanceof ImageIcon) {
                this.setIcon((ImageIcon) val);
            }
            if (val instanceof UserObject) {
                UserObject userObject = (UserObject) val;
                if (userObject.isOnline()) {
                    if (userObject.isActive()) {

                        setFont(new java.awt.Font("Dialog", 0, 10));
                        this.setText(userObject.getDisplay());
                        this.setForeground(userObject.getColor());
                    } else {
                        setFont(new java.awt.Font("Dialog", 3, 10));
                        setForeground(Color.YELLOW);
                        this.setText(userObject.getDisplay() + "-Away");
                    }
                } else {

                    setFont(new java.awt.Font("Dialog", 3, 10));
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

            setFont(new java.awt.Font("Dialog", 0, 11));
            this.setHorizontalAlignment(javax.swing.SwingConstants.LEFT);

            if (val instanceof ImageIcon) {
                this.setIcon((ImageIcon) val);
            }
            if (val instanceof UserObject) {
                UserObject userObject = (UserObject) val;
                if (userObject.isOnline()) {
                    if (userObject.isActive()) {
                        setFont(new java.awt.Font("Dialog", 0, 10));
                        this.setForeground(userObject.getColor());
                        this.setText(userObject.getDisplay());

                    } else {
                        setFont(new java.awt.Font("Dialog", 3, 10));
                        setForeground(Color.RED);
                        this.setText(userObject.getDisplay() + "-Away");

                    }
                } else {
                    setFont(new java.awt.Font("Dialog", 3, 10));
                    setForeground(Color.LIGHT_GRAY);
                    this.setText(userObject.getDisplay() + "- Offline");
                }
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
            UserListManager.this.base.getTcpClient().sendPacket(new PresencePacket(usr.getUser().getSessionId(),
                    PresenceConstants.YES_ICON, PresenceConstants.DONT_SHOW_YES,
                    usr.getUser().getUserName()));
            UserListManager.this.base.getTcpClient().sendPacket(new PresencePacket(usr.getUser().getSessionId(),
                    PresenceConstants.NO_ICON, PresenceConstants.DONT_SHOW_NO,
                    usr.getUser().getUserName()));
        }
        //ok, we can now play with our hands :)
        base.getToolbarManager().getHandButton().setEnabled(true);
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
    }

    private UserObject decorateUser(UserObject userObject, User usr) {

        String presenter = usr.isPresenter() ? "Presenter" : "";
        String display = "";

        if (usr.getLevel() == (UserLevel.ADMIN)) {
            if (usr.isPresenter()) {

                userObject.setColor(Color.ORANGE);
                if (usr.getUserName().trim().equals(base.getUser().getUserName().trim())) {
                    presenter += " [Me]";
                    userObject.setColor(Color.BLUE);

                }
                display = usr.getFullName() + " (A) " + presenter;// ");//[" + usr.getIpAddress() + "]");

            } else {
                userObject.setColor(new Color(0, 131, 0));
                if (usr.getUserName().trim().equals(base.getUser().getUserName().trim())) {
                    presenter += " [Me]";
                    userObject.setColor(Color.BLUE);

                }
                display = usr.getFullName() + " (A) " + presenter;
            }

        }
        if (usr.getLevel() == (UserLevel.STUDENT)) {
            if (usr.isPresenter()) {
                userObject.setColor(Color.ORANGE);
                if (usr.getUserName().trim().equals(base.getUser().getUserName().trim())) {
                    presenter += " [Me]";
                    userObject.setColor(Color.BLUE);

                }
                display = usr.getFullName() + " (S) " + presenter;
            } else {
                userObject.setColor(Color.BLACK);
                if (usr.getUserName().trim().equals(base.getUser().getUserName().trim())) {
                    presenter += " [Me]";
                    userObject.setColor(Color.BLUE);

                }
                display = usr.getFullName() + " (S) " + presenter;
            }

        }
        if (usr.getLevel() == (UserLevel.LECTURER)) {
            if (usr.isPresenter()) {
                userObject.setColor(Color.ORANGE);
                if (usr.getUserName().trim().equals(base.getUser().getUserName().trim())) {
                    presenter += " [Me]";
                    userObject.setColor(Color.BLUE);

                }
                display = usr.getFullName() + " (L) " + presenter;
            } else {
                userObject.setColor(Color.ORANGE);
                if (usr.getUserName().trim().equals(base.getUser().getUserName().trim())) {
                    presenter += " [Me]";
                    userObject.setColor(Color.BLUE);

                }
                display = usr.getFullName() + " (L) " + presenter;
            }

        }
        if (usr.getLevel() == (UserLevel.GUEST)) {
            if (usr.isPresenter()) {
                userObject.setColor(Color.ORANGE);
                if (usr.getUserName().trim().equals(base.getUser().getUserName().trim())) {
                    presenter += " [Me]";
                    userObject.setColor(Color.BLUE);

                }
                display = usr.getFullName() + " (G) " + presenter;
            } else {
                userObject.setColor(Color.DARK_GRAY);
                if (usr.getUserName().trim().equals(base.getUser().getUserName().trim())) {
                    presenter += " [Me]";
                    userObject.setColor(Color.BLUE);

                }

                display = usr.getFullName() + " (G) " + presenter;
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

        if (usr.isPresenter()) {
            userList.add(0, decorateUser(userObject, usr));
        } else {
            userList.addElement(decorateUser(userObject, usr));
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

        table.setDefaultRenderer(UserObject.class, new LRenderer());
        UserObject userObject;
        try {
            userObject = userList.elementAt(index);

        } catch (Exception ex) {
            return;
        }
        userObject.setOnline(online);
        User usr = userObject.getUser();
        usr.setOnline(online);
        ImageIcon icon = blankIcon;
        String fullNames = usr.getFullName();
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
        }

        if (iconType == PresenceConstants.EDIT_ICON) {
            if (show) {
                icon = editIcon;
            } else {
                icon = blankIcon;
            }
            model.setValueAt(icon, index, 2);
        }

        if (iconType == PresenceConstants.HAND_ICON) {
            userObject.setHandRaised(show);
            if (usr.getUserName().equals(base.getUser().getUserName())) {
                base.setControl(show);
            }
            if (show) {
                icon = handIcon;
                blinking = true;
            } else {
                icon = blankIcon;
                blinking = false;
            }
            doBlinking(index, iconType, 500);
            model.setValueAt(icon, index, 2);
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
            doBlinking(index, iconType, 500);
            model.setValueAt(icon, index, 2);
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
            doBlinking(index, iconType, 500);
            model.setValueAt(icon, index, 2);
        }


        if (iconType == PresenceConstants.YES_ICON) {

            if (show) {
                icon = yesIcon;

            } else {
                icon = blankIcon;
            }
            model.setValueAt(icon, index, 2);
        }

        if (iconType == PresenceConstants.NO_ICON) {
            if (show) {
                icon = noIcon;

            } else {
                icon = blankIcon;
            }
            model.setValueAt(icon, index, 2);
        }


        if (iconType == PresenceConstants.CONTROL_ICON) {
            base.setControl(show);

            if (show) {
                fullNames = "**" + fullNames;
            } else {
                fullNames = "" + fullNames;
            }
            if (base.getFileTransferPanel() != null) {
                base.getFileTransferPanel().getNewButton().setEnabled(base.getControl());
            }
            //only affects selected user, but presenter still has ultimate control
            if (!base.getUser().isPresenter()) {
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    base.getToolbarManager().getFirstSlideButton().setEnabled(base.getControl());
                    base.getToolbarManager().getBackSlideButton().setEnabled(base.getControl());
                    base.getToolbarManager().getNextSlideButton().setEnabled(base.getControl());
                    base.getToolbarManager().getLastSlideButton().setEnabled(base.getControl());
                }
            }
            model.setValueAt(icon, index, 2);
        }


        if (iconType == PresenceConstants.STEP_OUT_ICON) {
            userObject.setActive(!show);
            model.setValueAt(userObject, index, 3);
        }
        if (iconType == PresenceConstants.ONLINE_STATUS_ICON) {

            userObject.setOnline(online);
            model.setValueAt(userObject, index, 3);
        }

        userList.set(index, decorateUser(userObject, usr));


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


        Thread t = new Thread() {

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
                base.getToolbarManager().getHandButton().setSelected(false);

            }
        };
        t.start();
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
                    model.setValueAt(icon, index, 2);
                    blink = true;
                }
            }
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
    }

    class ParticipantListingTableModel extends AbstractTableModel {

        private String[] columnNames = {
            "S", //0
            "M",
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

                Object[] row = {userObject.getSpeakerIcon(), userObject.getMicIcon(),
                    userObject.getPresenceIcon(), userObject
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
}
