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
package avoir.realtime.appsharing;

import avoir.realtime.common.appshare.CompressedScreenScrapeRect;
import avoir.realtime.common.appshare.ScreenScrapeDataIntf;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Graphics;
import java.awt.GraphicsConfiguration;
import java.awt.GraphicsDevice;
import java.awt.GraphicsEnvironment;
import java.awt.HeadlessException;
import java.awt.Image;
import java.awt.Rectangle;
import java.awt.Toolkit;
import java.awt.Transparency;
import java.awt.image.BufferedImage;
import java.awt.image.ColorModel;
import java.awt.image.MemoryImageSource;
import java.awt.image.PixelGrabber;
import java.io.File;
import java.io.IOException;
import java.util.Vector;
import javax.imageio.ImageIO;
//import javax.media.MediaLocator;
import javax.swing.ImageIcon;
//import javax.swing.ImageIcon;

/**
 *
 * @author developer
 */
public class DesktopSaver {

    private boolean bGotKeyFrame = false;
    private Vector vectLocatedImages;
    private Graphics graphicsOffscreen;
    private Image imageOffscreen;
    private boolean bClearPending;
    private Dimension lastDim = new Dimension(3000, 3000);
    private int count = 0;

    public DesktopSaver() {
    }

    private void cleanup() {
        if (null != this.vectLocatedImages) {
            while (!this.vectLocatedImages.isEmpty()) {
                LocatedImage li = (LocatedImage) this.vectLocatedImages.elementAt(0);
                this.vectLocatedImages.removeElementAt(0);
                li.getImage().flush();
            }
        }
        if (null != this.graphicsOffscreen) {
            this.graphicsOffscreen.dispose();
            this.graphicsOffscreen = null;
        }
        if (null != this.imageOffscreen) {
            this.imageOffscreen.flush();
            this.imageOffscreen = null;
        }
        this.vectLocatedImages = new Vector();
    }

    public static Image toImage(BufferedImage bufferedImage) {
        return Toolkit.getDefaultToolkit().createImage(bufferedImage.getSource());
    }

    public void createMovie(String filemane) {
/*
        // Generate the output media locators.
        MediaLocator oml;
        String outputURL = filemane;
        if ((oml = createMediaLocator(outputURL)) == null) {
            System.err.println("Cannot build media locator from: " + outputURL);
            return;
        }

        Vector inputFiles = new Vector();
        String[] files = new File("recording").list();
        if (files != null) {
            for (int i = 0; i < files.length; i++) {
                inputFiles.addElement("recording/" + files[i]);
            }
            JpegImagesToMovie imageToMovie = new JpegImagesToMovie();
            imageToMovie.doIt(320, 240, 1, inputFiles, oml);
        }*/
    }

  
    private void save(int width, int height) {
        // Sets up a Graphics2DPlotCanvas
        try {
            // 1280x1024
            BufferedImage buffer =
                    new BufferedImage(width,
                    height,
                    BufferedImage.TYPE_INT_RGB);
            Graphics g = buffer.createGraphics();
            int iImageCount = vectLocatedImages.size();
            for (int i = 0; i < iImageCount; i++) {
                LocatedImage li = (LocatedImage) vectLocatedImages.elementAt(i);
                g.setColor(Color.white);
                g.drawImage(
                        li.getImage(),
                        li.x,
                        li.y,
                        null);
            }
            ImageIO.write(buffer, "jpg", new File("recording/frame" + (count++) + ".jpg"));

        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }

    public void pixelUpdate(ScreenScrapeDataIntf data) {

        if (data.isKeyFrame()) {
            this.bGotKeyFrame = true;
            cleanup();

            BufferedImage image = new BufferedImage(data.getWidth(), data.getHeight(), BufferedImage.TYPE_INT_RGB);
            imageOffscreen = toImage(image);


            //       graphicsOffscreen = imageOffscreen.getGraphics();
            Dimension dim = new Dimension(data.getWidth(), data.getHeight());
            if (!dim.equals(this.lastDim)) {
                this.lastDim = dim;
                this.bClearPending = true;
            }

        } else {
            if (!this.bGotKeyFrame) {
                System.out.println("waiting for key frame...");
                return;
            } else {
                //	System.out.println("#UPDATE#");
            }
        }

        CompressedScreenScrapeRect[] compRects = data.getCompressedRects();
        for (int i = 0; i < compRects.length; i++) {
            vectLocatedImages.addElement(
                    this.compressedScrape2LocatedImage(compRects[i]));
        }
        save(data.getWidth(), data.getHeight());
    }

    // This method returns true if the specified image has transparent pixels
    public static boolean hasAlpha(Image image) {
        // If buffered image, the color model is readily available
        if (image instanceof BufferedImage) {
            BufferedImage bimage = (BufferedImage) image;
            return bimage.getColorModel().hasAlpha();
        }

        // Use a pixel grabber to retrieve the image's color model;
        // grabbing a single pixel is usually sufficient
        PixelGrabber pg = new PixelGrabber(image, 0, 0, 1, 1, false);
        try {
            pg.grabPixels();
        } catch (InterruptedException e) {
        }

        // Get the image's color model
        ColorModel cm = pg.getColorModel();
        return cm.hasAlpha();
    }

    // This method returns a buffered image with the contents of an image
    public static BufferedImage toBufferedImage(Image image) {
        if (image instanceof BufferedImage) {
            return (BufferedImage) image;
        }

        // This code ensures that all the pixels in the image are loaded
        image = new ImageIcon(image).getImage();

        // Determine if the image has transparent pixels; for this method's
        // implementation, see e661 Determining If an Image Has Transparent Pixels
        boolean hasAlpha = hasAlpha(image);

        // Create a buffered image with a format that's compatible with the screen
        BufferedImage bimage = null;
        GraphicsEnvironment ge = GraphicsEnvironment.getLocalGraphicsEnvironment();
        try {
            // Determine the type of transparency of the new buffered image
            int transparency = Transparency.OPAQUE;
            if (hasAlpha) {
                transparency = Transparency.BITMASK;
            }

            // Create the buffered image
            GraphicsDevice gs = ge.getDefaultScreenDevice();
            GraphicsConfiguration gc = gs.getDefaultConfiguration();
            bimage = gc.createCompatibleImage(
                    image.getWidth(null), image.getHeight(null), transparency);
        } catch (HeadlessException e) {
            // The system does not have a screen
        }

        if (bimage == null) {
            // Create a buffered image using the default color model
            int type = BufferedImage.TYPE_INT_RGB;
            if (hasAlpha) {
                type = BufferedImage.TYPE_INT_ARGB;
            }
            bimage = new BufferedImage(image.getWidth(null), image.getHeight(null), type);
        }

        // Copy image to buffered image
        Graphics g = bimage.createGraphics();

        // Paint the image onto the buffered image
        g.drawImage(image, 0, 0, null);
        g.dispose();

        return bimage;
    }

    private LocatedImage compressedScrape2LocatedImage(CompressedScreenScrapeRect compRect) {

        Image imgRet = null;
        if (compRect instanceof ScreenScrapeJPEGData) {
            imgRet = Toolkit.getDefaultToolkit().createImage(
                    compRect.getBytes());
        } else {
            int[] ia = new int[compRect.width * compRect.height];
            decompressAppScrape(compRect, ia);
            MemoryImageSource mis = new MemoryImageSource(
                    compRect.width,
                    compRect.height,
                    ia,
                    0,
                    compRect.width);
            imgRet = Toolkit.getDefaultToolkit().createImage(mis);
        }
        return new LocatedImage(imgRet, new Rectangle(compRect));
    }

    private void decompressAppScrape(
            CompressedScreenScrapeRect compRect, int[] iaDest) {
        byte[] ba = PixelUtil.decompressPixels(compRect.getBytes());
        PixelUtil.upscalePixels(ba, iaDest);
    }

    private class LocatedImage extends Rectangle {

        private Image image;

        public LocatedImage(Image image, Rectangle rect) {

            super(rect);
            this.image = image;
        }

        public Image getImage() {
            return this.image;
        }
    }
}
