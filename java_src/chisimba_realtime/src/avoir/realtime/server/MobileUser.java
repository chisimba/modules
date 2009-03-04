/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.server;

import avoir.realtime.common.user.User;
import java.io.OutputStream;

/**
 *
 * @author developer
 */
public class MobileUser {

    private User user;
    private OutputStream out;

    public MobileUser(User user, OutputStream out) {
        this.user = user;
        this.out = out;
    }

    public OutputStream getOut() {
        return out;
    }

    public User getUser() {
        return user;
    }

    public void setUser(User user) {
        this.user = user;
    }

    @Override
    public String toString() {
        return user.getUserName();
    }
}
