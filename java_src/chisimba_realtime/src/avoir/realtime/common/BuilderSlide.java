/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common;

import avoir.realtime.common.packet.XmlQuestionPacket;
import java.awt.Color;
import java.io.Serializable;
import javax.swing.ImageIcon;

/**
 *
 * @author developer
 */
public class BuilderSlide implements Serializable{

    private String title;
    private String text;
    private Color textColor;
    private int textSize;
    private XmlQuestionPacket question;
    private ImageIcon image;
    private String imagePath;

    public BuilderSlide(String title, String text, Color textColor, int textSize, XmlQuestionPacket question, ImageIcon image, String imagePath) {
        this.title = title;
        this.text = text;
        this.textColor = textColor;
        this.textSize = textSize;
        this.question = question;
        this.image = image;
        this.imagePath = imagePath;
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

    
    public ImageIcon getImage() {
        return image;
    }

    public String getImagePath() {
        return imagePath;
    }

    public void setImagePath(String imagePath) {
        this.imagePath = imagePath;
    }

    public void setImage(ImageIcon image) {
        this.image = image;
    }

    public XmlQuestionPacket getQuestion() {
        return question;
    }

    public void setQuestion(XmlQuestionPacket question) {
        this.question = question;
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
