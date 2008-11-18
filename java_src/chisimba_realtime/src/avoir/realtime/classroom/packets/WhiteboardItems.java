/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.classroom.packets;

import avoir.realtime.classroom.whiteboard.item.Item;
import avoir.realtime.common.packet.*;

import java.util.Vector;

/**
 *
 * @author developer
 */
public class WhiteboardItems implements RealtimePacket {

    private Vector<Item> whiteboardItems;
    private String sessionId;

    public WhiteboardItems(Vector<Item> whiteboardItems, String sessionId) {
        this.whiteboardItems = whiteboardItems;
        this.sessionId = sessionId;
    }

    public Vector<Item> getWhiteboardItems() {
        return whiteboardItems;
    }

    public void setWhiteboardItems(Vector<Item> whiteboardItems) {
        this.whiteboardItems = whiteboardItems;
    }

    public String getId() {
        return "";
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public void setId(String id) {
    }
}
