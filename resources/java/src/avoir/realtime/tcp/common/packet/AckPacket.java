/**
 * 	$Id: AckPacket.java,v 1.3 2007/03/05 15:39:48 adrian Exp $
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

import avoir.realtime.tcp.common.user.User;

/**
 * Packet that stores the name of the user who owns this whiteboard instance
 */
@SuppressWarnings("serial")
public class AckPacket implements RealtimePacket {

    private User user;
    private boolean presenter;
    private boolean mediaProvider;
    private boolean mediaConsumer;

    /**
     * Constructor
     * @param user The user who started this client applet
     */
    public AckPacket(User user, boolean presenter) {
        this.user = user;
        this.presenter = presenter;
    }

    public boolean isMediaConsumer() {
        return mediaConsumer;
    }

    public void setMediaConsumer(boolean mediaConsumer) {
        this.mediaConsumer = mediaConsumer;
    }

    public boolean isMediaProvider() {
        return mediaProvider;
    }

    public void setMediaProvider(boolean mediaProvider) {
        this.mediaProvider = mediaProvider;
    }

    public boolean isPresenter() {
        return presenter;
    }
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
     * Returns the user name stored in this packet
     * @return String name
     */
    public User getUser() {
        return user;
    }
}
