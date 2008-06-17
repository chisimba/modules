/**
 * $Id: Launcher.java,v 1.5 2007-12-14 15:42:46 davidwaf Exp $
 * 
 * Copyright (C) GNU/GPL AVOIR 2007
 * 
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
package avoir.realtime.tcp.launcher;

import java.io.Serializable;


/**
 * Class to represent Users of the realtime tools, maps to the logged in user in
 * Chisimba.
 */
@SuppressWarnings("serial")
public class Launcher implements Serializable {

    private String userName;
    private String host;
    private String sessionId;
    private String slidesDir;
    private boolean isSlidesHost;
    private String slideServerId;
    private String sessionTitle;

    public Launcher(String userName, String host,
            String sessionId, String sessionTitle,String slidesDir,
            boolean isSlidesHost, String slideServerId) {
        this.userName = userName;
        this.host = host;
        this.sessionId = sessionId;
        this.slidesDir = slidesDir;
        this.isSlidesHost = isSlidesHost;
        this.slideServerId = slideServerId;
        this.sessionTitle=sessionTitle;
    }

    public String getSessionTitle() {
        return sessionTitle;
    }

    public void setSessionTitle(String sessionTitle) {
        this.sessionTitle = sessionTitle;
    }
    
    

    public String getSlideServerId() {
        return slideServerId;
    }

    public boolean isSlidesHost() {
        return isSlidesHost;
    }

    public String getSlidesDir() {
        return slidesDir;
    }

    public String getHost() {
        return host;
    }

    public String getSessionId() {
        return sessionId;
    }

    /**
     * returns userid of the user
     * 
     * @return userid the id of the user
     */
    public String getUserName() {
        return this.userName;
    }

    /**
     * Implemented to allow display of user name in GUI lists.
     * 
     * @return The user name.
     */
    @Override
    public String toString() {
        return this.userName;
    }

    public void setUserName(String userName) {
        this.userName = userName;
    }
}
