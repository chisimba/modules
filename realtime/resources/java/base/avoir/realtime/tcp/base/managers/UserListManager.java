/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.managers;

import avoir.realtime.tcp.base.*;

import avoir.realtime.tcp.base.user.User;
import avoir.realtime.tcp.base.user.UserLevel;
import avoir.realtime.tcp.base.user.UserObject;
import java.awt.Color;
import java.awt.Component;
import java.awt.FlowLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.util.Vector;
import javax.swing.DefaultListModel;
import javax.swing.JLabel;
import javax.swing.JList;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;
import javax.swing.JPopupMenu;
import javax.swing.ListCellRenderer;
import javax.swing.ListSelectionModel;
import javax.swing.table.AbstractTableModel;

/**
 *
 * @author developer
 */
public class UserListManager {

    RealtimeBase base;
    private DefaultListModel userListModel = new DefaultListModel();
    private JList userList = new JList(userListModel);
    private JPopupMenu popup = new JPopupMenu();
    private JMenuItem allowControlItem = new JMenuItem("Allow Control");
    private JMenuItem stopControlItem = new JMenuItem("Stop Control");
    private JMenuItem callItem = new JMenuItem("Give Microphone");
    private JMenuItem sendPrivateMessageItem = new JMenuItem("Send Private Message");

    public UserListManager(RealtimeBase base) {
        this.base = base;
        userList.setCellRenderer(new CustomCellRenderer());
        userList.addMouseListener(new MouseAdapter() {

            @Override
            public void mousePressed(MouseEvent e) {
                if (e.isPopupTrigger()) {
                    int index = userList.getSelectedIndex();
                    UserObject usr = (UserObject) userListModel.elementAt(index);
                    allowControlItem.setEnabled(usr.isRaisedHand());
                    popup.show(userList, e.getX(), e.getY());
                }
            }
        });

        if (base.isPresenter()) {
            popup.add(allowControlItem);
            popup.add(stopControlItem);
            popup.addSeparator();
        }
        popup.add(sendPrivateMessageItem);

        stopControlItem.setEnabled(false);
    }

    public void updateUserList(Vector<User> list) {
        userListModel.clear();
        for (int i = 0; i < list.size(); i++) {
            User usr = list.elementAt(i);
            addUser(usr, i);
        }
    }

    public JList getUserList() {
        return userList;
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

    public int getUserCount() {
        return userListModel.size();
    }

    /**
     * Update date the status of this user
     * @param userName the user to be updated
     * @param raiseHand if has raised hand or not
     * @param allowControl if user has control to manipulate session or not
     * @param order the order in which the user raised the hand
     */
    public void updateUserList(String userName, boolean raiseHand, boolean allowControl,
            int order, boolean yes, boolean no, boolean isYesNoSession,boolean speakerEnabled, boolean micEnabled) {
        for (int i = 0; i < userListModel.getSize(); i++) {

            UserObject usr = (UserObject) userListModel.elementAt(i);
            if (usr.getUser().getUserName().equals(userName)) {
                setUser(usr.getUser(), i, raiseHand, allowControl, order, yes, no, isYesNoSession,speakerEnabled,micEnabled);
            //  break;
            }
        }
    }

    /**
     * Clears any votes
     */
    public void clearVote() {
        for (int i = 0; i < userListModel.getSize(); i++) {
            UserObject usr = (UserObject) userListModel.elementAt(i);
            setUser(usr.getUser(), i);
        }
        //ok, we can now play with our hands :)
        base.getToolbarManager().getHandButton().setEnabled(true);
    }

    /**
     * adds new user at the bottom of the list
     * @param user
     */
    public void addNewUser(User user) {
        for (int i = 0; i < userListModel.size(); i++) {
            UserObject usr = (UserObject) userListModel.elementAt(i);
            if (usr.getUser().getUserName().equals(user.getUserName())) {
                return;
            }
        }
        addUser(user, userListModel.size());
    }

    /**
     * Adds the new user at the specified index
     * @param usr the user
     * @param index index at which the user is to be added
     */
    public void addUser(User usr, int index) {
        UserObject jp = new UserObject(usr);
        jp.setLayout(new FlowLayout(FlowLayout.LEFT));   // NEW

        JLabel label = new JLabel();
        jp.setName("nonraised");

        String presenter = usr.isPresenter() ? "Presenter" : "";

        if (usr.isPresenter()) {
            index = 0;
        }
        jp.add(label);

        if (usr.getLevel().equals(UserLevel.ADMIN)) {
            if (usr.isPresenter()) {

                label.setForeground(Color.ORANGE);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(usr.getFullName() + " (A) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(new Color(0, 131, 0));
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(usr.getFullName() + " (A) " + presenter);// [" + usr.getIpAddress() + "]");

            }
            userListModel.add(index, jp);
        }
        if (usr.getLevel().equals(UserLevel.STUDENT)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(usr.getFullName() + " (S) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.BLACK);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(usr.getFullName() + " (S) " + presenter);// [" + usr.getIpAddress() + "]");

            }
            userListModel.add(index, jp);
        }
        if (usr.getLevel().equals(UserLevel.LECTURER)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(usr.getFullName() + " (L) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.ORANGE);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(usr.getFullName() + " (L) " + presenter);// [" + usr.getIpAddress() + "]");

            }
            userListModel.add(index, jp);
        }
        if (usr.getLevel().equals(UserLevel.GUEST)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(usr.getFullName() + " (G) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.DARK_GRAY);
                label.setText(usr.getFullName() + " (G) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            }
            userListModel.add(index, jp);
        }

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
    public void setUser(User usr, int index, boolean raiseHand,
            boolean allowControl, int order, boolean yes, boolean no,
            boolean isYesNoSession, boolean speakerEnabled, boolean micEnabled) {
        UserObject jp = new UserObject(usr);
        jp.setLayout(new FlowLayout(FlowLayout.LEFT));   // NEW

        JLabel label = new JLabel();
        JLabel audioLabel = new JLabel(ImageUtil.createImageIcon(this, "/icons/mute_off.png"));
        JLabel micLabel = new JLabel(ImageUtil.createImageIcon(this, "/icons/mic.png"));
        if (speakerEnabled) {
            jp.add(audioLabel);
        }
        if (micEnabled) {
            jp.add(micLabel);
        }
        jp.add(label);

        String presenter = usr.isPresenter() ? "Presenter" : "";
        String fullNames = usr.getFullName();
        jp.setRaisedHand(raiseHand);
        if (!base.getUser().isPresenter()) {
            if (usr.getUserName().equals(base.getUser().getUserName())) {
            }
        }

        if (raiseHand) {

            label.setIcon(ImageUtil.createImageIcon(this, "/icons/hand.png"));
        }
        if (allowControl) {
            label.setIcon(ImageUtil.createImageIcon(this, "/icons/edit.png"));

        }
        base.setControl(allowControl);
        //only affects selected user, but presenter still has ultimate control
        if (!base.getUser().isPresenter()) {
            if (usr.getUserName().equals(base.getUser().getUserName())) {
                base.getToolbarManager().getFirstSlideButton().setEnabled(base.getControl());
                base.getToolbarManager().getBackSlideButton().setEnabled(base.getControl());
                base.getToolbarManager().getNextSlideButton().setEnabled(base.getControl());
                base.getToolbarManager().getLastSlideButton().setEnabled(base.getControl());
            }
        }
        sendPrivateMessageItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
            }
        });

        stopControlItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                int index = userList.getSelectedIndex();
                UserObject usr = (UserObject) userListModel.elementAt(index);
                base.getTcpClient().sendAttentionPacket(usr.getUser().getUserName(), base.getSessionId(),
                        false, false, false, false, false, false,base.isSpeakerEnabled(),base.isMicEnabled());
                stopControlItem.setEnabled(false);

            }
        });
        allowControlItem.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                int index = userList.getSelectedIndex();
                UserObject usr = (UserObject) userListModel.elementAt(index);
                base.getTcpClient().sendAttentionPacket(usr.getUser().getUserName(), base.getSessionId(),
                        false, true, false, false, false, false,base.isSpeakerEnabled(),base.isMicEnabled());
                allowControlItem.setEnabled(false);
                stopControlItem.setEnabled(true);
            }
        });
        userList.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        userList.addMouseListener(new MouseAdapter() {

            @Override
            public void mousePressed(MouseEvent e) {
                if (e.isPopupTrigger()) {
                    int index = userList.getSelectedIndex();
                    UserObject usr = (UserObject) userListModel.elementAt(index);
                    allowControlItem.setEnabled(usr.isRaisedHand());
                    popup.show(userList, e.getX(), e.getY());
                }
            }
        });


        if (isYesNoSession) {
            if (yes) {
                label.setIcon(ImageUtil.createImageIcon(this, "/icons/yes.png"));

            }

            if (no) {
                label.setIcon(ImageUtil.createImageIcon(this, "/icons/no.png"));

            }
        }
        if (usr.getLevel().equals(UserLevel.ADMIN)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(fullNames + " (A) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(new Color(0, 131, 0));
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(fullNames + " (A) " + presenter);// [" + usr.getIpAddress() + "]");

            }
            userListModel.set(index, jp);
        }
        if (usr.getLevel().equals(UserLevel.STUDENT)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(fullNames + " (S) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.BLACK);
                label.setText(fullNames + " (S) " + presenter);// [" + usr.getIpAddress() + "]");

            }
            userListModel.set(index, jp);
        }
        if (usr.getLevel().equals(UserLevel.LECTURER)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(fullNames + " (L) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.ORANGE);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(fullNames + " (L) " + presenter);// [" + usr.getIpAddress() + "]");

            }   //must always be on top

            userListModel.set(0, jp);
        }
        if (usr.getLevel().equals(UserLevel.GUEST)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(fullNames + " (G) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.DARK_GRAY);
                label.setText(fullNames + " (G) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            }
            userListModel.set(index, jp);
        }

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
    public void setUser(User usr, int index) {
        UserObject jp = new UserObject(usr);
        jp.setLayout(new FlowLayout(FlowLayout.LEFT));   // NEW

        JLabel label = new JLabel();
        String presenter = usr.isPresenter() ? "Presenter" : "";
        String fullNames = usr.getFullName();
        jp.add(label);
        if (usr.getLevel().equals(UserLevel.ADMIN)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(fullNames + " (A) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(new Color(0, 131, 0));
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(fullNames + " (A) " + presenter);// [" + usr.getIpAddress() + "]");

            }
            userListModel.set(index, jp);
        }
        if (usr.getLevel().equals(UserLevel.STUDENT)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(fullNames + " (S) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.BLACK);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(fullNames + " (S) " + presenter);// [" + usr.getIpAddress() + "]");

            }
            userListModel.set(index, jp);
        }
        if (usr.getLevel().equals(UserLevel.LECTURER)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(fullNames + " (L) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.ORANGE);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(fullNames + " (L) " + presenter);// [" + usr.getIpAddress() + "]");

            }   //must always be on top

            userListModel.set(0, jp);
        }
        if (usr.getLevel().equals(UserLevel.GUEST)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(fullNames + " (G) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.DARK_GRAY);
                if (usr.getUserName().equals(base.getUser().getUserName())) {
                    presenter += " [Me]";
                    label.setForeground(Color.BLUE);

                }
                label.setText(fullNames + " (G) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            }
            userListModel.set(index, jp);
        }

    }

    /**
     * Removes this user from the list
     * @param user
     */
    public void removeUser(User user) {
        for (int i = 0; i < userListModel.size(); i++) {
            UserObject usr = (UserObject) userListModel.elementAt(i);
            if (usr.getUser().getUserName().equals(user.getUserName())) {
                userListModel.remove(i);
            }
        }
    }

    class ParticipantListingTableModel extends AbstractTableModel {

        private String[] columnNames = {
            "Hand", //0
            "Yes/No",
            "Chat",
            "Volume",
            "Participant"
        };
        private Object[][] data = new Object[0][columnNames.length];

        public ParticipantListingTableModel() {
            try {
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }

        public ParticipantListingTableModel(Vector<Vector> sessions) {
            try {
            } catch (Exception ex) {
                ex.printStackTrace();
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
            return getValueAt(0, c).getClass();
        }
    }
}
