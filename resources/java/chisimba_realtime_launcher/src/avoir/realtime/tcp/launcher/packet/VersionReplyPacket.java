/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.launcher.packet;

import java.util.Map;

/**
 *
 * @author developer
 */
public class VersionReplyPacket implements LauncherPacket {

    private Map entries;
    private String id;
    private String sessionId;

    public VersionReplyPacket(Map entries) {
        this.entries = entries;
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

    public Map getEntries() {
        return entries;
    }

    public void setEntries(Map entries) {
        this.entries = entries;
    }
}
