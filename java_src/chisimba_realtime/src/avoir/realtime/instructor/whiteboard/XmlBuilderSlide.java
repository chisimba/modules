/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.instructor.whiteboard;

import avoir.realtime.survey.Value;
import java.awt.Color;
import java.io.Serializable;
import java.util.ArrayList;

/**
 *
 * @author developer
 */
public class XmlBuilderSlide implements Serializable {

    private String title;
    private String text;
    private String imageData;
    private String question;
    private ArrayList<Value> answerOptions;
    private int type;
    private String essayAnswer;
    private String sender;
    private String id;
    private String sessionId;
    private boolean answered;
    private String name;
    private String questionImageData;
    private String questionPath;
    private Color textColor;
    private int textSize;
    private int index;
    private int textXPos;
    private int textYPos;

    public XmlBuilderSlide(String title, String text, Color color, int size,
            String imageData, String question, ArrayList<Value> answerOptions,
            int type, String essayAnswer, String sender, String id, String sessionId,
            boolean answered, String name, String questionImageData, String questionPath, int index,
            int textXPos, int textYPos) {
        this.title = title;
        this.text = text;
        this.textColor = color;
        this.textSize = size;
        this.imageData = imageData;
        this.question = question;
        this.answerOptions = answerOptions;
        this.type = type;
        this.essayAnswer = essayAnswer;
        this.sender = sender;
        this.id = id;
        this.sessionId = sessionId;
        this.answered = answered;
        this.name = name;
        this.questionImageData = questionImageData;
        this.questionPath = questionPath;
        this.textXPos = textXPos;
        this.textYPos = textYPos;
    }

    public int getTextXPos() {
        return textXPos;
    }

    public void setTextXPos(int textXPos) {
        this.textXPos = textXPos;
    }

    public int getTextYPos() {
        return textYPos;
    }

    public void setTextYPos(int textYPos) {
        this.textYPos = textYPos;
    }

    public int getIndex() {
        return index;
    }

    public void setIndex(int index) {
        this.index = index;
    }

    public Color getTextColor() {
        return textColor;
    }

    public void setTextColor(Color textColor) {
        this.textColor = textColor;
    }

    public int getTextSize() {
        return textSize;
    }

    public void setTextSize(int textSize) {
        this.textSize = textSize;
    }

    public String getImageData() {
        return imageData;
    }

    public void setImageData(String imageData) {
        this.imageData = imageData;
    }

    public ArrayList<Value> getAnswerOptions() {
        return answerOptions;
    }

    public void setAnswerOptions(ArrayList<Value> answerOptions) {
        this.answerOptions = answerOptions;
    }

    public boolean isAnswered() {
        return answered;
    }

    public void setAnswered(boolean answered) {
        this.answered = answered;
    }

    public String getEssayAnswer() {
        return essayAnswer;
    }

    public void setEssayAnswer(String essayAnswer) {
        this.essayAnswer = essayAnswer;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getQuestion() {
        return question;
    }

    public void setQuestion(String question) {
        this.question = question;
    }

    public String getQuestionImageData() {
        return questionImageData;
    }

    public void setQuestionImageData(String questionImageData) {
        this.questionImageData = questionImageData;
    }

    public String getQuestionPath() {
        return questionPath;
    }

    public void setQuestionPath(String questionPath) {
        this.questionPath = questionPath;
    }

    public String getSender() {
        return sender;
    }

    public void setSender(String sender) {
        this.sender = sender;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public int getType() {
        return type;
    }

    public void setType(int type) {
        this.type = type;
    }

    public String getText() {
        return text;
    }

    public void setText(String text) {
        this.text = text;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }
}
