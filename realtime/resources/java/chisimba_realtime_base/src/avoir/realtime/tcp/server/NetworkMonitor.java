/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.server;

import avoir.realtime.tcp.base.protocol.*;
import avoir.realtime.tcp.base.RealtimeBase;
import avoir.realtime.tcp.common.MessageCode;
import avoir.realtime.tcp.common.PresenceConstants;
import avoir.realtime.tcp.common.packet.HeartBeat;
import java.util.Timer;
import java.util.TimerTask;

/**
 *
 * @author developer
 */
public class NetworkMonitor {

    private RealtimeBase base;
    private Timer timer;
    private long INTERVAL = 1 * 1000;
    private boolean serverContacted = false;
    private boolean online = true;
    private boolean firstTime = true;

    public NetworkMonitor(RealtimeBase base) {
        this.base = base;
    }

    private void delay(long time) {
        try {
            Thread.sleep(time);
        } catch (Exception ex) {
        }
    }

    public boolean isServerContacted() {
        return serverContacted;
    }

    public void setServerContacted(boolean serverContacted) {
        this.serverContacted = serverContacted;
        if (!online && serverContacted) {
            setUserOnline();
        }

    }

    public void sendPulse() {
        if (timer != null) {
            timer.cancel();
        }
        /*if (firstTime) {
            int userIndex = base.getUserManager().getUserIndex(base.getUser().getUserName());
            base.getUserManager().setUser(userIndex, PresenceConstants.ONLINE_STATUS_ICON,
                    PresenceConstants.ONLINE, PresenceConstants.ONLINE);
            firstTime = false;
            System.out.println("PP");
        }*/
        serverContacted = false;
        timer = new Timer();
        base.getTcpClient().sendPacket(new HeartBeat(base.getSessionId()));
        //ok..must monitor this pulse
        timer.schedule(new HeartBeatMonitor(), 20 * 1000);
        //sleep for a short timet
        delay(INTERVAL);

    }

    class HeartBeatMonitor extends TimerTask {

        public void run() {
            if (!serverContacted) {//if we reach here..then the network has a problem
                setUserOffline();
            }

        }
    }

    public void setUserOffline() {
        int userIndex = base.getUserManager().getUserIndex(base.getUser().getUserName());
        base.getUserManager().setUser(userIndex, PresenceConstants.ONLINE_STATUS_ICON,
                PresenceConstants.OFFLINE, PresenceConstants.OFFLINE);
        base.showMessage("Network Error!!! ", false, true, MessageCode.ALL);
        online = false;

    }

    public void setUserOnline() {
        int userIndex = base.getUserManager().getUserIndex(base.getUser().getUserName());
        base.getUserManager().setUser(userIndex, PresenceConstants.ONLINE_STATUS_ICON,
                PresenceConstants.ONLINE, PresenceConstants.ONLINE);
        base.showMessage("Back Online!!!", true, true, MessageCode.ALL);
        online = true;
    }
}
