/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package avoir.realtime.tcp.base.appsharing;

import java.awt.Rectangle;

public class ScreenScrapeJPEGData extends CompressedScreenScrapeRect
    implements java.io.Serializable{

    public ScreenScrapeJPEGData(byte[] ba, Rectangle rect){
        super(ba, rect);
    }

}