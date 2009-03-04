/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.server;

import avoir.realtime.common.Constants;
import avoir.realtime.common.packet.XmlQuestionPacket;
import avoir.realtime.survey.Value;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import javax.swing.ImageIcon;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.DocumentBuilder;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.Attributes;
import org.xml.sax.helpers.DefaultHandler;

/**
 *
 * @author developer
 */
public class QuestionProcessor {

    private ServerThread serverThread;

    class SaxHandler extends DefaultHandler {

        @Override
        public void startElement(String uri, String localName, String qname,
                Attributes attrs) {

            if (qname.equals("question")) {
                String value = attrs.getValue("qname");
                System.out.println("Value: " + value);
            }
            if (qname.equals("answer")) {
                String value = attrs.getValue("value");
                String opt = attrs.getValue("option");
                System.out.println("Option:  " + opt + " Value: " + value);

            }
            System.out.println("Qname: " + qname);
            System.out.println("uri: " + uri);
            System.out.println("localname: " + localName);
        }
    }

    public QuestionProcessor(ServerThread serverThread) {
        this.serverThread = serverThread;
    }

    public void sendQuestion(String path) {
        // String path = serverThread.getSERVER_HOME() + "/userfiles/" + serverThread.getThisUser().getUserName() + "/questions/";
        serverThread.sendPacket(readXml(path), serverThread.getObjectOutStream());


        /*File f = new File(path);
        String[] files = f.list();
        if (files != null) {
        for (int i = 0; i < files.length; i++) {
        serverThread.sendPacket(readXml(path + "/" + files[i]), serverThread.getObjectOutStream());
        }
        }*/
    }

    private XmlQuestionPacket readXml(String path) {
        try {
            DocumentBuilderFactory docBuilderFactory = DocumentBuilderFactory.newInstance();
            DocumentBuilder docBuilder = docBuilderFactory.newDocumentBuilder();
            Document doc = docBuilder.parse(new File(path));
            doc.getDocumentElement().normalize();

            String name = "";
            NodeList qn = doc.getElementsByTagName("question-str");
            for (int i = 0; i < qn.getLength(); i++) {
                Node node = qn.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    Element element = (Element) node;
                    NodeList vals = element.getChildNodes();
                    name = ((Node) vals.item(0)).getNodeValue();
                }
            }
            NodeList answersList = doc.getElementsByTagName("answer");
            ArrayList<Value> values = new ArrayList<Value>();
            for (int i = 0; i < answersList.getLength(); i++) {
                Node node = answersList.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    Element element = (Element) node;

                    NodeList optvals = element.getElementsByTagName("option");
                    Element optionElement = (Element) optvals.item(0);
                    NodeList optNodes = optionElement.getChildNodes();
                    String opt = ((Node) optNodes.item(0)).getNodeValue();

                    NodeList valvals = element.getElementsByTagName("value");
                    Element valElement = (Element) valvals.item(0);
                    NodeList valNodes = valElement.getChildNodes();
                    String val = ((Node) valNodes.item(0)).getNodeValue();

                    NodeList ca = element.getElementsByTagName("correct-answer");
                    Element caElement = (Element) ca.item(0);
                    NodeList caNodes = caElement.getChildNodes();
                    String castr = ((Node) caNodes.item(0)).getNodeValue();

                    values.add(new Value(opt, val, new Boolean(castr).booleanValue()));

                }
            }

            NodeList essay = doc.getElementsByTagName("type");
            int type = Constants.ESSAY_QUESTION;
            for (int i = 0; i < essay.getLength(); i++) {
                Node node = essay.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    Element element = (Element) node;
                    NodeList vals = element.getChildNodes();
                    type = Integer.parseInt(((Node) vals.item(0)).getNodeValue());
                }
            }

            NodeList sender = doc.getElementsByTagName("sender");
            String theSender = "";
            for (int i = 0; i < sender.getLength(); i++) {
                Node node = sender.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    Element element = (Element) node;
                    NodeList vals = element.getChildNodes();
                    theSender = ((Node) vals.item(0)).getNodeValue();
                }
            }
            NodeList id = doc.getElementsByTagName("id");
            String theId = "";
            for (int i = 0; i < id.getLength(); i++) {
                Node node = id.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    Element element = (Element) node;
                    NodeList vals = element.getChildNodes();
                    theId = ((Node) vals.item(0)).getNodeValue();
                }
            }
            NodeList im = doc.getElementsByTagName("image-path");
            String imagePath = "";
            for (int i = 0; i < im.getLength(); i++) {
                Node node = im.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    Element element = (Element) node;
                    NodeList vals = element.getChildNodes();
                    imagePath = ((Node) vals.item(0)).getNodeValue();
                }
            }

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

    public void processXmlQuestionPacket(XmlQuestionPacket p) {
        String path = serverThread.getSERVER_HOME() + "/userfiles/" + serverThread.getThisUser().getUserName() + "/questions/";
        new File(path).mkdirs();
        String name = "";
        if (p.getName().endsWith(".xml")) {
            name = path + "/" + p.getName();
        } else {
            name = path + "/" + p.getName() + ".xml";
        }

        String content = "<question qname=\"" + p.getQuestion() + "\">\n";
        content += "\t<question-str>" + p.getQuestion() + "</question-str>\n";

        content += "\t<answers>\n";
        ArrayList<Value> values = p.getAnswerOptions();
        for (int i = 0; i < values.size(); i++) {
            content += "\t\t<answer>\n";
            content += "\t\t\t<option>" + values.get(i).getOption() + "</option>\n";
            content += "\t\t\t<value>" + values.get(i).getValue() + "</value>\n";
            content += "\t\t\t<correct-answer>" + values.get(i).isCorrectAnswer() + "</correct-answer>\n";
            content += "\t\t</answer>\n";
        }
        content += "\t</answers>\n";
        content += "\t<type>" + p.getType() + "</type>\n";
        content += "\t<image-path>" + p.getImagePath() + "</image-path>\n";

        content += "\t<sender>" + p.getSender() + "</sender>\n";
        content += "\t<id>" + p.getId() + "</id>\n";

        content += "</question>\n";
        write(name, content);

    }

    public synchronized void write(String fileName, String txt) {
        try {
            BufferedWriter out = new BufferedWriter(new FileWriter(fileName));
            out.write(txt + "\n");
            out.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
