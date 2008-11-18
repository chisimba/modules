/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

/**
 *
 * @author developer
 */
public class SwitchTabPacket implements RealtimePacket {

    private String sessionId;
    private int tabIndex;

    public SwitchTabPacket(String sessionId, int tabIndex) {
        this.sessionId = sessionId;
        this.tabIndex = tabIndex;
    }

    public int getTabIndex() {
        return tabIndex;
    }

    public void setTabIndex(int tabIndex) {
        this.tabIndex = tabIndex;
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
