/**
 * 	$Id: ChatPacket.java,v 1.3 2007/02/02 11:01:21 davidwaf Exp $
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
package avoir.realtime.common.packet;

import avoir.realtime.common.packet.RealtimePacket;

/**
 * Packet that stores information about a Chat event
 */
@SuppressWarnings("serial")
public class ChatPacket implements RealtimePacket {

    private String usr;
    private String content;
    private String time;
    private String chatLogFile;
    private String id;
    private String sessionId;

    public void setId(String id) {
        this.id = id;
    }

    public String getId() {
        return id;
    }

    /**
     * Constructor
     * @param usr The person who sent this message
     * @param content The contents of the message
     * @param time The time message was send
     */
    public ChatPacket(String usr, String content, String time, String chatLogFile, String sessionId) {
        this.usr = usr;
        this.content = content;
        this.time = time;
        this.chatLogFile = chatLogFile;
        this.sessionId = sessionId;
    }

    public String getSessionId() {
        return sessionId;
    }

    /**
     * Returns the user who wrote this message
     * @return the user
     */
    public String getUsr() {
        return this.usr;
    }

    /**
     * Returns the contents of this message
     * @return String content
     */
    public String getContent() {
        return this.content;
    }

    public void setTime(String time) {
        this.time = time;
    }

    /**
     * Returns the time the message was send
     * @return String
     */
    public String getTime() {
        return this.time;
    }

    /**
     * Creates a string with the User's name and their message
     * @return Format- "User:" message"
     */
    @Override
    public String toString() {
        return "User: " + usr + " Content: " + content + " Time: " + time;
    }

    public String getChatLogFile() {
        return chatLogFile;
    }
}
