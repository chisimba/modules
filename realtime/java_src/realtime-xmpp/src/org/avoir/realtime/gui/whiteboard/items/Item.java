/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.whiteboard.items;

import java.awt.Color;
import java.awt.Graphics2D;
import java.awt.Point;
import java.awt.Rectangle;
import java.awt.geom.Point2D;
import java.io.Serializable;
import java.util.List;

/**
 *
 * @author developer
 */
public abstract class Item implements Serializable {

    protected String id;
    protected float strokeWidth = 1;
    protected Color color = Color.BLACK;
    protected boolean newItem;

    public boolean isNewItem() {
        return newItem;
    }

    public void setNewItem(boolean newItem) {
        this.newItem = newItem;
    }

    public Color getColor() {
        return color;
    }

    public void setColor(Color color) {
        this.color = color;
    }

    public float getStrokeWidth() {
        return strokeWidth;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getId() {
        return id;
    }

    public abstract void render(Graphics2D g);

    @Override
    public abstract String toString();

    public abstract List<Point2D> getSelectionPoints();

    public abstract boolean contains(Point point);

    public abstract Item translate(int dx, int dy);

    public abstract void setPosition(int x, int y);

    public abstract void setPosition(int x1, int y1, int x2, int y2);

    public abstract Rectangle getBounds();

    public abstract void southEastResize(int w, int h);

    public abstract void northEastResize(int w, int h);

    public abstract void northWestResize(int w, int h);

    public abstract void southWestResize(int w, int h);

    public void setStrokeWidth(float strokeWidth) {
        this.strokeWidth = strokeWidth;
    }
}
