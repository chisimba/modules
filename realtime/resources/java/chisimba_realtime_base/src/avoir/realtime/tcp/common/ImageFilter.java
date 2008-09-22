package avoir.realtime.tcp.common;

import java.io.File;
import javax.swing.*;
import javax.swing.filechooser.*;

/* PresentationFilter.java is used by FileChooserDemo2.java. */
public class ImageFilter extends FileFilter {

    //Accept all directories and all gif, jpg, tiff, or png files.
    public boolean accept(File f) {
        if (f.isDirectory()) {
            return true;
        }

        String extension = GraphicsFileUtils.getExtension(f);
        if (extension != null) {
            if (extension.equals(GraphicsFileUtils.jpeg) ||
                    extension.equals(GraphicsFileUtils.jpg) ||
                    extension.equals(GraphicsFileUtils.png) ||
                    extension.equals(GraphicsFileUtils.tiff) ||
                    extension.equals(GraphicsFileUtils.tif)) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    //The description of this filter
    public String getDescription() {
        return "Images";
    }
}
