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

import org.jivesoftware.smack.packet.IQ;

/**
 *
 * @author developer
 */
public class RealtimePacket extends IQ {

    private String mode;
    public final static String NAME_SPACE = "iq:avoir-realtime:realtime";
    private String content;

    public RealtimePacket() {
    }

    public RealtimePacket(String xmode) {
        setMode(xmode);
    }

    public static final class Mode {

        public static final String REQUEST_USER_LIST = "request-user-list";
        public static final String UPDATE_USER_PROPERTIES = "update-user-properties";
        public static final String REQUEST_USER_PROPERTIES = "request-user-properties";
        public static final String CREATE_ROOM = "create-room";
        public static final String REQUEST_ADMIN_LIST = "request-admin-list";
        public static final String REQUEST_ROOM_LIST = "request-room-list";
        public static final String POINTER = "pointer";
        public static final String REQUEST_FILE_VIEW = "request-file-view";
        public static final String REQUEST_SLIDE_QUESTION_FILE_VIEW = "request-slide-question-file-view";
        public static final String BROADCAST_IMAGE = "broadcast-image";
        public static final String DOWNLOAD_SLIDE_SHOW_IMAGE = "download-slide-show-image";
        public static final String DOWNLOAD_QUESTION_IMAGE = "download-question-image";
        public static final String UPLOAD_IMAGE = "upload-image";
        public static final String UPDATE_ITEM_POSITION = "update-item-position";
        public static final String DELETE_ITEM = "delete-item";
        public static final String RESIZE_ITEM = "resize-item";
        public static final String SAVE_QUESTION = "save-question";
        public static final String SAVE_SLIDE_SHOW = "save-slide-show";
        public static final String OPEN_QUESTION = "open-question";
        public static final String OPEN_SLIDE_SHOW = "open-slide-show";
        public static final String OPEN_SLIDE_SHOW_QUESTION = "open-slide-show-question";
        public static final String POST_QUESTION = "post-question";
        public static final String POST_ANSWER = "post-answer";
        public static final String ADD_SLIDE_SHOW_CLASS_RESOURCE = "add-slide-show-as-class-resource";
        public static final String REQUEST_ROOM_RESOURCES = "request-room-resources";
        public static final String DOWNLOAD_ROOM_SLIDE_SHOW = "download-room-slide-show";
        public static final String BROADCAST_SLIDE = "broadcast-in-slide";
        public static final String CHANGE_ACCESS = "change-access";
        public static final String BROADCAST_WB_ITEM = "broadcast-wb-item";
        public static final String UPDATE_TEXT_ITEM = "update-text-item";
        public static final String SCREEN_SHARE_INVITE = "screen-share-invite";
        public static final String AUDIO_VIDEO_INVITE = "audio-vide-invite";
        public static final String GIVE_MIC = "give-mic";
        public static final String TAKE_MIC = "take-mic";
        public static final String CHANGE_TAB = "change-tab";
        public static final String UPDATE_URL = "update-url";
        public static final String DELETE_ROOM_RESOURCE = "delete-room-resource";
        public static final String REQUEST_WEBPRESENT_SLIDES = "request-webpresent-slides";
        public static final String CLEAR_LAST_SESSION = "clear-last-session";
        public static final String ADMIN_LIST = "admin-list";
        public static final String INVITE_PARTICIPANTS = "invite-participants";
        public static final String FORCE_JOIN = "force-join";
        public static final String BROADCAST_IMAGE_DATA = "broadcast-image-data";
        public static final String FILE_UPLOAD = "file-upload";
        public static final String NEW_ROOM = "new-room";
        public static final String LAUNCH_EC2 = "launch-ec2";
        public static final String SAVE_MAIN_EC2_URL = "save-main-ec2-url";
        public static final String SAVE_AUDIO_VIDEO_EC2_URL = "save-audio-video-ec2-url";
        public static final String SAVE_FLASH_EC2_URL = "save-flash-ec2-url";
        public static final String TERMINATE_INSTANCE = "terminate-instance";
        public static final String JOIN_MEETING_ID = "join-meeting-id";
        public static final String REQUEST_ROOM_OWNER = "request-room-owner";
        public static final String REQUEST_MIC = "request-mic";
        public static final String PRIVATE_CHAT_SEND = "private-chat-send";
        public static final String ADD_ROOM_MEMBER = "add-room-member";
        public static final String REQUEST_ROOM_MEMBERS = "request-room-members";
        public static final String DELETE_ROOM_MEMBER = "delete-room-member";
        public static final String SET_ACCESS = "set-access";
    }

    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
    }

    public String getMode() {
        return mode;
    }

    public void setMode(String mode) {
        this.mode = mode;
    }

    @Override
    public String getChildElementXML() {
        StringBuilder buf = new StringBuilder();
        buf.append("<query xmlns=\"" + NAME_SPACE + "\">");

        buf.append("<mode>").append(mode).append("</mode>");
        buf.append("<content>").append(content).append("</content>");
        buf.append("</query>");

        return buf.toString();

    }
}
