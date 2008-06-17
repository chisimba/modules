/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package avoir.realtime.tcp.base;

import java.io.FileNotFoundException;
import java.net.URL;
import javax.swing.ImageIcon;

/**
 *
 * @author developer
 */
public class ImageUtil {
    /**
     * Creates an ImageIcon, retrieving the Image from the system classpath.
     *
     * @param path String location of the image file
     * @return Returns and ImageIcon object with the supplied image
     * @throws FileNotFoundException File can't be found
     */
    public static ImageIcon createImageIcon(String path) {
        try {
            URL imageURL = ClassLoader.getSystemResource(path);
            if (imageURL != null) {
                return new ImageIcon(imageURL);
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return null;
    }

    /**
     * Creates an ImageIcon, retrieving the image from the classes' classpath or 
     * the system classpath (searched in that order).
     *
     * @param classToLoadFrom Class to use to search classpath for image.
     * @param path String location of the image file
     * @return Returns and ImageIcon object with the supplied image
     * @throws FileNotFoundException File can't be found
     */
    public static ImageIcon createImageIcon(Object classToLoadFrom, String path) {
        try {
            URL imageURL = classToLoadFrom.getClass().getResource(path);
            if (imageURL == null) {
                imageURL = classToLoadFrom.getClass().getClassLoader().getResource(
                        path);
            }
            if (imageURL == null) {
                return createImageIcon(path);
            } else {
                return new ImageIcon(imageURL);
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return null;
    }

}
