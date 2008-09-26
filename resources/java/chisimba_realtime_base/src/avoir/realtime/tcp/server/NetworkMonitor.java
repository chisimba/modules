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
package avoir.realtime.tcp.server;

import avoir.realtime.tcp.common.packet.UpdateUserNetworkStatusPacket;
import java.util.Timer;
import java.util.TimerTask;

/**
 * This class monitors a client connected to the server. If the client goes offline
 * it should be detected in atleast 20 seconds, then a broadcast is sent to others
 * to show that the user is offline
 * @author David Wafula
 */
public class NetworkMonitor {

    private ServerThread serverThread;
    private Timer timer;
    private boolean clientContacted = false;
    private boolean online = true;
    private long INTERVAL= 60 * 1000;

    public NetworkMonitor(ServerThread serverThread) {
        this.serverThread = serverThread;
    }

    public boolean isClientContacted() {
        return clientContacted;
    }

    /**
     * If we have contacted the client, and the client was prevoiusly offline,
     * set online
     * @param clientContacted
     */

    public void setClientContacted(boolean clientContacted) {
        this.clientContacted = clientContacted;
        if (!online && clientContacted) {
            setUserOnline();
        }

    }

    /**
     * Start the monitoring process.
     */
    public void startMonitoring() {
        if (timer != null) {
            timer.cancel();
        }

        clientContacted = false;
        timer = new Timer();
        //ok..must monitor this pulse
        timer.schedule(new ClientHeartBeatMonitor(),INTERVAL);

    }

    /**
     * Task timer to manage the heart beat
     */
    class ClientHeartBeatMonitor extends TimerTask {

        public void run() {
            if (!clientContacted) {//if we reach here..then the network has a problem
                setUserOffline();
            }

        }
    }

    /**
     * Sets the user offline. Question, is this the time to remove this user from
     * the list?
     */
    public void setUserOffline() {
        serverThread.getThisUser().setOnline(false);
        serverThread.broadcastPacket(new UpdateUserNetworkStatusPacket(serverThread.getThisUser(), false),
                serverThread.getThisUser().getSessionId(), "");
        online = false;

    }

    /**
     * User is back online
     */
    public void setUserOnline() {
        serverThread.getThisUser().setOnline(true);
        serverThread.broadcastPacket(new UpdateUserNetworkStatusPacket(serverThread.getThisUser(), true),
                serverThread.getThisUser().getSessionId(), "");
        online = true;

    }
}
