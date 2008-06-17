package avoir.realtime.tcp.base.user;

import javax.swing.*;
import java.awt.*;

public class UserList {

     DefaultListModel listModel = new DefaultListModel();
    private JList userList = new JList(listModel);

    public UserList() {
        userList.setBorder(BorderFactory.createLoweredBevelBorder());
    }

    /**
    Sets the course title
    @param title 
     */
    public void setCourseTitle(String title) {
    //    userListPanel.rootNode.setUserObject(new User(UserLevel.GUEST, title, title));
    }

    /**
    Add user to the tree
    @param user 
     */
    public void addUser(User user) {
        // userListPanel.addUser(user);
        listModel.addElement(user);
    }

    /**
    Removes this user from the tree
    @param user 
     */
    public void removeUser(User user) {
        // userListPanel.removeUser(user);
        listModel.removeElement(user);
    }

    /**
     * Returns the user list
     * @return
     */
    public JList getUserList() {
        return userList;
    }

    /**
     * creates a user list panel
     * @return
     */
    public JPanel createUserListPane() {
        try {
            JLabel title = new JLabel("Double user click to call");
            title.setBorder(BorderFactory.createEtchedBorder());
            JPanel p = new JPanel();
            p.setLayout(new BorderLayout());
            p.add(title, BorderLayout.NORTH);
            p.add(userList, BorderLayout.CENTER);
            return p;
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return null;
    }
} 
