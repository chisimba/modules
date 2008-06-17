/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.packet;

/**
 *
 * @author developer
 */
public class BinaryFileChunkPacket implements RealtimePacket {

    private String sessionId;
    private String filename;
    private byte[] chunk;
    private String recepientName;
    private boolean last;

    public BinaryFileChunkPacket(String sessionId, String filename, byte[] chunk, String recepientName, boolean last) {
        this.sessionId = sessionId;
        this.filename = filename;
        this.chunk = chunk;
        this.recepientName = recepientName;
        this.last = last;
    }

    public boolean isLast() {
        return last;
    }

    public void setLast(boolean last) {
        this.last = last;
    }

    public String getRecepientName() {
        return recepientName;
    }

    public void setRecepientName(String recepientName) {
        this.recepientName = recepientName;
    }

    public byte[] getChunk() {
        return chunk;
    }

    public void setChunk(byte[] chunk) {
        this.chunk = chunk;
    }

    public String getFilename() {
        return filename;
    }

    public void setFilename(String filename) {
        this.filename = filename;
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

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }
}
