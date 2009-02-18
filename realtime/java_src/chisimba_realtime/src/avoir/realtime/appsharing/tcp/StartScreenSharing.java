/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.appsharing.tcp;

import avoir.realtime.common.packet.*;

/**
 *
 * @author developer
 */
public class StartScreenSharing implements RealtimePacket {

    private boolean record;

    public StartScreenSharing(boolean record) {
        this.record = record;
    }

    public boolean isRecord() {
        return record;
    }

    public void setRecord(boolean record) {
        this.record = record;
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
