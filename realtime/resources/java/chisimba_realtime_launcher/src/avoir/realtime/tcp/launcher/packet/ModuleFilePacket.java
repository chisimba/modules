/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.launcher.packet;

/**
 *
 * @author developer
 */
public class ModuleFilePacket implements LauncherPacket {

    private String sessionId;
    private String id;
    private byte[] buff;
    private int chunkNo;
    private int totalChunks;
    private String filename;
    private String clientId;
    private String desc;

    public ModuleFilePacket(
            String sessionId,
            String id,
            byte[] buff,
            int chunkNo,
            int totalChunks,
            String filename,
            String clientId,
            String desc) {
        this.sessionId = sessionId;
        this.id = id;
        this.buff = buff;
        this.chunkNo = chunkNo;
        this.totalChunks = totalChunks;
        this.filename = filename;
        this.clientId = clientId;
        this.desc = desc;

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

    public String getDesc() {
        return desc;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setId(String id) {
        this.id = id;
    }
}
