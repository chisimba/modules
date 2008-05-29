/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.packet;

import avoir.realtime.tcp.common.packet.RealtimePacket;
import java.util.Vector;

/**
 *
 * @author developer
 */
public class SurveyPackPacket implements RealtimePacket {

    private String sessionId;
    private Vector questions;
    private String title;

    public SurveyPackPacket(String sessionId, Vector questions, String title) {
        this.sessionId = sessionId;
        this.questions = questions;
        this.title = title;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public Vector getQuestions() {
        return questions;
    }

    public void setQuestions(Vector questions) {
        this.questions = questions;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public String getId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public void setId(String id) {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
