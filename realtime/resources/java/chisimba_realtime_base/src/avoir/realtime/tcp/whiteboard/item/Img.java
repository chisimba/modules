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
package avoir.realtime.tcp.whiteboard.item;

import java.awt.Color;
import java.awt.Graphics2D;
import java.awt.Rectangle;

/**
 * Imgangle object for drawing on the Whiteboard
 */
@SuppressWarnings("serial")
public class Img implements Item {

    private int x;
    private int y;
    private int width;
    private int height;
    private String path;
    private String id;
    private int index;
    private int imageIndex;

    public Img(int x, int y, int width, int height, String path, int imageIndex,String id) {
        this.x = x;
        this.y = y;
        this.width = width;
        this.height = height;
        this.path = path;
        this.imageIndex=imageIndex;
        this.id=id;
    }

    public int getImageIndex() {
        return imageIndex;
    }

    public void setImageIndex(int imageIndex) {
        this.imageIndex = imageIndex;
    }

    public void setId(String id) {
        this.id = id;
    }
    private String sessionId;

    public String getSessionId() {
        return sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }
    public String getId() {
        return id;
    }

    public int getIndex() {
        return index;
    }

    public void setIndex(int index) {
        this.index = index;
    }

    public Img(String path) {
        this.path = path;
    }

    public String getImagePath() {
        return path;
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
     * Sets the size of the object
     * @param width int
     * @param height int
     */
    public void setSize(int width, int height) {
        this.width = width;
        this.height = height;
    }

    /**
     * returns the size of the area covered my this image when drawn
     * @return Dimension
     */
    public java.awt.Dimension getSize() {
        return new java.awt.Dimension(width, height);
    }

    public java.awt.Point getPoint() {
        return new java.awt.Point(x, y);
    }

    /**
     * Returns the color of the Imgangle
     * @return Color
     */
    public Color getCol() {
        return new Color(0, 0, 0);
    }

    /**
     * sets this item color to the parsed color c
     * @param c Color
     */
    public void setCol(Color c) {
    }

    /**
     * Specifies how the Image is supposed to draw itself
     * @param g Graphics object to draw on
     * No longer used
     */
    public void paint(Graphics2D g) {
    }

    /**
     * Describes how the this object is to move itself given a new set of coordinates
     * @param dX Horizontal distance
     * @param dY Vertical distance
     * @return The Imgangle in its new position
     */
    public Item getTranslated(int dX, int dY) {
        return new Img(x + dX, y + dY, width, height, path,imageIndex,id);
    }

    /**
     * Provides a String representation of the Imgangle and its attributes
     * @return String of the form "Imgangle",X coord, Y coord, width, height, Color, fill
     */
    public String toString() {
        return "Image - x:" + x + " y:" + y + " w:" + width + " h:" + height + " path: "+path+"\n";
    }
}
