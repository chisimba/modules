/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.packet;

import avoir.realtime.tcp.whiteboard.item.Item;

/**
 *
 * @author developer
 */
public class WhiteboardPacket implements RealtimePacket {

    String sessionId;
    private Item item;
    private int status;

    public WhiteboardPacket(String sessionId, Item item, int status) {
        this.sessionId = sessionId;
        this.item = item;
        this.status = status;
    }

    public Item getItem() {
        return item;
    }

    public void setItem(Item item) {
        this.item = item;
    }

    public int getStatus() {
        return status;
    }

    public void setStatus(int status) {
        this.status = status;
    }

    public void setSessionId(String sessionId) {
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
