/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.client.applet;

import avoir.realtime.tcp.common.packet.ModuleFileRequestPacket;

/**
 *
 * @author developer
 */
public class LibManager {

    private static void dowloadLib(TCPClient client, String filename, String filepath,
            String slideServerId, String username) {
        ModuleFileRequestPacket p =
                new avoir.realtime.tcp.common.packet.ModuleFileRequestPacket(null,
                filename, filepath, slideServerId, username);

        client.sendPacket(p);
    }

    public static void detectJSpeex(TCPClient client, String resourcesPath,
            String slideServerId, String username) {
        try {
            Class cl = Class.forName("org.xiph.speex.SpeexEncoder");
//   cl = Class.forName("org.xiph.speex.SpeexDecoder");
        } catch (Exception ex) {
            String filepath = resourcesPath + "/jspeex.jar";
            dowloadLib(client, "jspeex.jar", filepath, slideServerId, username);
            System.out.println("Send request for jspeex.jar");
        }
    }
}
