/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.proxy;

import java.net.Socket;

/**
 *
 * @author developer
 */
public class Session {

    private String sessionId;
    private int slideIndex;
    private String time;
    private String fullName;
    private String[] outLine;
    private boolean state;
    private boolean sessionHasPresenter;
    private String userId;
    private String sessionName;
    private Socket socket;

    public Socket getSocket() {
        return socket;
    }

    public void setSocket(Socket socket) {
        this.socket = socket;
    }

    public boolean getState() {
        return state;
    }

    public String getSessionName() {
        return sessionName;
    }

    public void setSessionName(String sessionName) {
        this.sessionName = sessionName;
    }

    public void setState(boolean state) {
        this.state = state;
    }

    public String[] getOutLine() {
        return outLine;
    }

    public void setOutLine(String[] outLine) {
        this.outLine = outLine;
    }

    public String getFullName() {
        return fullName;
    }

    public void setFullName(String fullName) {
        this.fullName = fullName;
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

    public int getSlideIndex() {
        return slideIndex;
    }

    public void setSlideIndex(int slideIndex) {
        this.slideIndex = slideIndex;
    }

    public boolean isSessionHasPresenter() {
        return sessionHasPresenter;
    }

    public void setSessionHasPresenter(boolean sessionHasPresenter) {
        this.sessionHasPresenter = sessionHasPresenter;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    @Override
    public String toString() {
        return sessionId;
    }

    public Session() {
    }
}
