/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common.packet;



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
    private String presentationName;
    private boolean webPresent;

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

    public String getPresentationName() {
        return presentationName;
    }

    public void setPresentationName(String presentationName) {
        this.presentationName = presentationName;
    }

    public NewSlideReplyPacket(int slideIndex, String sessionId,
            boolean isPresenter, String userId, String presentationName, boolean webPresent) {
        this.presentationName = presentationName;
        this.slideIndex = slideIndex;
        this.sessionId = sessionId;
        this.isPresenter = isPresenter;
        this.userId = userId;
        this.webPresent = webPresent;
    }

    public boolean isWebPresent() {
        return webPresent;
    }

    public void setWebPresent(boolean webPresent) {
        this.webPresent = webPresent;
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
