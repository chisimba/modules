/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.whiteboard.items;

import java.awt.Color;
import java.awt.Font;
import java.awt.FontMetrics;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Point;
import java.awt.Rectangle;
import java.awt.geom.Point2D;
import java.awt.geom.RoundRectangle2D;
import java.util.ArrayList;
import java.util.List;

/**
 * this object represents a text object
 * @author developer
 */
public class Text extends Item {

    int x, y, width, height, red, blue, green;
    private String content;
    private int fontSize;
    private int fontStyle;
    private String fontName;
    private FontMetrics fm;
    private Graphics graphics;
private boolean filled;
    public Text(int x, int y, String content) {
        this.x = x;
        this.y = y;
        this.content = content;
    }

    public boolean isFilled() {
        return filled;
    }

    public void setFilled(boolean filled) {
        this.filled = filled;
    }

    @Override
    public List<Point2D> getSelectionPoints() {
        List<Point2D> list = new ArrayList<Point2D>();
        list.add(new Point2D.Double(x, y));
        return list;
    }

    public Graphics getGraphic() {
        return graphics;
    }

    public void setGraphic(Graphics graphics) {
        this.graphics = graphics;

    }

    public String getFontName() {
        return fontName;
    }

    public int getBlue() {
        return blue;
    }

    public void setBlue(int blue) {
        this.blue = blue;
    }

    public int getGreen() {
        return green;
    }

    public void setGreen(int green) {
        this.green = green;
    }

    public int getRed() {
        return red;
    }

    public void setRed(int red) {
        this.red = red;
    }

    public void setFontName(String fontName) {
        this.fontName = fontName;
    }

    public int getFontSize() {
        return fontSize;
    }

    public void setFontSize(int fontSize) {
        this.fontSize = fontSize;
    }

    public int getFontStyle() {
        return fontStyle;
    }

    public void setFontStyle(int fontStyle) {
        this.fontStyle = fontStyle;
    }

    @Override
    public void setPosition(int x, int y) {
        this.x = x;
        this.y = y;
    }

    @Override
    public void setPosition(int x1, int y1, int x2, int y2) {
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

    public int getX() {
        return x;
    }

    public void setX(int x) {
        this.x = x;
    }

    public int getY() {
        return y;
    }

    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
    }

    public void setY(int y) {
        this.y = y;
    }

    @Override
    public Rectangle getBounds() {
        return new RoundRectangle2D.Double(x, y, width, height, 10, 10).getBounds();
    }

    @Override
    public void southEastResize(int x, int y) {
        width = x - this.x;
        height = y - this.y;
    }

    @Override
    public void northEastResize(int x, int y) {
        int dy = this.y - y;
        width = x - this.x;
        this.y = y;
        height += dy;
    }

    @Override
    public void northWestResize(int width, int height) {
    }

    @Override
    public void southWestResize(int x, int y) {
        int dx = this.x - x;
        int dy = this.y - y;
        this.x = x;
        this.y = y;
        width += dx;
        height += dy;
    }

    @Override
    public Item translate(int dx, int dy) {
        Text text = new Text(x + dx, y + dy+height, content);
        text.setId(id);
        text.setFontName(fontName);
        text.setFontSize(fontSize);
        text.setFontStyle(fontStyle);
        text.setColor(color);
        text.setRed(red);
        text.setGreen(green);
        text.setBlue(blue);
        text.setGraphic(graphics);
        return text;
    }

    @Override
    public boolean contains(Point point) {
        Font font = new Font(fontName, fontStyle, fontSize);
        fm = graphics.getFontMetrics(font);
        width = fm.stringWidth(content);
        height = fm.getHeight();
        return new RoundRectangle2D.Double(x, y, width, height, 10, 10).getBounds().contains(point);
    }

    @Override
    public void render(Graphics2D g) {
        g.setFont(new Font(fontName, fontStyle, fontSize));
        g.setColor(new Color(red, green, blue));
        g.drawString(content, x, y + height);

    }

    public String toXml() {
        StringBuilder sb = new StringBuilder();
        sb.append("<x>").append(x).append("</x>");
        sb.append("<y>").append(y).append("</y>");
        sb.append("<text-content>").append(content).append("</text-content>");
        sb.append("<font-size>").append(fontSize).append("</font-size>");
        sb.append("<font-type>").append(fontStyle).append("</font-type>");
        sb.append("<font-name>").append(fontName).append("</font-name>");
        sb.append("<red>").append(red).append("</red>");
        sb.append("<blue>").append(blue).append("</blue>");
        sb.append("<green>").append(green).append("</green>");
        return sb.toString();
    }

    @Override
    public String toString() {
        return content + ": id " + id + " x=" + x + " y=" + y;
    }
}
