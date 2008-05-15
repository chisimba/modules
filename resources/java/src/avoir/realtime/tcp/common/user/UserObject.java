/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.user;

import javax.swing.JPanel;

/**
 *
 * @author developer
 */
public class UserObject extends JPanel {

    private User user;
    private boolean raisedHand;

    public boolean isRaisedHand() {
        return raisedHand;
    }

    public void setRaisedHand(boolean raisedHand) {
        this.raisedHand = raisedHand;
    }

    public UserObject(User user) {
        this.user = user;
    }

    public User getUser() {
        return user;
    }
}
