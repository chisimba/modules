/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

/**
 *
 * @author developer
 */
public class BinaryFileSaveReplyPacket implements RealtimePacket {

    private String sessionId;
    private String fileName;
    private String fullName;
    private String senderFullName;
    private boolean accepted;
    private String senderUsername;

    public BinaryFileSaveReplyPacket(String sessionId, String fileName, String fullName, String senderFullName, boolean accepted, String senderUsername) {
        this.sessionId = sessionId;
        this.fileName = fileName;
         this.fullName = fullName;
        this.senderFullName = senderFullName;
        this.accepted = accepted;
        this.senderUsername = senderUsername;
    }

    public String getFullName() {
        return fullName;
    }

    public void setFullName(String fullName) {
        this.fullName = fullName;
    }

    public String getSenderFullName() {
        return senderFullName;
    }

    public void setSenderFullName(String senderFullName) {
        this.senderFullName = senderFullName;
    }

    public String getSenderUsername() {
        return senderUsername;
    }

    public void setSenderUsername(String senderUsername) {
        this.senderUsername = senderUsername;
    }

    public String getFileName() {
        return fileName;
    }

    public void setFileName(String fileName) {
        this.fileName = fileName;
    }

    public boolean isAccepted() {
        return accepted;
    }

    public void setAccepted(boolean accepted) {
        this.accepted = accepted;
    }

    public String getfullName() {
        return fullName;
    }

    public void setfullName(String fullName) {
        this.fullName = fullName;
    }

    public String getId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public String getSessionId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public void setId(String id) {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
