/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

/**
 *
 * @author developer
 */
public class RemoveMePacket implements RealtimePacket {

    String sessionId;
    String id;

    public RemoveMePacket(String id) {
        this.id = id;
    }

    public String getId() {
        return id;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setId(String id) {
        this.id = id;
    }
}
