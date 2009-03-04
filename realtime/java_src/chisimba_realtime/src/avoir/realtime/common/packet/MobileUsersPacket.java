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
package avoir.realtime.common.packet;

import avoir.realtime.common.user.User;
import java.util.Vector;

/**
 *
 * @author developer
 */
public class MobileUsersPacket implements RealtimePacket {

    private String sessionId;
    private Vector<User> user;

    public Vector<User> getUsers() {
        return user;
    }

    public void setUsers(Vector<User> user) {
        this.user = user;
    }

    public MobileUsersPacket(String sessionId, Vector<User> user) {
        this.sessionId = sessionId;
        this.user = user;
    }

  

    public String getId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setId(String id) {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
