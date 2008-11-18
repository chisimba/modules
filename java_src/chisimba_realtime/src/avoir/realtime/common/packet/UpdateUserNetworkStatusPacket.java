/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

import avoir.realtime.common.packet.RealtimePacket;
import avoir.realtime.common.user.User;



/**
 *
 * @author developer
 */
public class UpdateUserNetworkStatusPacket implements RealtimePacket {

    private User user;
    private boolean online;

    public User getUser() {
        return user;
    }

    public UpdateUserNetworkStatusPacket(User user, boolean online) {
        this.user = user;
        this.online = online;
    }

    public boolean isOnline() {
        return online;
    }

    public void setOnline(boolean online) {
        this.online = online;
    }

    public UpdateUserNetworkStatusPacket(User user) {
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
