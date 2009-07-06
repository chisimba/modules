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
 * Packet used to request file from server
 * File is encoded using base64 class and embedded as imagedata using
 * this same packet
 * @author developer
 */
public class RealtimeNotepadPacket extends IQ implements RealtimePacketIntf {

    private String packetType = "notepad-save";
    private String content;
    private String filePath;
    private String filename;
    private String username;
    private String access;

    private static final String PREFERRED_ENCODING = "UTF-8";
    public final static String NAME_SPACE = "iq:avoir-realtime:notepad";
    public String getUsername() {
        return username;
    }

    public String getAccess() {
        return access;
    }

    public void setAccess(String access) {
        this.access = access;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public RealtimeNotepadPacket() {
    }

    public String getFilePath() {
        return filePath;
    }

    public String getFilename() {
        return filename;
    }

    public void setFilename(String filename) {
        this.filename = filename;
    }

    public void setFilePath(String filePath) {
        this.filePath = filePath;
    }

    public String getPacketType() {
        return packetType;
    }

    public void setPacketType(String packetType) {
        this.packetType = packetType;
    }

    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
    }

    @Override
    public String getChildElementXML() {
        StringBuilder buf = new StringBuilder();
        buf.append("<query xmlns=\"" + NAME_SPACE + "\">");
        buf.append("<packet-type>").append(packetType).append("</packet-type>");
        buf.append("<file-path>").append(filePath).append("</file-path>");
        buf.append("<filename>").append(filename).append("</filename>");
        buf.append("<username>").append(username).append("</username>");
        buf.append("<file-content>").append(content).append("</file-content>");
        buf.append("</query>");
        return buf.toString();
    }
}
