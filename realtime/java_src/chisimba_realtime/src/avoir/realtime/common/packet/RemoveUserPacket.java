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
public class RemoveUserPacket implements RealtimePacket {

    User user;
    private String id;
    private String sessionId;

    public RemoveUserPacket(String sessionId) {
        this.sessionId = sessionId;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getSessionId() {
        return sessionId;
    }

    public String getId() {
        return id;
    }

    public RemoveUserPacket(User user) {
        this.user = user;
    }

    public User getUser() {
        return user;
    }
}
