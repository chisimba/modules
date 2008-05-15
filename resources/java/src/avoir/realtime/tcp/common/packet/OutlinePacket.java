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
public class OutlinePacket implements RealtimePacket {

    String sessionId;
    String title;
    int index;
    String[] outlines;
    int max;

    public OutlinePacket(String sessionId, String title, int index, String[] outlines, int max) {
        this.sessionId = sessionId;
        this.title = title;
        this.index = index;
        this.outlines = outlines;
        this.max = max;
    }



    public int getMax() {
        return max;
    }

    public void setMax(int max) {
        this.max = max;
    }

    public String[] getOutlines() {
        return outlines;
    }

    public void setOutlines(String[] outlines) {
        this.outlines = outlines;
    }

    public int getIndex() {
        return index;
    }

    public void setIndex(int index) {
        this.index = index;
    }

    public String getId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public void setId(String id) {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
