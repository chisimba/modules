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
package org.avoir.realtime.net;

import java.awt.Color;
import java.awt.Dimension;
import java.awt.Toolkit;
import java.io.ByteArrayInputStream;
import java.io.File;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.Locale;
import java.util.Map;
import javax.swing.ImageIcon;
import javax.swing.JOptionPane;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.RealtimeFile;
import org.avoir.realtime.common.Constants.Dialogs;
import org.avoir.realtime.common.filetransfer.FileManager;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.common.util.RealtimePacketContent;
import org.avoir.realtime.common.util.XmlUtils;
import org.avoir.realtime.gui.QuestionNavigator;
import org.avoir.realtime.gui.RealtimeFileChooser;
import org.avoir.realtime.gui.SlidesNavigator;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.Main;
import org.avoir.realtime.gui.main.MainFrame;
import org.avoir.realtime.gui.main.WebPresentManager;
import org.avoir.realtime.gui.whiteboard.items.Item;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.avoir.realtime.privatechat.*;

import org.avoir.realtime.net.packets.RealtimeQuestionPacket;
import org.avoir.realtime.net.packets.RealtimeSlideShowPacket;
import org.avoir.realtime.net.providers.BroadcastItem;
import org.avoir.realtime.net.providers.DownloadedImage;
import org.avoir.realtime.net.providers.Pointer;
import org.avoir.realtime.net.providers.QuestionProcessor;
import org.avoir.realtime.net.providers.RealtimePacketProcessor;
import org.avoir.realtime.net.providers.SlideShowProcessor;
import org.avoir.realtime.questions.QuestionFrame;
import org.avoir.realtime.slidebuilder.SlideBuilderManager;
import org.jivesoftware.smack.PacketListener;
import org.jivesoftware.smack.packet.Message;
import org.jivesoftware.smack.packet.Packet;
import org.jivesoftware.smack.packet.Presence;
import org.jivesoftware.smackx.packet.DelayInformation;

import org.jivesoftware.smackx.packet.MUCUser;
import org.jivesoftware.smackx.packet.MUCUser.Destroy;
import org.w3c.dom.Document;

/**
 * This class is responsible for handling all the packets coming in from the server
 * @author david wafula
 */
public class RPacketListener implements PacketListener {

    public static DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();
    public static DocumentBuilder documentBuilder;
    private static ArrayList<FileChooserListener> fileChooserListeners = new ArrayList<FileChooserListener>();
    private static ArrayList<SlidesNavigator> slidesNavigatorListeners = new ArrayList<SlidesNavigator>();
    private static ArrayList<QuestionNavigator> qnNavigatorListeners = new ArrayList<QuestionNavigator>();
    private static ArrayList<org.avoir.realtime.gui.AnswerNavigator> anNavigatorListeners = new ArrayList<org.avoir.realtime.gui.AnswerNavigator>();
    private static ArrayList<QuestionFrameListener> questionImageDownloadListeners = new ArrayList<QuestionFrameListener>();
    private static ArrayList<SlideShowListener> slideShowListeners = new ArrayList<SlideShowListener>();
    private static RPacketListener instance;
    private static SimpleDateFormat formatter;
    private static Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();

    public RPacketListener() {
        try {
            instance = this;
            documentBuilder = documentBuilderFactory.newDocumentBuilder();
            formatter = new SimpleDateFormat("H:mm", new Locale("en", "US"));

        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    public static void addFileChooserListener(RealtimeFileChooser fc) {
        fileChooserListeners.add(fc);
    }

    public static void removeFileChooserListener(RealtimeFileChooser fc) {
        fileChooserListeners.remove(fc);
    }

    public static void addSlideShowListener(SlideBuilderManager sbm) {
        slideShowListeners.add(sbm);
    }

    public static void removeSlideShowListener(SlideBuilderManager sbm) {
        slideShowListeners.remove(sbm);
    }

    public static void addSlideShowListener(SlidesNavigator nav) {
        slideShowListeners.add(nav);
    }

    public static void removeSlideShowListener(SlidesNavigator nav) {
        slideShowListeners.remove(nav);
    }

    public static void addSlidesNavigatorFileVewListener(SlidesNavigator nav) {
        slidesNavigatorListeners.add(nav);
    }

    public static void removeNavigatorFileVewListener(SlidesNavigator nav) {
        slidesNavigatorListeners.remove(nav);
    }

    public static void addQuestionNavigatorFileVewListener(QuestionNavigator nav) {
        qnNavigatorListeners.add(nav);
    }

    public static void removeQuestionNavigatorFileVewListener(QuestionNavigator nav) {
        qnNavigatorListeners.remove(nav);
    }

    public static void addAnswerNavigatorFileVewListener(org.avoir.realtime.gui.AnswerNavigator nav) {
        anNavigatorListeners.add(nav);
    }

    public static void removeAnswerNavigatorFileVewListener(org.avoir.realtime.gui.AnswerNavigator nav) {
        anNavigatorListeners.remove(nav);
    }

    public static void addQuestionImageDownloadListener(QuestionFrame fr) {
        questionImageDownloadListeners.add(fr);
    }

    public static void removeQuestionImageDownloadListener(QuestionFrame fr) {
        questionImageDownloadListeners.add(fr);
    }

    public void processPacket(Packet packet) {

        if (packet instanceof RealtimePacket) {
            processRealtimePacket((RealtimePacket) packet);
        } else if (packet instanceof Presence) {
            Presence presence = (Presence) packet;

            //update user tree
            //userListTree.processSubscription(presence);
            processPresence(packet);
        } else if (packet instanceof Message) {
            Message message = (Message) packet;

            processMessage(message);

        }

    }

    private static void processRealtimePacket(RealtimePacket packet) {
        String xml = packet.toXML();
        xml = "<rpacket>" + xml + "</rpacket>";

        try {
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xml.getBytes(Constants.PREFERRED_ENCODING)));
            String mode = XmlUtils.readString(doc, "mode");
            if (mode.equals(Mode.USERLIST_ACCESSLEVEL_REPLY)) {
                // ChatRoomManager.doGUIAccessLevel(packet);
            } else if (mode.equals(Mode.USER_PROPERTIES)) {
                ArrayList<Map> users = RealtimePacketProcessor.getUserArrayList(packet.getContent());
                GUIAccessManager.mf.getUserListFrame().setUsers(users);
            } else if (mode.equals(Mode.UPDATED_URL)) {
                String instructor = XmlUtils.readString(doc, "instructor");

                String me = ConnectionManager.getConnection().getUser();

                if (!me.equals(instructor)) {

                    String url = RealtimePacketProcessor.getURL(packet.getContent());
                    GUIAccessManager.mf.updateURL(url);
                } else {
                }
            } else if (mode.equals(Mode.ROOM_MEMBERS)) {
                ArrayList<Map> memberList = RealtimePacketProcessor.getRoomMemberList(packet.getContent());
                GUIAccessManager.mf.getRoomMemberListFrame().setMembers(memberList);
            } else if (mode.equals(Mode.WARN)) {
                String message = RealtimePacketProcessor.getTag(packet.getContent(), "message");
                JOptionPane.showMessageDialog(GUIAccessManager.mf, message, "Warning", JOptionPane.WARNING_MESSAGE);
            } else if (mode.equals(Mode.ROOM_LIST)) {
                ArrayList<Map> roomList = RealtimePacketProcessor.getRoomList(packet.getContent());
                GUIAccessManager.mf.getRoomListFrame().populateRooms(roomList);

            } else if (mode.equals(Mode.FILE_UPLOAD_RESULT)) {
                String content = RealtimePacketProcessor.getTag(packet.getContent(), "message");
                String p = RealtimePacketProcessor.getTag(packet.getContent(), "progress");
                String uploadPath = RealtimePacketProcessor.getTag(packet.getContent(), "upload-path");
                int progress = Integer.parseInt(p.trim());
                FileManager.setUploadNote(content, progress, uploadPath);
            } else if (mode.equals(Mode.SERVER_INFO)) {
                String msg = RealtimePacketProcessor.getTag(packet.getContent(), "msg");
                JOptionPane.showMessageDialog(null, msg);
                System.exit(0);

            } else if (mode.equals(Mode.ROOM_OWNER_REPLY)) {
                ConnectionManager.roomOwner = RealtimePacketProcessor.getTag(packet.getContent(), "room-owner");

            } else if (mode.equals(Mode.REQUEST_MIC_FORWARDED)) {
                String nickname = RealtimePacketProcessor.getTag(packet.getContent(), "name");
                String username = RealtimePacketProcessor.getTag(packet.getContent(), "username");

                int n = JOptionPane.showConfirmDialog(null, nickname + " is requesting for MIC, grant?", "MIC Request", JOptionPane.YES_NO_OPTION);
                if (n == JOptionPane.YES_OPTION) {
                    GUIAccessManager.mf.getUserListPanel().getParticipantListTable().giveMic(username, nickname);
                } else {
                    RealtimePacket p = new RealtimePacket();
                    p.setMode(Mode.REQUEST_MIC_REPLY);
                    RealtimePacketContent realtimePacketContent = new RealtimePacketContent();
                    realtimePacketContent.addTag("username", username);
                    realtimePacketContent.addTag("response", Dialogs.REQUEST_DENIED);
                    p.setContent(realtimePacketContent.toString());
                    ConnectionManager.sendPacket(p);

                }
            } else if (mode.equals(Mode.SET_PERMISSIONS)) {
                String username = RealtimePacketProcessor.getTag(packet.getContent(), "username");
                String permissionString = RealtimePacketProcessor.getTag(packet.getContent(), "permissions");

                GUIAccessManager.mf.getUserListPanel().getParticipantListTable().setUserPermissions(username, permissionString);
            } else if (mode.equals(Mode.PRIVATE_CHAT_FORWARD)) {
                String sender = XmlUtils.readString(doc, "private-chat-sender");
                String senderName = XmlUtils.readString(doc, "private-chat-sender-name");
                String msg = XmlUtils.readString(doc, "private-chat-msg");
                String msgMode = XmlUtils.readString(doc, "private-chat-mode");
                PrivateChatManager.receiveMessage(sender, senderName, msg);

            } else if (mode.equals(Mode.EC2_FLASH_SERVER_READY)) {
                String host = GeneralUtil.getTagText(packet.getContent(), "host");
                saveEC2Urls(host, RealtimePacket.Mode.SAVE_FLASH_EC2_URL);
                ConnectionManager.flashUrlReady = true;
                ConnectionManager.FLASH_URL = host;
                if (ConnectionManager.flashUrlReady) {
                    GUIAccessManager.mf.getUserListPanel().getStartAudioVideoButton().setEnabled(true);
                }
            } else if (mode.equals(Mode.EC2_AUDIO_VIDEO_SERVER_READY)) {
                String host = GeneralUtil.getTagText(packet.getContent(), "host");
                saveEC2Urls(host, RealtimePacket.Mode.SAVE_AUDIO_VIDEO_EC2_URL);
                ConnectionManager.AUDIO_VIDEO_URL = host;
                ConnectionManager.audioVideoUrlReady = true;
                if (ConnectionManager.flashUrlReady) {
                    GUIAccessManager.mf.getUserListPanel().getStartAudioVideoButton().setEnabled(true);
                }

            } else if (mode.equals(Mode.EC2_MAIN_SERVER_READY)) {
                String host = GeneralUtil.getTagText(packet.getContent(), "host");
                String roomname = GeneralUtil.getTagText(packet.getContent(), "roomname");
                doEc2Login(host.trim(), 443, "", roomname);
            } else if (mode.equals(Mode.LAUNCH_EC2_STATUS_MSG)) {
                Main.ec2LauncherTimer.cancel();
                String message = GeneralUtil.getTagText(packet.getContent(), "info");
                EC2Manager.showStatus(message);
            } else if (mode.equals(Mode.POINTER_BROADCAST)) {
                Pointer pointer = RealtimePacketProcessor.getPointer(packet.getContent());
                GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().paintPointer(pointer.getPointer(),
                        pointer.getX(), pointer.getY());
            } else if (mode.equals("slide-image-file-view")) {
                ArrayList<RealtimeFile> fileView = RealtimePacketProcessor.getFileViewArrayList(packet.getContent());

                for (int i = 0; i < fileChooserListeners.size(); i++) {
                    fileChooserListeners.get(i).processFileView(fileView);
                }

            } else if (mode.equals("images-file-view")) {
                ArrayList<RealtimeFile> fileView = RealtimePacketProcessor.getFileViewArrayList(packet.getContent());

                for (int i = 0; i < fileChooserListeners.size(); i++) {
                    fileChooserListeners.get(i).processFileView(fileView);
                }
            } else if (mode.equals(Mode.INVITE_RESULT)) {
                Boolean success = new Boolean(GeneralUtil.getTagText(packet.getContent(), "success"));
                String successMessage = "Invitation was successfull";
                String errorMessage = "There was an error during invitation. The server couldnt not send emails to recipients.\n" +
                        "Try again. If problem persists, contact your system adminstrator.";
                // JOptionPane.showMessageDialog(null, success ? successMessage : errorMessage);
            } else if (mode.equals(Mode.SLIDES_COUNT)) {
                SlideShowProcessor.initProgressMonitorIfNecessary(packet.getContent());
            } else if (mode.equals(Mode.SCREEN_SHARE_INVITE_FROM_SERVER)) {

                String instructor = XmlUtils.readString(doc, "instructor");
                String me = ConnectionManager.getUsername();
                if (!me.equals(instructor)) {
                    GUIAccessManager.mf.getWebbrowserManager().showScreenShareViewer(800, 600,
                            "Desktop from " + packet.getFrom(), true);
                }
            } else if (mode.equals(Mode.DOWNLOAD_WEBPRESENT_SLIDES)) {
                SlideShowProcessor.saveWebPresentSlide(packet.getContent());
            } else if (mode.equals(Mode.OPEN_SLIDE_SHOW_QUESTION)) {
                RealtimeQuestionPacket qn = QuestionProcessor.getRealtimeQuestionPacket(packet.getContent());
                for (int i = 0; i < slideShowListeners.size(); i++) {
                    slideShowListeners.get(i).processSlideShowQuestion(qn);
                }
            } else if (mode.equals(Mode.BROADCAST_OUT_SLIDE)) {
                SlideShowProcessor.updateSlideContent(packet.getContent());
            } else if (mode.equals(Mode.OPEN_SLIDE_SHOW)) {
                RealtimeSlideShowPacket ssp = SlideShowProcessor.getRealtimeSlideShowPacket(packet.getContent());
                for (int i = 0; i < slideShowListeners.size(); i++) {
                    slideShowListeners.get(i).openSlideShow(ssp);
                }
            } else if (mode.equals(Mode.OPEN_QUESTION)) {
                RealtimeQuestionPacket qn = QuestionProcessor.getRealtimeQuestionPacket(packet.getContent());
                for (int i = 0; i < qnNavigatorListeners.size(); i++) {
                    qnNavigatorListeners.get(i).showQuestionFrame(qn);
                }
            } else if (mode.equals("slide-question-file-view")) {
                ArrayList<RealtimeFile> fileView = RealtimePacketProcessor.getFileViewArrayList(packet.getContent());
                for (int i = 0; i < fileChooserListeners.size(); i++) {
                    fileChooserListeners.get(i).processFileView(fileView);
                }
            } else if (mode.equals(Mode.DOWNLOAD_ROOM_SLIDE_SHOW)) {
                SlideShowProcessor.saveSlideShow(packet.getContent());
            } else if (mode.equals(Mode.SLIDE_SHOW_CHANGES)) {
                SlideShowProcessor.saveSlideShow(packet.getContent());
            } else if (mode.equals(Mode.CHANGED_TAB_INDEX)) {
                int index = RealtimePacketProcessor.getTabIndex(packet.getContent());
                GUIAccessManager.mf.getTabbedPane().setSelectedIndex(index);
            } else if (mode.equals(Mode.DELETE_ROOM_RESOURCE_SUCCESS)) {
                SlideShowProcessor.deleteLocalSlide(packet.getContent());
            } else if (mode.equals(org.avoir.realtime.net.Mode.ROOM_RESOURCE_LIST)) {
                ArrayList<Map> fileView = RealtimePacketProcessor.getRoomResourcesAsArrayList(packet.getContent());
                GUIAccessManager.mf.getRoomResourcesList().setRoomResources(fileView);
            } else if (mode.equals(org.avoir.realtime.net.Mode.ROOM_RESOURCES)) {

                ArrayList<RealtimeFile> fileView = RealtimePacketProcessor.getFileViewArrayList(packet.getContent(), true);

                String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;

                for (int i = 0; i < fileView.size(); i++) {
                    RealtimeFile rFile = fileView.get(i);
                    String local = resourceDir + "/" + rFile.getFileName();
                    local = GeneralUtil.removeExt(local);
                    int currentVer = rFile.getVersion();
                    boolean needsUpdate = false;
                    try {
                        String versionDir = resourceDir + "/" + rFile.getFileName();
                        versionDir = GeneralUtil.removeExt(versionDir);
                        String versionStr = GeneralUtil.readTextFile((versionDir + "/version.txt").trim());
                        int oldVersion = Integer.parseInt(versionStr.trim());

                        if (oldVersion < currentVer) {
                            needsUpdate = true;
                            System.out.println("Existing Version " + oldVersion + ", Server Version " + currentVer);

                        }
                    } catch (Exception ex) {
                        //    ex.printStackTrace();
                        needsUpdate = true;
                    }
                    if (needsUpdate) {
                        deleteLocal(new File(local));
                    }

                    if (!new File(local).exists() || needsUpdate) {
                        System.out.println(local + " not found or new version required, requesting for download ..");

                        WebPresentManager.slidesDir = GeneralUtil.removeExt(rFile.getFilePath());
                        WebPresentManager.presentationName = new File(GeneralUtil.removeExt(rFile.getFilePath())).getName();
                        WebPresentManager.presentationId = new File(GeneralUtil.removeExt(rFile.getFilePath())).getName();
                        GUIAccessManager.mf.getChatRoomManager().requestNewSlides(GeneralUtil.getExt(rFile.getFilePath()));
                    } else {
                        System.out.println(local + " exists, is uptodate, ignoring ..");

                    }
                }


            } else if (mode.equals("questions-file-view")) {
                ArrayList<RealtimeFile> fileView = RealtimePacketProcessor.getFileViewArrayList(packet.getContent());

                for (int i = 0; i < qnNavigatorListeners.size(); i++) {
                    qnNavigatorListeners.get(i).processFileView(fileView);
                }
            } else if (mode.equals("answers-file-view")) {
                ArrayList<RealtimeFile> fileView = RealtimePacketProcessor.getFileViewArrayList(packet.getContent());

                for (int i = 0; i < anNavigatorListeners.size(); i++) {
                    anNavigatorListeners.get(i).processFileView(fileView);
                }
                System.out.println(packet.getContent());
            } else if (mode.equals("slideshows-file-view")) {
                ArrayList<RealtimeFile> fileView = RealtimePacketProcessor.getFileViewArrayList(packet.getContent());

                for (int i = 0; i < slidesNavigatorListeners.size(); i++) {
                    slidesNavigatorListeners.get(i).processFileView(fileView);
                }
            } else if (mode.equals(Mode.ITEM_BROADCAST_FROM_SERVER)) {
                try {
                    String xmlContent = packet.getContent();

                    Item item = RealtimePacketProcessor.getItem(xmlContent, packet.getPacketID());
                    if (!GeneralUtil.isOwner(packet.getFrom())) {
                        item.setFromAdmin(GeneralUtil.isAdmin(packet.getFrom()));
                    }
                    String xfrom = packet.getFrom();
                    int at = xfrom.indexOf("@");
                    String from = xfrom.substring(0, at);
                    if (!GeneralUtil.isOwner(packet.getFrom())) {
                        item.setFrom(GUIAccessManager.mf.getUserListPanel().getParticipantListTable().getNames(from));
                    }
                    if (item.isNewItem()) {
                        GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().addItem(item);
                    } else {
                        GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().updateItem(item);
                    }
                } catch (Exception ex) {
                    // ex.printStackTrace();
                }
            } else if (mode.equals(Mode.DELETE_ITEM_BROADCAST)) {
                String id = RealtimePacketProcessor.getItemId(packet.getContent());
                GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().deleteItem(id);
            } else if (mode.equals(Mode.WB_IMAGE_BROADCAST)) {
                ImageIcon image = RealtimePacketProcessor.getImage(packet.getContent());
                if (image != null) {
                    GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().addImage(image, packet.getPacketID());
                }
            } else if (mode.equals(Mode.RESIZE_ITEM_BROADCAST)) {
                BroadcastItem item = RealtimePacketProcessor.getItemDim(packet.getContent());

                GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().resizeItem(item.getX(), item.getY(), item.getId(),
                        item.getResizeType());
            } else if (mode.equals(Mode.MODIFIED_TEXT_BROADCAST)) {
                Map<String, String> map = RealtimePacketProcessor.getModifiedTextContent(packet.getContent());
                String content = map.get("text-content");
                String id = map.get("id");
                GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().updateText(content, id);
            } else if (mode.equals(Mode.GIVEN_MIC)) {
                RealtimePacketProcessor.displayVideoMicWindow(packet.getContent());
            } else if (mode.equals(Mode.TAKEN_MIC)) {
                RealtimePacketProcessor.disposeAudioVideoWindow(packet.getContent());
            } else if (mode.equals(Mode.ITEM_HISTORY_FROM_SERVER)) {
                try {
                    String xmlContent = packet.getContent();
                    Item item = RealtimePacketProcessor.getItem(xmlContent);
                    if (item.isNewItem()) {
                        GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().addItem(item);

                    } else {
                        GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().updateItem(item);

                    }
                } catch (Exception ex) {
                    // ex.printStackTrace();
                }
                //end


            } else if (mode.equals(Mode.TRANSFORMED_ITEM_BROADCAST)) {
                BroadcastItem item = RealtimePacketProcessor.getItemCoords(packet.getContent());
                if (item.getType().equals("line")) {
                    GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().updateItemPostion(item.getX(), item.getY(), item.getW(), item.getH(), item.getId());
                } else {
                    GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().updateItemPostion(item.getX(), item.getY(), item.getId());
                }
            } else if (mode.equals(Mode.DOWNLOAD_QUESTION_IMAGE)) {
                DownloadedImage di = RealtimePacketProcessor.getDownloadedImage(packet.getContent());
                for (int i = 0; i < questionImageDownloadListeners.size(); i++) {
                    questionImageDownloadListeners.get(i).processImage(di);
                }
            } else if (mode.equals(Mode.DOWNLOAD_SLIDE_SHOW_IMAGE)) {
                DownloadedImage di = RealtimePacketProcessor.getDownloadedImage(packet.getContent());
                for (int i = 0; i < slideShowListeners.size(); i++) {
                    slideShowListeners.get(i).processSlideShowImage(di);
                }
            } else if (mode.equals(Mode.ADMIN_LIST)) {
                // ChatRoomManager.doGUIAccessLevel(packet);
            } else if (mode.equals(Mode.BROADCAST_QUESTION)) {
                RealtimeQuestionPacket qn = QuestionProcessor.getRealtimeQuestionPacket(packet.getContent());
                String from = qn.getInstructor();
                String me = ConnectionManager.getConnection().getUser();
                if (me.equals(from)) {
                    JOptionPane.showMessageDialog(null, "Your question has been posted");
                    for (int i = 0; i < qnNavigatorListeners.size(); i++) {
                        qnNavigatorListeners.get(i).enablePostButton();
                    }

                } else {

                    QuestionProcessor.displayQuestionForAnswering(qn.getQuestion(), qn.getQuestionType(), qn.getFilename(),
                            qn.getAnswerOptions(), qn.getImageData(), qn.getUsername(),
                            qn.getInstructor(), true);
                }
            } else if (mode.equals(Mode.POSTED_ANSWER)) {

                QuestionProcessor.extractAnswerOpts(packet.getContent());
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }

    }

    private static void doEc2Login(String host, int port, String audioVideoUrl, String roomName) {
        Main.ec2LauncherTimer.cancel();
        ConnectionManager.oldConnection = ConnectionManager.getConnection();
        boolean connected = false;
        int maxTries = 100;
        int tryCount = 0;
        EC2Manager.showStatus("Logging in ...");
        if (ConnectionManager.useEC2) {
            if (roomName == null) {
                roomName = ConnectionManager.getRoomName();
            }
            while (tryCount < maxTries) {
                if (ConnectionManager.init(host, port, audioVideoUrl)) {

                    ConnectionManager.createUser(ConnectionManager.getUsername(), "--LDAP--", ConnectionManager.userProps);
                    if (ConnectionManager.login(ConnectionManager.getUsername(), "--LDAP--", ConnectionManager.getUsername())) {

                        saveEC2Urls(host, RealtimePacket.Mode.SAVE_MAIN_EC2_URL);
                        connected = true;

                        MainFrame mf = new MainFrame(roomName);
                        mf.setSize(Toolkit.getDefaultToolkit().getScreenSize());
                        mf.setVisible(true);
                        EC2Manager.dispose();
                        break;
                    } else {

                        JOptionPane.showMessageDialog(null, "Internal Error occured. Cannot connect to server.\n" +
                                "Contact your system adminstrator");
                        System.exit(0);
                    }
                }
                try {
                    Thread.sleep(2000);
                } catch (Exception ex) {
                }
                EC2Manager.updateConnectionStatus(tryCount);
                tryCount++;
                System.out.println("Retrying " + tryCount + " of " + maxTries);


                if (tryCount > maxTries || connected) {
                    break;
                }
            }
        } else {
            MainFrame mf = new MainFrame(roomName);
            mf.setSize(Toolkit.getDefaultToolkit().getScreenSize());
            mf.setVisible(true);
            EC2Manager.dispose();
        }
    }

    private static void deleteLocal(File f) {
        if (f.exists()) {
            String[] contents = f.list();
            for (int i = 0; i < contents.length; i++) {
                new File(f.getAbsoluteFile() + "/" + contents[i]).delete();
            }
            f.delete();
        }
    }

    private static void processMessage(Message message) {

        DelayInformation inf = (DelayInformation) message.getExtension("x", "jabber:x:delay");
        Date sentDate;
        if (inf != null) {
            sentDate = inf.getStamp();
        } else {
            sentDate = new Date();
        }
        //first see what type is it
        String type = (String) message.getProperty("message-type");
        Color color = (Color) message.getProperty("color");
        if (color == null) {
            color = Color.BLACK;
        }
        int size = (Integer) message.getProperty("size");
        if (size == 0) {
            size = 17;
        }
        if (type == null) {
            String from = message.getFrom();

            String host = ConnectionManager.getConnection().getHost();
            if (from.equals(host)) {
                return;
            }
            int index = from.lastIndexOf("/");
            if (index > -1) {
                from = from.substring(index + 1);
            }
            from = from.split(":")[1];
            if (from.length() > 18) {
                from = from.substring(0, 18) + "..";
            }
            if (!GUIAccessManager.mf.isActive()) {

                GeneralUtil.showChatPopup(from, message.getBody(), true);
            }
            /*  try {

            GUIAccessManager.mf.setIconImage(GUIAccessManager.mf.getIcon("alert").getImage());
            } catch (Exception ex) {
            ex.printStackTrace();
            }
            }*/
            GUIAccessManager.mf.getChatRoomManager().getChatRoom().insertParticipantName("(" + from + ") ");
            GUIAccessManager.mf.getChatRoomManager().getChatRoom().insertParticipantMessage(from, message.getBody(), size, color);

        } else {
            if (type.equals("sys-text")) {
                GUIAccessManager.mf.getChatRoomManager().getChatRoom().insertSystemMessage(message.getBody());
            } else if (type.equals("sys-participant-cleanup")) {
                GUIAccessManager.mf.removeAllSpeakers();
            } else if (type.equals("stop-screen-share")) {
                GUIAccessManager.mf.getWebbrowserManager().closeScreenShareViewer();
            } else {
                WhiteboardMessageProcessor.processCustomMessage(message);
            }
        }
    }

    private static void saveEC2Urls(String host, String mode) {
        StringBuilder sb = new StringBuilder();
        sb.append("<host>").append(host).append("</host>");
        sb.append("<username>").append(ConnectionManager.getUsername()).append("</username>");
        sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
        RealtimePacket p = new RealtimePacket(mode);
        p.setContent(sb.toString());
        if (ConnectionManager.oldConnection != null) {
            try {
                ConnectionManager.oldConnection.sendPacket(p);
            } catch (Exception ex) {
                ex.printStackTrace();

            }
        } else {
            ConnectionManager.sendPacket(p);
        }
    }

    private static void processPresence(Packet packet) {
        Presence presence = (Presence) packet;
        if (presence.getError() != null) {
            return;
        }

        String from = presence.getFrom();
        int index = from.lastIndexOf("/");
        if (index > -1) {
            from = from.substring(index + 1);
        }
        MUCUser mucUser = (MUCUser) packet.getExtension("x", "http://jabber.org/protocol/muc#user");

        String code = "";
        if (mucUser != null) {
            code = mucUser.getStatus() != null ? mucUser.getStatus().getCode() : "";
            Destroy destroy = mucUser.getDestroy();
            if (destroy != null) {
                String reason = destroy.getReason();
                JOptionPane.showMessageDialog(null, "Room Destroyed: " + reason, "Room Destroyed", JOptionPane.INFORMATION_MESSAGE);
                return;
            }
        }

        String username = from.split(":")[0];
        String names = from.split(":")[1];
        if (presence.getType() == Presence.Type.unavailable && !"303".equals(code)) {
            GUIAccessManager.mf.getUserListPanel().getParticipantListTable().removeUser(from);
            GUIAccessManager.mf.removeSpeaker(username);
            GUIAccessManager.mf.getChatRoomManager().getChatRoom().insertSystemMessage(names + " left the room\n");
        } else {

            GUIAccessManager.mf.getChatRoomManager().getChatRoom().insertSystemMessage(names + " joined the room\n");
            GUIAccessManager.mf.getUserListPanel().getParticipantListTable().addUser(from);

        }
    }
}
