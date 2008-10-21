/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.launcher.packet;

/**
 *
 * @author developer
 */
public class VersionRequestPacket implements LauncherPacket {

    private String id;
    private String sessionId;
    private String[][] corePlugins;

    public VersionRequestPacket(String[][] corePlugins) {
        this.corePlugins = corePlugins;
    }

    public String getId() {
        return id;
    }

    public String[][] getCorePlugins() {
        return corePlugins;
    }

    public void setCorePlugins(String[][] corePlugins) {
        this.corePlugins = corePlugins;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setId(String id) {
        this.id = id;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }
}
