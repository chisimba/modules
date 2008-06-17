/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.packet;

import avoir.realtime.tcp.common.packet.RealtimePacket;
import avoir.realtime.tcp.base.user.User;



/**
 *
 * @author developer
 */
public class RemoveUserPacket implements RealtimePacket {

    User user;
    private String id;

    public void setId(String id) {
        this.id = id;
    }

    public String getSessionId() {
        throw new UnsupportedOperationException("Not supported yet.");
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
