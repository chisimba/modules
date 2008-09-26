/**
 *  $Id: WBLine.java,v 1.12 2007/03/05 09:43:40 adrian Exp $
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
import java.awt.geom.Line2D;

/**
 * Represents a line Item on the Whiteboard
 */
@SuppressWarnings("serial")
public class WBLine implements Item {

    /**
     * Horizontal coordinate of endpoint 1
     */
    public int x1;
    /**
     * vertical coordinate of endpoint 1
     */
    public int y1;
    /**
     * horizontal coordinate of endpoint 2
     */
    public int x2;
    /**
     * vertical coordinate for end point 2
     */
    public int y2;
    private Color Colour;
    private float stroke;
    private String id;

    public void setId(String id) {
        this.id = id;
    }

    public String getId() {
        return id;
    }
    private String sessionId;

    public String getSessionId() {
        return sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }
    /**
     * Constructor
     * @param x x coord, end point 1
     * @param y y coord, end point 1
     * @param dx x coord, end point 2
     * @param dy y coord, end point 2
     * @param c Color
     */
    public WBLine(int x, int y, int dx, int dy, Color c, float stroke) {
        x1 = x;
        y1 = y;
        x2 = dx;
        y2 = dy;
        Colour = c;
        this.stroke = stroke;

    }

    /**
     * Returns the Color of the Line
     * @return Color
     */
    public Color getCol() {
        return Colour;
    }

    /**
     * gets the stroke width to be used for external drawing
     * @return float
     */
    public float getStroke() {
        return stroke;
    }

    /**
     * Returns true if the supplied point lies on (is containe by) the Line
     * @param x horizontal coordinate of point
     * @param y vertical coordinate of point
     * @return boolean
     */
    public boolean contains(int x, int y) {
        Line2D line = new Line2D.Double(x1, y1, x2, y2);
        return line.intersects(new java.awt.Rectangle(x - 2, y - 2, 4, 4));
    }
    int index;

    public int getIndex() {
        return index;
    }

    public void setIndex(int index) {
        this.index = index;
    }

    /**
     * returns line2D fro external drawing
     * @return Line2D
     */
    public Line2D getLine() {
        return new Line2D.Double(x1, y1, x2, y2);
    }

    /**
     * return the size of this object
     * @return Dimension
     */
    public java.awt.Rectangle getBounds() {
        return new java.awt.Rectangle();
    }

    /**
     * Sets the size of the object
     * @param width int
     * @param height int
     */
    public void setSize(int width, int height) {
    }

    /**
     * Returns a String representation of the Line
     * @return Form- "String", x1, y1, x2, y2, color
     */
    public String toString() {
        return "Line - x1:" + x1 + " y1:" + y1 + " x2:" + x2 + " y2:" + y2 + " colour:" + Colour + " stroke.width: " + stroke + "\n";
    }

    /**
     * Describes how the Line is to paint itself on the given Graphics object
     * @param g the Graphics object to be drawn on
     */
    public void paint(Graphics2D g) {
        g.setColor(Colour);
        g.drawLine(x1, y1, x2, y2);
    }

    /**
     * Describes how the Line is supposed to move itself
     * @param dX horizontal distance
     * @param dY vertical distance
     * @return Returns the Line in its new position
     */
    public WBLine getTranslated(int dX, int dY) {
        return new WBLine(x1 + dX, y1 + dY, x2 + dX, y2 + dY, Colour, stroke);
    }
}
