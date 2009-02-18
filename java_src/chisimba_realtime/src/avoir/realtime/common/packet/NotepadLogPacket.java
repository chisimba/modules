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

import java.util.ArrayList;

/**
 *
 * @author developer
 */
public class NotepadLogPacket implements RealtimePacket {

    private ArrayList<NotepadPacket> notepads;

    public NotepadLogPacket(ArrayList<NotepadPacket> notepads) {
        this.notepads = notepads;
    }

    public ArrayList<NotepadPacket> getNotepads() {
        return notepads;
    }

    public void setNotepads(ArrayList<NotepadPacket> notepads) {
        this.notepads = notepads;
    }

    public String getId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public String getSessionId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public void setId(String id) {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
