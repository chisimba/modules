/**
 *  $Id: Pen.java,v 1.9 2007/02/27 11:10:52 davidwaf Exp $
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

import java.awt.BasicStroke;
import java.awt.Color;

import java.awt.Graphics2D;
import java.util.Vector;

/**
 * Pen Item for drawing on the Whiteboard
 */
@SuppressWarnings("serial")
public class Pen implements Item {

    private Vector<WBLine> v;
    private Color Colour;
    private float stroke;
    private String id;

    public void setId(String id) {
        this.id = id;
    }

    public String getId() {
        return id;
    }
    /**
     * Constructor
     * @param v Vector
     * @param c Color
     */
    public Pen(Vector<WBLine> v, Color c, float stroke) {
        this.v = v;
        Colour = c;
        this.stroke = stroke;
    }
    private String sessionId;

    public String getSessionId() {
        return sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }
    /**
     * return the size of this object
     * @return Dimension
     */
    public java.awt.Rectangle getBounds() {
        return new java.awt.Rectangle();
    }
    int index;

    public int getIndex() {
        return index;
    }

    public void setIndex(int index) {
        this.index = index;
    }

    /**
     * Sets the size of the object
     * @param width int
     * @param height int
     */
    public void setSize(int width, int height) {
    }

    /**
     * Returns the Color of the pen's marking
     * @return Color
     */
    public Color getCol() {
        return Colour;
    }

    /**
     * gets the stroke width
     * @return float
     */
    public float getStroke() {
        return stroke;
    }

    /**
     * Returns true if the line drawn by the pen includes the given point
     * @param x horizontal coordinate
     * @param y vertical coordinate
     * @return boolean
     */
    public boolean contains(int x, int y) {
        for (int i = 0; i < v.size(); i++) {
            WBLine tmp = v.elementAt(i);

            if (tmp.contains(x, y)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Describes how the Pen is to display itself on the Whiteboard
     * @param g Graphics object the pen is to be displayed on
     */
    public void paint(Graphics2D g) {
        g.setColor(Colour);
        g.setStroke(new BasicStroke(stroke));
        for (int i = 0; i < v.size(); i++) {
            WBLine tmp = v.elementAt(i);
            g.drawLine(tmp.x1, tmp.y1, tmp.x2, tmp.y2);
        }
        
        
    }

    /**
     * Provides a String representation of the Pen
     * @return String of the form: "Pen", Color
     */
    public String toString() {
        return "pen - colour:" + Colour + " stroke.width: " + stroke + "\n";
    }

    /**
     * Describes how the Pen object moves itself
     * @param dX Horizontal distance
     * @param dY Vertical distance
     * @return the translated Pen object
     */
    public Pen getTranslated(int dX, int dY) {
        Vector<WBLine> v2 = new Vector<WBLine>();

        for (int i = 0; i < v.size(); i++) {
            v2.addElement((v.elementAt(i)).getTranslated(dX, dY));
        }

        return new Pen(v2, Colour, stroke);
    }

    /**
     * get the points for external drawing
     * @return Vector
     */
    public Vector<WBLine> getPoints() {
        return v;
    }
}
