/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.proxy;

import java.io.ObjectOutputStream;

/**
 *
 * @author developer
 */
public class SessionMonitor {

    private ObjectOutputStream stream;
    private String monitorId;

    public SessionMonitor(ObjectOutputStream stream, String monitorId) {
        this.stream = stream;
        this.monitorId = monitorId;
    }

    public String getMonitorId() {
        return monitorId;
    }

    public void setMonitorId(String monitorId) {
        this.monitorId = monitorId;
    }

    public ObjectOutputStream getStream() {
        return stream;
    }

    public void setStream(ObjectOutputStream stream) {
        this.stream = stream;
    }
}
