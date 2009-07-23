/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.whiteboard.items;

import java.awt.BasicStroke;
import java.awt.Font;
import java.awt.Graphics2D;
import java.awt.Point;
import java.awt.Rectangle;
import java.awt.geom.Line2D;
import java.awt.geom.Point2D;
import java.util.ArrayList;
import java.util.List;
import org.avoir.realtime.net.ConnectionManager;

/**
 * This object represents a line item
 * @author developer
 */
public class Line extends Item {

    int x1;
    int y1;
    int x2;
    int y2;

    @Override
    public void setPosition(int x, int y) {
    }

    @Override
    public void setPosition(int x1, int y1, int x2, int y2) {
        this.x1 = x1;
        this.x2 = x2;
        this.y1 = y1;
        this.y2 = y2;
    }

    @Override
    public Rectangle getBounds() {
        int xx, yy, width, height;
        if (x2 < x1) {
            xx = x1 + x2 - x1;
            width = x1 - x2;
        } else {
            xx = x1;
            width = x2 - x1;
        }
        if (y2 < y1) {
            yy = y1 + y2 - y1;
            height = y1 - y2;
        } else {
            yy = y1;
            height = y2 - y1;
        }
        if (Math.abs(x2 - x1) < 5) {
            xx -= 10;
            yy -= 10;
            width = 20;
        }
        if (Math.abs(y2 - y1) < 5) {
            xx -= 10;
            yy -= 10;

            height = 20;
        }
        Rectangle rect = new Rectangle(xx, yy, Math.abs(width), Math.abs(height));
        return rect;
    }

    @Override
    public Item translate(int x, int y) {
        Line line = new Line(id, x1 + x, y1 + y, x2 + x, y2 + y);
        line.setColor(color);
        line.setStrokeWidth(strokeWidth);
        return line;
    }

    public List<Point2D> getSelectionPoints() {
        List<Point2D> list = new ArrayList<Point2D>();
        list.add(new Point2D.Double(x1, y1));
        list.add(new Point2D.Double(x2, y2));
        return list;
    }

    @Override
    public void render(Graphics2D g) {
        g.setStroke(new BasicStroke(strokeWidth));
        g.drawLine(x1, y1, x2, y2);
        if (isFromAdmin()) {
            g.setFont(new Font("Dialog", 2,11));
            g.drawString(getFrom(), x2, y2 + 5);
        }

    }

    @Override
    public boolean contains(Point point) {
        /* Line2D line = new Line2D.Double(x1, y1, x2, y2);

        return line.intersects(point.getX(), point.getY(), 1, 1);*/
        return getBounds().contains(point);
    }

    @Override
    public void northEastResize(int x2, int y2) {
        this.x2 = x2;
        this.y2 = y2;
    }

    @Override
    public void northWestResize(int x1, int y1) {
        this.x1 = x1;
        this.y1 = y1;

    }

    @Override
    public void southWestResize(int w, int h) {
    }

    @Override
    public void southEastResize(int x2, int y2) {
        this.x2 = x2;
        this.y2 = y2;
    /*
    if (x2 < x1) {
    this.x1 = x2;
    this.x2 = x1 + (x2 - x1);
    } else {
    this.x2 = x2;
    }
    if (y2 < y1) {
    y1 = y2;
    this.y2 = y1 + (y2 - y1);
    } else {
    this.y2 = y2;
    }*/
    }

    public Line(String id, int x1, int y1, int x2, int y2) {
        this.id = id;
        this.x1 = x1;
        this.x2 = x2;
        this.y1 = y1;
        this.y2 = y2;

    }

    public int getX1() {
        return x1;
    }

    public void setX1(int x1) {
        this.x1 = x1;
    }

    public int getX2() {
        return x2;
    }

    public void setX2(int x2) {
        this.x2 = x2;
    }

    public int getY1() {
        return y1;
    }

    public void setY1(int y1) {
        this.y1 = y1;
    }

    public int getY2() {
        return y2;
    }

    public void setY2(int y2) {
        this.y2 = y2;
    }

    @Override
    public String toString() {
        return "Line: ID=" + id + " x1=" + x1 + " y1=" + y1 + " x2=" + x2 + " y2=" + y2;
    }
}
