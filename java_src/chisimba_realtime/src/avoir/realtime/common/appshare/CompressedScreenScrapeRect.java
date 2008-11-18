/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.appshare;

import avoir.realtime.appsharing.*;
import java.awt.Rectangle;


public abstract class CompressedScreenScrapeRect extends Rectangle
        implements
        java.io.Serializable,
        Droppable {

    public CompressedScreenScrapeRect(byte[] ba, Rectangle rect) {//, int iIndex){//Point pOrigin, int width, int height){
        super(rect);
        this.ba = ba;
//        this.width = rect.width;
//        this.height = rect.height;
//        this.pOrigin = rect.getLocation();
//        this.iIndex = iIndex;
    }
//
//    public int getWidth(){
//        return width;
//    }
//
//    public int getHeight(){
//        return height;
//    }
//
//    public Point getOrigin(){
//    	return this.pOrigin;
//    }
    public byte[] getBytes() {
        return ba;
    }

    public int getByteCount() {
        return ba.length;
    }
//
//    public int getIndex(){
//    	return this.iIndex;
//    }
    private byte[] ba;
//    private int width;
//    private int height;
//    private Point pOrigin;
//    //private int iIndex;
    public String toString() {
        String strRet = super.toString();
//    	strRet += "\n\twidth: " + this.getWidth();
//    	strRet += "\n\theight: " + this.getHeight();
//    	strRet += "\n\torigin: " + this.getOrigin();
        strRet += " byte count: " + this.getByteCount();
//    	strRet += "\n\tindex: " + this.getIndex();
        return strRet;
    }    //private Point pointMouse;
    // cursor type and position
    // incremental or full scrape
}
