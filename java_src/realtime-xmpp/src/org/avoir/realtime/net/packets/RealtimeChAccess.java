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
public class RealtimeChAccess extends IQ implements RealtimePacketIntf {

    private String packetType = "access";
    private String fileName;
    private String access;
    private String username;
    private String fileType;

    private static final String PREFERRED_ENCODING = "UTF-8";
    public final static String NAME_SPACE = "iq:avoir-realtime:change-access";
    @Override
    public String getChildElementXML() {
        StringBuilder buf = new StringBuilder();
        buf.append("<query xmlns=\"" + NAME_SPACE + "\">");
        buf.append("<packet-type>").append(packetType).append("</packet-type>");
        buf.append("<filename>").append(fileName).append("</filename>");
        buf.append("<file-type>").append(fileType).append("</file-type>");
        buf.append("<access>").append(access).append("</access>");
        buf.append("<username>").append(username).append("</username>");
        buf.append("</query>");
        return buf.toString();
    }

    public String getFileType() {
        return fileType;
    }

    public void setFileType(String fileType) {
        this.fileType = fileType;
    }

    public String getFileName() {
        return fileName;
    }

    public void setFileName(String fileName) {
        this.fileName = fileName;
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
