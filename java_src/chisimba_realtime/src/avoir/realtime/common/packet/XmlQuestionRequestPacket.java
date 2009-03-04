/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

/**
 *
 * @author developer
 */
public class XmlQuestionRequestPacket implements RealtimePacket {

    private String sessionId;
    private String questionPath;

    public XmlQuestionRequestPacket(String questionPath) {
        this.questionPath = questionPath;
    }

    public String getQuestionPath() {
        return questionPath;
    }

    public void setQuestionPath(String questionPath) {
        this.questionPath = questionPath;
    }

    public String getId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setId(String id) {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
