/*

 *
 * Copyright (C) GNU/GPL AVOIR 2008
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
package org.avoir.realtime.plugins;

import org.jivesoftware.openfire.muc.MUCEventListener;
import org.xmpp.packet.JID;
import org.xmpp.packet.Message;

/**
 *
 * @author developer
 */
public class RoomEventListener implements MUCEventListener {

    AvoirRealtimePlugin pl;

    public RoomEventListener(AvoirRealtimePlugin pl) {
        this.pl = pl;
    }

    public void messageReceived(JID arg0, JID arg1, String arg2, Message arg3) {
    }

    public void nicknameChanged(JID arg0, JID arg1, String arg2, String arg3) {
    }

    public void occupantJoined(JID roomJID, JID user, String nickname) {
        String username = user.toBareJID();
        username = username.substring(0, username.indexOf("@"));
        String room = roomJID.toBareJID();
        room = room.substring(0, room.indexOf("@"));
        room = room.toLowerCase();

        pl.getRoomResourceManager().addUserAsOnline(username, room);
        RUserManager.users.put(room, pl.getRoomResourceManager().getOnlineUsers(room));
    }

    public void occupantLeft(JID roomJID, JID user) {

        String username = user.toBareJID();
        username = username.substring(0, username.indexOf("@"));
        String room = roomJID.toBareJID();
        room = room.substring(0, room.indexOf("@"));
        room = room.toLowerCase();

        pl.getRoomResourceManager().removeUserAsOnline(username, room);
        RUserManager.users.put(room, pl.getRoomResourceManager().getOnlineUsers(room));
    }

    public void roomCreated(JID roomJID) {
        String room = roomJID.toBareJID();
        room = room.substring(0, room.indexOf("@"));
        room = room.toLowerCase();
    }

    public void roomDestroyed(JID roomJID) {
        String room = roomJID.toBareJID();
        room = room.substring(0, room.indexOf("@"));
        room = room.toLowerCase();
        pl.getRoomResourceManager().deleteRoom(room);
    }

    public void roomSubjectChanged(JID arg0, JID arg1, String arg2) {
    }
}
