/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.instructor.whiteboard;

import avoir.realtime.common.BuilderSlide;
import avoir.realtime.classroom.whiteboard.item.Img;
import avoir.realtime.common.Base64;
import avoir.realtime.common.Constants;
import avoir.realtime.common.packet.FileUploadPacket;
import avoir.realtime.common.packet.UploadMsgPacket;
import avoir.realtime.common.packet.XmlQuestionPacket;
import avoir.realtime.filetransfer.FileReceiverManager;
import avoir.realtime.survey.Value;
import java.awt.Color;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.OutputStream;
import java.text.DecimalFormat;
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
public class InstructorFileReceiverManager extends FileReceiverManager {

    private Classroom mf;

    public InstructorFileReceiverManager(Classroom mf) {
        super(mf);
        this.mf = mf;
    }

    static public String getContents(File aFile) {
        StringBuilder contents = new StringBuilder();

        try {

            BufferedReader input = new BufferedReader(new FileReader(aFile));
            try {
                String line = null;

                while ((line = input.readLine()) != null) {
                    contents.append(line);
                    contents.append(System.getProperty("line.separator"));
                }
            } finally {
                input.close();
            }
        } catch (IOException ex) {
            ex.printStackTrace();
        }

        return contents.toString();
    }

    @Override
    public void processFileDownload(FileUploadPacket p) {
        new File(mf.getUser().getUserName()).mkdirs();
        new File(Constants.getRealtimeHome() + "/classroom/images/" + mf.getUser().getSessionId()).mkdirs();
        new File(Constants.getRealtimeHome() + "/classroom/flash/" + mf.getUser().getSessionId()).mkdirs();
        new File(Constants.getRealtimeHome() + "/classroom/documents/" + mf.getUser().getSessionId()).mkdirs();
        if (p.getFileType() == Constants.IMAGE ||
                p.getFileType() == Constants.QUESTION_IMAGE ||
                p.getFileType() == Constants.SLIDE_BUILDER_IMAGE) {
            filename = Constants.getRealtimeHome() + "/classroom/images/" + mf.getUser().getSessionId() + "/" + p.getFilename();
        }
        if (p.getFileType() == Constants.SLIDE_BUILDER_TEXT ||
                p.getFileType() == Constants.QUESTION_FILE ||
                p.getFileType() == Constants.NOTEPAD ||
                p.getFileType() == Constants.SLIDE_SHOW) {
            filename = Constants.getRealtimeHome() + "/classroom/documents/" + mf.getUser().getSessionId() + "/" + p.getFilename();
        }


        if (p.getFileType() == Constants.FLASH) {
            filename = Constants.getRealtimeHome() + "/classroom/flash/" + mf.getUser().getSessionId() + "/" + p.getFilename();
        }
        int nChunks = p.getTotalChunks();
        int chunk = p.getChunkNo();
        String clientID = p.getClientId();

        if (nChunks == -1 || chunk == -1) {
            System.out.println("Missing chunk information");
            mf.getTcpConnector().sendPacket(
                    new UploadMsgPacket(mf.getUser().getSessionId(), "Missing chunck information", p.getSender(), p.getRecepientIndex()));

        }

        if (chunk == 0) {
            // check permission to create file here
        }

        OutputStream out = null;

        try {
            if (nChunks == 1) {
                out = new FileOutputStream(filename);
            } else {
                out = new FileOutputStream(getTempFile(clientID), (chunk > 0));
            }

            out.write(p.getBuff());
            out.close();
            if (nChunks == 1) {

                mf.getTcpConnector().sendPacket(new UploadMsgPacket(mf.getUser().getSessionId(), "Successful", p.getSender(), p.getRecepientIndex()));

                mf.showInfoMessage("Download complete.");
                if (p.getFileType() == Constants.SLIDE_SHOW) {
                    ArrayList<BuilderSlide> list = readXmlSlideShowFile(filename);
                    mf.getSlideBuilderManager().setSlides(list, stripExt(new File(filename).getName()));

                }
                if (p.getFileType() == Constants.NOTEPAD) {
                    javax.swing.text.Document doc = getNotepadContent(filename);
                    base.showNotepad(doc, new File(filename).getName(), filename);
                }
                if (p.getFileType() == Constants.QUESTION_IMAGE) {
                    ImageIcon image = new ImageIcon(filename);
                    mf.getSurveyManagerFrame().setQuestionImage(image);
                }
                if (p.getFileType() == Constants.SLIDE_BUILDER_IMAGE) {
                    ImageIcon image = new ImageIcon(filename);
                    mf.getSlideBuilderManager().setSlideImage(image);
                }
                if (p.getFileType() == Constants.QUESTION_FILE) {
                    mf.getSlideBuilderManager().setQuestion(readXmlQuestionFile(filename));
                }
                if (p.getFileType() == Constants.SLIDE_BUILDER_TEXT) {
                    mf.getSlideBuilderManager().setSlideText(getContents(new File(filename)));
                }
                if (p.getFileType() == Constants.IMAGE) {

                    base.getWhiteBoardSurface().setPointer(Constants.WHITEBOARD);
                    ImageIcon image = new ImageIcon(filename);

                    base.getWhiteBoardSurface().addItem(new Img(100, 100, image.getIconWidth(), image.getIconHeight(), p.getFilename(), base.getWhiteBoardSurface().getImgs().size(), p.getId()));/// (int) image.getIconWidth(), (int) image.getIconHeight(), base.getSessionId(),imageIndex++));

                    base.getWhiteBoardSurface().setImage(image);
                }
                return;
            }

        } catch (Exception ex) {
            ex.printStackTrace();
            System.out.println("Error creating file");
            mf.showErrorMessage("Error saving file.");
            mf.getTcpConnector().sendPacket(new UploadMsgPacket(mf.getUser().getSessionId(), "Error saving file", p.getSender(), p.getRecepientIndex()));
            return;
        }

        if (nChunks > 1 && chunk == nChunks - 1) {

            File tmpFile = new File(getTempFile(clientID));
            File destFile = new File(filename);
            if (destFile.exists()) {
                destFile.delete();
            }
            if (!tmpFile.renameTo(destFile)) {
                mf.getTcpConnector().sendPacket(new UploadMsgPacket(mf.getUser().getSessionId(), "Unable to create file", p.getSender(), p.getRecepientIndex()));
                mf.showErrorMessage("Error saving file.");
            } else {
                mf.getTcpConnector().sendPacket(new UploadMsgPacket(mf.getUser().getSessionId(), "Successful.", p.getSender(), p.getRecepientIndex()));
                mf.showInfoMessage("Download complete.");
                if (p.getFileType() == Constants.SLIDE_BUILDER_IMAGE) {
                    ImageIcon image = new ImageIcon(filename);
                    mf.getSlideBuilderManager().setSlideImage(image);

                }
                if (p.getFileType() == Constants.QUESTION_IMAGE) {
                    ImageIcon image = new ImageIcon(filename);
                    mf.getSurveyManagerFrame().setQuestionImage(image);
                }
                if (p.getFileType() == Constants.SLIDE_SHOW) {
                    ArrayList<BuilderSlide> list = readXmlSlideShowFile(filename);
                    mf.getSlideBuilderManager().setSlides(list, stripExt(new File(filename).getName()));

                }
                if (p.getFileType() == Constants.NOTEPAD) {
                    javax.swing.text.Document doc = getNotepadContent(filename);
                    base.showNotepad(doc, new File(filename).getName(), filename);
                }
                if (p.getFileType() == Constants.SLIDE_BUILDER_TEXT) {
                    mf.getSlideBuilderManager().setSlideText(getContents(new File(filename)));
                }
                if (p.getFileType() == Constants.QUESTION_FILE) {
                    mf.getSlideBuilderManager().setQuestion(readXmlQuestionFile(filename));
                }
                if (p.getFileType() == Constants.IMAGE) {

                    base.getWhiteBoardSurface().setPointer(Constants.WHITEBOARD);
                    ImageIcon image = new ImageIcon(filename);

                    base.getWhiteBoardSurface().addItem(new Img(100, 100, image.getIconWidth(), image.getIconHeight(), p.getFilename(), base.getWhiteBoardSurface().getImgs().size(), p.getId()));/// (int) image.getIconWidth(), (int) image.getIconHeight(), base.getSessionId(),imageIndex++));

                    base.getWhiteBoardSurface().setImage(image);
                }
            }

        } else {
            float val = chunk + 1;
            float total = p.getTotalChunks();
            String sval = new DecimalFormat("#.#").format((val / total) * 100) + "%";
            mf.getTcpConnector().sendPacket(new UploadMsgPacket(mf.getUser().getSessionId(), sval, p.getSender(), p.getRecepientIndex()));
            mf.showInfoMessage("Downloading " + new File(filename).getName() + " " + sval + " ..");

        }
    }

    private String stripExt(String s) {
        if (s.lastIndexOf(".") > -1) {
            return s.substring(0, s.lastIndexOf("."));
        }
        return s;
    }

    private javax.swing.text.Document getNotepadContent(String filePath) {
        File f = new File(filePath);
        try {
            if (f.exists()) {

                FileInputStream fin = new FileInputStream(f);
                ObjectInputStream istrm = new ObjectInputStream(fin);
                javax.swing.text.Document doc = (javax.swing.text.Document) istrm.readObject();

                return doc;
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return null;
    }

    private XmlQuestionPacket readXmlQuestionFile(String path) {
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
            NodeList slides = doc.getElementsByTagName("answer");
            ArrayList<Value> values = new ArrayList<Value>();
            for (int i = 0; i < slides.getLength(); i++) {
                Node node = slides.item(i);
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

    private XmlQuestionPacket readXmlElememnt(Element doc) {
        try {
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
            NodeList slides = doc.getElementsByTagName("answer");
            ArrayList<Value> values = new ArrayList<Value>();
            for (int i = 0; i < slides.getLength(); i++) {
                Node node = slides.item(i);
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
                return new XmlQuestionPacket(name, values, type, theSender, theId, null,
                        null, imagePath);
            }
            ImageIcon image = new ImageIcon(Base64.decode(imagePath));
            if (imagePath != null || !imagePath.equals("null")) {
                image = new ImageIcon(imagePath);
            }
            return new XmlQuestionPacket(name, values, type, theSender, theId, null,
                    image, imagePath);
        } catch (Exception ex) {
            ex.printStackTrace();
            return null;
        }
    }

    private ArrayList<BuilderSlide> readXmlSlideShowFile(String path) {
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

                    NodeList titlevals = element.getElementsByTagName("title");
                    Element titleElement = (Element) titlevals.item(0);
                    NodeList titleNodes = titleElement.getChildNodes();
                    String title = ((Node) titleNodes.item(0)).getNodeValue();

                    NodeList textvals = element.getElementsByTagName("text");
                    Element textElement = (Element) textvals.item(0);
                    NodeList textNodes = textElement.getChildNodes();
                    String text = ((Node) textNodes.item(0)).getNodeValue();

                    String imageData = "";

                    try {
                        NodeList imagevals = element.getElementsByTagName("image-data");
                        Element imageElement = (Element) imagevals.item(0);
                        NodeList imageNodes = imageElement.getChildNodes();
                        imageData = ((Node) imageNodes.item(0)).getNodeValue();
                    } catch (Exception ex) {
                        ex.printStackTrace();
                    }


                    byte[] data = Base64.decode(imageData);

                    String texts = "18";
                    NodeList textsvals = element.getElementsByTagName("text-size");
                    Element textsElement = (Element) textsvals.item(0);
                    if (textsElement != null) {
                        NodeList textsNodes = textsElement.getChildNodes();
                        texts = ((Node) textsNodes.item(0)).getNodeValue();
                    }
                    int textSize = 18;
                    try {
                        textSize = Integer.parseInt(texts.trim());
                    } catch (NumberFormatException ex) {
                        ex.printStackTrace();
                    }

                    NodeList colorList = doc.getElementsByTagName("text-color");
                    Color color = Color.BLUE;
                    for (int k = 0; k < colorList.getLength(); k++) {
                        Node colorNode = colorList.item(k);
                        if (colorNode.getNodeType() == Node.ELEMENT_NODE) {
                            Element colorElement = (Element) colorNode;

                            NodeList rvals = colorElement.getElementsByTagName("red");
                            Element rElement = (Element) rvals.item(0);
                            NodeList rNodes = rElement.getChildNodes();
                            String rs = ((Node) rNodes.item(0)).getNodeValue();
                            int red = 0;
                            try {
                                red = Integer.parseInt(rs.trim());
                            } catch (NumberFormatException ex) {
                                ex.printStackTrace();
                            }
                            NodeList gvals = element.getElementsByTagName("green");
                            Element gElement = (Element) gvals.item(0);
                            NodeList gNodes = gElement.getChildNodes();
                            String gs = ((Node) gNodes.item(0)).getNodeValue();
                            int green = 0;
                            try {
                                green = Integer.parseInt(gs.trim());
                            } catch (NumberFormatException ex) {
                                ex.printStackTrace();
                            }

                            NodeList bvals = element.getElementsByTagName("blue");
                            Element bElement = (Element) bvals.item(0);
                            NodeList bNodes = bElement.getChildNodes();
                            String bs = ((Node) bNodes.item(0)).getNodeValue();
                            int blue = 0;

                            try {
                                blue = Integer.parseInt(bs.trim());
                            } catch (NumberFormatException ex) {
                                ex.printStackTrace();
                            }
                            color = new Color(red, green, blue);
                        }
                    }

                    XmlQuestionPacket qpacket = readXmlElememnt((Element) node);
                    BuilderSlide slide = new BuilderSlide(title, text, color, textSize, qpacket, new ImageIcon(data), null);
                    slides.add(slide);

                }
            }
            return slides;
        } catch (Exception ex) {
            ex.printStackTrace();
            return null;
        }
    }
}
