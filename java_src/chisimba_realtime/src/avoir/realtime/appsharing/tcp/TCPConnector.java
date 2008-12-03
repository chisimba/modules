/**
 * This class is used for the actual communications with the server via tcp
 * Copyright (C) GNU/GPL AVOIR 2007
 *
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
package avoir.realtime.appsharing.tcp;

import avoir.realtime.appsharing.DefaultDesktopPacketProcessor;
import avoir.realtime.appsharing.ScreenScraper;

import avoir.realtime.chat.PrivateChatFrame;
import avoir.realtime.common.TCPSocket;

import avoir.realtime.common.appshare.DesktopPacketIntf;
import avoir.realtime.common.appshare.DesktopPacketProcessor;
import avoir.realtime.common.user.User;
import avoir.realtime.common.user.UserLevel;
import java.util.Map;

/**
 * David Wafula
 */
public class TCPConnector extends TCPSocket {

    private User user;
    private ScreenScraper screenScraper;
    private DesktopPacketProcessor processor = new DefaultDesktopPacketProcessor();

    public TCPConnector(String username, String sessionId) {
        user = new User(UserLevel.GUEST, username, username, true, sessionId);
    }

    @Override
    public Map<String, PrivateChatFrame> getPrivateChats() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public ScreenScraper getScreenScraper() {
        return screenScraper;
    }

    public void setScreenScraper(ScreenScraper screenScraper) {
        this.screenScraper = screenScraper;
    }

    @Override
    public void startSession() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public User getUser() {
        return user;
    }

    public void setUser(User user) {
        this.user = user;
    }

    public boolean connectToServer(String host, int port) {
        if (connect(host, port)) {
            publish(user);
            return true;
        }
        return false;
    }

    @Override
    public void listen() {
        while (running) {

            try {
                Object packet = null;
                try {
                    packet = reader.readObject();
                    connected = true;
                    if (packet instanceof DesktopPacketIntf) {
                        processor.processDesktopPacket((DesktopPacketIntf) packet);
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                    connected = false;
                    running = false;

                    break;
                }

            } catch (Exception ex) {
                //for now, just cut off the listening
                running = false;
                connected = false;
                // displayDisconnectionMsg();
                ex.printStackTrace();
            }
        }

        if (!connected && !running) {
            refreshConnection();
        }
    }

    /**
     * If diconnected from server, try to auto re-connect
     */
    public void refreshConnection() {
        connected = false;
        int count = 0;
        int max = 10;
        while (!connected) {
            System.out.println("Disconnected from server. Retrying " + count + " of " + max + "...");
            if (connect(host, port)) {

                publish(user);
                break;
            }
            if (count > max) {
                break;
            }
            sleep(5000);
            count++;

        }
        if (!connected) {
            System.out.println("Connection to server failed. Contact your system administrator.");

        }
    }

    /**
     * Pause for a specified time
     * @param time
     */
    private void sleep(long time) {
        try {
            Thread.sleep(time);
        } catch (Exception ex) {
        }
    }
}
