/*
 *  Copyright (C) GNU/GPL AVOIR 2008
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
package avoir.realtime.instructor.whiteboard;

import java.awt.AWTEvent;
import java.awt.AWTEventMulticaster;
import java.awt.BasicStroke;
import java.awt.Color;
import java.awt.Component;
import java.awt.Dimension;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseEvent;

/**
 * This is still our own button, with most basic jbutton methods, but
 * we do our own painting on paint method
 */
class PixelButtonOption extends Component implements ActionListener {

    boolean pressed = false;
    ActionListener actionListener;
    String actionCommand;
    float width;
    BasicStroke basicStroke;
    float strokeWidth;
    PixelButton pixelButton;

    PixelButtonOption(float width, float strokeWidth, PixelButton pixelButton) {
        this.width = width;
        this.pixelButton = pixelButton;
        this.strokeWidth = strokeWidth;
        basicStroke = new BasicStroke(width);
        this.addActionListener(this);
        enableEvents(AWTEvent.MOUSE_EVENT_MASK);
    }

    /**
     * we do our own painting here
     * @param g Graphics
     */
    public void paint(Graphics g) {

        int width = getSize().width, height = getSize().height;
        g.setColor(Color.white);
        g.draw3DRect(1, 1, width - 3, height - 3, !pressed);
        g.setColor(Color.white);
        g.fillRect(3, 3, width - 6, height - 6);
        Graphics2D g2 = (Graphics2D) g;
        g2.setColor(Color.black);
        g2.setStroke(basicStroke);
        g2.drawLine(2, height / 2, width - 2, height / 2);
    }

    public void actionPerformed(ActionEvent e) {
        strokeWidth = width;
        pixelButton.repaint();
    }

    public Dimension getPreferredSize() {
        return getSize();
    }

    public void processEvent(AWTEvent event) {
        if (event.getID() == MouseEvent.MOUSE_PRESSED) {
            // Set the button in the pressed state
            pressed = true;
            // and paint it in pressed state.
            repaint();
        } else if (event.getID() == MouseEvent.MOUSE_RELEASED) {
            // Set the button in up state
            pressed = false;
            repaint();
            // and call fireEvent, which will send events
            // to the listeners to PictureButton.
            fireEvent();
        }
        super.processEvent(event);
    }

    public void setActionCommand(String actionCommand) {
        this.actionCommand = actionCommand;
    }

    public void addActionListener(ActionListener l) {
        actionListener = AWTEventMulticaster.add(actionListener, l);
    }

    public void removeActionListener(ActionListener l) {
        actionListener = AWTEventMulticaster.remove(actionListener, l);
    }

    private void fireEvent() {
        if (actionListener != null) {
            ActionEvent event = new ActionEvent(this,
                    ActionEvent.ACTION_PERFORMED, actionCommand);
            actionListener.actionPerformed(event);
        }
    }
}
