/**
 *  $Id: Oval.java,v 1.11 2007/02/27 11:11:42 davidwaf Exp $
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
package avoir.realtime.classroom.whiteboard.item;

import java.awt.Color;
import java.awt.Graphics2D;
import java.awt.Rectangle;
import java.awt.geom.Ellipse2D;

/**
 * Oval object for use on the Whiteboard
 */
@SuppressWarnings("serial")
public class Oval implements Item {

    private int x;
    private int y;
    private int width;
    private int height;
    private Color colour;
    private boolean fill;
    private float stroke;
    private String id;

    public void setId(String id) {
        this.id = id;
    }

    public String getId() {
        return id;
    }

    /**
     * Constructs a new Oval.
     *
     * @param ix Horizontal position
     * @param iy Vertical position
     * @param iw width
     * @param ih height
     * @param colour Color
     * @param fill fill
     */
    public Oval(int ix, int iy, int iw, int ih, Color colour, boolean fill,
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
    private String sessionId;

    public String getSessionId() {
        return sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }
    /**
     * Constructs a new Oval with a the default value of 1 for stroke.
     *
     * @param ix Horizontal position
     * @param iy Vertical position
     * @param iw width
     * @param ih height
     * @param colour Color
     * @param fill fill
     */
    public Oval(int ix, int iy, int iw, int ih, Color colour, boolean fill) {
        this(ix, iy, iw, ih, colour, fill, 1);
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
     * Returns true if the Oval contains the specified point
     * @param x horizontal coordinate
     * @param y vertical coordinate
     * @return boolean
     */
    public boolean contains(int x, int y) {
//        Ellipse2D tmp = new Ellipse2D.Double(this.x, this.y, width, height);
        Rectangle tmp = new Rectangle(x, y, width, height);
        return tmp.contains(x, y);
    }

    /**
     * Returns the color of the Oval
     * @return Color
     */
    public Color getCol() {
        return colour;
    }

    /**
     * get the stroke
     * @return float
     */
    public float getStroke() {
        return stroke;
    }

    /**
     * Paints the Oval on the supplied Graphics object
     * @param g Graphics object
     * No longer used
     */
    public void paint(Graphics2D g) {
        g.setColor(colour);

        if (fill) {
            g.fillOval(x, y, width, height);
        } else {
            g.drawOval(x, y, width, height);
        }
    }

    /**
     * Provides a String representation of the Oval and its properties
     * @return String of the form: "Oval", horizontal position, vertical position, width, height, color, fill
     */
    public String toString() {
        return "Oval - x:" + x + " y:" + y + " w:" + width + " h:" + height +
                " colour:" + colour + " fill:" + fill + " stroke.width:" +
                stroke +
                "\n";
    }

    /**
     * gets the rectangle bounding the coodinates of this object
     * @return Rectangle
     */
    public Rectangle getRect() {
        return new Rectangle(x, y, width, height);
    }

    /**
     * returns true if this object is filled
     * @return boolean
     */
    public boolean isFilled() {
        return fill;
    }

    /**
     * Specifies how the Oval is to move itself
     * @param dX horizontal distance to move
     * @param dY vertical distance to move
     * @return returns the Oval in its new position
     */
    public Item getTranslated(int dX, int dY) {
        return new Oval(x + dX, y + dY, width, height, colour, fill, stroke);
    }
}
