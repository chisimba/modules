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
package avoir.realtime.tcp.common.packet;

/**
 *
 * @author David Wafula <davidwaf@gmail.com>
 */
public class LocalSlideCacheRequestPacket implements RealtimePacket {

    private String sessionId;
    private String slidesServerId;
    private String pathToSlides;
    private String username;

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public LocalSlideCacheRequestPacket(String sessionId, String slidesServerId, String pathToSlides, String username) {
        this.sessionId = sessionId;
        this.slidesServerId = slidesServerId;
        this.pathToSlides = pathToSlides;
        this.username = username;
    }

    public String getPathToSlides() {
        return pathToSlides;
    }

    public void setPathToSlides(String pathToSlides) {
        this.pathToSlides = pathToSlides;
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
        return sessionId;
    }

    public void setId(String id) {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
