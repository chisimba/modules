/*
 *
 *  Copyright (C) GNU/GPL AVOIR 2008
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if notfchat, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
package avoir.realtime.tcp.base;

import avoir.realtime.tcp.base.RealtimeBase;
import avoir.realtime.tcp.common.MessageCode;
import avoir.realtime.tcp.common.PresenceConstants;
import avoir.realtime.tcp.common.packet.HeartBeat;
import java.util.Timer;
import java.util.TimerTask;

/**
 * This class monitors the network status of this client. It reports to this client
 * when unable to contact the server. Note, there is a similar class in the server
 * package that does something similar, although in aa different mode.
 * @see avoir.realtime.tcp.server.NetworkMonitor
 * @author David Wafula
 */
public class NetworkMonitor {

    private RealtimeBase base;
    private Timer timer;
    private long INTERVAL = 60 * 1000;
    private boolean serverContacted = false;
    private boolean online = true;

    public NetworkMonitor(RealtimeBase base) {
        this.base = base;
    }

    public void startHeartBeat() {
        Timer t = new Timer();
        t.scheduleAtFixedRate(new HeartGenerator(), 0, INTERVAL);
    }

    public boolean isServerContacted() {
        return serverContacted;
    }

    public void setServerContacted(boolean serverContacted) {
        this.serverContacted = serverContacted;
      //  System.out.println("from seerver:  online: " + online + " serverContacted: " + serverContacted);

        if (!online && serverContacted) {
            setUserOnline();
        }

    }

    /**
     * send a pulse to server, and start monitoring feed back. If after 20 secs
     * there is no reply, then the network must be having problem, alert user
     */
    private void sendPulse() {
        if (timer != null) {
            timer.cancel();
        }
    //    System.out.println("send pulse:  online: " + online + " serverContacted: " + serverContacted);
        serverContacted = false;

        timer = new Timer();
        base.getTcpClient().sendPacket(new HeartBeat(base.getSessionId()));
        //ok..must monitor this pulse
        timer.schedule(new HeartBeatMonitor(), INTERVAL);

    }

    class HeartBeatMonitor extends TimerTask {

        public void run() {
            if (!serverContacted) {//if we reach here..then the network has a problem
                setUserOffline();
            }

        }
    }

    class HeartGenerator extends TimerTask {

        public void run() {


            sendPulse();

        }
    }

    /**
     * Sets the user to appear offline status
     */
    public void setUserOffline() {
        int userIndex = base.getUserManager().getUserIndex(base.getUser().getUserName());
        base.getUserManager().setUser(userIndex, PresenceConstants.ONLINE_STATUS_ICON,
                PresenceConstants.OFFLINE, PresenceConstants.OFFLINE);
        base.showMessage("Network Error!!! ", false, true, MessageCode.ALL);
        online = false;

    }

    /**
     * sets the user to appear in online status
     */
    public void setUserOnline() {
        int userIndex = base.getUserManager().getUserIndex(base.getUser().getUserName());
        base.getUserManager().setUser(userIndex, PresenceConstants.ONLINE_STATUS_ICON,
                PresenceConstants.ONLINE, PresenceConstants.ONLINE);
        base.showMessage("Back Online!!!", true, true, MessageCode.ALL);
        online =
                true;
    }
}
