/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package org.avoir.realtime.gui.whiteboard;
import java.awt.Color;
import java.awt.Image;

class ThumbNail {

    Image image;
    int x, y, index, width, height;
    Color selectColor;
    private String presentationName,slideName;

    public ThumbNail(Image image, int x, int y, int width, int height, 
            int index, Color color,String presentationName,String slideName) {
        this.image = image;
        this.x = x;
        this.y = y;
        this.index = index;
        this.height = height;
        this.width = width;
        this.selectColor = color;
        this.presentationName=presentationName;
        this.slideName=slideName;
    }

    public String getPresentationName() {
        return presentationName;
    }

    public void setPresentationName(String presentationName) {
        this.presentationName = presentationName;
    }

    public String getSlideName() {
        return slideName;
    }

    public void setSlideName(String slideName) {
        this.slideName = slideName;
    }

    public int getHeight() {
        return height;
    }

    public void setHeight(int height) {
        this.height = height;
    }

    public int getWidth() {
        return width;
    }

    public void setWidth(int width) {
        this.width = width;
    }

    public Color getSelectColor() {
        return selectColor;
    }

    public void setSelectColor(Color selectColor) {
        this.selectColor = selectColor;
    }

    public Image getImage() {
        return image;
    }

    public int getIndex() {
        return index;
    }

    public void setIndex(int index) {
        this.index = index;
    }

    public void setImage(Image image) {
        this.image = image;
    }

    public int getX() {
        return x;
    }

    public void setX(int x) {
        this.x = x;
    }

    public int getY() {
        return y;
    }

    public void setY(int y) {
        this.y = y;
    }
}


