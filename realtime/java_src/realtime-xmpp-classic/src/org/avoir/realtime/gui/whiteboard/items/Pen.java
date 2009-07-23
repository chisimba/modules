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
import java.awt.geom.Line2D.Double;
import java.awt.geom.Point2D;
import java.util.ArrayList;
import java.util.List;

/**
 *
 * @author developer
 */
public class Pen extends Item {

    private ArrayList<Line2D.Double> points;

    public Pen(ArrayList<Double> points) {
        this.points = points;
    }

    @Override
    public void setPosition(int x, int y) {
    }

    public ArrayList<Double> getPoints() {
        return points;
    }

    @Override
    public void setPosition(int x1, int y1, int x2, int y2) {
        ArrayList<Line2D.Double> points2 = new ArrayList<Double>();
        for (int i = 0; i < points.size(); i++) {
            Line2D.Double p = points.get(i);
            Line2D.Double p2 = new Double();
            p2.x1 = p.x1 + x1;
            p2.x2 = p.x2 + x2;
            p2.y1 = p.y1 + y1;
            p2.y2 = p.y2 + y2;
            points2.add(p2);
        }
        points = points2;
    }

    @Override
    public List<Point2D> getSelectionPoints() {
        List<Point2D> list = new ArrayList<Point2D>();
        int x1 = (int) points.get(0).x1;
        int y1 = (int) points.get(0).y1;
        int x2 = (int) points.get(points.size() - 1).x2;
        int y2 = (int) points.get(points.size() - 1).y2;
        list.add(new Point2D.Double(x1, y1));
        list.add(new Point2D.Double(x2, y2));

        return list;
    }

    @Override
    public boolean contains(Point point) {

        for (int i = 0; i < points.size(); i++) {
            Line2D line = points.get(i);
            if (line.getBounds().intersects(point.getX(), point.getY(), strokeWidth, strokeWidth)) {
                return true;
            }

        }
        return false;
    }

    @Override
    public Rectangle getBounds() {
        int minX = 0;
        int maxX = 0;
        int minY = 0;
        int maxY = 0;

        for (int i = 0; i < points.size(); i++) {

            Line2D line = points.get(i);
            if (i == 0) {
                minX = line.getBounds().x;
                minY = line.getBounds().y;
                maxX = line.getBounds().x;
                maxY = line.getBounds().y;
            }
            if (line.getBounds().x < minX) {
                minX = line.getBounds().x;
            }
            if (line.getBounds().x > maxX) {
                maxX = line.getBounds().x;
            }
            if (line.getBounds().y < minY) {
                minY = line.getBounds().y;
            }
            if (line.getBounds().y > maxY) {
                minY = line.getBounds().y;
            }
        }

        return new Rectangle(minX, minY, maxX - minX, maxY - minY);
    }

    @Override
    public void northEastResize(int w, int h) {
    }

    @Override
    public void northWestResize(int w, int h) {
    }

    @Override
    public void render(Graphics2D g) {
        g.setStroke(new BasicStroke(strokeWidth));
        Line2D line=null;
        for (int i = 0; i < points.size(); i++) {
           line = points.get(i);
            g.draw(line);
        }
        if(line != null){
                    if (isFromAdmin()) {
            g.setFont(new Font("Dialog", 2,11));
            g.drawString(getFrom(), (int)line.getX2(), (int)(line.getY2() + 5));
        }
        }
    }

    @Override
    public void southEastResize(int w, int h) {
    }

    @Override
    public void southWestResize(int w, int h) {
    }

    @Override
    public String toString() {
        return "Oval: " + id;
    }

    @Override
    public Item translate(int dx, int dy) {
        /*ArrayList<Line2D.Double> points2 = new ArrayList<Double>();
        for (int i = 0; i < points.size(); i++) {
            Line2D.Double p = points.get(i);
            Line2D.Double p2 = new Double();
            p2.x1 = p.x1 + dx;
            p2.x2 = p.x2 + dx;
            p2.y1 = p.y1 + dy;
            p2.y2 = p.y2 + dy;
            points2.add(p2);
        }
        Pen pen = new Pen(points2);
        pen.setId(id);
        pen.setColor(color);
        pen.setStrokeWidth(strokeWidth);
        return pen;*/
        return this;
    }
}
