/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.packet;

/**
 *
 * @author developer
 */
public class BinaryFileSaveRequestPacket implements RealtimePacket {

    private String sessionId;
    private String fileName;
    private String senderName;
    private String userName;
    private boolean lastChunk;

    public BinaryFileSaveRequestPacket(String sessionId, String fileName, String senderName, String userName, boolean lastChunk) {
        this.sessionId = sessionId;
        this.fileName = fileName;
        this.senderName = senderName;
        this.userName = userName;
        this.lastChunk = lastChunk;
    }

    public String getUserName() {
        return userName;
    }

    public void setUserName(String userName) {
        this.userName = userName;
    }

    public boolean isLastChunk() {
        return lastChunk;
    }

    public void setLastChunk(boolean lastChunk) {
        this.lastChunk = lastChunk;
    }

    public String getFileName() {
        return fileName;
    }

    public void setFileName(String fileName) {
        this.fileName = fileName;
    }

    public String getSenderName() {
        return senderName;
    }

    public void setSenderName(String senderName) {
        this.senderName = senderName;
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
