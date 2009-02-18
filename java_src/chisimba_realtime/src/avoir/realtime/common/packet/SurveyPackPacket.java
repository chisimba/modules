/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

import avoir.realtime.common.packet.QuestionPacket;
import java.util.ArrayList;
import java.util.Vector;

/**
 *
 * @author developer
 */
public class SurveyPackPacket implements RealtimePacket {

    private String sessionId;
    private ArrayList<QuestionPacket> questions;
    private String title;

    public SurveyPackPacket(String sessionId, ArrayList<QuestionPacket> questions, String title) {
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

    public ArrayList<QuestionPacket> getQuestions() {
        return questions;
    }

    public void setQuestions(ArrayList<QuestionPacket> questions) {
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
