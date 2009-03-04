/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

import avoir.realtime.common.RealtimeFile;
import java.util.ArrayList;

/**
 *
 * @author developer
 */
public class FileViewReplyPacket implements RealtimePacket {

    private ArrayList<RealtimeFile> list;
    private String sessionId;

    public FileViewReplyPacket(ArrayList<RealtimeFile> list) {
        this.list = list;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public ArrayList<RealtimeFile> getList() {
        return list;
    }

    public void setList(ArrayList<RealtimeFile> list) {
        this.list = list;
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
