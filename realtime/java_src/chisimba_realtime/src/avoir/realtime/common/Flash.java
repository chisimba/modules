/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package avoir.realtime.common;

/**
 *
 * @author developer
 */
public class Flash {
private String filename;
private String id;
private String sessionId;

    public Flash(String filename, String id, String sessionId) {
        this.filename = filename;
        this.id = id;
        this.sessionId = sessionId;
    }

    public String getFilename() {
        return filename;
    }

    public void setFilename(String filename) {
        this.filename = filename;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

}
