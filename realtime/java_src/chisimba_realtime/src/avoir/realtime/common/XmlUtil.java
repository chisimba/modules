/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.common;

import avoir.realtime.common.packet.XmlQuestionPacket;
import avoir.realtime.survey.Value;
import java.awt.Color;
import java.io.File;
import java.util.ArrayList;
import javax.swing.ImageIcon;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

/**
 *
 * @author developer
 */
public class XmlUtil {

    public static ArrayList<BuilderSlide> readXmlSlideShowFile(String path) {
        ArrayList<BuilderSlide> slides = new ArrayList<BuilderSlide>();
        try {
            DocumentBuilderFactory docBuilderFactory = DocumentBuilderFactory.newInstance();
            DocumentBuilder docBuilder = docBuilderFactory.newDocumentBuilder();
            Document doc = docBuilder.parse(new File(path));
            doc.getDocumentElement().normalize();


            NodeList slideList = doc.getElementsByTagName("slide");

            for (int i = 0; i < slideList.getLength(); i++) {
                Node node = slideList.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    Element element = (Element) node;
                    String title = readString(element, "title");
                    String text = readString(element, "text");
                    String imageData = readString(element, "image-data");
                    byte[] data = Base64.decode(imageData);
                    int textSize = readInt(element, "text-size");
                    int textXPos = readInt(element, "text-x-pos");
                    int textYPos = readInt(element, "text-y-pos");
                    NodeList colorList = doc.getElementsByTagName("text-color");
                    Color color = Color.BLUE;
                    for (int k = 0; k < colorList.getLength(); k++) {
                        Node colorNode = colorList.item(k);
                        if (colorNode.getNodeType() == Node.ELEMENT_NODE) {
                            Element colorElement = (Element) colorNode;
                            color = new Color(
                                    readInt(colorElement, "red"),
                                    readInt(colorElement, "green"),
                                    readInt(colorElement, "blue"));

                        }
                    }

                    int index = readInt(element, "index");
                    String qname = readString(doc, "question-str");
                    int type = readInt(doc, "type");
                    String qsender = readString(doc, "sender");
                    String qid = readString(doc, "id");
                    String qimagePath = readString(doc, "image-path");
                    NodeList answersList = doc.getElementsByTagName("answers");
                    ArrayList<Value> values = new ArrayList<Value>();
                    for (int k = 0; k < colorList.getLength(); k++) {
                        Node answerNode = answersList.item(k);
                        if (node.getNodeType() == Node.ELEMENT_NODE) {
                            Element answerElement = (Element) answerNode;
                            String opt = readString(answerElement, "option");
                            String value = readString(answerElement, "value");
                            String correctAnswer = readString(answerElement, "corrent-answer");
                            values.add(new Value(opt, value, new Boolean(correctAnswer).booleanValue()));
                        }
                    }
                    XmlQuestionPacket qpacket = null;
                    if (qimagePath.equals("null")) {
                        qpacket = new XmlQuestionPacket(qname, values, type, qsender, qid,
                                new File(path).getName(),
                                null, qimagePath);
                    }
                    ImageIcon image = new ImageIcon();
                    if (qimagePath != null || !qimagePath.equals("null")) {
                        image = new ImageIcon(qimagePath);
                        qpacket = new XmlQuestionPacket(qname, values, type, qsender, qid,
                                new File(path).getName(),
                                image, qimagePath);
                    }


                    BuilderSlide slide = new BuilderSlide(title, text, color, textSize, qpacket,
                            new ImageIcon(data), null, index, textXPos, textYPos);
                    slides.add(slide);

                }
            }
            return slides;
        } catch (Exception ex) {
            ex.printStackTrace();
            return null;
        }
    }

    private static String readString(Element element, String str) {
        String val = "";

        try {
            NodeList bvals = element.getElementsByTagName(str);
            Element bElement = (Element) bvals.item(0);
            NodeList bNodes = bElement.getChildNodes();
            val = ((Node) bNodes.item(0)).getNodeValue();
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return val;
    }

    private static String readString(Document doc, String str) {
        String val = "";
        try {
            NodeList sender = doc.getElementsByTagName(str);
            for (int i = 0; i < sender.getLength(); i++) {
                Node node = sender.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    Element element = (Element) node;
                    NodeList vals = element.getChildNodes();
                    val = ((Node) vals.item(0)).getNodeValue();
                }
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }

        return val;
    }

    private static int readInt(Document doc, String str) {
        int val = 0;
        try {
            NodeList sender = doc.getElementsByTagName(str);

            for (int i = 0; i < sender.getLength(); i++) {
                Node node = sender.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    Element element = (Element) node;
                    NodeList vals = element.getChildNodes();
                    String s = ((Node) vals.item(0)).getNodeValue();
                    val = Integer.parseInt(s);
                }
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return val;
    }

    private static int readInt(Element element, String str) {
        int val = 0;

        try {
            NodeList bvals = element.getElementsByTagName(str);
            Element bElement = (Element) bvals.item(0);
            NodeList bNodes = bElement.getChildNodes();
            String bs = ((Node) bNodes.item(0)).getNodeValue();

            val = Integer.parseInt(bs.trim());
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return val;
    }

    public static XmlQuestionPacket readXmlQuestionFile(String path) {
        try {
            DocumentBuilderFactory docBuilderFactory = DocumentBuilderFactory.newInstance();
            DocumentBuilder docBuilder = docBuilderFactory.newDocumentBuilder();
            Document doc = docBuilder.parse(new File(path));
            doc.getDocumentElement().normalize();

            String name = readString(doc, "question-str");
            NodeList slides = doc.getElementsByTagName("answer");
            ArrayList<Value> values = new ArrayList<Value>();
            for (int i = 0; i < slides.getLength(); i++) {
                Node node = slides.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    Element element = (Element) node;
                    String opt = readString(element, "option");
                    String value = readString(element, "value");
                    String correctAnswer = readString(element, "corrent-answer");
                    values.add(new Value(opt, value, new Boolean(correctAnswer).booleanValue()));

                }
            }
            int type = readInt(doc, "type");
            String theSender = readString(doc, "sender");
            String theId = readString(doc, "id");
            String imagePath = readString(doc, "image-path");

            if (imagePath.equals("null")) {
                return new XmlQuestionPacket(name, values, type, theSender, theId, new File(path).getName(),
                        null, imagePath);
            }
            ImageIcon image = new ImageIcon();
            if (imagePath != null || !imagePath.equals("null")) {
                image = new ImageIcon(imagePath);
            }
            return new XmlQuestionPacket(name, values, type, theSender, theId, new File(path).getName(),
                    image, imagePath);
        } catch (Exception ex) {
            ex.printStackTrace();
            return null;
        }
    }
}
