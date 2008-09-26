/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.packet;

/**
 *
 * @author developer
 */
public class FileUploadPacket implements RealtimePacket {

    private String sessionId;
    private String id;
    private byte[] buff;
    private int chunkNo;
    private int totalChunks;
    private String filename;
    private String clientId;
    private String recepient;
    boolean serverUpload;
    private String sender;
    private int recepientIndex;
    private int fileType;
    private int index;

    public FileUploadPacket(
            String sessionId,
            String id,
            byte[] buff,
            int chunkNo,
            int totalChunks,
            String filename,
            String clientId,
            String recepient,
            boolean serverUpload,
            String sender,
            int recepientIndex,
            int fileType) {
        this.sessionId = sessionId;
        this.id = id;
        this.buff = buff;
        this.chunkNo = chunkNo;
        this.totalChunks = totalChunks;
        this.filename = filename;
        this.clientId = clientId;
        this.recepient = recepient;
        this.serverUpload = serverUpload;
        this.sender = sender;
        this.recepientIndex = recepientIndex;
        this.fileType = fileType;
        
    }


    public void setFileType(int fileType) {
        this.fileType = fileType;
    }

    public int getIndex() {
        return index;
    }

    public void setIndex(int index) {
        this.index = index;
    }

    public int getFileType() {
        return fileType;
    }

    public int getRecepientIndex() {
        return recepientIndex;
    }

    public void setRecepientIndex(int recepientIndex) {
        this.recepientIndex = recepientIndex;
    }

    public String getSender() {
        return sender;
    }

    public void setSender(String sender) {
        this.sender = sender;
    }

    public boolean isServerUpload() {
        return serverUpload;
    }

    public void setServerUpload(boolean serverUpload) {
        this.serverUpload = serverUpload;
    }

    public String getRecepient() {
        return recepient;
    }

    public void setRecepient(String recepient) {
        this.recepient = recepient;
    }

    public String getClientId() {
        return clientId;
    }

    public void setClientId(String clientId) {
        this.clientId = clientId;
    }

    public String getFilename() {
        return filename;
    }

    public void setFilename(String filename) {
        this.filename = filename;
    }

    public byte[] getBuff() {
        return buff;
    }

    public void setBuff(byte[] buff) {
        this.buff = buff;
    }

    public int getChunkNo() {
        return chunkNo;
    }

    public void setChunkNo(int chunkNo) {
        this.chunkNo = chunkNo;
    }

    public int getTotalChunks() {
        return totalChunks;
    }

    public void setTotalChunks(int totalChunks) {
        this.totalChunks = totalChunks;
    }

    public String getId() {
        return id;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setId(String id) {
        this.id = id;
    }
}
