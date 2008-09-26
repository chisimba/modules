/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.packet;

import avoir.realtime.tcp.whiteboard.item.Img;

/**
 *
 * @author developer
 */
public class SessionImgPacket implements RealtimePacket {

    private String sessionId;
    private String id;
    private byte[] buff;
    private int chunkNo;
    private int totalChunks;
    private String filename;
    private String clientId;
    private Img img;

    public SessionImgPacket(
            String sessionId,
            String id,
            byte[] buff,
            int chunkNo,
            int totalChunks,
            String filename,
            String clientId,
            Img img) {
        this.sessionId = sessionId;
        this.id = id;
        this.buff = buff;
        this.chunkNo = chunkNo;
        this.totalChunks = totalChunks;
        this.filename = filename;
        this.clientId = clientId;
        this.img = img;
    }

    public String getClientId() {
        return clientId;
    }

    public void setClientId(String clientId) {
        this.clientId = clientId;
    }

    public Img getImg() {
        return img;
    }

    public void setImg(Img img) {
        this.img = img;
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
