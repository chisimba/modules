/*
 * Copyright (C) GNU/GPL AVOIR 2008
 *    This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
package avoir.realtime.common.packet;

import avoir.realtime.common.packet.RealtimePacket;

/**
 *
 * @author David Wafula <davidwaf@gmail.com>
 */
public class SystemFilePacket implements RealtimePacket {

    private String sessionId;
    private String username;
    private byte[] byteArray;
    private String filename;
    private boolean lastFile;
    private int maxValue;
    private int currentValue;

    public int getCurrentValue() {
        return currentValue;
    }

    public void setCurrentValue(int currentValue) {
        this.currentValue = currentValue;
    }

    public int getMaxValue() {
        return maxValue;
    }

    public void setMaxValue(int maxValue) {
        this.maxValue = maxValue;
    }

    
    
    public SystemFilePacket(String sessionId, String username, byte[] byteArray, String filename, boolean lastFile) {
        this.sessionId = sessionId;
        this.username = username;
        this.byteArray = byteArray;
        this.filename = filename;
        this.lastFile = lastFile;
    }

    public boolean isLastFile() {
        return lastFile;
    }

    public void setLastFile(boolean lastFile) {
        this.lastFile = lastFile;
    }

    public String getFilename() {
        return filename;
    }

    public void setFilename(String filename) {
        this.filename = filename;
    }

    public byte[] getByteArray() {
        return byteArray;
    }

    public void setByteArray(byte[] byteArray) {
        this.byteArray = byteArray;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getId() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setId(String id) {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
