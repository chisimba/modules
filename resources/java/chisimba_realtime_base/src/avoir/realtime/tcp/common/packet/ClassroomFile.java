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
    private int type;
    private String sessionId;
    private String id;
    private boolean newFile;
    private String oldId;

    public ClassroomFile(String path, int type, String sessionId, String id, boolean newFile, String oldId) {
        this.path = path;
        this.sessionId = sessionId;
        this.id = id;
        this.type = type;
        this.newFile = newFile;
        this.oldId = oldId;
    }

    public String getOldId() {
        return oldId;
    }

    public void setOldId(String oldId) {
        this.oldId = oldId;
    }

    public boolean isNewFile() {
        return newFile;
    }

    public int getType() {
        return type;
    }

    public void setType(int type) {
        this.type = type;
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

    public String getPath() {
        return path;
    }

    public void setPath(String path) {
        this.path = path;
    }
}
