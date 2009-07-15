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
import java.io.FileWriter;
import java.io.IOException;
import java.io.Writer;
import java.util.ArrayList;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.dom4j.DocumentHelper;
import org.dom4j.Element;
import org.dom4j.QName;
import org.jivesoftware.openfire.PacketRouter;
import org.jivesoftware.util.Base64;
import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xmpp.packet.IQ;
import org.xmpp.packet.JID;

/**
 *
 * @author developer
 */
public class QuestionProcessor {

    private AvoirRealtimePlugin pl;
    // private ArrayList<JID> jids;
    private PacketRouter packetRouter;
    private DocumentBuilder documentBuilder;
    private DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();

    public QuestionProcessor(AvoirRealtimePlugin pl) {
        this.pl = pl;
//        jids = pl.getUsers(false);
        packetRouter = pl.getPacketRouter();
        try {
            documentBuilder = documentBuilderFactory.newDocumentBuilder();
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    public void broadQuestion(IQ packet, Document xdoc,String roomName) {
        String fileName = XmlUtils.readString(xdoc, "filename");
        String username = XmlUtils.readString(xdoc, "username");
        String access = XmlUtils.readString(xdoc, "access");
        if (!fileName.endsWith(".qn")) {
            fileName += ".qn";
        }
        IQ replyPacket = IQ.createResultIQ(packet);
        String path = Constants.FILES_DIR + "/questions/" + fileName;
        if (access.equals("private")) {
            path = Constants.FILES_DIR + "/questions/" + username + "/" + fileName;
        }
        String xmlContents = Util.getContents(new File(path));
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(Mode.BROADCAST_QUESTION);

        queryResult.addElement("content").addText(extractQuestionContent(xmlContents,
                access, packet.getFrom(), path));
        replyPacket.setChildElement(queryResult);
       ArrayList<JID> jids = RUserManager.users.get(roomName);
        for (int i = 0; i < jids.size(); i++) {
            JID jid = jids.get(i);
            replyPacket.setTo(jid);
            replyPacket.setFrom(packet.getFrom());
            packetRouter.route(replyPacket);
        }
    }

    public void saveAndForwardAnswer(IQ packet, Document doc) {
        String qnName = XmlUtils.readString(doc, "qn-name");
        String username = XmlUtils.readString(doc, "username");
        String instructor = XmlUtils.readString(doc, "instructor");
        String contents = packet.toXML();
        String jid = packet.getFrom().toBareJID();
        String from = jid.substring(0, jid.indexOf("@"));
        String path = "/answers/" + username + "/" + qnName;
        new File(Constants.FILES_DIR + path).mkdirs();
        String distFile = Constants.FILES_DIR + path + "/" + from + ".ans";
        Writer output = null;
        try {
            output = new BufferedWriter(new FileWriter(distFile));
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
        IQ replyPacket = IQ.createResultIQ(packet);
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(Mode.POSTED_ANSWER);
        queryResult.addElement("content").addText(extractAnswerContent(contents, packet.getFrom().toBareJID()));
        replyPacket.setChildElement(queryResult);
        replyPacket.setTo(instructor);

        packetRouter.route(replyPacket);
    }

    public IQ saveQuestionFile(IQ packet, Document doc) {
        String fileName = XmlUtils.readString(doc, "question-filename");
        String username = XmlUtils.readString(doc, "question-username");
        String access = XmlUtils.readString(doc, "access");
         String roomName = XmlUtils.readString(doc, "room-name");
        String contents = packet.toXML();

        new File(Constants.FILES_DIR + "/questions/" + username).mkdirs();
        String distFile = Constants.FILES_DIR + "/questions/" + fileName + ".qn";
        if (access.equals("private")) {
            distFile = Constants.FILES_DIR + "/questions/" + username + "/" + fileName + ".qn";
        }
        Writer output = null;
        try {
            output = new BufferedWriter(new FileWriter(distFile));
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

        return pl.getDefaultPacketProcessor().getFileView(packet, "questions", username,roomName);
    }

    public String extractQuestionContent(String xmlContents, String access,
            JID from, String path) {


        StringBuilder sb = new StringBuilder();
        try {

            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));

            String question = XmlUtils.readString(doc, "question");
            String username = XmlUtils.readString(doc, "question-username");
            String filename = XmlUtils.readString(doc, "question-filename");
            int qnType = XmlUtils.readInt(doc, "question-type");
            String imagePath = XmlUtils.readString(doc, "question-image-path");
            String imageData = Base64.encodeFromFile(imagePath);
            NodeList slides = doc.getElementsByTagName("answer");
            ArrayList<Value> values = new ArrayList<Value>();
            for (int i = 0; i < slides.getLength(); i++) {
                Node node = slides.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    org.w3c.dom.Element element = (org.w3c.dom.Element) node;
                    String opt = XmlUtils.readString(element, "option");
                    String value = XmlUtils.readString(element, "value");
                    String correctAnswer = XmlUtils.readString(element, "correct-answer");
                    values.add(new Value(opt, value, new Boolean(correctAnswer).booleanValue()));

                }
            }
            sb.append("<question>").append(question).append("</question>");
            sb.append("<question-filename>").append(filename).append("</question-filename>");
            sb.append("<question-username>").append(username).append("</question-username>");
            sb.append("<question-instructor>").append(from.toString()).append("</question-instructor>");
            sb.append("<question-type>").append(qnType).append("</question-type>");
            sb.append("<question-path>").append(path).append("</question-path>");
            sb.append("<question-image-path>").append(imagePath).append("</question-image-path>");
            sb.append("<question-image-data>").append(imageData).append("</question-image-data>");
            sb.append("<question-access>").append(access).append("</question-access>");
            sb.append("<answers>");

            for (int i = 0; i < values.size(); i++) {
                Value value = values.get(i);
                sb.append("<answer>");
                sb.append("<option>").append(value.getOption()).append("</option>");
                sb.append("<value>").append(value.getValue()).append("</value>");
                sb.append("<correct-answer>").append(value.isCorrectAnswer()).append("</correct-answer>");
                sb.append("</answer>");
            }
            sb.append("</answers>");
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return sb.toString();
    }

    private String extractAnswerContent(String xmlContents, String from) {
        StringBuilder sb = new StringBuilder();
        try {

            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            String username = XmlUtils.readString(doc, "username");
            String filename = XmlUtils.readString(doc, "qn-name");
            int qnType = XmlUtils.readInt(doc, "qn-type");
            String passed = XmlUtils.readString(doc, "passed");

            sb.append("<qn-type>").append(qnType).append("</qn-type>");
            sb.append("<qn-name>").append(filename).append("</qn-name>");
            sb.append("<username>").append(username).append("</username>");
            sb.append("<passed>").append(passed).append("</passed>");
            sb.append("<respondent>").append(from).append("</respondent>");
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return sb.toString();
    }

    public IQ openQuestionFile(IQ packet, Document xdoc) {
        String fileName = XmlUtils.readString(xdoc, "filename");
        String username = XmlUtils.readString(xdoc, "username");
        String access = XmlUtils.readString(xdoc, "access");
        if (!fileName.endsWith(".qn")) {
            fileName += ".qn";
        }
        IQ replyPacket = IQ.createResultIQ(packet);
        String path = Constants.FILES_DIR + "/questions/" + fileName;
        if (access.equals("private")) {
            path = Constants.FILES_DIR + "/questions/" + username + "/" + fileName;
        }
        String xmlContents = Util.getContents(new File(path));

        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(Mode.OPEN_QUESTION);


        queryResult.addElement("content").addText(extractQuestionContent(xmlContents,
                access, packet.getFrom(), path));
        replyPacket.setChildElement(queryResult);


        return replyPacket;
    }

    public IQ openQuestionFile(IQ packet, Document xdoc, String extra) {
        String fileName = XmlUtils.readString(xdoc, "filename");
        String username = XmlUtils.readString(xdoc, "username");
        String access = XmlUtils.readString(xdoc, "access");
        if (!fileName.endsWith(".qn")) {
            fileName += ".qn";
        }
        IQ replyPacket = IQ.createResultIQ(packet);
        String path = Constants.FILES_DIR + "/questions/" + fileName;
        if (access.equals("private")) {
            path = Constants.FILES_DIR + "/questions/" + username + "/" + fileName;
        }
        String xmlContents = Util.getContents(new File(path));

        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText("open-" + extra + "-question");


        queryResult.addElement("content").addText(extractQuestionContent(xmlContents, access,
                packet.getFrom(), path));
        replyPacket.setChildElement(queryResult);


        return replyPacket;
    }
}
