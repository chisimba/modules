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

import java.io.IOException;
import java.util.ArrayList;
import org.avoir.realtime.common.RealtimeFile;
import org.avoir.realtime.common.util.XmlUtils;
import org.avoir.realtime.net.packets.RealtimeImagePacket;
import org.avoir.realtime.net.packets.RealtimeFileViewPacket;
import org.jivesoftware.smack.packet.IQ;
import org.jivesoftware.smack.provider.IQProvider;
import org.jivesoftware.smack.util.StringUtils;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xmlpull.v1.XmlPullParser;
import org.xmlpull.v1.XmlPullParserException;

/**
 *
 * @author developer
 */
public class RealtimePacketProvider implements IQProvider {

    private RealtimePacketListener realtimePacketListener;
    private QuestionProcessor questionProcessor = new QuestionProcessor();
    private SlideShowProcessor slideShowProcessor = new SlideShowProcessor();
    private NotepadProcessor notePadProcessor = new NotepadProcessor();
    private MessageProcessor messageProcessor = new MessageProcessor();

    public void addRealtimePacketListener(RealtimePacketListener realtimePacketListener) {
        this.realtimePacketListener = realtimePacketListener;

      
        notePadProcessor.addRealtimePacketListener(realtimePacketListener);

        messageProcessor.addRealtimePacketListener(realtimePacketListener);
    }

    /** Creates a new Provider. ProviderManager requires that every PacketExtensionProvider
     *  has a public,no-argument constructor
     */
    public RealtimePacketProvider() {
        /***/
    }

    public IQ parseIQ(XmlPullParser parser) throws Exception {

        final StringBuilder sb = new StringBuilder();
        try {
            int event = parser.getEventType();
            // get the content
            while (true) {

                if (event == XmlPullParser.END_TAG && "iq".equals(parser.getName())) {
                    break;
                }
                switch (event) {
                    case XmlPullParser.TEXT:
                        // We must re-escape the xml so that the DOM won't throw an exception
                        sb.append(StringUtils.escapeForXML(parser.getText()));
                        break;
                    case XmlPullParser.START_TAG:
                        sb.append('<').append(parser.getName()).append('>');
                        break;
                    case XmlPullParser.END_TAG:
                        sb.append("</").append(parser.getName()).append('>');
                        break;
                    default:
                }



                event = parser.next();
            }
        } catch (XmlPullParserException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }

        String xmlText = sb.toString();

        return createRealtimePacketFromXML("<server-reply>" + xmlText + "</server-reply>");
    }

    private IQ createRealtimePacketFromXML(String xml) throws Exception {
        /*
        DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();
        DocumentBuilder documentBuilder = documentBuilderFactory.newDocumentBuilder();
        Document doc = documentBuilder.parse(
        new ByteArrayInputStream(xml.getBytes(PREFERRED_ENCODING)));
        String packetType = XmlUtils.readString(doc, "packet-type");

        if (packetType.equals("file-view")) {
        return createRealtimeFileViewPacket(doc);
        }
        if (packetType.equals("image-download")) {
        return createRealtimeImageDownloadPacket(doc);
        }
        if (packetType.equals("question-open")) {
        return questionProcessor.getRealtimeQuestionPacket(xml);
        }
        if (packetType.equals("slide-show-open")) {
        return slideShowProcessor.getRealtimeSlideShowPacket(xml);
        }
        if (packetType.equals("notepad-open")) {

        return notePadProcessor.getRealtimeNotepadPacket(xml);
        }
        if (packetType.equals("message")) {
        return messageProcessor.getRealtimeMessagePacket(xml);
        }*/
        return null;
    }

    private RealtimeFileViewPacket createRealtimeFileViewPacket(Document doc) {
        RealtimeFileViewPacket realtimePacket = new RealtimeFileViewPacket();

        String fileType = XmlUtils.readString(doc, "file-type");
        ArrayList<RealtimeFile> fileList = new ArrayList<RealtimeFile>();
        realtimePacket.setFileType(fileType);
        NodeList fileListNode = doc.getElementsByTagName("file-list");

        for (int i = 0; i < fileListNode.getLength(); i++) {
            Node node = fileListNode.item(i);
            if (node.getNodeType() == Node.ELEMENT_NODE) {
                NodeList fileListElement = doc.getElementsByTagName("file");


                for (int k = 0; k < fileListElement.getLength(); k++) {
                    Node fileNode = fileListElement.item(k);
                    if (fileNode.getNodeType() == Node.ELEMENT_NODE) {
                        Element fileElement = (Element) fileNode;
                        String fileName = XmlUtils.readString(fileElement, "file-name");
                        String filePath = XmlUtils.readString(fileElement, "file-path");
                        String isDirectory = XmlUtils.readString(fileElement, "is-directory");
                        String accessStr = XmlUtils.readString(fileElement, "access");
                        boolean publicAccess = true;
                        if (accessStr.equals("private")) {
                            publicAccess = false;
                        }
                        RealtimeFile file = new RealtimeFile(fileName, filePath,
                                new Boolean(isDirectory), publicAccess);
                        fileList.add(file);
                    }
                }

            }
        }
        realtimePacket.setFileList(fileList);
        if (realtimePacketListener != null) {
//            realtimePacketListener.processPacket(realtimePacket);
        }
        return realtimePacket;
    }

    private RealtimeImagePacket createRealtimeImageDownloadPacket(Document doc) {
        RealtimeImagePacket p = new RealtimeImagePacket();

        String fileType = XmlUtils.readString(doc, "file-type");
        p.setPacketType(fileType);
        String imageData = XmlUtils.readString(doc, "image-data");
        String filePath = XmlUtils.readString(doc, "file-path");
        p.setFilePath(filePath);
        p.setImageData(imageData);
        if (realtimePacketListener != null) {
            realtimePacketListener.processPacket(p);
        }
        return p;
    }
}
