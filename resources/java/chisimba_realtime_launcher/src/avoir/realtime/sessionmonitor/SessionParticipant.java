/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.sessionmonitor;

/**
 *
 * @author developer
 */
public class SessionParticipant implements java.io.Serializable {

    private String userId;
    private String sessionId;
    private String fullnames;
    private String sessionName;

    public SessionParticipant(String userId, String sessionId, String sessionName, String fullnames) {
        this.userId = userId;
        this.sessionId = sessionId;
        this.fullnames = fullnames;
        this.sessionName = sessionName;
    }

    public String getSessionName() {
        return sessionName;
    }

    public void setSessionName(String sessionName) {
        this.sessionName = sessionName;
    }

    public String getFullnames() {
        return fullnames;
    }

    public void setFullnames(String fullnames) {
        this.fullnames = fullnames;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public String getUserId() {
        return userId;
    }

    public void setUserId(String userId) {
        this.userId = userId;
    }
}
