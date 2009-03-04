/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common;

import java.awt.Point;
import javax.swing.ImageIcon;

public class Pointer {

    Point point;
    ImageIcon icon;

    public Pointer(Point point, ImageIcon icon) {
        this.point = point;
        this.icon = icon;
    }

    public ImageIcon getIcon() {
        return icon;
    }

    public void setIcon(ImageIcon icon) {
        this.icon = icon;
    }

    public Point getPoint() {
        return point;
    }

    public void setPoint(Point point) {
        this.point = point;
    }
}