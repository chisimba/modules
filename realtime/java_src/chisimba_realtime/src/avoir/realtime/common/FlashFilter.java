package avoir.realtime.common;

import java.io.File;
import javax.swing.filechooser.*;

/* PresentationFilter.java is used by FileChooserDemo2.java. */
public class FlashFilter extends FileFilter {

    //Accept all directories and all gif, jpg, tiff, or png files.
    public boolean accept(File f) {
        if (f.isDirectory()) {
            return true;
        }

        String extension = GraphicsFileUtils.getExtension(f);
        if (extension != null) {
            if (extension.equals(FlashUtils.swf)) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    //The description of this filter
    public String getDescription() {
        return "Flash Files";
    }
}
