/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.server;

import java.io.ObjectOutputStream;

/**
 *
 * @author developer
 */
public class ClientRelay {

    private ObjectOutputStream os;
    private String id;

    public ClientRelay(ObjectOutputStream os, String id) {
        this.os = os;
        this.id = id;
    }

    public ObjectOutputStream getOutputStream() {
        return os;
    }

    public String getId() {
        return id;
    }
}
