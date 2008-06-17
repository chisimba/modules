/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.common.packet;


/**
 *
 * @author developer
 */
public class VotePacket implements RealtimePacket {

    private String id;
    private String sessionId;
    private boolean vote;
    private boolean clear;
    private boolean isVisibleToAll;
    boolean speakerEnabled, micEnabled;

    public VotePacket(String sessionId, boolean vote, boolean clear, boolean visibleToAll, boolean speakerEnabled, boolean micEnabled) {
        this.sessionId = sessionId;
        this.vote = vote;
        this.clear = clear;
        this.isVisibleToAll = visibleToAll;
        this.speakerEnabled = speakerEnabled;
        this.micEnabled = micEnabled;
    }

    public boolean isMicEnabled() {
        return micEnabled;
    }

    public void setMicEnabled(boolean micEnabled) {
        this.micEnabled = micEnabled;
    }

    public boolean isSpeakerEnabled() {
        return speakerEnabled;
    }

    public void setSpeakerEnabled(boolean speakerEnabled) {
        this.speakerEnabled = speakerEnabled;
    }

    public boolean isVisibleToAll() {
        return isVisibleToAll;
    }

    public void setIsVisibleToAll(boolean isVisibleToAll) {
        this.isVisibleToAll = isVisibleToAll;
    }

    public boolean isClear() {
        return clear;
    }

    public void setClear(boolean clear) {
        this.clear = clear;
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

    public boolean isVote() {
        return vote;
    }

    public void setVote(boolean vote) {
        this.vote = vote;
    }
}
