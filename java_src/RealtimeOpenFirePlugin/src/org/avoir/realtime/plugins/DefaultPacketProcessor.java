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

import java.io.BufferedWriter;
import java.io.ByteArrayInputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.Writer;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;
import java.util.ArrayList;

import javax.swing.JOptionPane;
import javax.swing.JTextArea;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.dom4j.DocumentHelper;
import org.dom4j.Element;
import org.dom4j.QName;

import org.jivesoftware.openfire.PacketRouter;
import org.jivesoftware.openfire.XMPPServer;
import org.jivesoftware.util.Base64;
import org.w3c.dom.Document;
import org.xmpp.packet.IQ;
import org.xmpp.packet.JID;

/**
 *
 * @author developer
 */
public class DefaultPacketProcessor {

  private AvoirRealtimePlugin pl;
  private PacketRouter packetRouter;

  public DefaultPacketProcessor(AvoirRealtimePlugin pl) {
    this.pl = pl;
    packetRouter = pl.getPacketRouter();
  }

  /**
   * open notepad. it contains the serialized
   *
   * @param packet
   * @param xdoc
   * @return
   */
  public IQ openNotepadFile(IQ packet, Document xdoc) {
    String fileName = XmlUtils.readString(xdoc, "filename");
    if (!fileName.endsWith(".np")) {
      fileName += ".np";
    }
    String username = XmlUtils.readString(xdoc, "username");
    IQ replyPacket = IQ.createResultIQ(packet);
    String xmlContents = Util.getContents(new File(Constants.FILES_DIR + "/notepads/" + username + "/" + fileName));
    DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();

    try {
      DocumentBuilder documentBuilder = documentBuilderFactory.newDocumentBuilder();
      Document doc = documentBuilder.parse(
          new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));

      String filename = XmlUtils.readString(doc, "filename");
      String content = XmlUtils.readString(doc, "file-content");
      Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
      queryResult.addElement("packet-type").addText("notepad-open");
      queryResult.addElement("filename").addText(filename);
      queryResult.addElement("file-content").addText(content);

      replyPacket.setChildElement(queryResult);

    } catch (Exception ex) {
      ex.printStackTrace();
    }


    return replyPacket;
  }

  public void forwardPrivateChatToReceiver(IQ packet, String receiver, String sender, String msg) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.PRIVATE_CHAT_FORWARD);
    StringBuilder sb = new StringBuilder();
    sb.append("<private-chat-sender>").append(sender).append("</private-chat-sender>");
    sb.append("<private-chat-receiver>").append(receiver).append("</private-chat-receiver>");
    sb.append("<private-chat-msg>").append(msg).append("</private-chat-msg>");
    sb.append("<private-chat-mode>").append("forward").append("</private-chat-mode>");
    queryResult.addElement("content").addText(sb.toString());
    replyPacket.setChildElement(queryResult);
    XMPPServer server = XMPPServer.getInstance();
    replyPacket.setTo(server.createJID(receiver, pl.getResoureName()));
    packetRouter.route(replyPacket);

    replyPacket.setTo(server.createJID(sender, pl.getResoureName()));
    packetRouter.route(replyPacket);
  }
  /*
    public void returnPrivateChatToSender(IQ packet, String receiver, String sender, String msg) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.PRIVATE_CHAT_FORWARD);
    StringBuilder sb = new StringBuilder();
    sb.append("<private-chat-sender>").append(receiver).append("</private-chat-sender>");
    sb.append("<private-chat-receiver>").append(sender).append("</private-chat-receiver>");
    sb.append("<private-chat-msg>").append(msg).append("</private-chat-msg>");
    sb.append("<private-chat-mode>").append("return").append("</private-chat-mode>");
    queryResult.addElement("content").addText(sb.toString());
    replyPacket.setChildElement(queryResult);
    XMPPServer server = XMPPServer.getInstance();
    replyPacket.setTo(server.createJID(sender, pl.getResoureName()));
    packetRouter.route(replyPacket);

    }*/

  public void forwardMICRequest(IQ packet, String roomOwner, String requester, String requesterName) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.REQUEST_MIC_FORWARDED);
    StringBuilder sb = new StringBuilder();
    sb.append("<username>").append(requester).append("</username>");
    sb.append("<name>").append(requesterName).append("</name>");
    queryResult.addElement("content").addText(sb.toString());
    replyPacket.setChildElement(queryResult);
    XMPPServer server = XMPPServer.getInstance();
    replyPacket.setTo(server.createJID(roomOwner, pl.getResoureName()));
    packetRouter.route(replyPacket);
  }

  public void warnUser(IQ packet, String username, String message) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.WARN);
    StringBuilder sb = new StringBuilder();
    sb.append("<message>").append(message).append("</message>");
    queryResult.addElement("content").addText(sb.toString());
    replyPacket.setChildElement(queryResult);
    XMPPServer server = XMPPServer.getInstance();
    replyPacket.setTo(server.createJID(username, pl.getResoureName()));
    packetRouter.route(replyPacket);
  }

  public void sendServerDetails(IQ packet, String mode, String host, String roomName) {

    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(mode);
    StringBuilder sb = new StringBuilder();
    sb.append("<host>").append(host).append("</host>");
    sb.append("<roomname>").append(roomName).append("</roomname>");
    queryResult.addElement("content").addText(sb.toString());
    replyPacket.setChildElement(queryResult);
    replyPacket.setTo(packet.getFrom());
    packetRouter.route(replyPacket);

  }

  public IQ sendInfo(IQ packet, String msg) {

    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.SERVER_INFO);
    StringBuilder sb = new StringBuilder();
    sb.append("<msg>").append(msg).append("</msg>");
    queryResult.addElement("content").addText(sb.toString());
    replyPacket.setChildElement(queryResult);
    replyPacket.setTo(packet.getFrom());
    return replyPacket;

  }

  /**
   * save the notepad
   * @param packet
   * @param doc
   * @return
   */
  public IQ saveNotepadFile(IQ packet, Document doc) {
    String fileName = XmlUtils.readString(doc, "filename");
    String userName = XmlUtils.readString(doc, "username");
    new File(Constants.FILES_DIR + "/notepads/" + userName).mkdirs();
    String contents = packet.toXML();
    Writer output = null;
    try {
      output = new BufferedWriter(new FileWriter(Constants.FILES_DIR + "/notepads/" + userName + "/" + fileName + ".np"));
      output.write(contents);
    } catch (IOException ex) {
      ex.printStackTrace();
    } finally {
      try {
        output.close();
      } catch (Exception ex) {
        ex.printStackTrace();
      }
    }

    return null;
  }

  /**
   * save the image and return the updasted fileview
   * @param packet
   * @param filePath
   * @param doc
   * @return
   */
  public IQ saveRealtimeImageFile(IQ packet, Document doc) {
    String filename = XmlUtils.readString(doc, "filename");
    String roomName = XmlUtils.readString(doc, "room-name");
    String path = Constants.FILES_DIR + "/images/" + filename;
    String username = XmlUtils.readString(doc, "username");
    new File(Constants.FILES_DIR + "/images/" + username).mkdirs();
    path = Constants.FILES_DIR + "/images/" + username + "/" + filename;

    String imageData = XmlUtils.readString(doc, "image-data");

    try {
      FileChannel fc =
        new FileOutputStream(path).getChannel();
      byte[] byteArray = Base64.decode(imageData);
      fc.write(ByteBuffer.wrap(byteArray));
      fc.close();

    } catch (Exception ex) {
      ex.printStackTrace();
    }
    return getFileView(packet, "images", username, roomName);
  }

  public IQ getRealtimeImageFile(IQ packet, String imagePath, String type) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText("download-" + type + "-image");
    String imageData = Base64.encodeFromFile(imagePath);
    StringBuilder sb = new StringBuilder();
    sb.append("<image-data>").append(imageData).append("</image-data>");
    sb.append("<image-path>").append(imagePath).append("</image-path>");
    queryResult.addElement("content").addText(sb.toString());
    replyPacket.setChildElement(queryResult);
    return replyPacket;
  }

  /**
   * Changes file access between private and public
   * @param packet
   * @param doc
   * @return
   */
  public IQ changeAccess(IQ packet, Document doc) {
    String username = XmlUtils.readString(doc, "username");
    String newAccessType = XmlUtils.readString(doc, "access");
    String fileType = XmlUtils.readString(doc, "file-type");
    String fileName = XmlUtils.readString(doc, "filename");
    String roomName = XmlUtils.readString(doc, "room-name");
    String oldFilePath = ".";
    String newFilePath = ".";

    if (newAccessType.equals("private")) {
      oldFilePath = Constants.FILES_DIR + "/" + fileType + "/" + fileName;
      newFilePath = Constants.FILES_DIR + "/" + fileType + "/" + username + "/" + fileName;
      new File(Constants.FILES_DIR + "/" + fileType + "/" + username).mkdirs();
    }
    if (newAccessType.equals("public")) {
      oldFilePath = Constants.FILES_DIR + "/" + fileType + "/" + username + "/" + fileName;
      newFilePath = Constants.FILES_DIR + "/" + fileType + "/" + fileName;

    }
    //then do the moving
    File oldFile = new File(oldFilePath);
    File newFile = new File(newFilePath);
    oldFile.renameTo(newFile);
    pl.getRoomResourceManager().updateSlideShowRoomResourcePath(oldFilePath, newFilePath);
    //update view
    return getFileView(packet, fileType, username, roomName);
  }

  private String constructFileView(String fileType, String username, String roomName) {
    if (fileType.equals("slideshows")) {
      return pl.getRoomResourceManager().getAllSlideShows(roomName);
    }
    StringBuilder sb = new StringBuilder();
    File privateFileDir = new File(Constants.FILES_DIR + "/" + fileType + "/" + username + "/");
    String[] privateList = privateFileDir.list();
    sb.append("<fileview>");
    if (privateList != null) {

      for (int i = 0; i < privateList.length; i++) {
        String path = Constants.FILES_DIR + "/" + fileType + "/" + username + "/" + privateList[i];
        File file = new File(path);
        sb.append("<file>");
        sb.append("<file-name>").append(file.getName()).append("</file-name>");
        sb.append("<file-path>").append(path).append("</file-path>");
        sb.append("<is-directory>").append(file.isDirectory() + "").append("</is-directory>");
        sb.append("<access>").append("private").append("</access>");
        sb.append("</file>");
      }
    }

    File publicFileDir = new File(Constants.FILES_DIR + "/" + fileType + "/");

    String[] publicList = publicFileDir.list();

    if (publicList != null) {
      for (int i = 0; i < publicList.length; i++) {
        File file = new File(Constants.FILES_DIR + "/" + fileType + "/" + publicList[i]);
        if (!file.isDirectory()) { //jump dirs
          sb.append("<file>");
          sb.append("<file-name>").append(file.getName()).append("</file-name>");
          sb.append("<file-path>").append(file.getAbsolutePath()).append("</file-path>");
          sb.append("<is-directory>").append(file.isDirectory()).append("</is-directory>");
          sb.append("<access>").append("public").append("</access>");
          sb.append("</file>");
        }
      }
    }
    sb.append("</fileview>");
    return sb.toString();
  }

  /**
   * this creates a list of files in the specified directory and send sit back to the
   * client. private file and those in public dir
   * @param packet
   * @param dirPath the directory to list
   * @param fileType the type of file: question, slideshow, image
   * @param  username the users idrectory
   * @return
   */
  public IQ getFileView(IQ packet, String fileType, String username, String roomName) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(fileType + "-file-view");
    queryResult.addElement("content").addText(constructFileView(fileType, username, roomName));
    replyPacket.setChildElement(queryResult);

    return replyPacket;
  }

  public IQ getFileView(IQ packet, String fileType, String username, String extra, String roomName) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));

    queryResult.addElement("mode").addText(extra + "-file-view");
    queryResult.addElement("content").addText(constructFileView(fileType, username, roomName));
    replyPacket.setChildElement(queryResult);

    return replyPacket;
  }

  public void broadcastImage(IQ packet, String imagePath, String roomName) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.WB_IMAGE_BROADCAST);
    String imageData = Base64.encodeFromFile(imagePath);
    StringBuilder sb = new StringBuilder();
    sb.append("<image-data>").append(imageData).append("</image-data>");
    queryResult.addElement("content").addText(sb.toString());
    replyPacket.setChildElement(queryResult);
    ArrayList<JID> jids = RUserManager.users.get(roomName);
    for (int i = 0; i < jids.size(); i++) {
      JID jid = jids.get(i);
      replyPacket.setTo(jid);
      replyPacket.setFrom(packet.getFrom());
      packetRouter.route(replyPacket);
    }
  }

  public void broadcastImageData(IQ packet, String imageData, String roomName) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.WB_IMAGE_BROADCAST);
    StringBuilder sb = new StringBuilder();
    sb.append("<image-data>").append(imageData).append("</image-data>");
    queryResult.addElement("content").addText(sb.toString());
    replyPacket.setChildElement(queryResult);
    ArrayList<JID> jids = RUserManager.users.get(roomName);
    for (int i = 0; i < jids.size(); i++) {
      JID jid = jids.get(i);
      replyPacket.setTo(jid);
      replyPacket.setFrom(packet.getFrom());
      packetRouter.route(replyPacket);
    }
  }

  public void broadScreenInvite(IQ packet, String from, String roomName) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.SCREEN_SHARE_INVITE_FROM_SERVER);
    StringBuilder sb = new StringBuilder();
    sb.append("<instructor>").append(from).append("</instructor>");
    queryResult.addElement("content").addText(sb.toString());
    replyPacket.setChildElement(queryResult);
    ArrayList<JID> jids = RUserManager.users.get(roomName);
    for (int i = 0; i < jids.size(); i++) {
      JID jid = jids.get(i);
      replyPacket.setTo(jid);
      replyPacket.setFrom(packet.getFrom());
      packetRouter.route(replyPacket);
    }
  }

  public void broadAudioVideoInvite(IQ packet, String from, String roomName) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.AUDIO_VIDEO_BROADCAST);
    StringBuilder sb = new StringBuilder();
    sb.append("<instructor>").append(from).append("</instructor>");
    queryResult.addElement("content").addText(sb.toString());
    replyPacket.setChildElement(queryResult);
    ArrayList<JID> jids = RUserManager.users.get(roomName);
    for (int i = 0; i < jids.size(); i++) {
      JID jid = jids.get(i);
      replyPacket.setTo(jid);
      replyPacket.setFrom(packet.getFrom());
      packetRouter.route(replyPacket);
    }
  }

  public static String getTagText(String xmlContent, String tag) {
    String content = null;
    int start = xmlContent.indexOf("<" + tag + ">") + ("<" + tag + ">").length();
    int end = xmlContent.indexOf("</" + tag + ">");
    if (start > -1 && end > -1) {
      content = xmlContent.substring(start, end);
    }

    return content;
  }

  public void broadcastItem(IQ packet) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.ITEM_BROADCAST_FROM_SERVER);
    String xmlContent = packet.toXML();
    String content = getTagText(xmlContent, "item-content");
    String roomName = getTagText(xmlContent, "room-name");
    pl.getRoomResourceManager().addItem(roomName, packet.getID(), content);
    queryResult.addElement("content").addText(content);
    replyPacket.setChildElement(queryResult);
    ArrayList<JID> jids = RUserManager.users.get(roomName.toLowerCase());
    for (int i = 0; i < jids.size(); i++) {
      JID jid = jids.get(i);

      replyPacket.setTo(jid);
      replyPacket.setFrom(packet.getFrom());
      packetRouter.route(replyPacket);
    }
  }

  public void broadcastModifiedTextItem(IQ packet) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.MODIFIED_TEXT_BROADCAST);
    String xmlContent = packet.toXML();
    String content = getTagText(xmlContent, "item-content");
    String roomName = getTagText(xmlContent, "room-name");
    String id = getTagText(xmlContent, "item-id");
    pl.getRoomResourceManager().updateItem(roomName, id, content);
    queryResult.addElement("content").addText(content);
    replyPacket.setChildElement(queryResult);
    ArrayList<JID> jids = RUserManager.users.get(roomName);
    for (int i = 0; i < jids.size(); i++) {
      JID jid = jids.get(i);
      replyPacket.setTo(jid);
      replyPacket.setFrom(packet.getFrom());
      packetRouter.route(replyPacket);
    }
  }

  public void broadcastItemPosition(IQ packet, double x, double y, String id, String type, String roomName) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.TRANSFORMED_ITEM_BROADCAST);

    StringBuilder sb = new StringBuilder();
    sb.append("<x>").append(x).append("</x>");
    sb.append("<y>").append(y).append("</y>");
    sb.append("<id>").append(id).append("</id>");
    sb.append("<item-type>").append(type).append("</item-type>");
    String xmlContent = packet.toXML();
    String content = getTagText(xmlContent, "item-content");
    pl.getRoomResourceManager().updateItem(roomName, id, content);
    queryResult.addElement("content").addText(sb.toString());
    replyPacket.setChildElement(queryResult);
    ArrayList<JID> jids = RUserManager.users.get(roomName);
    for (int i = 0; i < jids.size(); i++) {
      JID jid = jids.get(i);
      replyPacket.setTo(jid);
      replyPacket.setFrom(packet.getFrom());
      packetRouter.route(replyPacket);
    }
  }

  public void broadcastItemPosition(IQ packet, double x1, double y1, double x2, double y2,
      String id, String type, String roomName) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.TRANSFORMED_ITEM_BROADCAST);

    StringBuilder sb = new StringBuilder();
    sb.append("<x1>").append(x1).append("</x1>");
    sb.append("<y1>").append(y1).append("</y1>");
    sb.append("<x2>").append(x2).append("</x2>");
    sb.append("<y2>").append(y2).append("</y2>");
    sb.append("<item-type>").append(type).append("</item-type>");
    sb.append("<id>").append(id).append("</id>");
    String xmlContent = packet.toXML();
    String content = getTagText(xmlContent, "item-content");
    pl.getRoomResourceManager().updateItem(roomName, id, content);
    queryResult.addElement("content").addText(sb.toString());
    replyPacket.setChildElement(queryResult);
    ArrayList<JID> jids = RUserManager.users.get(roomName);
    for (int i = 0; i < jids.size(); i++) {
      JID jid = jids.get(i);
      replyPacket.setTo(jid);
      replyPacket.setFrom(packet.getFrom());
      try {
        packetRouter.route(replyPacket);
      } catch (Exception ex) {
        ex.printStackTrace();
      }
    }
  }

  public void broadcastItemToBeDeleted(IQ packet, String id, String roomName) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.DELETE_ITEM_BROADCAST);
    StringBuilder sb = new StringBuilder();
    sb.append("<id>").append(id).append("</id>");
    queryResult.addElement("content").addText(sb.toString());
    pl.getRoomResourceManager().deleteItem(roomName, id);
    replyPacket.setChildElement(queryResult);
    ArrayList<JID> jids = RUserManager.users.get(roomName);
    for (int i = 0; i < jids.size(); i++) {
      JID jid = jids.get(i);
      replyPacket.setTo(jid);
      replyPacket.setFrom(packet.getFrom());
      try {
        packetRouter.route(replyPacket);
      } catch (Exception ex) {
        ex.printStackTrace();
      }
    }
  }

  public void broadcastGiveMicPacket(IQ packet, String rec, String roomName) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.GIVEN_MIC);
    StringBuilder sb = new StringBuilder();
    sb.append("<instructor>").append(packet.getFrom()).append("</instructor>");
    sb.append("<speaker>").append(rec).append("</speaker>");
    queryResult.addElement("content").addText(sb.toString());
    replyPacket.setChildElement(queryResult);
    ArrayList<JID> jids = RUserManager.users.get(roomName.toLowerCase());
    if (jids == null) {
      jids = RUserManager.users.get(roomName.toUpperCase());
    }
    for (int i = 0; i < jids.size(); i++) {
      JID jid = jids.get(i);
      replyPacket.setTo(jid);
      replyPacket.setFrom(packet.getFrom());
      packetRouter.route(replyPacket);
    }
  }

  public void broadcastAccessChange(IQ packet, String username, String roomName, String permissions) {
    if (permissions == null) {
      permissions = "";
    }
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.SET_PERMISSIONS);

    RealtimePacketContent content = new RealtimePacketContent();
    content.addTag("username", username);
    content.addTag("permissions", permissions);
    queryResult.addElement("content").addText(content.toString());
    replyPacket.setChildElement(queryResult);
    ArrayList<JID> jids = RUserManager.users.get(roomName.toLowerCase());
    if (jids == null) {
      jids = RUserManager.users.get(roomName.toUpperCase());
    }
    for (int i = 0; i < jids.size(); i++) {
      JID jid = jids.get(i);
      replyPacket.setTo(jid);
      packetRouter.route(replyPacket);
    }
  }

  public void broadcastChangeTab(IQ packet, int index, String roomName) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.CHANGED_TAB_INDEX);
    StringBuilder sb = new StringBuilder();

    sb.append("<index>").append(index).append("</index>");
    queryResult.addElement("content").addText(sb.toString());
    replyPacket.setChildElement(queryResult);
    ArrayList<JID> jids = RUserManager.users.get(roomName);
    try {
      for (int i = 0; i < jids.size(); i++) {
        JID jid = jids.get(i);
        replyPacket.setTo(jid);
        replyPacket.setFrom(packet.getFrom());
        packetRouter.route(replyPacket);
      }
    } catch (Exception ex) {
      ex.printStackTrace();
    }
  }

  public void broadcastChangeURL(IQ packet, String url, String roomName) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.UPDATED_URL);
    StringBuilder sb = new StringBuilder();
    sb.append("<url>").append(url).append("</url>");
    sb.append("<instructor>").append(packet.getFrom()).append("</instructor>");
    queryResult.addElement("content").addText(sb.toString());
    replyPacket.setChildElement(queryResult);
    ArrayList<JID> jids = RUserManager.users.get(roomName);
    for (int i = 0; i < jids.size(); i++) {
      JID jid = jids.get(i);
      replyPacket.setTo(jid);
      replyPacket.setFrom(packet.getFrom());
      packetRouter.route(replyPacket);
    }
  }

  public void broadcastTakeMicPacket(IQ packet, String rec, String roomName) {
    try{
      IQ replyPacket = IQ.createResultIQ(packet);
      Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
      queryResult.addElement("mode").addText(Mode.TAKEN_MIC);
      StringBuilder sb = new StringBuilder();
      sb.append("<instructor>").append(packet.getFrom()).append("</instructor>");
      sb.append("<speaker>").append(rec).append("</speaker>");
      queryResult.addElement("content").addText(sb.toString());
      replyPacket.setChildElement(queryResult);

      //this is a test
      ArrayList<JID> jids = RUserManager.users.get(roomName.toLowerCase());
      if (jids == null) {
        jids = RUserManager.users.get(roomName.toUpperCase());
      }
      for (int i = 0; i < jids.size(); i++) {
        JID jid = jids.get(i);
        replyPacket.setTo(jid);
        replyPacket.setFrom(packet.getFrom());
        packetRouter.route(replyPacket);
      }
    }catch(Exception ex){
      ex.printStackTrace();
    }
  }

  public void broadcastResizedItem(IQ packet, String id, double x, double y, String type, String roomName) {
    IQ replyPacket = IQ.createResultIQ(packet);
    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
    queryResult.addElement("mode").addText(Mode.RESIZE_ITEM_BROADCAST);

    StringBuilder sb = new StringBuilder();
    sb.append("<x>").append(x).append("</x>");
    sb.append("<y>").append(y).append("</y>");
    sb.append("<r-type>").append(type).append("</r-type>");
    sb.append("<id>").append(id).append("</id>");
    queryResult.addElement("content").addText(sb.toString());
    String xmlContent = packet.toXML();
    String content = getTagText(xmlContent, "item-content");
    pl.getRoomResourceManager().updateItem(roomName, id, content);
    replyPacket.setChildElement(queryResult);
    ArrayList<JID> jids = RUserManager.users.get(roomName);
    for (int i = 0; i < jids.size(); i++) {
      JID jid = jids.get(i);
      replyPacket.setTo(jid);
      replyPacket.setFrom(packet.getFrom());
      try {
        packetRouter.route(replyPacket);
      } catch (Exception ex) {
        ex.printStackTrace();
      }
    }
  }
}
