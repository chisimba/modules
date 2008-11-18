/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.instructor.whiteboard;

import java.awt.Color;
import java.awt.Image;

class ThumbNail {

    Image image;
    int x, y, index, width, height;
    Color selectColor;

    public ThumbNail(Image image, int x, int y, int width, int height, int index, Color color) {
        this.image = image;
        this.x = x;
        this.y = y;
        this.index = index;
        this.height = height;
        this.width = width;
        this.selectColor = color;
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

