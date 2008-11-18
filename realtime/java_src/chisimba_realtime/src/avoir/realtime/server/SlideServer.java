/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.server;

import java.io.ObjectOutputStream;
import java.net.Socket;

/**
 *
 * @author developer
 */
public class SlideServer {

    private String id;
    private ObjectOutputStream objectOutputStream;
    private Socket socket;

    public String getId() {
        return id;
    }

    public SlideServer(String id, ObjectOutputStream objectOutputStream, Socket socket) {
        this.id = id;
        this.objectOutputStream = objectOutputStream;
        this.socket = socket;
    }

    public Socket getSocket() {
        return socket;
    }

    public void setSocket(Socket socket) {
        this.socket = socket;
    }

    public void setId(String id) {
        this.id = id;
    }

    public ObjectOutputStream getObjectOutputStream() {
        return objectOutputStream;
    }

    public void setObjectOutputStream(ObjectOutputStream objectOutputStream) {
        this.objectOutputStream = objectOutputStream;
    }

    public String toString() {
        return id;
    }
}
