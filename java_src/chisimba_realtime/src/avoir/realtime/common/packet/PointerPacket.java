/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

import java.awt.Point;

/**
 *
 * @author developer
 */
public class PointerPacket implements RealtimePacket {

    private String sessionId;
    private Point point;
    private int type;

    public PointerPacket(String sessionId, Point point, int type) {
        this.sessionId = sessionId;
        this.point = point;
        this.type = type;
    }

    public Point getPoint() {
        return point;
    }

    public void setPoint(Point point) {
        this.point = point;
    }

    public int getType() {
        return type;
    }

    public void setType(int type) {
        this.type = type;
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
