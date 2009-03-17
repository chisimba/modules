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
                    String url=readString(element, "url");
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
                    String qname = readString(element, "question-str");
                    int type = readInt(element, "type");
                    String qsender = readString(element, "sender");
                    String qid = readString(element, "id");
                    String qimageData = readString(element, "image-data");
                    NodeList answersList = doc.getElementsByTagName("answer");
                    ArrayList<Value> values = new ArrayList<Value>();
                    for (int k = 0; k < answersList.getLength(); k++) {
                        Node answerNode = answersList.item(k);
                        if (node.getNodeType() == Node.ELEMENT_NODE) {
                            Element answerElement = (Element) answerNode;
                            String opt = readString(answerElement, "option");
                            String value = readString(answerElement, "value");
                            String correctAnswer = readString(answerElement, "correct-answer");
                            values.add(new Value(opt, value, new Boolean(correctAnswer).booleanValue()));
                            
                        }
                    }


                    XmlQuestionPacket qpacket = null;
                    ImageIcon icon = null;
                    if (qimageData != null) {
                        byte[] xdata = Base64.decode(qimageData);
                        icon = new ImageIcon(xdata);
                    }
                    qpacket = new XmlQuestionPacket(qname, values, type, qsender, qid,
                            new File(path).getName(),
                            icon, "");

                    BuilderSlide slide = new BuilderSlide(title, text, color, textSize, qpacket,
                            new ImageIcon(data), null, index, textXPos, textYPos,url);
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
            String imageData = readString(doc, "image-data");
            if (imageData != null) {
                byte[] data = Base64.decode(imageData);
                ImageIcon icon = new ImageIcon(data);
                return new XmlQuestionPacket(name, values, type, theSender, theId, new File(path).getName(),
                        icon, null);

            }
        } catch (Exception ex) {
            ex.printStackTrace();
            return null;
        }
        return null;
    }
}
