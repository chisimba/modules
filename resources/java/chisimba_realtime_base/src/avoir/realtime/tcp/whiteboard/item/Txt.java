/**
 *  $Id: Txt.java,v 1.9 2007/02/27 11:11:16 davidwaf Exp $
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

import java.awt.*;

/**
 * Text object for drawing on the Whiteboard
 */
@SuppressWarnings("serial")
public class Txt implements Item {

    private int x;
    private int y;
    private Color Colour;
    private String content;
    Font font;
    boolean underline;

    /**
     * Constructor
     * @param ix int
     * @param iy int
     * @param c Color
     * @param content String
     * @param font Font
     * @param underline boolean
     */
    public Txt(int ix, int iy, Color c, String content, Font font,
            boolean underline) {
        x = ix;
        y = iy;

        this.font = font;
        this.underline = underline;
        this.content = content;
        Colour = c;
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
     * Returns true if the text contains the given coordinates
     * @param x Horizontal coordinate
     * @param y Vertical coordinate
     * @return boolean
     */
    public boolean contains(int xx, int yy) {
        return false;
    }

    public boolean contains(int xx, int yy, Graphics2D g2) {
        FontMetrics metrics = g2.getFontMetrics(font);

        int hgt = metrics.getHeight();
        int adv = metrics.stringWidth(content);
        Dimension size = new Dimension(adv + 2, hgt + 2);
        Rectangle r = new Rectangle(x, y - size.height, size.width,
                size.height + 5);

        return r.contains(xx, yy);
    }

    /**
     * return the rectagle bounding the text object
     * @return Rectangle
     */
    public Rectangle getRect(Graphics2D g2) {
        FontMetrics metrics = g2.getFontMetrics(font);

        int hgt = metrics.getHeight();
        int adv = metrics.stringWidth(content);
        Dimension size = new Dimension(adv + 2, hgt + 2);
        Rectangle r = new Rectangle(x, y - size.height, size.width,
                size.height + 5);

        return r;
    }

    /**
     * returns the content of this object
     * @return String
     */
    public String getContent() {
        return content;
    }

    /**
     * Sets the content of this string to the supplied aurgument
     * @param content String
     */
    public void setContent(String content) {
        this.content = content;
    }

    /**
     * sets the current color as the supplied value
     * @param c Color
     */
    public void setColor(Color c) {
        Colour = c;
    }

    /**
     * sets the font to thr supplied value
     * @param font Font
     */
    public void setFont(Font font) {
        this.font = font;
    }

    /**
     * sets the underline property to the supplied value
     * @param underline boolean
     */
    public void setUnderline(boolean underline) {
        this.underline = underline;
    }

    /**
     * Returns the color of the Rectangle
     * @return Color
     */
    public Color getCol() {
        return Colour;
    }

    /**
     * Specifies how the Rectangle is supposed to draw itself
     * @param g Graphics object to draw on
     */
    public void paint(Graphics2D g) {
    }

    /**
     * get the font of this object
     * @return Font
     */
    public Font getFont() {
        return font;
    }

    /**
     * returns true if text is underlined
     * @return boolean
     */
    public boolean isUnderlined() {
        return underline;
    }

    /**
     * returns the x,y location of this object string
     * @return Point
     */
    public Point getPoint() {
        return new Point(x, y);
    }

    /**
     * Describes how the Text is to move itself given a new set of coordinates
     * @param dX Horizontal distance
     * @param dY Vertical distance
     * @return The Rectangle in its new position
     */
    public Item getTranslated(int dX, int dY) {
        return new Txt(x + dX, y + dY, Colour, content, font, underline);
    }

    /**
     * Provides a String representation of the Rectangle and its attributes
     * @return String of the form "Rectangle",X coord, Y coord, width, height, Color, fill
     */
    public String toString() {
        return "Text - x:" + x + " y:" + y + " colour:" + Colour + " content:" +
                content + " font:" + font + " underlined:" + underline + "\n";
    }
}
