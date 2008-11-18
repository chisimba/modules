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
 *
 * Create our own custom button that basically shows current pixel selection
 * Has some basic jbutton methods
 * */
class PixelButton extends Component {

    boolean pressed = false;
    ActionListener actionListener;
    String actionCommand;
    float strokeWidth;

    PixelButton(float strokeWidth) {
        this.strokeWidth = strokeWidth;
        // Rather than adding a MouseListener we force all Mouse
        // button events to be sent to the ProcessEvents()
        // method with this call to enableEvents with this mask.
        enableEvents(AWTEvent.MOUSE_EVENT_MASK);
    }

    public void paint(Graphics g) {

        int width = getSize().width,   height = getSize().height;

        // Use gray for the button sides.
        g.setColor(Color.GRAY);

        // Use the 3D rectangle drawing methods to create a
        // button style border around the image.
        // Redraw with smaller dimensions to make border larger.
        // Note that the last argument determines if the rectangle
        // shading is for raised or lowered bevel.
        g.draw3DRect(0, 0, width - 1, height - 1, !pressed);
        g.draw3DRect(1, 1, width - 3, height - 3, !pressed);
        g.draw3DRect(2, 2, width - 5, height - 5, !pressed);

        // Change back to white for the image background.
        g.setColor(Color.white);

        // Fill the area inside the last 3D rectangle
        g.fillRect(3, 3, width - 6, height - 6);

        Graphics2D g2 = (Graphics2D) g;
        g2.setColor(Color.black);
        g2.setStroke(new BasicStroke(strokeWidth));
        g2.drawLine(2, height / 2, width - 2, height / 2);

    }

    // The layout managers call this for a component when they
    // decide how much space to allow for it.
    public Dimension getPreferredSize() {
        return getSize();
    }

    // We forced all mouse click events to come here
    // in a manner similar to the old Java 1.0 event
    // handling style. The event is examined to see
    // what kind it is.
    public void processEvent(AWTEvent e) {
        if (e.getID() == MouseEvent.MOUSE_PRESSED) {
            // Set the button in the pressed state
            pressed = true;
            // and paint it in pressed state.
            repaint();
        } else if (e.getID() == MouseEvent.MOUSE_RELEASED) {
            // Set the button in up state
            pressed = false;
            repaint();
            // and call fireEvent, which will send events
            // to the listeners to PictureButton.
            fireEvent();
        }
        super.processEvent(e);
    }

    // A listener or event handling for this button may check
    // its ActionCommand value to see which button it is.
    // For a regular Button the ActionCommand value defaults
    // to the name on the Button. The setActionCommand allows
    // one to use a different value to test for in the
    // listener. Here we are making our own button so we
    // should set the ActionCommand value.
    public void setActionCommand(String actionCommand) {
        this.actionCommand = actionCommand;
    }

    // These three methods are what allow this button to become
    // an event generator. The AWTEventMulticaster takes care
    // of most of the work.

    // Here we allow listeners to add themselves to our
    // listener list. We use the AWTEventMulticaster static add.
    public void addActionListener(ActionListener l) {

        // NOTE: an AWTEventMulticaster object is returned here.
        // but since it implements all the listener interfaces
        // so we can reference it with our actionListener variable.
        actionListener = AWTEventMulticaster.add(actionListener, l);
    }

    // Here we allow listeners to remove themselves from our
    // listener list. We use the AWTEventMulticaster static
    // remove method.
    public void removeActionListener(ActionListener l) {
        actionListener = AWTEventMulticaster.remove(actionListener, l);
    }

    // This method is called by the processEvent() above
    // when the mouse button is released over the button .
    private void fireEvent() {

        if (actionListener != null) {
            // listener list. We use the AWTEventMulticaster
            // static add.
            ActionEvent event = new ActionEvent(this,
                    ActionEvent.ACTION_PERFORMED, actionCommand);

            // The AWTEventMulticaster object, but referenced
            // here with our actionListener variable, will call
            // all the actionListeners with this call to its
            // actionPerformed.
            actionListener.actionPerformed(event);
        }
    }
}
