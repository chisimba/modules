/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.classroom;

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

    public SessionTimer(ClassroomMainFrame mf) {
        super();
        this.mf = mf;
    }

    public boolean isPaused() {
        return paused;
    }

    public void setPaused(boolean paused) {
        this.paused = paused;
    }

    public void run() {
        if (!paused) {
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


