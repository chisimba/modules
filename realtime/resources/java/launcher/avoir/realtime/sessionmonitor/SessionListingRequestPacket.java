/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.sessionmonitor;

/**
 *
 * @author developer
 */
public class SessionListingRequestPacket implements java.io.Serializable {

    private String sessionId;

    public SessionListingRequestPacket(String sessionId) {
        this.sessionId = sessionId;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }
}
