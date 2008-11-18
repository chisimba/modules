/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.appsharing;

import java.awt.Rectangle;
import java.awt.Robot;
import java.awt.Toolkit;
import java.awt.image.BufferedImage;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.util.zip.DataFormatException;
import java.util.zip.Deflater;
import java.util.zip.Inflater;
import javax.imageio.ImageIO;

/**
 *
 * @author developer
 */
public class DesktopUtil {

    private Robot robot;
    private Rectangle screenSize;
    private String login;
    private String password;
    private ByteArrayOutputStream baos;

    public DesktopUtil() {
        
        try {
            robot = new Robot();
            screenSize = new Rectangle(0,0,100,100);//Toolkit.getDefaultToolkit().getScreenSize());
        } catch (Exception e) {
            e.printStackTrace();
            
        }
    }

    private byte[] doCompression(byte[] input) {
        // Create the compressor with highest level of compression
        Deflater compressor = new Deflater();
        compressor.setLevel(Deflater.BEST_SPEED);

        // Give the compressor the data to compress
        compressor.setInput(input);
        compressor.finish();

        // Create an expandable byte array to hold the compressed data.
        // You cannot use an array that's the same size as the orginal because
        // there is no guarantee that the compressed data will be smaller than
        // the uncompressed data.
        ByteArrayOutputStream bos = new ByteArrayOutputStream(input.length);

        // Compress the data
        byte[] buf = new byte[1024];
        while (!compressor.finished()) {
            int count = compressor.deflate(buf);
            bos.write(buf, 0, count);
        }
        try {
            bos.close();
        } catch (IOException e) {
        }

        // Get the compressed data
        return bos.toByteArray();
    }

    /**
     * 
     * @throws java.rmi.RemoteException 
     * @return 
     */
    public byte[] getScreenShot() {
        try {
            baos = new ByteArrayOutputStream();
            BufferedImage img = robot.createScreenCapture(screenSize);
            ImageIO.write(img, "jpeg", baos);

            return this.doCompression(baos.toByteArray());
        } catch (Exception e) {
            e.printStackTrace();

        }

        return new byte[0];
    }

    public static byte[] undoCompression(byte[] compressedData) {
        // Create the decompressor and give it the data to compress
        Inflater decompressor = new Inflater();
        decompressor.setInput(compressedData);

        // Create an expandable byte array to hold the decompressed data
        ByteArrayOutputStream bos = new ByteArrayOutputStream(compressedData.length);

        // Decompress the data
        byte[] buf = new byte[1024];
        while (!decompressor.finished()) {
            try {
                int count = decompressor.inflate(buf);
                bos.write(buf, 0, count);
            } catch (DataFormatException e) {
            }
        }
        try {
            bos.close();
        } catch (IOException e) {
        }

        // Get the decompressed data
        return bos.toByteArray();
    }
}
