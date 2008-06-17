/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.whiteboard;

import avoir.realtime.tcp.common.Constants;
import avoir.realtime.tcp.base.RealtimeBase;
import avoir.realtime.tcp.common.packet.WhiteboardPacket;
import avoir.realtime.tcp.whiteboard.item.Item;
import avoir.realtime.tcp.whiteboard.item.Pen;
import avoir.realtime.tcp.whiteboard.item.WBLine;
import java.awt.BasicStroke;
import java.awt.Color;
import java.util.Vector;

/**
 *
 * @author developer
 */
public class Whiteboard {

    public static final int BRUSH_PEN = 0;
    public static final int BRUSH_RECT = 1;
    public static final int BRUSH_RECT_FILLED = 2;
    public static final int BRUSH_OVAL = 3;
    public static final int BRUSH_OVAL_FILLED = 4;
    public static final int BRUSH_LINE = 5;
    public static final int BRUSH_TEXT = 6;
    public static final int BRUSH_MOVE = 7;
    public static final int BRUSH_IMAGE = 8;
    private static final String[] brushName = new String[9];
    private static int BRUSH = BRUSH_PEN;
    private boolean dragging;
    private int startX; //mouse cursor position on draw start
    private int startY; //mouse cursor position on draw start
    private int prevX; //last captured mouse co-ordinate pair
    private int prevY; //last captured mouse co-ordinate pair
    private Color colour;
    private Item selected;
    private Item selectedItem;
    private int selectedIndex = -1;
    public boolean liveDrag = false;
    private final float[] dash1 = {1.0f};
    private final BasicStroke dashed = new BasicStroke(1.0f,
            BasicStroke.CAP_BUTT, BasicStroke.JOIN_MITER, 1.0f, dash1, 0.0f);
    private Vector<WBLine> penVector = new Vector<WBLine>();
    private float strokeWidth = 5;
    private RealtimeBase base;

    public Whiteboard(RealtimeBase base) {
        this.base = base;
    }
    

    static {
        brushName[0] = "Pen";
        brushName[1] = "Rectangle";
        brushName[2] = "Filled Rectangle";
        brushName[3] = "Oval";
        brushName[4] = "Filled Oval";
        brushName[5] = "Line";
        brushName[6] = "Text";
        brushName[7] = "Move";
        brushName[8] = "Image";
    }

    public static int getBRUSH() {
        return BRUSH;
    }

    public static void setBRUSH(int BRUSH) {
        Whiteboard.BRUSH = BRUSH;
    }

    public static int getBRUSH_IMAGE() {
        return BRUSH_IMAGE;
    }

    public static int getBRUSH_LINE() {
        return BRUSH_LINE;
    }

    public static int getBRUSH_MOVE() {
        return BRUSH_MOVE;
    }

    public static int getBRUSH_OVAL() {
        return BRUSH_OVAL;
    }

    public static int getBRUSH_OVAL_FILLED() {
        return BRUSH_OVAL_FILLED;
    }

    public static int getBRUSH_PEN() {
        return BRUSH_PEN;
    }

    public static int getBRUSH_RECT() {
        return BRUSH_RECT;
    }

    public static int getBRUSH_RECT_FILLED() {
        return BRUSH_RECT_FILLED;
    }

    public static int getBRUSH_TEXT() {
        return BRUSH_TEXT;
    }

    public void setPrevXY(int x, int y) {
        prevX = x;
        prevY = y;

    }

    public void addDot(int x, int y, int index) {
        penVector = new Vector<WBLine>();
        penVector.addElement(new WBLine(prevX, prevY, x,
                y, colour, strokeWidth));
        Pen pen = new Pen(penVector, colour,
                strokeWidth);
        pen.setIndex(index);
        base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(), pen, Constants.ADD_NEW_ITEM));
    }

    public void drawPenStart(int x, int y, int index) {
        Pen pen = new Pen(penVector, colour,
                strokeWidth);
        pen.setIndex(index);
        base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(), pen, Constants.CLEAR_ITEM));

        prevX = startX = x;
        prevY = startY = y;
        penVector = new Vector<WBLine>();
        penVector.addElement(new WBLine(startX, startY, startX,
                startY, colour, strokeWidth));
        pen = new Pen(penVector, colour,
                strokeWidth);
        pen.setIndex(index);
        base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(), pen, Constants.ADD_NEW_ITEM));

    }

    public void drawPenStop(int x, int y, int index) {
        // Pen pen = new Pen(penVector, colour,
        //       strokeWidth);
        //pen.setIndex(index);
        // base.getTcpClient().sendPacket(new WhiteboardPacket(base.getSessionId(), pen, Constants.ADD_NEW_ITEM));
        penVector.removeAllElements();
        penVector = null;
    }
}
