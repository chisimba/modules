/**
 * $Id: User.java,v 1.5 2007-12-14 15:42:46 davidwaf Exp $
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
package avoir.realtime.tcp.base.user;

import java.io.Serializable;
import java.util.logging.Logger;

/**
 * Class to represent Users of the realtime tools, maps to the logged in user in
 * Chisimba.
 */
@SuppressWarnings("serial")
public class User implements Serializable {

    private static Logger logger = Logger.getLogger(User.class.getName());
    private UserLevel level;
    private String fullname;
    private String userName;
    private boolean hasToken;
    private boolean isPresenter;
    boolean active = true;
    private String host;
    private int port;
    private String sessionId;
    private String slidesDir;
    private boolean isSlidesHost;
    private String siteRoot;
    private String slideServerId;
    private String sessionTitle;

    public User(UserLevel level, String fullname, String userName, String host,
            int port, boolean isPresenter, String sessionId,String sessionTitle, String slidesDir,
            boolean isSlidesHost, String siteRoot, String slideServerId) {
        this.level = level;
        this.fullname = fullname;
        this.userName = userName;
        this.host = host;
        this.port = port;
        this.isPresenter = isPresenter;
        this.sessionId = sessionId;
        this.slidesDir = slidesDir;
        this.isSlidesHost = isSlidesHost;
        this.siteRoot = siteRoot;
        this.slideServerId = slideServerId;
        this.sessionTitle=sessionTitle;
        logger.finest("Created new user object with name " + fullname + " and level " + level);
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

    public String getSiteRoot() {
        return siteRoot;
    }

    public boolean isSlidesHost() {
        return isSlidesHost;
    }

    public String getSlidesDir() {
        return slidesDir;
    }

    public void setAsPresenter(boolean isPresenter) {
        this.isPresenter = isPresenter;
    }

    /**
     * Returns the user's name.
     * 
     * @return String The user's name.
     */
    public String getFullName() {
        return this.fullname;
    }

    public boolean isActive() {
        return active;
    }

    public String getHost() {
        return host;
    }

    public int getPort() {
        return port;
    }

    public boolean isPresenter() {
        return isPresenter;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setActive(boolean active) {
        this.active = active;
    }

    /*** Returns the user's level.
     * 
     * @return UserLevel The user's level.
     */
    public UserLevel getLevel() {
        return this.level;
    }

    public boolean isAdmin() {
        return level == UserLevel.ADMIN ? true : false;
    }

    public boolean isLecturer() {
        return level == UserLevel.LECTURER ? true : false;
    }

    public boolean isStudent() {
        return level == UserLevel.STUDENT ? true : false;
    }

    public boolean isGuest() {
        return level == UserLevel.GUEST ? true : false;
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
     * Flips a user's "has token" boolean to the opposite value.
     */
    public void flipToken() {
        if (!hasToken) {
            hasToken = true;
        } else {
            hasToken = false;
        }
    }

    /**
     * Implemented to allow display of user name in GUI lists.
     * 
     * @return The user name.
     */
    public String toString() {
        return this.fullname;
    }

    public boolean equals(Object object) {
        if (this == object) {
            return true;
        }
        if ((object == null) || (object.getClass() != this.getClass())) {
            return false;
        }
        User otherUser = (User) object;
        if (this.level.equals(otherUser.getLevel()) && this.fullname.equals(otherUser.getFullName())) {
            if (this.userName == null && otherUser.getUserName() == null) {
                return true;
            }
            if (this.userName != null && otherUser.getUserName() != null) {
                if (this.userName.equals(otherUser.getUserName())) {
                    return true;
                }
            }
        }
        return false;
    }

    public void setFullName(String name) {
        fullname = name;
    }

    public void setUserName(String userName) {
        this.userName = userName;
    }
}
