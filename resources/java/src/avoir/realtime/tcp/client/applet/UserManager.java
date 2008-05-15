/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.client.applet;

import javax.swing.DefaultListModel;
import avoir.realtime.tcp.common.user.*;
import javax.swing.JLabel;
import java.awt.FlowLayout;
import java.awt.Color;
import avoir.realtime.tcp.client.applet.ImageUtil;

/**
 *
 * @author developer
 */
public class UserManager {

    TCPTunnellingApplet applet;

    public UserManager(TCPTunnellingApplet applet) {
        this.applet = applet;
    }

    /**
     * adds new user at the bottom of the list
     * @param user
     */
    public void addNewUser(User user) {
        for (int i = 0; i < applet.listModel.size(); i++) {
            UserObject usr = (UserObject) applet.listModel.elementAt(i);
            if (usr.getUser().getUserName().equals(user.getUserName())) {
                return;
            }
        }
        addUser(user, applet.listModel.size());
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
                label.setText(usr.getFullName() + " (A) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(new Color(0, 131, 0));
                label.setText(usr.getFullName() + " (A) " + presenter);// [" + usr.getIpAddress() + "]");

            }
            applet.listModel.add(index, jp);
        }
        if (usr.getLevel().equals(UserLevel.STUDENT)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                label.setText(usr.getFullName() + " (S) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.BLACK);
                label.setText(usr.getFullName() + " (S) " + presenter);// [" + usr.getIpAddress() + "]");

            }
            applet.listModel.add(index, jp);
        }
        if (usr.getLevel().equals(UserLevel.LECTURER)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                label.setText(usr.getFullName() + " (L) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.ORANGE);
                label.setText(usr.getFullName() + " (L) " + presenter);// [" + usr.getIpAddress() + "]");

            }
            applet.listModel.add(index, jp);
        }
        if (usr.getLevel().equals(UserLevel.GUEST)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                label.setText(usr.getFullName() + " (G) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.DARK_GRAY);
                label.setText(usr.getFullName() + " (G) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            }
            applet.listModel.add(index, jp);
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
            boolean isYesNoSession) {
        UserObject jp = new UserObject(usr);
        jp.setLayout(new FlowLayout(FlowLayout.LEFT));   // NEW

        JLabel label = new JLabel();
        String presenter = usr.isPresenter() ? "Presenter" : "";
        String fullNames = usr.getFullName();
        jp.setRaisedHand(raiseHand);
        if (!applet.getUser().isPresenter()) {
            if (usr.getUserName().equals(applet.getUser().getUserName())) {
//                this.hasControl = allowControl;
            }
        }

        //      stopControlItem.setEnabled(!this.hasControl);
        if (raiseHand) {

            label.setIcon(ImageUtil.createImageIcon(this, "/icons/hand.png"));
        }
        if (allowControl) {
            label.setIcon(ImageUtil.createImageIcon(this, "/icons/edit.png"));

        }
        applet.setControl(allowControl);
        //only affects selected user, but presenter still has ultimate control
        if (!applet.getUser().isPresenter()) {
            if (usr.getUserName().equals(applet.getUser().getUserName())) {
                applet.firstSlideButton.setEnabled(applet.getControl());
                applet.backSlideButton.setEnabled(applet.getControl());
                applet.nextSlideButton.setEnabled(applet.getControl());
                applet.lastSlideButton.setEnabled(applet.getControl());
            }
        }
        if (isYesNoSession) {
            if (yes) {
                label.setIcon(ImageUtil.createImageIcon(this, "/icons/yes.png"));

            }

            if (no) {
                label.setIcon(ImageUtil.createImageIcon(this, "/icons/no.png"));

            }
        }
        jp.add(label);
        if (usr.getLevel().equals(UserLevel.ADMIN)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);

                label.setText(fullNames + " (A) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(new Color(0, 131, 0));
                label.setText(fullNames + " (A) " + presenter);// [" + usr.getIpAddress() + "]");

            }
            applet.listModel.set(index, jp);
        }
        if (usr.getLevel().equals(UserLevel.STUDENT)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                label.setText(fullNames + " (S) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.BLACK);
                label.setText(fullNames + " (S) " + presenter);// [" + usr.getIpAddress() + "]");

            }
            applet.listModel.set(index, jp);
        }
        if (usr.getLevel().equals(UserLevel.LECTURER)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                label.setText(fullNames + " (L) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.ORANGE);
                label.setText(fullNames + " (L) " + presenter);// [" + usr.getIpAddress() + "]");

            }   //must always be on top

            applet.listModel.set(0, jp);
        }
        if (usr.getLevel().equals(UserLevel.GUEST)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                label.setText(fullNames + " (G) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.DARK_GRAY);
                label.setText(fullNames + " (G) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            }
            applet.listModel.set(index, jp);
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

                label.setText(fullNames + " (A) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(new Color(0, 131, 0));
                label.setText(fullNames + " (A) " + presenter);// [" + usr.getIpAddress() + "]");

            }
            applet.listModel.set(index, jp);
        }
        if (usr.getLevel().equals(UserLevel.STUDENT)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                label.setText(fullNames + " (S) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.BLACK);
                label.setText(fullNames + " (S) " + presenter);// [" + usr.getIpAddress() + "]");

            }
            applet.listModel.set(index, jp);
        }
        if (usr.getLevel().equals(UserLevel.LECTURER)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                label.setText(fullNames + " (L) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.ORANGE);
                label.setText(fullNames + " (L) " + presenter);// [" + usr.getIpAddress() + "]");

            }   //must always be on top

            applet.listModel.set(0, jp);
        }
        if (usr.getLevel().equals(UserLevel.GUEST)) {
            if (usr.isPresenter()) {
                label.setForeground(Color.ORANGE);
                label.setText(fullNames + " (G) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            } else {
                label.setForeground(Color.DARK_GRAY);
                label.setText(fullNames + " (G) " + presenter);// ");//[" + usr.getIpAddress() + "]");

            }
            applet.listModel.set(index, jp);
        }

    }

    /**
     * Removes this user from the list
     * @param user
     */
    public void removeUser(User user) {
        for (int i = 0; i < applet.listModel.size(); i++) {
            UserObject usr = (UserObject) applet.listModel.elementAt(i);
            if (usr.getUser().getUserName().equals(user.getUserName())) {
                applet.listModel.remove(i);
            }
        }
    }
}
