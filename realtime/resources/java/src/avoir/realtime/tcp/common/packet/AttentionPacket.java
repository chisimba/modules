/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.packet;

/**
 *
 * @author developer
 */
public class AttentionPacket implements RealtimePacket {

    private String userName,  sessionId,  id;
    private boolean handRaised;
    private boolean allowControl;
    private int order;
    private boolean yes;
    private boolean no;
    private boolean yesNoSession;
    private boolean isPrivate;

    public AttentionPacket(String userName, String sessionId, boolean handRaised,
            boolean allowControl, boolean yes, boolean no, boolean yesNoSession,
            boolean isPrivate) {
        this.userName = userName;
        this.sessionId = sessionId;
        this.handRaised = handRaised;
        this.allowControl = allowControl;
        this.yes = yes;
        this.no = no;
        this.yesNoSession = yesNoSession;
        this.isPrivate = isPrivate;
    }

    public boolean isPrivate() {
        return isPrivate;
    }

    public void setPrivate(boolean isPrivate) {
        this.isPrivate = isPrivate;
    }

    public boolean isYesNoSession() {
        return yesNoSession;
    }

    public void setYesNoSession(boolean yesNoSession) {
        this.yesNoSession = yesNoSession;
    }

    public boolean isNo() {
        return no;
    }

    public void setNo(boolean no) {
        this.no = no;
    }

    public boolean isYes() {
        return yes;
    }

    public void setYes(boolean yes) {
        this.yes = yes;
    }

    public int getOrder() {
        return order;
    }

    public void setOrder(int order) {
        this.order = order;
    }

    public boolean isAllowControl() {
        return allowControl;
    }

    public void setAllowControl(boolean allowControl) {
        this.allowControl = allowControl;
    }

    public boolean isHandRaised() {
        return handRaised;
    }

    public void setHandRaised(boolean handRaised) {
        this.handRaised = handRaised;
    }

    public String getId() {
        return this.id;
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

    public String getUserName() {
        return userName;
    }

    public void setUserName(String userName) {
        this.userName = userName;
    }
}
