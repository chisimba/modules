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
package org.avoir.realtime.net.providers;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Rectangle;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.geom.Line2D;
import java.io.ByteArrayInputStream;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JPanel;
import javax.swing.SwingUtilities;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.RealtimeFile;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.common.util.XmlUtils;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.WebPresentManager;
import org.avoir.realtime.gui.whiteboard.items.Item;
import org.avoir.realtime.gui.whiteboard.items.Line;
import org.avoir.realtime.gui.whiteboard.items.Oval;
import org.avoir.realtime.gui.whiteboard.items.Pen;
import org.avoir.realtime.gui.whiteboard.items.Rect;
import org.avoir.realtime.gui.whiteboard.items.Text;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.jivesoftware.smack.util.Base64;
import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

/**
 *
 * @author developer
 */
public class RealtimePacketProcessor {

    private static DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();
    private static DocumentBuilder documentBuilder;
    //private static Map<String, JDialog> speakers = new HashMap<String, JDialog>();


    static {
        try {
            documentBuilder = documentBuilderFactory.newDocumentBuilder();
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    public static Item getItem(String xmlContents) {
        String id = GeneralUtil.getTagText(xmlContents, "item-id");
        return getItem(xmlContents, id);
    }

    public static Item getItem(String xmlContents, String id) {
        xmlContents = "<cb>" + xmlContents + "</cb>";
        Item item = null;
        Color c = Color.BLACK;
        float strokeWidth = 1.0f;
        String itemMode = "new";
        try {

            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            String type = XmlUtils.readString(doc, "item-type");
            itemMode = XmlUtils.readString(doc, "item-mode");

            int r = XmlUtils.readInt(doc, "red");
            int g = XmlUtils.readInt(doc, "green");
            int b = XmlUtils.readInt(doc, "blue");
            strokeWidth = XmlUtils.readFloat(doc, "stroke-width");
            c = new Color(r, g, b);
            if (type.equals("line")) {
                double xx1 = XmlUtils.readDouble(doc, "x1");
                double yy1 = XmlUtils.readDouble(doc, "y1");
                double xx2 = XmlUtils.readDouble(doc, "x2");
                double yy2 = XmlUtils.readDouble(doc, "y2");

                Rectangle rect = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getWhiteboardSize();
                int x1 = (int) (xx1 * rect.getWidth());
                int y1 = (int) (yy1 * rect.getHeight());
                int x2 = (int) (xx2 * rect.getWidth());
                int y2 = (int) (yy2 * rect.getHeight());

                item = new Line(id, x1, y1, x2, y2);

            }

            if (type.equals("rect")) {
                double xx1 = XmlUtils.readDouble(doc, "x");
                double yy1 = XmlUtils.readDouble(doc, "y");

                Rectangle rectS = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getWhiteboardSize();
                int x = (int) (xx1 * rectS.getWidth());
                int y = (int) (yy1 * rectS.getHeight());


                int w = XmlUtils.readInt(doc, "width");
                int h = XmlUtils.readInt(doc, "height");
                Rect rect = new Rect(x, y, w, h);
                String filled = XmlUtils.readString(doc, "filled");
                rect.setFilled(new Boolean(filled));
                item = rect;
                item.setId(id);
            }
            if (type.equals("oval")) {
                double xx1 = XmlUtils.readDouble(doc, "x");
                double yy1 = XmlUtils.readDouble(doc, "y");

                Rectangle rect = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getWhiteboardSize();
                int x = (int) (xx1 * rect.getWidth());
                int y = (int) (yy1 * rect.getHeight());

                int w = XmlUtils.readInt(doc, "width");
                int h = XmlUtils.readInt(doc, "height");
                Oval oval = new Oval(x, y, h, w);
                String filled = XmlUtils.readString(doc, "filled");
                oval.setFilled(new Boolean(filled));
                item = oval;
                item.setId(id);
            }
            if (type.equals("text")) {
                double xx1 = XmlUtils.readDouble(doc, "x");
                double yy1 = XmlUtils.readDouble(doc, "y");

                Rectangle rect = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getWhiteboardSize();
                int x = (int) (xx1 * rect.getWidth());
                int y = (int) (yy1 * rect.getHeight());

                String content = XmlUtils.readString(doc, "text-content");
                int fontSize = XmlUtils.readInt(doc, "font-size");
                int fontStyle = XmlUtils.readInt(doc, "font-style");
                String fontName = XmlUtils.readString(doc, "font-name");
                int red = XmlUtils.readInt(doc, "red");
                int green = XmlUtils.readInt(doc, "green");
                int blue = XmlUtils.readInt(doc, "blue");

                Text text = new Text(x, y, content);
                text.setGraphic(GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getGraphics2D());
                text.setRed(red);

                text.setGreen(green);
                text.setBlue(blue);
                text.setFontName(fontName);
                text.setFontSize(fontSize);
                text.setFontStyle(fontStyle);
                text.setId(id);
                item = text;
            }
            if (type.equals("pen")) {
                String content = XmlUtils.readString(doc, "point-data");
                String[] points = content.split("#");
                ArrayList<Line2D.Double> linePoints = new ArrayList<Line2D.Double>();
                Rectangle rect = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getWhiteboardSize();

                for (int i = 0; i < points.length; i++) {
                    String point[] = points[i].split(",");
                    double xx1 = Double.valueOf(point[0]);
                    double yy1 = Double.valueOf(point[1]);
                    double xx2 = Double.valueOf(point[2]);
                    double yy2 = Double.valueOf(point[3]);

                    double x1 = (xx1 * rect.getWidth());
                    double y1 = (yy1 * rect.getHeight());
                    double x2 = (xx2 * rect.getWidth());
                    double y2 = (yy2 * rect.getHeight());
                    Line2D.Double line = new Line2D.Double(
                            x1,
                            y1,
                            x2,
                            y2);
                    linePoints.add(line);

                }
                item = new Pen(linePoints);
                item.setId(id);

            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        if (item != null) {
            item.setColor(c);
            item.setNewItem(itemMode.equals("new"));
            item.setStrokeWidth(strokeWidth);
        }
        return item;
    }

    public static Map<String, String> getModifiedTextContent(String xmlContents) {
        Map<String, String> map = new HashMap<String, String>();
        try {
            xmlContents = "<modified-text>" + xmlContents + "</modified-text>";
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            String content = XmlUtils.readString(doc, "text-content");
            String id = XmlUtils.readString(doc, "text-id");
            map.put("text-content", content);
            map.put("id", id);
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return map;
    }

    public static String getTag(String xmlContents, String tag) {
        xmlContents = "<dt>" + xmlContents + "</dt>";
        String rtag = "";
        try {

            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            rtag = XmlUtils.readString(doc, tag);

        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return rtag;
    }

    public static String getItemId(String xmlContents) {
        String id = "";
        try {

            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            id = XmlUtils.readString(doc, "id");

        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return id;
    }

    public static String getURL(String xmlContents) {
        xmlContents = "<web>" + xmlContents + "</web>";
        String url = "";
        try {

            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            url = XmlUtils.readString(doc, "url");

        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return url;
    }

    public static int getTabIndex(String xmlContents) {
        xmlContents = "<tb>" + xmlContents + "</tb>";
        int index = 0;
        try {

            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            index = XmlUtils.readInt(doc, "index");
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return index;
    }

    public static void disposeAudioVideoWindow(String xmlContents) {

        try {
            xmlContents = "<inst>" + xmlContents + "</inst>";
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            String from = XmlUtils.readString(doc, "instructor");
            String speaker = XmlUtils.readString(doc, "speaker");
            int index = from.lastIndexOf("@");
            if (index > -1) {
                from = from.substring(0, index);
            }
            System.err.println("*****init remove MIC from " + from);
            GUIAccessManager.mf.removeSpeaker(speaker);
        // String me = ConnectionManager.getUsername();
        // JDialog fr = speakers.remove(speaker);
        // fr.dispose();

        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    public static void displayVideoMicWindow(String xmlContents) {

        try {
            xmlContents = "<inst>" + xmlContents + "</inst>";
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            String from = XmlUtils.readString(doc, "instructor");
            String speaker = XmlUtils.readString(doc, "speaker");
            int index = from.lastIndexOf("@");
            if (index > -1) {
                from = from.substring(0, index);
            }
            String me = ConnectionManager.getUsername();

            String url = "";
            String title = "";
            if (me.equalsIgnoreCase(from)) {
                url =
                        ConnectionManager.AUDIO_VIDEO_URL + "/video/receiver.html?me=" + ConnectionManager.getRoomName() + "&you=" + speaker;
                title = speaker + " given mic";
                dispayAudioVideoWindow(url, title, true, speaker);
            } else if (ConnectionManager.fullnames.equalsIgnoreCase(speaker)) {
                url =
                        ConnectionManager.AUDIO_VIDEO_URL + "/video/broadcaster.html?me=" + speaker + "&you=" + ConnectionManager.getRoomName();
                title = ConnectionManager.getUsername() + ": MIC from " + from;
                dispayAudioVideoWindow(url, title, false, speaker);
            } else {
                url =
                        ConnectionManager.AUDIO_VIDEO_URL + "/video/receiver.html?me=" + ConnectionManager.getRoomName() + "&you=" + speaker;
                title = speaker + " given mic";
                dispayAudioVideoWindow(url, title, false, speaker);
            }

        } catch (Exception ex) {
            ex.printStackTrace();
        }



    }

    private static void dispayAudioVideoWindow(
            final String url,
            final String title,
            final boolean enableClose,
            final String speaker) {
        SwingUtilities.invokeLater(new Runnable() {

            public void run() {
                //  JWebBrowser webBrowser = new JWebBrowser();
                //   webBrowser.setMenuBarVisible(false);
                //    webBrowser.setBarsVisible(false);
                //    webBrowser.setButtonBarVisible(false);
                //     webBrowser.navigate(url);
                // final JDialog fr = new JDialog(GUIAccessManager.mf);
                JButton takeMICButton = new JButton("Close");
                takeMICButton.setEnabled(enableClose);
                takeMICButton.addActionListener(new ActionListener() {

                    public void actionPerformed(ActionEvent e) {
                        StringBuilder sb = new StringBuilder();
                        sb.append("<recipient>").append(speaker).append("</recipient>");
                        sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
                        RealtimePacket p = new RealtimePacket();
                        p.setMode(RealtimePacket.Mode.TAKE_MIC);
                        p.setContent(sb.toString());
                        ConnectionManager.sendPacket(p);
                    }
                });
                //fr.setTitle(title);
                //fr.setDefaultCloseOperation(JDialog.DO_NOTHING_ON_CLOSE);
                // fr.setLayout(new BorderLayout());

                // fr.add(webBrowser, BorderLayout.CENTER);
                JPanel mPanel = new JPanel(new BorderLayout());
                //  mPanel.add(webBrowser, BorderLayout.CENTER);
                JPanel p = new JPanel();
                p.add(takeMICButton);
                mPanel.add(p, BorderLayout.SOUTH);
                GUIAccessManager.mf.addSpeaker(url, speaker);
            //fr.setSize(380, 250);
            //fr.setLocationRelativeTo(null);
            //fr.setVisible(true);
            //speakers.put(speaker, fr);
            }
        });
    }

    public static DownloadedImage getDownloadedImage(String xmlContents) {
        DownloadedImage di = new DownloadedImage();
        ImageIcon image = null;
        try {
            xmlContents = "<image>" + xmlContents + "</image>";
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            String imageData = XmlUtils.readString(doc, "image-data");
            String imagePath = XmlUtils.readString(doc, "image-path");
            image = new ImageIcon(Base64.decode(imageData));
            di.setImage(image);
            di.setImagePath(imagePath);
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return di;
    }

    public static ImageIcon getImage(String xmlContents) {
        ImageIcon image = null;
        try {

            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            String imageData = XmlUtils.readString(doc, "image-data");
            image = new ImageIcon(Base64.decode(imageData));
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return image;
    }

    public static Pointer getPointer(String xmlContents) {
        Pointer pointer = new Pointer();
        try {
            xmlContents = "<pointerdata>" + xmlContents + "</pointerdata>";
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            int p = XmlUtils.readInt(doc, "pointer");
            double xx = XmlUtils.readDouble(doc, "x");
            double yy = XmlUtils.readDouble(doc, "y");
            Rectangle rect = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getWhiteboardSize();
            int x = (int) (rect.getWidth() * xx);
            int y = (int) (rect.getHeight() * yy);
            pointer.setPointer(p);
            pointer.setX(x);
            pointer.setY(y);
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return pointer;
    }

    public static BroadcastItem getItemDim(String xmlContents) {
        BroadcastItem item = new BroadcastItem();
        try {
            xmlContents = "<itemdata>" + xmlContents + "</itemdata>";
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            String id = XmlUtils.readString(doc, "id");
            // int x = XmlUtils.readInt(doc, "x");
            // int y = XmlUtils.readInt(doc, "y");
            double xx1 = XmlUtils.readDouble(doc, "x");
            double yy1 = XmlUtils.readDouble(doc, "y");

            Rectangle rect = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getWhiteboardSize();
            int x = (int) (xx1 * rect.getWidth());
            int y = (int) (yy1 * rect.getHeight());
            String type = XmlUtils.readString(doc, "r-type");
            item.setResizeType(type);

            item.setId(id);
            item.setX(x);
            item.setY(y);
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return item;
    }

    public static BroadcastItem getItemCoords(String xmlContents) {
        BroadcastItem item = new BroadcastItem();
        try {
            xmlContents = "<itemdata>" + xmlContents + "</itemdata>";
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            String id = XmlUtils.readString(doc, "id");
            String itemType = XmlUtils.readString(doc, "item-type");
            if (itemType.equals("line") || itemType.equals("pen")) {
                double xx1 = XmlUtils.readDouble(doc, "x1");
                double yy1 = XmlUtils.readDouble(doc, "y1");
                double xx2 = XmlUtils.readDouble(doc, "x2");
                double yy2 = XmlUtils.readDouble(doc, "y2");

                Rectangle rect = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getWhiteboardSize();
                int x1 = (int) (xx1 * rect.getWidth());
                int y1 = (int) (yy1 * rect.getHeight());
                int x2 = (int) (xx2 * rect.getWidth());
                int y2 = (int) (yy2 * rect.getHeight());
                item.setId(id);
                item.setX(x1);
                item.setY(y1);

                item.setW(x2);
                item.setH(y2);
            } else {
                double xx1 = XmlUtils.readDouble(doc, "x");
                double yy1 = XmlUtils.readDouble(doc, "y");

                Rectangle rect = GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().getWhiteboardSize();
                int x = (int) (xx1 * rect.getWidth());
                int y = (int) (yy1 * rect.getHeight());
                item.setId(id);
                item.setX(x);
                item.setY(y);
            }
            item.setType(itemType);
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return item;
    }

    public static ArrayList<Map> getRoomList(String xmlContents) {
        ArrayList<Map> rooms = new ArrayList<Map>();
        try {
            xmlContents = "<xlist>" + xmlContents + "</xlist>";
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));

            NodeList usersNodeList = doc.getElementsByTagName("room");

            for (int i = 0; i < usersNodeList.getLength(); i++) {
                Node node = usersNodeList.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    org.w3c.dom.Element element = (org.w3c.dom.Element) node;
                    String roomName = XmlUtils.readString(element, "room-name");
                    String roomDesc = XmlUtils.readString(element, "room-desc");
                    String roomType = XmlUtils.readString(element, "room-type");

                    Map<String, String> map = new HashMap<String, String>();
                    map.put("room-name", roomName);
                    map.put("room-desc", roomDesc);
                    map.put("room-type", roomType);

                    rooms.add(map);
                }
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return rooms;
    }

    public static ArrayList<Map> getRoomMemberList(String xmlContents) {
        ArrayList<Map> rooms = new ArrayList<Map>();
        try {
            xmlContents = "<xlist>" + xmlContents + "</xlist>";
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));

            NodeList usersNodeList = doc.getElementsByTagName("member");

            for (int i = 0; i < usersNodeList.getLength(); i++) {
                Node node = usersNodeList.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    org.w3c.dom.Element element = (org.w3c.dom.Element) node;
                    String username = XmlUtils.readString(element, "member-username");
                    String name = XmlUtils.readString(element, "member-name");
                    Map<String, String> map = new HashMap<String, String>();
                    if (!username.equals("null")) {
                        map.put("username", username);
                        map.put("name", name);
                        rooms.add(map);
                    }
                }
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return rooms;
    }
    

    public static ArrayList<Map> getUserArrayList(String xmlContents) {
        ArrayList<Map> users = new ArrayList<Map>();
        try {
            xmlContents = "<adminlist>" + xmlContents + "</adminlist>";
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));

            NodeList usersNodeList = doc.getElementsByTagName("user");

            for (int i = 0; i < usersNodeList.getLength(); i++) {
                Node node = usersNodeList.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    org.w3c.dom.Element element = (org.w3c.dom.Element) node;
                    String userId = XmlUtils.readString(element, "userid");
                    String userName = XmlUtils.readString(element, "username");
                    String inst = XmlUtils.readString(element, "admin");

                    Map<String, String> map = new HashMap<String, String>();
                    map.put("userid", userId);
                    map.put("username", userName);
                    map.put("admin", inst);

                    users.add(map);
                }
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return users;
    }

    public static void updateCurrentWB(String xmlContents) {
        /*

        String itemContent = GeneralUtil.getTagText(xmlContents, "item-content");
        while (itemContent != null) {
        String id = GeneralUtil.getTagText(xmlContents, "item-id");
        Item item = getItem(itemContent, id);

        GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().addItem(item);
        try {
        xmlContents = xmlContents.substring(xmlContents.indexOf(itemContent) + itemContent.length());
        xmlContents = xmlContents.substring(xmlContents.indexOf("<current-item>"));
        } catch (Exception ex) {
        ex.printStackTrace();
        }
        itemContent = GeneralUtil.getTagText(xmlContents, "item-content");
        }*/
        /*
        try {

        Document doc = documentBuilder.parse(
        new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));

        NodeList itemsNodeList = doc.getElementsByTagName("current-item");
        System.out.println("no: = " + itemsNodeList.getLength());
        for (int i = 0; i < itemsNodeList.getLength(); i++) {
        Node node = itemsNodeList.item(i);
        if (node.getNodeType() == Node.ELEMENT_NODE) {
        org.w3c.dom.Element element = (org.w3c.dom.Element) node;
        System.out.println(element.getUserData("item-content"));
        String currentItem = XmlUtils.readString(element, "item-content");
        System.out.println(currentItem);
        String id = GeneralUtil.getTagText(xmlContents, "item-id");
        Item item = getItem(currentItem, id);
        GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().addItem(item);
        }
        }
        } catch (Exception ex) {
        ex.printStackTrace();
        }
         */
    }

    public static ArrayList<RealtimeFile> getFileViewArrayList(String xmlContents) {
        return getFileViewArrayList(xmlContents, false);
    }

    public static ArrayList<RealtimeFile> getFileViewArrayList(String xmlContents, boolean detectVersion) {
        ArrayList<RealtimeFile> files = new ArrayList<RealtimeFile>();


        try {
            xmlContents = "<rs>" + xmlContents + "</rs>";
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));

            NodeList usersNodeList = doc.getElementsByTagName("file");
            GUIAccessManager.defaultPresentationName = XmlUtils.readString(doc, "presentation-name");
            GUIAccessManager.defaultPresentationId = XmlUtils.readString(doc, "presentation-id");
            GUIAccessManager.defaultSlideName = XmlUtils.readString(doc, "slide-name");
            if (GUIAccessManager.defaultPresentationName != null && GUIAccessManager.defaultSlideName != null) {
                ImageIcon slideImage = null;
                try {
                    String imgExt = WebPresentManager.hasBeenLaunchedAsWebPresent ? "" : ".jpg";
                    String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
                    String slideImagePath = resourceDir + "/" + GUIAccessManager.defaultPresentationId + "/" + GUIAccessManager.defaultSlideName + imgExt;
                    slideImage = new ImageIcon(GeneralUtil.readFile(slideImagePath));
                    if (slideImage == null) {
                        if (WebPresentManager.hasBeenLaunchedAsWebPresent) {
                            System.out.println("image was null, so just picking first one");
                            SlideShowProcessor.displayInitialSlide(WebPresentManager.presentationId);
                        }
                    } else {
                        System.out.println("image not null, so surely got it");
                        GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setSlideImage(slideImage);
                    }
                } catch (Exception ex) {
                    System.out.println(ex.getLocalizedMessage());
                }
            } else {
                SlideShowProcessor.displayInitialSlide(WebPresentManager.presentationId);
            }
            for (int i = 0; i < usersNodeList.getLength(); i++) {
                Node node = usersNodeList.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    org.w3c.dom.Element element = (org.w3c.dom.Element) node;
                    String filename = XmlUtils.readString(element, "file-name");
                    String filePath = XmlUtils.readString(element, "file-path");
                    String isDirectory = XmlUtils.readString(element, "is-directory");
                    String access = XmlUtils.readString(element, "access");
                    boolean publicAccess = true;
                    if (access.equals("private")) {
                        publicAccess = false;
                    }
                    RealtimeFile f = new RealtimeFile(filename, filePath, new Boolean(isDirectory), publicAccess);
                    if (detectVersion) {
                        int ver = XmlUtils.readInt(element, "version");
                        f.setVersion(ver);
                    }
                    files.add(f);
                }
            }
            String wbContent = GeneralUtil.getTagText(xmlContents, "current-wb");
            if (wbContent != null) {
                updateCurrentWB("<wb>" + wbContent + "</wb>");
            }

        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return files;
    }
}
