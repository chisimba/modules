package avoir.realtime.common;

import java.io.File;
import javax.swing.filechooser.*;

/* ImageFilter.java is used by FileChooserDemo2.java. */
public class PresentationFilter extends FileFilter {

    //Accept all directories and all gif, jpg, tiff, or png files.
    public boolean accept(File f) {
        if (f.isDirectory()) {
            return true;
        }

        String extension = PresentationFileUtils.getExtension(f);
        if (extension != null) {
            if (extension.equals(PresentationFileUtils.odp) ||
                extension.equals(PresentationFileUtils.sxi) ||
                extension.equals(PresentationFileUtils.ppt)) {
                    return true;
            } else {
                return false;
            }
        }

        return false;
    }

    //The description of this filter
    public String getDescription() {
        return "Supported Presentations";
    }
}
