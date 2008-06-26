/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.whiteboard.item;

import java.awt.Color;
import java.awt.Graphics2D;
import java.awt.Rectangle;

/**
 *
 * @author developer
 */
public class FreeHand implements Item {

    private String id;

    public void setId(String id) {
        this.id = id;
    }

    public String getId() {
        return id;
    }

    public boolean contains(int x, int y) {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public Rectangle getBounds() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public Color getCol() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public int getIndex() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public Item getTranslated(int dX, int dY) {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public void paint(Graphics2D g) {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public void setIndex(int index) {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public void setSize(int x, int y) {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
