package avoir.realtime.tcp.launcher.packet;

/**
 *    Copyright (C) GNU/GPL AVOIR 2008
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
/**
 *
 * @author David Wafula <davidwaf@gmail.com>
 */
public class ModuleFileRequestPacket implements LauncherPacket {

    private byte[] byteArray;
    private String filename;
    private String filePath;
    private String slidesServerId;
    private String username;

    public ModuleFileRequestPacket(byte[] byteArray, String filename, String filePath, String slidesServerId, String username) {
        this.byteArray = byteArray;
        this.filename = filename;
        this.filePath = filePath;
        this.slidesServerId = slidesServerId;
        this.username = username;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getSlidesServerId() {
        return slidesServerId;
    }

    public void setSlidesServerId(String slidesServerId) {
        this.slidesServerId = slidesServerId;
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

    public String getFilename() {
        return filename;
    }

    public void setFilename(String filename) {
        this.filename = filename;
    }

    public String getFilePath() {
        return filePath;
    }

    public String toString() {
        return filePath;
    }

    public byte[] getByteArray() {
        return byteArray;
    }

    public void setByteArray(byte[] byteArray) {
        this.byteArray = byteArray;
    }
}
