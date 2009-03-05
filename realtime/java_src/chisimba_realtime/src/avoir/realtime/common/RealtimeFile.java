/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common;

import java.io.Serializable;

/**
 *
 * @author developer
 */
public class RealtimeFile implements Serializable {

    private boolean directory;
    private String fileName;
    private String path;

    public RealtimeFile(boolean directory, String fileName, String path) {
        this.directory = directory;
        this.fileName = fileName;
        this.path = path;
    }

    public String getPath() {
        return path;
    }

    public void setPath(String path) {
        this.path = path;
    }

    public String getFileName() {
        return fileName;
    }

    public void setFileName(String fileName) {
        this.fileName = fileName;
    }

    public boolean isDirectory() {
        return directory;
    }

    public void setDirectory(boolean directory) {
        this.directory = directory;
    }

    @Override
    public String toString() {
        return fileName;
    }
}
