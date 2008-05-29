/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.sessionmonitor;

/**
 *
 * @author developer
 */
public class SessionPresenter implements java.io.Serializable {

    private String sessionId;
    private int slideIndex;
    private String time;
    private String fullName;
    private String[] outLine;
    private boolean state;
    private boolean sessionHasPresenter;
    private String userId;
    private String sessionName;

    public SessionPresenter(String sessionId,String sessionName, int slideIndex, String time, String fullName, String[] outLine, boolean state, boolean sessionHasPresenter, String userId) {
        this.sessionId = sessionId;
        this.slideIndex = slideIndex;
        this.time = time;
        this.sessionName=sessionName;
        this.fullName = fullName;
        this.outLine = outLine;
        this.state = state;
        this.sessionHasPresenter = sessionHasPresenter;
        this.userId = userId;
    }

    public String getFullName() {
        return fullName;
    }

    public void setFullName(String fullName) {
        this.fullName = fullName;
    }

    public String[] getOutLine() {
        return outLine;
    }

    public void setOutLine(String[] outLine) {
        this.outLine = outLine;
    }

    public boolean isSessionHasPresenter() {
        return sessionHasPresenter;
    }

    public void setSessionHasPresenter(boolean sessionHasPresenter) {
        this.sessionHasPresenter = sessionHasPresenter;
    }

    public String getSessionName() {
        return sessionName;
    }

    public void setSessionName(String sessionName) {
        this.sessionName = sessionName;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public int getSlideIndex() {
        return slideIndex;
    }

    public void setSlideIndex(int slideIndex) {
        this.slideIndex = slideIndex;
    }

    public boolean isState() {
        return state;
    }

    public void setState(boolean state) {
        this.state = state;
    }

    public String getTime() {
        return time;
    }

    public void setTime(String time) {
        this.time = time;
    }

    public String getUserId() {
        return userId;
    }

    public void setUserId(String userId) {
        this.userId = userId;
    }
}
