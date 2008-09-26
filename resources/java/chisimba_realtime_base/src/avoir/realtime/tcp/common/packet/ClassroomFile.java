/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.packet;

/**
 *
 * @author developer
 */
public class ClassroomFile implements RealtimePacket {

    private String path;
    boolean image;
    boolean presentation;
    private String sessionId;
    private String id;

    public ClassroomFile(String path, boolean image, boolean presentation, String sessionId,String id) {
        this.path = path;
        this.image = image;
        this.presentation = presentation;
        this.sessionId = sessionId;
        this.id=id;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public boolean isImage() {
        return image;
    }

    public void setImage(boolean image) {
        this.image = image;
    }

    public String getPath() {
        return path;
    }

    public void setPath(String path) {
        this.path = path;
    }

    public boolean isPresentation() {
        return presentation;
    }

    public void setPresentation(boolean presentation) {
        this.presentation = presentation;
    }
}
