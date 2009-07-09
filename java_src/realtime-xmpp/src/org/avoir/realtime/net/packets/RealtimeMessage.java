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
package org.avoir.realtime.net.packets;

import org.avoir.realtime.net.providers.RealtimePacketProvider;
import org.jivesoftware.smack.packet.IQ;

/**
 *
 * @author developer
 */
public class RealtimeMessage extends IQ implements RealtimePacketIntf {

    private String access;
    private String packetType="message";
    private String username;
    private String message;
    private String sender;


    private static final String PREFERRED_ENCODING = "UTF-8";
    public final static String NAME_SPACE = "iq:avoir-realtime:message";
    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public String getSender() {
        return sender;
    }

    public void setSender(String sender) {
        this.sender = sender;
    }

    @Override
    public String getChildElementXML() {
        StringBuilder buf = new StringBuilder();
        buf.append("<query xmlns=\"" + NAME_SPACE + "\">");
        buf.append("<packet-type>").append(packetType).append("</packet-type>");
        buf.append("<message>").append(message).append("</message>");
        buf.append("<sender>").append(sender).append("</sender>");
        buf.append("</query>");
        return buf.toString();
    }

    public String getAccess() {
        return access;
    }

    public String getPacketType() {
        return packetType;
    }

    public String getUsername() {
        return username;
    }

    public void setAccess(String access) {
        this.access = access;
    }

    public void setPacketType(String packetType) {
        this.packetType = packetType;
    }

    public void setUsername(String username) {
        this.username = username;
    }
}
