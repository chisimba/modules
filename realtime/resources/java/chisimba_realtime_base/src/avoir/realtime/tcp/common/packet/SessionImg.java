/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.packet;

import avoir.realtime.tcp.whiteboard.item.Img;

/**
 *
 * @author developer
 */
public class SessionImg implements RealtimePacket {

    private String sessionId;
    private Img img;

    public SessionImg(String sessionId, Img img) {
        this.sessionId = sessionId;
        this.img = img;
    }

    public Img getImg() {
        return img;
    }

    public void setImg(Img img) {
        this.img = img;
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
