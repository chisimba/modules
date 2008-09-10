package avoir.realtime.tcp.common;

import java.io.File;
import javax.swing.filechooser.*;


public class PresentationFileView extends FileView {

    public String getName(File f) {
        return null; //let the L&F FileView figure this out
    }

    public String getDescription(File f) {
        return null; //let the L&F FileView figure this out
    }

    public Boolean isTraversable(File f) {
        return null; //let the L&F FileView figure this out
    }

    public String getTypeDescription(File f) {
        String extension = PresentationFileUtils.getExtension(f);
        String type = null;

        if (extension != null) {
            if (extension.equals(PresentationFileUtils.odp)) {
                type = "OpenDocument Presentation";
            } else if (extension.equals(PresentationFileUtils.sxi)) {
                type = "OpenOffice.org 1.0 Presentation";
            } else if (extension.equals(PresentationFileUtils.ppt)) {
                type = "Microsoft PowerPoint";
            }
        }
        return type;
    }
}
