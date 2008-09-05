package avoir.realtime.tcp.common.packet;

import avoir.realtime.tcp.common.packet.RealtimePacket;

public class NewSlideRequestPacket implements RealtimePacket {

    private String slideDirHost;
    private int slideIndex;
    private String id;
    private boolean isPresenter;
    private boolean control;
    private String sessionId;
    private String userId;
    private String slideServerId;
    private String presentationName;

    public boolean isIsPresenter() {
        return isPresenter;
    }

    public void setIsPresenter(boolean isPresenter) {
        this.isPresenter = isPresenter;
    }

    public String getPresentationName() {
        return presentationName;
    }

    public void setPresentationName(String presentationName) {
        this.presentationName = presentationName;
    }

    public String getSlideServerId() {
        return slideServerId;
    }

    public void setSlideServerId(String slideServerId) {
        this.slideServerId = slideServerId;
    }

    public boolean isControl() {
        return control;
    }

    public void setControl(boolean control) {
        this.control = control;
    }

    public boolean isPresenter() {
        return isPresenter;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getId() {
        return id;
    }

    public String getSessionId() {
        return sessionId;
    }

    public NewSlideRequestPacket(String slideDirHost, int slideIndex, boolean isPresenter, String sessionId, String userId,
            String presentationName) {
        this.slideIndex = slideIndex;
        this.slideDirHost = slideDirHost;
        this.isPresenter = isPresenter;
        this.sessionId = sessionId;
        this.userId = userId;
        this.presentationName = presentationName;
    }

    public String getUserId() {
        return userId;
    }

    public String getSlideDirHost() {
        return slideDirHost;
    }

    public int getSlideIndex() {
        return slideIndex;
    }
}
