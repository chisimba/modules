/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

import avoir.realtime.common.packet.*;

/**
 *
 * @author developer
 */
public class RemoveDocumentPacket implements RealtimePacket {

    private String sessionId;
    private String id;
    private int type;

    public RemoveDocumentPacket(String sessionId, String id, int type) {
        this.sessionId = sessionId;
        this.id = id;
        this.type = type;
    }

    public int getType() {
        return type;
    }

    public void setType(int type) {
        this.type = type;
    }

    public String getId() {
        return id;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setId(String id) {
        this.id = id;
    }
}
