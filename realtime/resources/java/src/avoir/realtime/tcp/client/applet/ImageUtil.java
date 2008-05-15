/**
 * 	$Id: ImageUtil.java,v 1.1 2007/03/22 14:11:48 mohamed Exp $
 * 
 *  Copyright (C) GNU/GPL AVOIR 2007
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
package avoir.realtime.tcp.client.applet;

import java.io.FileNotFoundException;
import java.net.URL;
import java.util.logging.Logger;

import javax.swing.ImageIcon;

/**
 * Utility class that loads and displays the icons
 */
public class ImageUtil {

    private static Logger logger = Logger.getLogger(ImageUtil.class.getName());

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
            logger.finest("Could not find image at " + path + ", returning null");
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
