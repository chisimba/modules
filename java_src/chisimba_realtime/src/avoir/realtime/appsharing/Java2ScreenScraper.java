/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.appsharing;

import avoir.realtime.common.TCPSocket;
import java.awt.Color;
import java.awt.Graphics2D;
import java.awt.Point;
import java.awt.Polygon;
import java.awt.Rectangle;
import java.awt.Robot;
import java.awt.image.BufferedImage;
import java.awt.image.PixelGrabber;
import java.io.ByteArrayOutputStream;

import com.sun.image.codec.jpeg.JPEGCodec;
import com.sun.image.codec.jpeg.JPEGImageEncoder;

public class Java2ScreenScraper
        extends ScreenScraper {

    public Java2ScreenScraper(TCPSocket client, String sessionId, boolean record)
            throws Exception {
        super(client, sessionId, record);
        robot = new Robot();
    }

    public byte[] doJpegCompression(int[] pixels, int iWidth, int iHeight) {
        byte[] baRet = null;
        try {
            ByteArrayOutputStream baos = new ByteArrayOutputStream();
            JPEGImageEncoder jpeg = JPEGCodec.createJPEGEncoder(baos);
            BufferedImage bi = new BufferedImage(
                    iWidth, iHeight, BufferedImage.TYPE_INT_RGB);

            bi.setRGB(0, 0, iWidth, iHeight, pixels, 0, iWidth);
            jpeg.encode(bi);
            baRet = baos.toByteArray();
            bi.flush();
        } catch (Exception e) {
            e.printStackTrace();
        }
        return baRet;
    }

    public int[] grabPixels(int[] pixels, Rectangle rect)
            throws Exception {
//    	System.out.println("grabbing rect:" + rect);
        BufferedImage bImage = grabBufferedImage(rect);
        //rect.x + " y:" + rect.y)
        PixelGrabber pg = new PixelGrabber(
                bImage,
                0,//rect.x,
                0,//rect.y,
                rect.width,
                rect.height,
                pixels,
                0,
                rect.width);
        pg.grabPixels();
        return pixels;
    }
    /*
    public byte[] grabJpeg(Rectangle rect)
    throws Exception{

    ByteArrayOutputStream baos = new ByteArrayOutputStream();
    JPEGImageEncoder jpeg = JPEGCodec.createJPEGEncoder(baos);
    jpeg.encode(grabBufferedImage(rect));
    //byte[] ba = baos.toByteArray();
    //System.out.println(getClass().getName()
    //  + ".grabJpeg: " + ba.length + " bytes.");
    return baos.toByteArray();
    }
     */

    public void stopScraping() {
        super.stopScraping();

    //System.out.println(getClass().getName() + ".stopScraping: "
    //  + " setting robot = null.");
    // robot = null;
    }

    private BufferedImage grabBufferedImage(Rectangle rect) {
        BufferedImage bi = robot.createScreenCapture(rect);
        drawVirtualCursor(bi, rect);
        return bi;
    }

    private void drawVirtualCursor(BufferedImage bi, Rectangle rectCapture) {
        // draw mouse cursor
        Point point = MouseLocator.getMouseLocation();
        if (null != point) {
            Polygon polygon = new Polygon(
                    this.X_POINTS, this.Y_POINTS, this.X_POINTS.length);
            polygon.translate(point.x - rectCapture.x, point.y - rectCapture.y);
            // check if cursor overlaps capture rect?
            Graphics2D graphics2d = (Graphics2D) bi.getGraphics();
            graphics2d.setXORMode(Color.GREEN);
            graphics2d.fillPolygon(polygon);
            graphics2d.setXORMode(Color.DARK_GRAY);
            graphics2d.drawPolygon(polygon);
        }
    }
    private Robot robot;
    private static final int[] X_POINTS = {0, 0, 8, 15, 23, 16, 24};
    private static final int[] Y_POINTS = {0, 35, 32, 45, 42, 28, 24};
}

