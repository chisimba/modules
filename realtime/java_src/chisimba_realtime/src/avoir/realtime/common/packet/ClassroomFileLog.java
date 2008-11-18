/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

import java.util.Vector;

/**
 *
 * @author developer
 */
public class ClassroomFileLog implements RealtimePacket {

    Vector<ClassroomFile> log;
    String sessionId;

    public ClassroomFileLog(Vector<ClassroomFile> log) {
        this.log = log;
    }

    public Vector<ClassroomFile> getLog() {
        return log;
    }

    public void setLog(Vector<ClassroomFile> log) {
        this.log = log;
    }

    public String getId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setId(String id) {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
