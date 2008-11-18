/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.appsharing;

import avoir.realtime.common.appshare.*;
import java.awt.Rectangle;

public class ScreenScrapeData
        implements
        java.io.Serializable,
        Droppable, ScreenScrapeDataIntf {

    public ScreenScrapeData(CompressedScreenScrapeRect[] rects, Rectangle rectArea, boolean bKeyframe) {
        this.rects = rects;
        for (int i = 0; i < rects.length; i++) {
            this.iByteCount += rects[i].getByteCount();
        }
        this.rectArea = rectArea;
        this.bKeyFrame = bKeyframe;
    //System.out.println("keyframe: " + this.bKeyFrame);
    //if()

    //this.ba = ba;
    //System.out.println(getClass().getName() + " " + ba.length + " bytes.");
    //this.width = width;
    //this.height = height;
    //this.pointMouse = MouseLocator.getMouseLocation();
    }

    public boolean isKeyFrame() {
        return this.bKeyFrame;
    }

    public int getWidth() {
        return this.rectArea.width;
    }

    public int getHeight() {
        return this.rectArea.height;//height;
    }

    public CompressedScreenScrapeRect[] getCompressedRects() {
        return this.rects;
    }
//
//    public byte[] getBytes(){
//        return this.iByteCount;//ba;
//    }
    public int getByteCount() {
        return iByteCount;
    //	int iCount = 0;
    //return iCount;
    }
    //public Point getMouseLocation(){
    //	return this.pointMouse;
    //}

    //private byte[] ba;
    //private int width;
    //private int height;
    public String toString() {
        String str = super.toString();
        str += "\n\tbyte count: " + this.getByteCount();
        str += "\n\trect count: " + this.getCompressedRects().length;
        str += "\n\tregion: " + this.rectArea;
        return str;
    }
    private int iByteCount;
//    private int width;
//    private int height;
    private boolean bKeyFrame;
    private Rectangle rectArea;
    private CompressedScreenScrapeRect[] rects;    //private Point pointMouse;
    // cursor type and position
    // incremental or full scrape
}
