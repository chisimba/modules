/**
 *  $Id: Rect.java,v 1.10 2007/02/27 11:12:04 davidwaf Exp $
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
package avoir.realtime.tcp.whiteboard.item;

import java.awt.Color;
import java.awt.Graphics2D;
import java.awt.Rectangle;

/**
 * Rectangle object for drawing on the Whiteboard
 */
@SuppressWarnings("serial")
public class Rect implements Item {

    private int x;
    private int y;
    private int width;
    private int height;
    private Color colour;
    private boolean fill;
    private float stroke;

    /**
     * Constructs a new rectangle.
     *
     * @param ix Horizontal coordinate
     * @param iy Vertical coordinate
     * @param iw Width
     * @param ih Height
     * @param colour Color
     * @param fill Fill
     * @param stroke Stroke
     */
    public Rect(int ix, int iy, int iw, int ih, Color colour, boolean fill,
            float stroke) {
        x = ix;
        y = iy;
        width = iw;
        height = ih;
        this.fill = fill;
        this.colour = colour;
        this.stroke = stroke;
    }
    int index;

    public int getIndex() {
        return index;
    }

    public void setIndex(int index) {
        this.index = index;
    }

    /**
     * Constructs a new rectangle with the default value of 1 for the stroke.
     *
     * @param ix Horizontal coordinate
     * @param iy Vertical coordinate
     * @param iw Width
     * @param ih Height
     * @param colour Color
     * @param fill Fill
     */
    public Rect(int ix, int iy, int iw, int ih, Color colour, boolean fill) {
        this(ix, iy, iw, ih, colour, fill, 1);
    }

    /**
     * Returns true if the rectangle contains the given coordinates
     * @param x Horizontal coordinate
     * @param y Vertical coordinate
     * @return boolean
     */
    public boolean contains(int x, int y) {
        Rectangle tmp = new Rectangle(this.x, this.y, width, height);

        return tmp.contains(x, y);
    }

    /**
     * returns a Rectangle object covered by the coodinates of this object
     * @return Rectangle
     */
    public Rectangle getRect() {
        return new Rectangle(this.x, this.y, width, height);
    }

    /**
     * returns true if this rect is filled
     * @return boolean
     */
    public boolean isFilled() {
        return fill;
    }

    /**
     * return the size of this object
     * @return Dimension
     */
    public java.awt.Rectangle getBounds() {
        return new java.awt.Rectangle(x, y, width, height);
    }

    /**
     * Sets the size of the object
     * @param width int
     * @param height int
     */
    public void setSize(int width, int height) {
        this.width = width;
        this.height = height;
    }

    /**
     * Returns the color of the Rectangle
     * @return Color
     */
    public Color getCol() {
        return colour;
    }

    /**
     * return the stroke width to be used for drawing
     * @return float
     */
    public float getStroke() {
        return stroke;
    }

    /**
     * Specifies how the Rectangle is supposed to draw itself
     * @param g Graphics object to draw on
     * No longer used
     */
    public void paint(Graphics2D g) {
        g.setColor(colour);

        if (fill) {
            g.fillRect(x, y, width, height);
        } else {
            g.drawRect(x, y, width, height);
        }
    }

    /**
     * Describes how the Rectangle is to move itself given a new set of coordinates
     * @param dX Horizontal distance
     * @param dY Vertical distance
     * @return The Rectangle in its new position
     */
    public Item getTranslated(int dX, int dY) {
        return new Rect(x + dX, y + dY, width, height, colour, fill, stroke);
    }

    /**
     * Provides a String representation of the Rectangle and its attributes
     * @return String of the form "Rectangle",X coord, Y coord, width, height, Color, fill
     */
    public String toString() {
        return "Rectangle - x:" + x + " y:" + y + " w:" + width + " h:" +
                height + " colour:" + colour + " fill:" + fill +
                " stroke.width: " +
                stroke + "\n";
    }
}
