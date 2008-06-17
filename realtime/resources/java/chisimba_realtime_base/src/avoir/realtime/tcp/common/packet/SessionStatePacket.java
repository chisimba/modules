/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.packet;

import avoir.realtime.tcp.common.packet.RealtimePacket;

/**
 *
 * @author developer
 */
public class SessionStatePacket implements RealtimePacket {

    private String sessionId;
    private boolean state;

    public boolean getState() {
        return state;
    }

    public void setState(boolean state) {
        this.state = state;
    }

    public SessionStatePacket(String sessionId, boolean state) {
        this.sessionId = sessionId;
        this.state = state;
    }

    public String getId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setId(String id) {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
