/**
 *  $Id: ChatPacket.java,v 1.3 2007/02/02 11:01:21 davidwaf Exp $
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

import java.awt.Color;

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
    private Color color;
    private String fontName;
    private int fontSize;
    private int fontStyle;
    private boolean privateChat;
    private String receiver;

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
    public ChatPacket(String usr, String content, String time, String chatLogFile,
            String sessionId, Color color, String fontName, int fontStyle, int fontSize,
            boolean privateChat, String receiver) {
        this.usr = usr;
        this.content = content;
        this.time = time;
        this.chatLogFile = chatLogFile;
        this.sessionId = sessionId;
        this.color = color;
        this.fontName = fontName;
        this.fontSize = fontSize;
        this.fontStyle = fontStyle;
        this.privateChat = privateChat;
        this.receiver = receiver;
    }

    public boolean isPrivateChat() {
        return privateChat;
    }

    public void setPrivateChat(boolean privateChat) {
        this.privateChat = privateChat;
    }

    public String getReceiver() {
        return receiver;
    }

    public void setReceiver(String receiver) {
        this.receiver = receiver;
    }

    public String getFontName() {
        return fontName;
    }

    public void setFontName(String fontName) {
        this.fontName = fontName;
    }

    public int getFontSize() {
        return fontSize;
    }

    public void setFontSize(int fontSize) {
        this.fontSize = fontSize;
    }

    public int getFontStyle() {
        return fontStyle;
    }

    public void setFontStyle(int fontStyle) {
        this.fontStyle = fontStyle;
    }

    public Color getColor() {
        return color;
    }

    public void setColor(Color color) {
        this.color = color;
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
