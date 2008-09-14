/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.packet;

/**
 *
 * @author developer
 */
public class UploadMsgPacket implements RealtimePacket {

    private String id;
    private String sessionId;
    private int chunkNo;
    private String message;
    private String recepient;
    private int recepientIndex;

    public UploadMsgPacket(String sessionId, String message, String username, int recepientIndex) {
        this.sessionId = sessionId;
        this.message = message;
        this.recepient = username;
        this.recepientIndex = recepientIndex;
    }

    public int getRecepientIndex() {
        return recepientIndex;
    }

    public void setRecepientIndex(int recepientIndex) {
        this.recepientIndex = recepientIndex;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public String getRecepient() {
        return recepient;
    }

    public void setRecepient(String recepient) {
        this.recepient = recepient;
    }

    public String getId() {
        return id;
    }

    public String getSessionId() {
        return sessionId;
    }

    public int getChunkNo() {
        return chunkNo;
    }

    public void setChunkNo(int chunkNo) {
        this.chunkNo = chunkNo;
    }

    public void setId(String id) {
        this.id = id;
    }
}
