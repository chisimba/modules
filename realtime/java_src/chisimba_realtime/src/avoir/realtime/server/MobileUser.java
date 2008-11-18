/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.server;

import java.io.OutputStream;

/**
 *
 * @author developer
 */
public class MobileUser {

    private String username;
    private OutputStream out;

    public MobileUser(String username, OutputStream out) {
        this.username = username;
        this.out = out;
    }

    public OutputStream getOut() {
        return out;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String toString() {
        return username;
    }
}
