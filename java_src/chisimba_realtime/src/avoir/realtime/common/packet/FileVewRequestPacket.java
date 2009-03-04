/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

/**
 *
 * @author developer
 */
public class FileVewRequestPacket implements RealtimePacket {

    private String path;
    private String sessionId;

    public String getPath() {
        return path;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public void setPath(String path) {
        this.path = path;
    }

    public FileVewRequestPacket(String path) {
        this.path = path;
    }

    public String getId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setId(String id) {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
