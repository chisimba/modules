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

import java.awt.Dimension;
import java.awt.Toolkit;
import java.io.ByteArrayInputStream;
import java.util.ArrayList;
import javax.swing.ImageIcon;
import javax.swing.JOptionPane;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.util.XmlUtils;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.net.packets.RealtimeQuestionPacket;
import org.avoir.realtime.questions.AnsweringFrame;
import org.avoir.realtime.questions.Value;
import org.jivesoftware.smack.util.Base64;
import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

/**
 *
 * @author developer
 */
public class QuestionProcessor {

    private static Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();

    public static void displayQuestionForAnswering(
            String question,
            int type,
            String questionName,
            ArrayList<Value> answerOptions,
            String imageData,
            String username,
            String instructor,boolean enable) {
        RealtimeQuestionPacket p = new RealtimeQuestionPacket();
        p.setQuestion(question);
        p.setQuestionType(type);
        p.setUsername(username);
        p.setFilename(questionName);
        p.setInstructor(instructor);
        p.setAnswerOptions(answerOptions);
        ImageIcon image = new ImageIcon(Base64.decode(imageData));
        p.setImage(image);
        AnsweringFrame fr = new AnsweringFrame(p, enable);
        fr.setSize((ss.width / 8) * 7, (ss.height / 8) * 7);
        fr.setLocationRelativeTo(null);
        fr.setVisible(true);
    }

    public static void extractAnswerOpts(String xmlContents) {
        try {
            xmlContents = "<realtime-answer>" + xmlContents + "</realtime-answer>";

            DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();

            DocumentBuilder documentBuilder = documentBuilderFactory.newDocumentBuilder();
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));

            RealtimeQuestionPacket p = new RealtimeQuestionPacket();

            String passStatus = XmlUtils.readString(doc, "passed");
            int qnType = XmlUtils.readInt(doc, "qn-type");
            String from = XmlUtils.readString(doc, "respondent");
            int i = from.indexOf("@");
            String user = from.substring(0, i);

//            GUIAccessManager.mf.getUserListPanel().getUserListTree().updateUser(user, from, new Boolean(passStatus));

        } catch (Exception ex) {
            ex.printStackTrace();
        }

    }

    public static RealtimeQuestionPacket getRealtimeQuestionPacket(String xmlContents) {
        try {

            xmlContents = "<realtime-question>" + xmlContents + "</realtime-question>";

            DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();

            DocumentBuilder documentBuilder = documentBuilderFactory.newDocumentBuilder();
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));

            RealtimeQuestionPacket p = new RealtimeQuestionPacket();
            String question = XmlUtils.readString(doc, "question");

            p.setQuestion(question == null? null: (question.trim().equals("") ? null : question));
            String filename = XmlUtils.readString(doc, "question-filename");
            String access = XmlUtils.readString(doc, "question-access");
            String instrutor = XmlUtils.readString(doc, "question-instructor");

            String username = XmlUtils.readString(doc, "question-username");
            p.setUsername(username);
            p.setInstructor(instrutor);
            p.setAccess(access);
            p.setFilename(filename);
            int qnType = XmlUtils.readInt(doc, "question-type");
            p.setQuestionType(qnType);
            String imageData = XmlUtils.readString(doc, "question-image-data");
            p.setImageData(imageData);
            String questionPath = XmlUtils.readString(doc, "question-path");
            p.setQuestionPath(questionPath);
            String imagePath = XmlUtils.readString(doc, "question-image-path");
            p.setImagePath(imagePath);
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
            p.setAnswerOptions(values);

            return p;
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return null;
    }
}
