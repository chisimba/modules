/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.net;

import java.awt.geom.Line2D;
import java.util.ArrayList;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.whiteboard.items.Line;
import org.avoir.realtime.gui.whiteboard.items.Oval;
import org.avoir.realtime.gui.whiteboard.items.Pen;
import org.avoir.realtime.gui.whiteboard.items.Rect;
import org.jivesoftware.smack.packet.Message;

/**
 *
 * @author developer
 */
public class WhiteboardMessageProcessor {

    public static void processCustomMessage(Message message) {
        String type = (String) message.getProperty("message-type");
        if (type.equals("whiteboard-msg")) {

            String itemType = (String) message.getProperty("item-type");

            if (itemType.trim().equals("line")) {
                try {
                    int x1 = ((Integer) message.getProperty("x1"));
                    int x2 = ((Integer) message.getProperty("x2"));
                    int y1 = ((Integer) message.getProperty("y1"));
                    int y2 = ((Integer) message.getProperty("y2"));
                    float strokeWidth = 1;
                    try {
                        strokeWidth = ((Float) message.getProperty("stroke-width"));
                    } catch (Exception ex) {
                    }
                    Line line = new Line(message.getPacketID(), x1, y1, x2, y2);
                    line.setStrokeWidth(strokeWidth);
                    GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().addItem(line);
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }
            if (itemType.trim().equals("rect")) {
                int x = ((Integer) message.getProperty("x"));
                int y = ((Integer) message.getProperty("y"));
                int w = ((Integer) message.getProperty("width"));
                int h = ((Integer) message.getProperty("height"));

                Rect rect = new Rect(x, y, w, h);
                rect.setId(message.getPacketID());
                GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().addItem(rect);
            }
            if (itemType.trim().equals("oval")) {
                int x = ((Integer) message.getProperty("x"));
                int y = ((Integer) message.getProperty("y"));
                int w = ((Integer) message.getProperty("width"));
                int h = ((Integer) message.getProperty("height"));
                Oval oval = new Oval(x, y, w, h);
                oval.setId(message.getPacketID());
                GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().addItem(oval);
            }
            if (itemType.trim().equals("pen")) {
                ArrayList<Line2D.Double> points = (ArrayList<Line2D.Double>) message.getProperty("points");
                float strokeWidth = 1;
                try {
                    strokeWidth = ((Float) message.getProperty("stroke-width"));
                } catch (Exception ex) {
                }
                Pen pen = new Pen(points);
                pen.setStrokeWidth(strokeWidth);
                pen.setId(message.getPacketID());
                GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().addItem(pen);
            }
        }
    }
}
