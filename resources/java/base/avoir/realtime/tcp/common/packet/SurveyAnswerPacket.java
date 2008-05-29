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
public class SurveyAnswerPacket implements RealtimePacket {

    private String id;
    private String sessionId;
    private int question;
    private boolean answer;

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public SurveyAnswerPacket(String sessionId, int question, boolean answer) {
        this.sessionId = sessionId;
        this.question = question;
        this.answer = answer;
    }

    public boolean isAnswer() {
        return answer;
    }

    public void setAnswer(boolean answer) {
        this.answer = answer;
    }

    public int getQuestion() {
        return question;
    }

    public void setQuestion(int question) {
        this.question = question;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }
}
