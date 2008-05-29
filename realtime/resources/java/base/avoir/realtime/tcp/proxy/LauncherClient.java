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
public class LauncherClient {

    private String id;
    private ObjectOutputStream objectOutputStream;

    public String getId() {
        return id;
    }

    public LauncherClient(String id, ObjectOutputStream objectOutputStream) {
        this.id = id;
        this.objectOutputStream = objectOutputStream;
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
    public String toString(){
        return id;
    }
}
