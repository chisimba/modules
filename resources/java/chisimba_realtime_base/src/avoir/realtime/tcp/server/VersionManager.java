/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.server;

import avoir.realtime.tcp.launcher.packet.VersionReplyPacket;
import java.util.HashMap;
import java.util.Map;

/**
 *
 * @author developer
 */
public class VersionManager {

    private String[][] corePlugins;
    private Map<String, Integer> map = new HashMap<String, Integer>();

    private ServerThread server;

    public VersionManager(String[][] corePlugins, ServerThread server) {
        this.corePlugins = corePlugins;
        this.server = server;
    }

    public void processVersioning() {
        for (int i = 0; i < corePlugins.length; i++) {
            int ver = Utils.getJarVersionNo("../lib/" + corePlugins[i][0]);

            map.put(corePlugins[i][0], new Integer(ver));
        }
        server.sendPacket(new VersionReplyPacket(map), server.getObjectOutStream());
    }
}
