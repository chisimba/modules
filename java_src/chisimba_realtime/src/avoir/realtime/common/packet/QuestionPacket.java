/*
 * 
 * Copyright (C) GNU/GPL AVOIR 2008
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
package avoir.realtime.common.packet;

import avoir.realtime.survey.*;
import java.util.ArrayList;

/**
 *
 * @author developer
 */
public class QuestionPacket implements RealtimePacket {

    private String question;
    private ArrayList<Value> answerOptions;
    private boolean essay;
    private String essayAnswer;
    private String sender;

    private String id;
    private String sessionId;
    private boolean answered;

    public String getId() {
        return id;
    }

    public boolean isAnswered() {
        return answered;
    }

    public void setAnswered(boolean answered) {
        this.answered = answered;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setId(String id) {
        this.id = id;
    }

    public QuestionPacket(String question, ArrayList<Value> answerOptions, boolean essay,String sender,String id) {
        this.question = question;
        this.answerOptions = answerOptions;
        this.essay = essay;
        this.sender=sender;
        this.id=id;
    }

    public String getSender() {
        return sender;
    }

    public void setSender(String sender) {
        this.sender = sender;
    }

   
    public String getEssayAnswer() {
        return essayAnswer;
    }

    public void setEssayAnswer(String essayAnswer) {
        this.essayAnswer = essayAnswer;
    }

    public boolean isEssay() {
        return essay;
    }

    public ArrayList<Value> getAnswerOptions() {
        return answerOptions;
    }

    public void setAnswerOptions(ArrayList<Value> answerOptions) {
        this.answerOptions = answerOptions;
    }

    public String getQuestion() {
        return question;
    }

    public void setQuestion(String question) {
        this.question = question;
    }
}
