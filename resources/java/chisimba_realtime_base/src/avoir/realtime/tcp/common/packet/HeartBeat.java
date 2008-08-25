/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.packet;

/**
 *
 * @author developer
 */
public class HeartBeat implements RealtimePacket {

    private String sessionId;

    public HeartBeat(String sessionId) {
        this.sessionId = sessionId;
    }

    public String getId() {
        return "";
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setId(String id) {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
