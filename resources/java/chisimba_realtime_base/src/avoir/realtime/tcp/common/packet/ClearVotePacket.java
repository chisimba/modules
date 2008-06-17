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
public class ClearVotePacket implements RealtimePacket {

    private String sessionId;

    public ClearVotePacket(String sessionId) {
        this.sessionId = sessionId;
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
