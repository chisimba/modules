/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

/**
 *
 * @author developer
 */
public class ClassroomSlidePacket implements RealtimePacket {

    private String sessionId;
    private String username;
    private byte[] byteArray;
    private String filename;
    private boolean lastFile;
    private int maxValue;
    private int currentValue;
   private String presentationName;

    public void setPresentationName(String presentationName) {
        this.presentationName = presentationName;
    }
   
    public int getCurrentValue() {
        return currentValue;
    }

    public void setCurrentValue(int currentValue) {
        this.currentValue = currentValue;
    }

    public int getMaxValue() {
        return maxValue;
    }

    public void setMaxValue(int maxValue) {
        this.maxValue = maxValue;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public ClassroomSlidePacket(String sessionId, String username, byte[] byteArray, String filename, boolean lastFile, int maxValue, int currentValue, String presentationName) {
        this.sessionId = sessionId;
        this.username = username;
        this.byteArray = byteArray;
        this.filename = filename;
        this.lastFile = lastFile;
        this.maxValue = maxValue;
        this.currentValue = currentValue;
        this.presentationName = presentationName;
    }

    public String getPresentationName() {
        return presentationName;
    }

  

    public boolean isLastFile() {
        return lastFile;
    }

    public void setLastFile(boolean lastFile) {
        this.lastFile = lastFile;
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
