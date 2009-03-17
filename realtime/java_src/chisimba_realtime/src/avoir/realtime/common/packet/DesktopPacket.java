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
    private boolean record;
    private String sender;

    public DesktopPacket(ScreenScrapeData data, String sessionId,
            boolean record,String sender) {
        this.data = data;
        this.sessionId = sessionId;
        this.record=record;
        this.sender=sender;
    }

    public String getSender() {
        return sender;
    }

    public void setSender(String sender) {
        this.sender = sender;
    }

    public boolean isRecord() {
        return record;
    }

    public void setRecord(boolean record) {
        this.record = record;
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
