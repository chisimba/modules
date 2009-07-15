/**
 *  $Id: Img.java,v 1.2 2007/05/18 10:40:16 davidwaf Exp $
 *
 *  Copyright (C) GNU/GPL AVOIR 2007
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
package org.avoir.realtime.gui.whiteboard.items;

import java.awt.Graphics2D;
import java.awt.Image;
import java.awt.Point;
import java.awt.Rectangle;
import java.awt.geom.Point2D;
import java.util.ArrayList;
import java.util.List;

/**
 * Imgangle object for drawing on the Whiteboard
 */
@SuppressWarnings("serial")
public class Img extends Item {

    private int x;
    private int y;
    private int width;
    private int height;
    private Image image;

    public Img(int x, int y, int width, int height, Image image, String id) {
        this.x = x;
        this.y = y;
        this.width = width;
        this.height = height;
        this.image = image;
        this.id = id;
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

    @Override
    public void setPosition(int x, int y) {
        this.x = x;
        this.y = y;
    }

    @Override
    public boolean contains(Point point) {
        return new Rectangle(x, y, width, height).contains(point);
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
    public void render(Graphics2D g) {
    }

    public int getHeight() {
        return height;
    }

    public void setHeight(int height) {
        this.height = height;
    }

    public Image getImage() {
        return image;
    }

    public void setImage(Image image) {
        this.image = image;
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
    public void southEastResize(int x, int y) {
        width = x - this.x;
        height = y - this.y;
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
        Img img = new Img(x + dx, y + dy, width, height, image, id);
        return img;
    }

    /**
     * Returns true if the Image contains the given coordinates
     * @param x Horizontal coordinate
     * @param y Vertical coordinate
     * @return boolean
     */
    public boolean contains(int x, int y) {
        Rectangle tmp = new Rectangle(this.x, this.y, width, height);

        return tmp.contains(x, y);
    }

    /**
     * return the size of this object
     * @return Dimension
     */
    public java.awt.Rectangle getBounds() {
        return new java.awt.Rectangle(x, y, width, height);
    }

    /**
     * Provides a String representation of the Imgangle and its attributes
     * @return String of the form "Imgangle",X coord, Y coord, width, height, Color, fill
     */
    public String toString() {
        return "Image - x:" + x + " y:" + y + " w:" + width + " h:" + height + "\n";
    }
}
