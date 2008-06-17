/**
 * 	$Id: ClientPacket.java,v 1.3 2007/03/05 15:40:32 adrian Exp $
 * 
 *  Copyright (C) GNU/GPL AVOIR 2007
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
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
package avoir.realtime.tcp.common.packet;

import avoir.realtime.tcp.base.user.User;
import java.util.Vector;



/**
 * Packet that contains the Vector of clients currently using the realtime tools
 */
@SuppressWarnings("serial")
public class ClientPacket implements RealtimePacket {
    private Vector<User> users;
    private String id;

    public void setId(String id) {
        this.id = id;
    }

    public String getSessionId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public String getId() {
        return id;
    }
    /**
     * Constructor
     * @param v Vector of clients
     */
    public ClientPacket(Vector<User> v) {
        this.users = v;
    }

    /**
     * Returns the Vector of clients
     * @return Vector names
     */
    public Vector<User> getUsers() {
        return users;
    }
}