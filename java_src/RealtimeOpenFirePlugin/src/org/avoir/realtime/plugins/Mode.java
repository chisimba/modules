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
package org.avoir.realtime.plugins;

/**
 *
 * @author developer
 */
public class Mode {

    public static final String POINTER = "pointer";
    public static final String SAVE_SLIDE_SHOW = "save-slide-show";
    public static final String POSTED_ANSWER = "posted-answer";
    public static final String POST_ANSWER = "post-answer";
    public static final String OPEN_SLIDE_SHOW_QUESTION = "open-slide-show-question";
    public static final String OPEN_SLIDE_SHOW = "open-slide-show";
    public static final String OPEN_QUESTION = "open-question";
    public static final String BROADCAST_QUESTION = "broadcast-question";
    public static final String POST_QUESTION = "post-question";
    public static final String SAVE_QUESTION = "save-question";
    public static final String UPLOAD_IMAGE = "upload-image";
    public static final String REQUEST_SLIDE_QUESTION_FILE_VIEW = "request-slide-question-file-view";
    public static final String REQUEST_FILE_VIEW = "request-file-view";
    public static final String DOWNLOAD_QUESTION_IMAGE = "download-question-image";
    public static final String DOWNLOAD_SLIDE_SHOW_IMAGE = "download-slide-show-image";
    public static final String BROADCAST_IMAGE = "broadcast-image";
    public static final String DELETE_ITEM_BROADCAST = "delete-item-broadcast";
    public static final String RESIZE_ITEM_BROADCAST = "resize-item-broadcast";
    public static final String TRANSFORMED_ITEM_BROADCAST = "transform-item-broadcast";
    public static final String UPDATE_ITEM_POSITION = "update-item-position";
    public static final String RESIZE_ITEM = "resize-item";
    public static final String DELETE_ITEM = "delete-item";
    public static final String WB_IMAGE_BROADCAST = "wb-image-broadcast";
    public static final String POINTER_BROADCAST = "pointer-broadcast";
    public static final String REQUEST_USER_LIST = "request-user-list";
    public static final String REQUEST_USER_PROPERTIES = "request-user-properties";
    public static final String UPDATE_USER_PROPERTIES = "update-user-properties";
    public static final String CREATE_ROOM = "create-room";
    public static final String REQUEST_ADMIN_LIST = "request-admin-list";
    public static final String REQUEST_ROOM_LIST = "request-room-list";
    public static final String USER_PROPERTIES = "user-properties";
    public static final String USERLIST_ACCESSLEVEL_REPLY = "userlist-accesslevel-reply";
    public static final String CREATE_ROOM_ERROR = "create-room-error";
    public static final String ADD_SLIDE_SHOW_CLASS_RESOURCE = "add-slide-show-as-class-resource";//
    public static final String ROOM_RESOURCES = "room-resources";
    public static final String SLIDE_SHOW_CHANGES = "slide-show-changes";
    public static final String REQUEST_ROOM_RESOURCES = "request-room-resources";
    public static final String DOWNLOAD_ROOM_SLIDE_SHOW = "download-room-slide-show";
    public static final String BROADCAST_IN_SLIDE = "broadcast-in-slide";
    public static final String BROADCAST_OUT_SLIDE = "broadcast-out-slide";
    public static final String CHANGE_ACCESS = "change-access";
    public static final String BROADCAST_WB_ITEM = "broadcast-wb-item";
    public static final String ITEM_BROADCAST_FROM_SERVER = "item-broadcast-from-server";
    public static final String UPDATE_TEXT_ITEM = "update-text-item";
    public static final String MODIFIED_TEXT_BROADCAST = "modified-text-broadcast";
    public static final String SCREEN_SHARE_INVITE = "screen-share-invite";
    public static final String SCREEN_SHARE_INVITE_FROM_SERVER = "screen-share-invite-from-server";
    public static final String AUDIO_VIDEO_INVITE = "audio-vide-invite";
    public static final String AUDIO_VIDEO_BROADCAST = "audio-video-broadcast";
    public static final String GIVE_MIC = "give-mic";
    public static final String GIVEN_MIC = "given-mic";
    public static final String TAKE_MIC = "take-mic";
    public static final String TAKEN_MIC = "taken-mic";
    public static final String CHANGE_TAB = "change-tab";
    public static final String CHANGED_TAB_INDEX = "changed-tab-index";
    public static final String UPDATE_URL = "update-url";
    public static final String UPDATED_URL = "updated-url";
    public static final String DELETE_ROOM_RESOURCE = "delete-room-resource";
    public static final String DELETE_ROOM_RESOURCE_SUCCESS = "delete-room-resource-success";
    public static final String REQUEST_WEBPRESENT_SLIDES = "request-webpresent-slides";
    public static final String DOWNLOAD_WEBPRESENT_SLIDES = "download-webpresent-slides";
    public static final String SLIDES_COUNT = "slides-count";
    public static final String CLEAR_LAST_SESSION = "clear-last-session";
    public static final String ADMIN_LIST = "admin-list";
    public static final String INVITE_PARTICIPANTS = "invite-participants";
    public static final String BROADCAST_IMAGE_DATA = "broadcast-image-data";
    public static final String INVITE_RESULT = "invite-result";
    public static final String SLIDE_UPLOAD_SUCCESS = "slide-upload-success";
    public static final String SLIDE_UPLOAD_FAILURE = "slide-upload-failure";
    public static final String FILE_UPLOAD = "file-upload";
    public static final String FILE_UPLOAD_RESULT = "file-upload-result";
    public static final String INFO = "info";
    public static final String NEW_ROOM = "new-room";
    public static final String ROOM_LIST = "room-list";
    public static final String LAUNCH_EC2 = "launch-ec2";
    public static final String LAUNCH_EC2_STATUS_MSG = "launch-ec2-status-message";
    public static final String EC2_MAIN_SERVER_READY = "ec2-main-server-ready";
    public static final String EC2_AUDIO_VIDEO_SERVER_READY = "ec2-audio-video-server-ready";
    public static final String EC2_FLASH_SERVER_READY = "ec2-flash-server-ready";
    public static final String SAVE_MAIN_EC2_URL = "save-main-ec2-url";
    public static final String SAVE_AUDIO_VIDEO_EC2_URL = "save-audio-video-ec2-url";
    public static final String SAVE_FLASH_EC2_URL = "save-flash-ec2-url";
    public static final String TERMINATE_INSTANCE = "terminate-instance";
    public static final String JOIN_MEETING_ID = "join-meeting-id";
    public static final String SERVER_INFO = "server-info";
    public static final String REQUEST_ROOM_OWNER = "request-room-owner";
    public static final String ROOM_OWNER_REPLY = "room-owner-reply";
    public static final String REQUEST_MIC = "request-mic";
    public static final String REQUEST_MIC_FORWARDED = "request-mic-forwarded";
    public static final String PRIVATE_CHAT_SEND = "private-chat-send";
    public static final String PRIVATE_CHAT_FORWARD = "private-chat-forward";
    public static final String ADD_ROOM_MEMBER = "add-room-member";
    public static final String DELETE_ROOM_MEMBER = "delete-room-member";
    public static final String REQUEST_ROOM_MEMBERS = "request-room-members";
    public static final String ROOM_MEMBERS = "room-members";
    public static final String ITEM_HISTORY_FROM_SERVER = "item-history-from-server";
    public static final String MIC_HOLDER = "mic-holder";
    public static final String WARN = "warn";
    public static final String REQUEST_MIC_REPLY = "request-mic-reply";
    public static final String MIC_ADMIN_HOLDER = "mic-admin-holder";
    public static final String SET_ACCESS = "set-access";
    public static final String SET_PERMISSIONS = "set-permissions";
    public static final String REQUEST_ROOM_RESOURCE_LIST = "request-room-resource-list";
    public static final String ROOM_RESOURCE_LIST = "room-resource-list";
    public static final String SET_ROOM_RESOURCE_STATE = "set-room-resource-state";
}
