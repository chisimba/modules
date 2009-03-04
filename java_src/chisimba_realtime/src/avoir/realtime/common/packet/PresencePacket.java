/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;

import avoir.realtime.common.packet.*;

/**
 *
 * @author developer
 */
public class PresencePacket implements RealtimePacket {

    private String sessionId;
    private int presenceType;
    private boolean showIcon;
    private String userName;
    private boolean forward=true;

    public PresencePacket(String sessionId, int presenceType, boolean showIcon, String userName) {
        this.sessionId = sessionId;
        this.presenceType = presenceType;
        this.showIcon = showIcon;
        this.userName = userName;
    }

    public boolean shouldForward() {
        return forward;
    }

    public void setForward(boolean forward) {
        this.forward = forward;
    }

    public String getUserName() {
        return userName;
    }

    public void setUserName(String userName) {
        this.userName = userName;
    }

    public boolean isShowIcon() {
        return showIcon;
    }

    public void setShowIcon(boolean showIcon) {
        this.showIcon = showIcon;
    }

    public int getPresenceType() {
        return presenceType;
    }

    public void setPresenceType(int presenceType) {
        this.presenceType = presenceType;
    }

    public String getId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setId(String id) {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
