/*
 * 
 * Copyright (C) GNU/GPL AVOIR 2008
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
package avoir.realtime.instructor.whiteboard;

import javax.swing.*;
import java.awt.*;
import java.awt.event.AWTEventListener;
import java.awt.event.MouseEvent;

/**
 * GlassPane tutorial
 * "A well-behaved GlassPane"
 * http://weblogs.java.net/blog/alexfromsun/
 * <p/>
 * This is the final version of the GlassPane
 * it is transparent for MouseEvents,
 * and respects underneath component's cursors by default,
 * it is also friedly for other users,
 * if someone adds a mouseListener to this GlassPane
 * or set a new cursor it will respect them
 *
 * @author Alexander Potochkin
 */
public class FinalGlassPane extends JPanel implements AWTEventListener {

    private final JFrame frame;
    private Point point = new Point();
    private Classroom mf;
    private boolean drawRect = false;
    JTabbedPane pane;
    Point location;
    int width = 0;
    int height = 0;

    public FinalGlassPane(Classroom mf) {
        super(null);
        this.mf = mf;
        this.frame = mf.getParentFrame();
        setOpaque(false);
    }

    private void determineWebPageCoordinates() {
        pane = mf.getMainTabbedPane();
        location = pane.getSelectedComponent().getLocationOnScreen();
        width = mf.getWhiteboard().getWidth();
        height = mf.getWhiteboard().getHeight();

    }

    public void setPoint(Point point) {
        this.point = point;
    }

    public void setMf(Classroom mf) {
        this.mf = mf;
        determineWebPageCoordinates();
    }

    public void setDrawRect(boolean drawRect) {
        this.drawRect = drawRect;
    }

    protected void paintComponent(Graphics g) {
        super.paintComponent(g);
        Graphics2D g2 = (Graphics2D) g;
        if (location != null) {
            g2.setStroke(new BasicStroke(4));
            g2.setColor(Color.RED);
            g2.drawRect(location.x - 10, location.y - 25, width + 15, height+10);
            g2.dispose();
        }
    }

    public void eventDispatched(AWTEvent event) {
        if (event instanceof MouseEvent) {
            MouseEvent me = (MouseEvent) event;
            if (!SwingUtilities.isDescendingFrom(me.getComponent(), frame)) {
                return;
            }
            if (me.getID() == MouseEvent.MOUSE_EXITED && me.getComponent() == frame) {
                point = null;
            } else {
                MouseEvent converted = SwingUtilities.convertMouseEvent(me.getComponent(), me, frame.getGlassPane());
                point = converted.getPoint();
            }
            repaint();
        }
    }

    /**
     * If someone adds a mouseListener to the GlassPane or set a new cursor
     * we expect that he knows what he is doing
     * and return the super.contains(x, y)
     * otherwise we return false to respect the cursors
     * for the underneath components
     */
    public boolean contains(int x, int y) {
        if (getMouseListeners().length == 0 && getMouseMotionListeners().length == 0 && getMouseWheelListeners().length == 0 && getCursor() == Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR)) {
            return false;
        }
        return super.contains(x, y);
    }
}


