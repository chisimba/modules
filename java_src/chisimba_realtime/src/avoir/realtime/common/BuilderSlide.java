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
public class BuilderSlide implements Serializable {

    private String title;
    private String text;
    private Color textColor;
    private int textSize;
    private int textXPos;
    private int textYPos;
    private XmlQuestionPacket question;
    private ImageIcon image;
    private String imagePath;
    int index;
    private String url;

    public BuilderSlide(String title, String text, Color textColor, int textSize,
            XmlQuestionPacket question, ImageIcon image, String imagePath,int index,
            int textXPos,int textYPos,String url) {
        this.title = title;
        this.text = text;
        this.textColor = textColor;
        this.textSize = textSize;
        this.question = question;
        this.image = image;
        this.imagePath = imagePath;
        this.index=index;
        this.textXPos=textXPos;
        this.textYPos=textYPos;
        this.url=url;
    }

    public String getUrl() {
        return url;
    }

    public void setUrl(String url) {
        this.url = url;
    }

    public int getIndex() {
        return index;
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

    @Override
    public String toString() {
        return title;
    }
}
