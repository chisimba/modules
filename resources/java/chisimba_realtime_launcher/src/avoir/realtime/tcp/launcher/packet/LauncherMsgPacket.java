/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.launcher.packet;



/**
 *
 * @author developer
 */
public class LauncherMsgPacket implements LauncherPacket {

    private String msg;
    private boolean temp;
    private boolean isErrorMsg = false;
    private String id;

    public void setId(String id) {
        this.id = id;
    }

    public String getId() {
        return id;
    }

    public String getSessionId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public LauncherMsgPacket(String msg, boolean temp, boolean isErrorMsg) {
        this.msg = msg;
        this.temp = temp;
        this.isErrorMsg = isErrorMsg;
    }

    public boolean isErrorMsg() {
        return isErrorMsg;
    }

    /**
     * returns the status of this image as whether temporary or not
     * @return
     */
    public boolean isTemporary() {
        return temp;
    }

    public String getMessage() {
        return this.msg;
    }
}
