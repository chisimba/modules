/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.packet;

import avoir.realtime.tcp.common.packet.RealtimePacket;

/**
 *
 * @author developer
 */
public class NewSlideReplyPacket implements RealtimePacket {

    private String id;
    private int slideIndex;
    private String sessionId;
    private boolean isPresenter;
    private boolean control;

    public boolean isControl() {
        return control;
    }

    public void setControl(boolean control) {
        this.control = control;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }
    private String userId;
    private String message;

    public NewSlideReplyPacket(int slideIndex, String sessionId, boolean isPresenter, String userId) {

        this.slideIndex = slideIndex;
        this.sessionId = sessionId;
        this.isPresenter = isPresenter;
        this.userId = userId;
    }

    public String getUserId() {
        return userId;
    }

    public boolean isIsPresenter() {
        return isPresenter;
    }

    public void setIsPresenter(boolean isPresenter) {
        this.isPresenter = isPresenter;
    }

    public String getSessionId() {
        return sessionId;
    }

    public int getSlideIndex() {
        return slideIndex;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getId() {
        return id;
    }
}
