/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.questions;

import java.awt.Image;
import java.util.ArrayList;
import static org.avoir.realtime.common.Constants.*;

/**
 *
 * @author developer
 */
public class Question {

    private String question;
    private ArrayList<Value> answerOptions = new ArrayList<Value>();
    private int type = MCQ_QUESTION;
    private Image image;

    public Image getImage() {
        return image;
    }

    public void setImage(Image image) {
        this.image = image;
    }

    public int getType() {
        return type;
    }

    public void setType(int type) {
        this.type = type;
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
