/*

 *
 * Copyright (C) GNU/GPL AVOIR 2008
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
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

import java.io.ByteArrayInputStream;
import java.io.File;


import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.jivesoftware.openfire.IQHandlerInfo;
import org.jivesoftware.openfire.IQRouter;
import org.jivesoftware.openfire.PacketRouter;
import org.jivesoftware.openfire.XMPPServer;
import org.jivesoftware.openfire.auth.UnauthorizedException;
import org.jivesoftware.openfire.container.Plugin;
import org.jivesoftware.openfire.container.PluginManager;
import org.jivesoftware.openfire.handler.IQHandler;

import org.jivesoftware.openfire.muc.MUCEventDispatcher;
import org.jivesoftware.openfire.session.ClientSession;
import org.jivesoftware.openfire.user.PresenceEventDispatcher;
import org.jivesoftware.openfire.user.PresenceEventListener;
import org.jivesoftware.util.JiveGlobals;
import org.w3c.dom.Document;
import org.xmpp.packet.IQ;
import org.xmpp.packet.JID;
import org.xmpp.packet.Presence;

import java.util.Map;

/**
 *
 * @author developer
 */
public class AvoirRealtimePlugin implements Plugin {

    private XMPPServer server = XMPPServer.getInstance();
    private PacketRouter packetRouter = server.getPacketRouter();
    private String domain = server.getServerInfo().getXMPPDomain();
    private IQRouter iqRouter = XMPPServer.getInstance().getIQRouter();
    private DefaultPacketProcessor defaultPacketProcessor = new DefaultPacketProcessor(this);
    private QuestionProcessor questionProcessor = new QuestionProcessor(this);
    private SlideShowProcessor slideshowProcessor = new SlideShowProcessor(this);
    private RealtimeRoomProcessor roomProcessor = new RealtimeRoomProcessor(this);
    private PointerProcessor pointerProcessor = new PointerProcessor(this);
    private RoomResourceManager roomResourceManager = new RoomResourceManager(this);
    private EmailSender emailSender = new EmailSender(this);
    private FileManager fileManager = new FileManager(this);
    private String propertyDomain = "default";
    private String resoureName = "Smack";
    // private EC2Processor ec2Processor;

    public AvoirRealtimePlugin() {
        PresenceEventDispatcher.addListener(new RPresenceListener());
        iqRouter.addHandler(new RealtimeIQHandler());
        String[] exts = {"images", "slideshows", "questions", "notepads", "answers"};
        for (int i = 0; i < exts.length; i++) {
            new File(Constants.FILES_DIR + "/" + exts[i]).mkdirs();
        }

        MUCEventDispatcher.addListener(new RoomEventListener(this));
    }

    public void destroyPlugin() {
    }

    public String getResoureName() {
        return resoureName;
    }

    public void initializePlugin(PluginManager pluginManager, File file) {
    }

    protected class RealtimeIQHandler extends IQHandler {

        public RealtimeIQHandler() {
            super("Realtime Handler");
        }

        public IQHandlerInfo getInfo() {
            return new IQHandlerInfo("query", Constants.NAME_SPACE);
        }

        public IQ handleIQ(IQ packet) throws UnauthorizedException {
            String xml = packet.toXML();

            // System.out.println(xml);
            DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();
            try {
                DocumentBuilder documentBuilder = documentBuilderFactory.newDocumentBuilder();
                Document doc = documentBuilder.parse(
                        new ByteArrayInputStream(xml.getBytes(Constants.PREFERRED_ENCODING)));

                String mode = XmlUtils.readString(doc, "mode");

                String roomName = XmlUtils.readString(doc, "room-name");
                if (roomName != null) {
                    roomName = roomName.toLowerCase();
                }
                if (mode.equals(Mode.POINTER)) {
                    pointerProcessor.broadCastPointer(packet, roomName);
                } else if (mode.trim().equals(Mode.LAUNCH_EC2.trim())) {
                    String mainInstanceURL[] = roomResourceManager.getEC2Property("ec2-main-instance-url", propertyDomain);
                    String audioVideoInstanceURL[] = roomResourceManager.getEC2Property("ec2-audio-video-instance-url", propertyDomain);
                    String flashInstanceURL[] = roomResourceManager.getEC2Property("ec2-flash-instance-url", propertyDomain);
                    if ((mainInstanceURL != null) &&
                            (audioVideoInstanceURL != null) &&
                            (flashInstanceURL != null)) {
                        defaultPacketProcessor.sendServerDetails(packet, Mode.EC2_MAIN_SERVER_READY, mainInstanceURL[0], mainInstanceURL[1]);
                        defaultPacketProcessor.sendServerDetails(packet, Mode.EC2_AUDIO_VIDEO_SERVER_READY, audioVideoInstanceURL[0], audioVideoInstanceURL[1]);
                        defaultPacketProcessor.sendServerDetails(packet, Mode.EC2_FLASH_SERVER_READY, flashInstanceURL[0], flashInstanceURL[1]);
                    } else {
                        packet.setTo("ec2launcher.127.0.0.1");
                        return packet;
                    }
                } else if (mode.equals(Mode.ADD_ROOM_MEMBER)) {
                    String roomMember = XmlUtils.readString(doc, "username");
                    String names = XmlUtils.readString(doc, "names");
                    String owner = XmlUtils.readString(doc, "room-owner");
                    roomResourceManager.addRoomMember(roomName, roomMember, names, owner);
                } else if (mode.equals(Mode.REQUEST_MIC_FORWARDED)) {
                    return packet;
                } else if (mode.equals(Mode.DELETE_ROOM_MEMBER)) {
                    String userName = XmlUtils.readString(doc, "username");
                    String roomOwner = XmlUtils.readString(doc, "room-owner");
                    return roomResourceManager.deleteRoomMember(packet, roomName, userName, roomOwner);
                } else if (mode.equals(Mode.REQUEST_MIC_FORWARDED)) {
                    return packet;
                } else if (mode.equals(Mode.SET_ROOM_RESOURCE_STATE)) {
                    String id = XmlUtils.readString(doc, "id");
                    String state = XmlUtils.readString(doc, "active");
                    return roomResourceManager.updateRoomResourceState(packet, roomName, state, new Integer(id));
                } else if (mode.equals(Mode.REQUEST_ROOM_RESOURCE_LIST)) {
                    return roomResourceManager.getRoomResourceList(packet, roomName);
                } else if (mode.equals(Mode.PRIVATE_CHAT_SEND)) {
                    String receiver = XmlUtils.readString(doc, "private-chat-receiver");
                    String sender = XmlUtils.readString(doc, "private-chat-sender");
                    String senderName = XmlUtils.readString(doc, "private-chat-sender-name");
                    String msg = XmlUtils.readString(doc, "private-chat-msg");
                    defaultPacketProcessor.forwardPrivateChatToReceiver(packet, receiver, sender, senderName, msg);
                } else if (mode.equals(Mode.JOIN_MEETING_ID)) {
                    String joinId = XmlUtils.readString(doc, "joinid");
                    String longURL[] = roomResourceManager.getLongUrl(joinId);
                    if (longURL != null) {
                        String[] mainInstanceURL = roomResourceManager.getEC2Property("ec2-main-instance-url", propertyDomain);
                        String[] audioVideoInstanceURL = roomResourceManager.getEC2Property("ec2-audio-video-instance-url", propertyDomain);
                        String[] flashInstanceURL = roomResourceManager.getEC2Property("ec2-flash-instance-url", propertyDomain);
                        if ((mainInstanceURL != null) &&
                                (audioVideoInstanceURL != null) &&
                                (flashInstanceURL != null)) {

                            defaultPacketProcessor.sendServerDetails(packet, Mode.EC2_MAIN_SERVER_READY, mainInstanceURL[0], longURL[1]);
                            defaultPacketProcessor.sendServerDetails(packet, Mode.EC2_AUDIO_VIDEO_SERVER_READY, audioVideoInstanceURL[0], longURL[1]);
                            defaultPacketProcessor.sendServerDetails(packet, Mode.EC2_FLASH_SERVER_READY, flashInstanceURL[0], longURL[1]);
                        } else {
                            return defaultPacketProcessor.sendInfo(packet, "Error: cannot join this meeting. The meeting convener is not online");
                        }
                    } else {
                        return defaultPacketProcessor.sendInfo(packet, "Error: Unable to join meeting. Invalid join id");
                    }
                } else if (mode.equals(Mode.TERMINATE_INSTANCE)) {
                    String username = XmlUtils.readString(doc, "username");
                    roomResourceManager.deleteEC2Url(username);
                    if (roomResourceManager.terminateInstance(propertyDomain)) {
                        packet.setTo("ec2launcher.127.0.0.1");
                        return packet;
                    }

                } else if (mode.equals(Mode.REQUEST_MIC)) {
                    String requester = XmlUtils.readString(doc, "username");
                    Map<Character, Boolean> userPermissions = roomResourceManager.getPermissions(requester);
                    Map user = roomResourceManager.getUserInfo(requester);
                    if (userPermissions.get('m')) {
                        //if the user already has the mic, ignore.
                        defaultPacketProcessor.warnUser(packet, requester, "You already have the MIC.");
                    } else {
                        roomResourceManager.addUserPermissions(requester, "m");
                        defaultPacketProcessor.forwardMICRequest(packet, (String) (user.get("room_owner")), requester, (String) (user.get("name")));
                    }
                //case where has_mic = -1 is completely ignored
                } else if (mode.equals(Mode.REQUEST_ROOM_OWNER)) {
                    return roomResourceManager.getRoomOwner(packet, roomName);
                } else if (mode.equals(Mode.TAKEN_MIC)) {
                    return packet;
                } else if (mode.equals(Mode.SAVE_MAIN_EC2_URL)) {
                    String url = XmlUtils.readString(doc, "host");
                    String username = XmlUtils.readString(doc, "username");
                    roomResourceManager.addEC2Url(username, roomName, "ec2-main-instance-url", url, propertyDomain);
                } else if (mode.equals(Mode.SAVE_AUDIO_VIDEO_EC2_URL)) {
                    String url = XmlUtils.readString(doc, "host");
                    String username = XmlUtils.readString(doc, "username");
                    roomResourceManager.addEC2Url(username, roomName, "ec2-audio-video-instance-url", url, propertyDomain);
                } else if (mode.equals(Mode.SAVE_FLASH_EC2_URL)) {
                    String url = XmlUtils.readString(doc, "host");
                    String username = XmlUtils.readString(doc, "username");
                    roomResourceManager.addEC2Url(username, roomName, "ec2-flash-instance-url", url, propertyDomain);
                } else if (mode.equals(Mode.FILE_UPLOAD_RESULT)) {
                    return packet;
                } else if (mode.equals(Mode.NEW_ROOM)) {
                    String roomDesc = XmlUtils.readString(doc, "room-desc");
                    String roomOwner = XmlUtils.readString(doc, "room-owner");
                    String roomType = XmlUtils.readString(doc, "room-type");
                    roomResourceManager.addRoom(roomName, roomDesc, roomOwner, roomType);
                } else if (mode.equals(Mode.FILE_UPLOAD)) {
                    return fileManager.downloadFile(packet, doc);
                } else if (mode.equals(Mode.INVITE_PARTICIPANTS)) {
                    return emailSender.sendEmails(packet, doc);
                } else if (mode.equals(Mode.CHANGED_TAB_INDEX)) {
                    return packet;
                } else if (mode.equals(Mode.LAUNCH_EC2_STATUS_MSG)) {

                    return packet;
                } else if (mode.equals(Mode.EC2_AUDIO_VIDEO_SERVER_READY)) {
                    String host = XmlUtils.readString(doc, "host");
                    JiveGlobals.setProperty("ec2-audio-video-instance-url", host);
                    return packet;
                } else if (mode.equals(Mode.EC2_FLASH_SERVER_READY)) {
                    String host = XmlUtils.readString(doc, "host");
                    JiveGlobals.setProperty("ec2-flash-instance-url", host);
                    return packet;
                } else if (mode.equals(Mode.EC2_MAIN_SERVER_READY)) {
                    String host = XmlUtils.readString(doc, "host");
                    JiveGlobals.setProperty("ec2-main-instance-url", host);
                    return packet;

                } else if (mode.equals(Mode.DOWNLOAD_WEBPRESENT_SLIDES)) {
                    return packet;
                } else if (mode.equals(Mode.UPDATED_URL)) {
                    return packet;
                } else if (mode.equals(Mode.GIVEN_MIC)) {
                    return packet;
                } else if (mode.equals(Mode.SCREEN_SHARE_INVITE_FROM_SERVER)) {
                    return packet;
                } else if (mode.equals(Mode.ADMIN_LIST)) {
                    return RUserManager.getAdmins(packet);
                } else if (mode.equals(Mode.REQUEST_WEBPRESENT_SLIDES)) {
                    String slidesDir = XmlUtils.readString(doc, "slides-dir");
                    String presentationName = XmlUtils.readString(doc, "presentation-name");
                    String presentationId = XmlUtils.readString(doc, "presentation-id");

                    slideshowProcessor.downloadWebpresentSlides(packet, slidesDir, presentationName, presentationId);
                } else if (mode.equals(Mode.SET_ACCESS)) {
                    //change the access of a member of a room
                    //this code will even change to owner
                    //could be security hazard. Anyone can send this command
                    //currently. Need to check if sender is the owner.
                    String username = XmlUtils.readString(doc, "username");
                    String newPermissions = XmlUtils.readString(doc, "permissions");
                    //Map<Character,Boolean> userPermissions = roomResourceManager.getPermissions(username);
                    roomResourceManager.updateUserPermissions(username, newPermissions);
                    String message = XmlUtils.readString(doc, "message");
                    if (message != null) {
                        defaultPacketProcessor.warnUser(packet, username, message);
                    } else {
                        //defaultPacketProcessor.warnUser(packet, username, "Your room permissions have been changed.");
                    }

                    Map user = roomResourceManager.getUserInfo(username);
                    String room = (String) user.get("room_name");
                    String permissions = (String) user.get("permissions");
                    defaultPacketProcessor.broadcastAccessChange(packet, username, room, permissions);

                } else if (mode.equals(Mode.CHANGE_TAB)) {
                    int index = XmlUtils.readInt(doc, "index");
                    defaultPacketProcessor.broadcastChangeTab(packet, index, roomName);
                } else if (mode.equals(Mode.MIC_ADMIN_HOLDER)) {
                    //  return packet;
                } else if (mode.equals(Mode.UPDATE_URL)) {
                    String url = XmlUtils.readString(doc, "url");
                    defaultPacketProcessor.broadcastChangeURL(packet, url, roomName);
                } else if (mode.equals(Mode.BROADCAST_IMAGE_DATA)) {
                    String imageData = XmlUtils.readString(doc, "image-data");
                    defaultPacketProcessor.broadcastImageData(packet, imageData, roomName);
                } //this should all be covered by SET_ACCESS
                /* else if (mode.equals(Mode.GIVE_MIC)) {
                String recipientUsername = XmlUtils.readString(doc, "recipient-username");
                String recipientName = XmlUtils.readString(doc, "recipient-names");
                String permissions = XmlUtils.readString(doc, "permissions");
                Map user = roomResourceManager.getUserInfo(recipientUsername);
                roomResourceManager.updateOnlineUser(recipientUsername, 1);
                roomResourceManager.updateUserPermissions(recipientUsername, permissions);
                defaultPacketProcessor.broadcastGiveMicPacket(packet, recipientUsername, (String) (user.get("room_name")));

                }else if (mode.equals(Mode.REQUEST_MIC_REPLY)) {
                //user asks for mic, which gets forwarded to the channel owner
                //this is the reply, but GIVE_MIC is sent if the request is granted
                //so this code only really gets run when the request is denied.
                //Suggest change the mode to MIC_REQUEST_DENY
                String username = XmlUtils.readString(doc, "username");
                int response = XmlUtils.readInt(doc, "response");
                if (response == 0) {
                Map user = roomResourceManager.getUserInfo(username);
                roomResourceManager.updateOnlineUser(username, 0);
                defaultPacketProcessor.warnUser(packet, username, "Your request for the MIC has been denied by the room owner, try again in a few minutes.");
                }

                }*/ else if (mode.equals(Mode.SCREEN_SHARE_INVITE)) {
                    String instructor = XmlUtils.readString(doc, "instructor");
                    defaultPacketProcessor.broadScreenInvite(packet, instructor, roomName);
                } else if (mode.equals(Mode.MODIFIED_TEXT_BROADCAST)) {
                    return packet;
                } else if (mode.equals(Mode.UPDATE_TEXT_ITEM)) {
                    defaultPacketProcessor.broadcastModifiedTextItem(packet);
                } else if (mode.equals(Mode.CHANGE_ACCESS)) {
                    return defaultPacketProcessor.changeAccess(packet, doc);
                } else if (mode.equals(Mode.BROADCAST_OUT_SLIDE)) {
                    return packet;
                } else if (mode.equals(Mode.BROADCAST_IN_SLIDE)) {
                    slideshowProcessor.broadcastSlide(packet, doc);
                } else if (mode.equals(Mode.DOWNLOAD_ROOM_SLIDE_SHOW)) {
                    return slideshowProcessor.downloadSlideShowFile(packet, doc);
                } else if (mode.equals(Mode.CLEAR_LAST_SESSION)) {
                    roomResourceManager.clearLastSession(roomName);
                } else if (mode.equals(Mode.REQUEST_ROOM_RESOURCES)) {

                    return roomResourceManager.getRoomResources(packet, roomName);
                } else if (mode.equals(Mode.DELETE_ROOM_RESOURCE)) {

                    String serverFilePath = XmlUtils.readString(doc, "server-file-path");
                    String localFilePath = XmlUtils.readString(doc, "local-file-path");
                    return roomResourceManager.deleteRoomResource(packet, serverFilePath, localFilePath);
                } else if (mode.equals(Mode.ADD_SLIDE_SHOW_CLASS_RESOURCE)) {
                    String filePath = XmlUtils.readString(doc, "file-path");
                    roomResourceManager.addSlideShowAsRoomResource(packet, roomName, filePath);
                } else if (mode.equals(Mode.SAVE_SLIDE_SHOW)) {
                    return slideshowProcessor.saveSlideShowFile(packet, doc, roomName);
                } else if (mode.equals(Mode.POSTED_ANSWER)) {
                    return packet;
                } else if (mode.equals(Mode.POST_ANSWER)) {
                    questionProcessor.saveAndForwardAnswer(packet, doc);
                } else if (mode.equals(Mode.OPEN_SLIDE_SHOW_QUESTION)) {
                    return questionProcessor.openQuestionFile(packet, doc, "slide-show");
                } else if (mode.equals(Mode.OPEN_SLIDE_SHOW)) {
                    return slideshowProcessor.openSlideShowFile(packet, doc);

                } else if (mode.equals(Mode.OPEN_QUESTION)) {
                    return questionProcessor.openQuestionFile(packet, doc);
                } else if (mode.equals(Mode.BROADCAST_QUESTION)) {
                    return packet;
                } else if (mode.equals(Mode.POST_QUESTION)) {
                    questionProcessor.broadQuestion(packet, doc, roomName);
                } else if (mode.equals(Mode.SAVE_QUESTION)) {
                    return questionProcessor.saveQuestionFile(packet, doc);
                } else if (mode.equals(Mode.UPLOAD_IMAGE)) {
                    return defaultPacketProcessor.saveRealtimeImageFile(packet, doc);

                } else if (mode.equals(Mode.REQUEST_SLIDE_QUESTION_FILE_VIEW)) {
                    String fileType = XmlUtils.readString(doc, "file-type");
                    String username = XmlUtils.readString(doc, "username");
                    return defaultPacketProcessor.getFileView(packet, fileType, username, "slide-question");
                } else if (mode.equals(Mode.BROADCAST_WB_ITEM)) {
                    defaultPacketProcessor.broadcastItem(packet);
                } else if (mode.equals(Mode.ITEM_BROADCAST_FROM_SERVER)) {
                    return packet;
                } else if (mode.equals(Mode.REQUEST_FILE_VIEW)) {
                    String fileType = XmlUtils.readString(doc, "file-type");
                    String username = XmlUtils.readString(doc, "username");
                    return defaultPacketProcessor.getFileView(packet, fileType, username, roomName);
                } else if (mode.equals(Mode.DOWNLOAD_QUESTION_IMAGE)) {
                    String imagePath = XmlUtils.readString(doc, "image-path");
                    return defaultPacketProcessor.getRealtimeImageFile(packet, imagePath, "question");
                } else if (mode.equals(Mode.DOWNLOAD_SLIDE_SHOW_IMAGE)) {
                    String imagePath = XmlUtils.readString(doc, "image-path");
                    return defaultPacketProcessor.getRealtimeImageFile(packet, imagePath, "slide-show");

                } else if (mode.equals(Mode.BROADCAST_IMAGE)) {
                    String imagePath = XmlUtils.readString(doc, "image-path");
                    defaultPacketProcessor.broadcastImage(packet, imagePath, roomName);
                } else if (mode.equals(Mode.DELETE_ITEM_BROADCAST)) {
                    return packet;
                } else if (mode.equals(Mode.RESIZE_ITEM_BROADCAST)) {
                    return packet;
                } else if (mode.equals(Mode.TRANSFORMED_ITEM_BROADCAST)) {
                    return packet;
                } else if (mode.equals(Mode.UPDATE_ITEM_POSITION)) {
                    String id = XmlUtils.readString(doc, "item-id");
                    String type = XmlUtils.readString(doc, "item-type");

                    if (type.equals("line")) {
                        double x1 = XmlUtils.readDouble(doc, "x1");
                        double y1 = XmlUtils.readDouble(doc, "y1");
                        double x2 = XmlUtils.readDouble(doc, "x2");
                        double y2 = XmlUtils.readDouble(doc, "y2");

                        defaultPacketProcessor.broadcastItemPosition(packet,
                                x1, y1, x2, y2, id, type, roomName);
                    } else {
                        double x = XmlUtils.readDouble(doc, "x");
                        double y = XmlUtils.readDouble(doc, "y");
                        defaultPacketProcessor.broadcastItemPosition(packet, x, y, id, type, roomName);
                    }
                } else if (mode.equals(Mode.RESIZE_ITEM)) {
                    String id = XmlUtils.readString(doc, "item-id");
                    String type = XmlUtils.readString(doc, "item-type");

                    if (type.equals("line")) {
                        double x1 = XmlUtils.readDouble(doc, "x1");
                        double y1 = XmlUtils.readDouble(doc, "y1");
                        double x2 = XmlUtils.readDouble(doc, "x2");
                        double y2 = XmlUtils.readDouble(doc, "y2");

                        defaultPacketProcessor.broadcastItemPosition(packet, x1,
                                y1, x2, y2, id, type, roomName);
                    } else {
                        double x = XmlUtils.readDouble(doc, "x");
                        double y = XmlUtils.readDouble(doc, "y");
                        String r_type = XmlUtils.readString(doc, "r-type");
                        defaultPacketProcessor.broadcastResizedItem(packet, id, x, y, r_type, roomName);
                    }
                } else if (mode.equals(Mode.DELETE_ITEM)) {
                    String id = XmlUtils.readString(doc, "item-id");

                    defaultPacketProcessor.broadcastItemToBeDeleted(packet, id, roomName);
                } else if (mode.equals(Mode.WB_IMAGE_BROADCAST)) {
                    return packet;
                } else if (mode.equals(Mode.POINTER_BROADCAST)) {
                    return packet;
                } else if (mode.equals(Mode.REQUEST_USER_LIST)) {

                    return roomProcessor.getUsers(packet, mode);
                } else if (mode.equals(Mode.REQUEST_USER_PROPERTIES)) {

                    return roomProcessor.getUsers(packet, mode);
                } else if (mode.equals(Mode.UPDATE_USER_PROPERTIES)) {
                    roomProcessor.updateUserProperties(packet);
                } else if (mode.equals(Mode.CREATE_ROOM)) {
                    return roomProcessor.initCreateRoom(packet, doc);
                } else if (mode.equals(Mode.REQUEST_ADMIN_LIST)) {
                    return roomProcessor.getAdmins(packet);
                } else if (mode.equals(Mode.REQUEST_ROOM_LIST)) {
                    String roomOwner = XmlUtils.readString(doc, "room-owner");
                    return roomResourceManager.getRoomList(packet, roomOwner);
                } else if (mode.equals(Mode.REQUEST_ROOM_MEMBERS)) {

                    return roomResourceManager.getRoomMemberList(packet, roomName);
                }


            } catch (Exception ex) {
                ex.printStackTrace();
            }

            return null;
        }
    }

    public RoomResourceManager getRoomResourceManager() {
        return roomResourceManager;
    }

    public PacketRouter getPacketRouter() {
        return packetRouter;
    }

    public String getDomain() {
        return domain;
    }

    public DefaultPacketProcessor getDefaultPacketProcessor() {
        return defaultPacketProcessor;
    }

    public QuestionProcessor getQuestionProcessor() {
        return questionProcessor;
    }

    public SlideShowProcessor getSlideshowProcessor() {
        return slideshowProcessor;
    }

    public IQRouter getIqRouter() {
        return iqRouter;
    }

    class RPresenceListener implements PresenceEventListener {

        public void availableSession(ClientSession cl, Presence pr) {
        }

        public void presenceChanged(ClientSession cl, Presence pr) {
        }

        public void subscribedToPresence(JID arg0, JID arg1) {
        }

        public void unavailableSession(ClientSession arg0, Presence pr) {
        }

        public void unsubscribedToPresence(JID arg0, JID arg1) {
        }
    }
}
