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
package avoir.realtime.chat;

/**
 *
 * @author developer
 */
public class PrivateChat {

    private String from;
    private String to;
    private PrivateChatFrame privateChatFrame;

    public PrivateChat(String from, String to, PrivateChatFrame privateChatFrame) {
        this.from = from;
        this.to = to;
        this.privateChatFrame = privateChatFrame;
    }

    public PrivateChatFrame getPrivateChatFrame() {
        return privateChatFrame;
    }

    @Override
    public String toString() {
        return from + " > " + to;
    }

    public boolean contains(String xfrom, String xto) {
        boolean result = false;
        result = xfrom.equals(from) && xto.equals(to);
        return result;
    }

    public String getFrom() {
        return from;
    }

    public void setFrom(String from) {
        this.from = from;
    }

    public String getTo() {
        return to;
    }

    public void setTo(String to) {
        this.to = to;
    }
}
