/**
 *         $Id: Item.java,v 1.3 2007/02/26 13:33:17 davidwaf Exp $
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
 * Interface Item represents all objects used in drawing on the Whiteboard
 */
public interface Item extends java.io.Serializable {

    /**
     * Returns the color of the object
     * @return Color
     */
    public Color getCol();

    public void setIndex(int index);

    public int getIndex();

    public void setId(String id);

    public String getId();

    public String getSessionId();

    public void setSessionId(String sessionId);

    /**
     * Returns whether an object contains the specified coordinates
     * @param x horizontal coordinate
     * @param y vertical coordinate
     * @return boolean
     */
    public boolean contains(int x, int y);

    /**
     * The object must know how to draw itself
     * @param g the Graphics object to draw on
     */
    public void paint(Graphics2D g);

    /**
     * The Item must know how to move itself
     * @param dX Distance to move horizontally
     * @param dY Distance to move vertically
     * @return Returns the Item in its new location
     */
    public Item getTranslated(int dX, int dY);

    /**
     * Returns a string representation of the object
     * @return String
     */
    public String toString();

    /**
     * returns the rectangle bounding this object
     * @return Rectangle
     */
    public Rectangle getBounds();

    /**
     * sets the current size of the time
     * @param x int
     * @param y int
     */
    public void setSize(int x, int y);
}
