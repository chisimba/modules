/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;


import avoir.realtime.appsharing.ScreenScrapeData;


/**
 *
 * @author developer
 */
public class DesktopPacket implements RealtimePacket{

    private ScreenScrapeData data;
    private String sessionId;

    public DesktopPacket(ScreenScrapeData data, String sessionId) {
        this.data = data;
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

    public ScreenScrapeData getData() {
        return data;
    }

    public void setData(ScreenScrapeData data) {
        this.data = data;
    }
}
