/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package avoir.realtime.common.packet;

import avoir.realtime.common.packet.RealtimePacket;

/**
 *
 * @author developer
 */
public class ChatLogRequestPacket implements RealtimePacket {
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
}
