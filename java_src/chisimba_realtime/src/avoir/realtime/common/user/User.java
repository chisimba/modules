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
package avoir.realtime.common.user;

import java.io.Serializable;
import java.util.logging.Logger;

/**
 * Class to represent Users of the realtime tools, maps to the logged in user in
 * Chisimba.
 */
@SuppressWarnings("serial")
public class User implements Serializable {

    private static Logger logger = Logger.getLogger(User.class.getName());
    private int level;
    private String fullname;
    private String userName;
    private boolean hasToken;
    private boolean isPresenter;
    private boolean online;
    boolean active = true;
    private String host;
    private int port;
    private String sessionId;
    private String slidesDir;
    private boolean isSlidesHost;
    private String siteRoot;
    private String slideServerId;
    private String sessionTitle;
    private boolean speakerOn,  micOn,  handOn,  editOn,  yesOn,  noOn;
    private String userDetails;
    private String userImagePath;
    private boolean chatEnabled = true;

    public User(int level, String fullname, String userName, String host,
            int port, boolean isPresenter, String sessionId, String sessionTitle, String slidesDir,
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
        this.sessionTitle = sessionTitle;
        logger.finest("Created new user object with name " + fullname + " and level " + level);
    }

    public User(int level, String fullname, String userName, boolean isPresenter, String sessionId) {
        this.level = level;
        this.fullname = fullname;
        this.userName = userName;
        this.isPresenter = isPresenter;
        this.sessionId = sessionId;
    }

    public boolean isChatEnabled() {
        return chatEnabled;
    }

    public void setChatEnabled(boolean chatEnabled) {
        this.chatEnabled = chatEnabled;
    }

    public String getUserDetails() {
        return userDetails;
    }

    /**
     * Compares if the two user objects are equal. Two users are equal if and only
     * if thier username are same
     * @param usr
     * @return boolean
     */
    public boolean equals(User usr) {
        return usr.getUserName().equals(userName);
    }

    public boolean isIsSlidesHost() {
        return isSlidesHost;
    }

    public void setUserDetails(String userDetails) {
        this.userDetails = userDetails;
    }

    public String getUserImagePath() {
        return userImagePath;
    }

    public void setUserImagePath(String userImagePath) {
        this.userImagePath = userImagePath;
    }

    public boolean isOnline() {
        return online;
    }

    public void setOnline(boolean online) {
        this.online = online;
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

    public boolean isEditOn() {
        return editOn;
    }

    public void setEditOn(boolean editOn) {
        this.editOn = editOn;
    }

    public boolean isHandOn() {
        return handOn;
    }

    public void setHandOn(boolean handOn) {
        this.handOn = handOn;
    }

    public boolean isMicOn() {
        return micOn;
    }

    public void setMicOn(boolean micOn) {
        this.micOn = micOn;
    }

    public boolean isNoOn() {
        return noOn;
    }

    public void setNoOn(boolean noOn) {
        this.noOn = noOn;
    }

    public boolean isSpeakerOn() {
        return speakerOn;
    }

    public void setSpeakerOn(boolean speakerOn) {
        this.speakerOn = speakerOn;
    }

    public boolean isYesOn() {
        return yesOn;
    }

    public void setYesOn(boolean yesOn) {
        this.yesOn = yesOn;
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
    public int getLevel() {
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

    public void setFullName(String name) {
        fullname = name;
    }

    public void setUserName(String userName) {
        this.userName = userName;
    }
}
