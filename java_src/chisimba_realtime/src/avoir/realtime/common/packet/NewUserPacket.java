/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

import avoir.realtime.common.packet.*;
import avoir.realtime.common.packet.RealtimePacket;
import avoir.realtime.common.user.User;

/**
 *
 * @author developer
 */
public class NewUserPacket implements RealtimePacket {

    private User user;
    private String sessionId;

    public NewUserPacket(String sessionId) {
        this.sessionId = sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public User getUser() {
        return user;
    }

    public NewUserPacket(User user) {
        this.user = user;
    }

    public String getSessionId() {
        return sessionId;
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
