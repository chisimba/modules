/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.whiteboard.items;

import java.awt.Graphics2D;
import java.awt.Point;
import java.awt.Rectangle;
import java.awt.geom.Point2D;
import java.awt.geom.RoundRectangle2D;
import java.util.ArrayList;
import java.util.List;

/**
 * this object represents a rectangle object
 * @author developer
 */
public class Rect extends Item {

    int x, y, width, height;
    private boolean filled;

    public Rect(int x, int y, int width, int height) {
        this.x = x;
        this.y = y;
        this.width = width;
        this.height = height;
    }

    public boolean isFilled() {
        return filled;
    }

    public void setFilled(boolean filled) {
        this.filled = filled;
    }

    @Override
    public void setPosition(int x, int y) {
        this.x = x;
        this.y = y;
    }

    @Override
    public void setPosition(int x1, int y1, int x2, int y2) {
    }

    @Override
    public List<Point2D> getSelectionPoints() {
        List<Point2D> list = new ArrayList<Point2D>();
        list.add(new Point2D.Double(x, y));
        list.add(new Point2D.Double(x + width, y));
        list.add(new Point2D.Double(x + width, y + height));
        list.add(new Point2D.Double(x, y + height));

        return list;
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
    public void northWestResize(int x, int y) {
        int dx = this.x - x;
        int dy = this.y - y;
        this.x = x;
        this.y = y;
        width += dx;
        height += dy;
    }

    @Override
    public void southWestResize(int x, int y) {
        int dx = this.x - x;
        this.x = x;
        width += dx;
        height = y - this.y;
    }

    @Override
    public Item translate(int dx, int dy) {
        Rect rect = new Rect(x + dx, y + dy, width, height);
        rect.setId(id);
        rect.setFilled(filled);
        rect.setColor(color);
        rect.setStrokeWidth(strokeWidth);
        return rect;
    }

    @Override
    public boolean contains(Point point) {
        return new RoundRectangle2D.Double(x, y, width, height, 10, 10).getBounds().contains(point);
    }

    @Override
    public void render(Graphics2D g) {
        if (width < 0) {
            x = x - width;
            width = Math.abs(width);
        }
        if (height < 0) {
            y = y - height;
            height = Math.abs(height);
        }
        RoundRectangle2D rect = new RoundRectangle2D.Double(x, y, width, height, 10, 10);
        if (filled) {
            g.fill(rect);
        } else {
            g.draw(rect);
        }
    }

    @Override
    public String toString() {
        return "Rectangle: id " + id + " x=" + x + " y=" + y + " width=" + width + " height=" + height;
    }
}
