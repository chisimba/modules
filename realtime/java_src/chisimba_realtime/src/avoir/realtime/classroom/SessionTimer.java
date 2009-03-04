/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.classroom;

import avoir.realtime.common.packet.PresencePacket;
import avoir.realtime.common.PresenceConstants;
import java.util.TimerTask;

/**
 *
 * @author developer
 */
public class SessionTimer extends TimerTask {

    private boolean paused = false;
    int secs = 0;
    int mins = 0;
    int hrs = 0;
    private ClassroomMainFrame mf;
    private int idleTime = 0;
    private int ORANGE_IDLE_TIME =  3 * 60;
    private int RED_IDLE_TIME =  5 * 60;
    private boolean sendRed = false;
    private boolean sendOrange = false;

    public SessionTimer(ClassroomMainFrame mf) {
        super();
        this.mf = mf;
    }

    public int getIdleTime() {
        return idleTime;
    }

    public void setIdleTime(int idleTime) {
        this.idleTime = 0;// idleTime;

        sendOrange = false;
        sendOrange = false;
    }

    public boolean isPaused() {
        return paused;
    }

    public void setPaused(boolean paused) {
        this.paused = paused;
    }

    public void run() {
        if (!paused) {
            if (idleTime > ORANGE_IDLE_TIME && idleTime < RED_IDLE_TIME && !sendOrange) {
                mf.getTcpConnector().sendPacket(new PresencePacket(mf.getUser().getSessionId(), PresenceConstants.USER_IDLE_ICON, PresenceConstants.USER_ORANGE_IDLE, mf.getUser().getUserName()));
                sendOrange = true;
            }
            if (idleTime > RED_IDLE_TIME && !sendRed) {
                mf.getTcpConnector().sendPacket(new PresencePacket(mf.getUser().getSessionId(), PresenceConstants.USER_IDLE_ICON, PresenceConstants.USER_RED_IDLE, mf.getUser().getUserName()));
                sendRed = true;
            }

            idleTime++;
            if (secs == 60) {
                mins++;
                secs = 0;
            }
            if (mins == 60) {
                hrs++;
                mins = 0;
            }

            String secsVal = "" + secs;
            String minsVal = "" + mins;
            String hrsVal = "" + hrs;
            if (secs < 10) {
                secsVal = "0" + secs;
            }
            if (mins < 10) {
                minsVal = "0" + mins;

            }
            if (hrs < 10) {
                hrsVal = "0" + hrs;
            }
            mf.getTimerField().setText("Session Duration: " + hrsVal + ":" + minsVal + ":" + secsVal);
            secs++;
        }
    }
}


