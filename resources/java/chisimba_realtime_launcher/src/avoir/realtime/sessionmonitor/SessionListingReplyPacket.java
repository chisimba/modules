/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.sessionmonitor;

import java.util.Vector;

/**
 *
 * @author developer
 */
public class SessionListingReplyPacket implements java.io.Serializable {

    private Vector<SessionParticipant> participants;
    private Vector<SessionPresenter> presenters;

    public SessionListingReplyPacket(Vector<SessionParticipant> participants, Vector<SessionPresenter> presenters) {
        this.participants = participants;
        this.presenters = presenters;
    }

    public Vector<SessionParticipant> getParticipants() {
        return participants;
    }

    public void setParticipants(Vector<SessionParticipant> participants) {
        this.participants = participants;
    }

    public Vector<SessionPresenter> getPresenters() {
        return presenters;
    }

    public void setPresenters(Vector<SessionPresenter> presenters) {
        this.presenters = presenters;
    }
}
