/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

/**
 *
 * @author developer
 */
public class ImagePacket implements RealtimePacket {

    private String sessionId;
    private String username;
    private byte[] byteArray;
    private String filename;

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public ImagePacket(String sessionId, String username, byte[] byteArray, String filename) {
        this.sessionId = sessionId;
        this.username = username;
        this.byteArray = byteArray;
        this.filename = filename;
    }

    public String getFilename() {
        return filename;
    }

    public void setFilename(String filename) {
        this.filename = filename;
    }

    public byte[] getByteArray() {
        return byteArray;
    }

    public void setByteArray(byte[] byteArray) {
        this.byteArray = byteArray;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
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
