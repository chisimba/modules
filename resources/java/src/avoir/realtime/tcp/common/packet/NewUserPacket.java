/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.packet;

import avoir.realtime.tcp.common.user.User;

/**
 *
 * @author developer
 */
public class NewUserPacket implements RealtimePacket {

    private User user;

    public User getUser() {
        return user;
    }

    public NewUserPacket(User user) {
        this.user = user;
    }

    public String getSessionId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public void setUser(User user) {
        this.user = user;
    }

    public String getId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public void setId(String id) {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
