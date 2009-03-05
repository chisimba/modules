/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.server;

import avoir.realtime.common.Base64;
import avoir.realtime.common.XmlUtil;
import avoir.realtime.common.packet.XmlQuestionPacket;
import avoir.realtime.survey.Value;
import java.awt.Graphics;
import java.awt.Image;
import java.awt.image.BufferedImage;
import java.io.BufferedWriter;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;

import java.util.ArrayList;
import javax.imageio.ImageIO;
import javax.swing.ImageIcon;
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
        serverThread.sendPacket(XmlUtil.readXmlQuestionFile(path).getQuestionPacket(), serverThread.getObjectOutStream());


        /*File f = new File(path);
        String[] files = f.list();
        if (files != null) {
        for (int i = 0; i < files.length; i++) {
        serverThread.sendPacket(readXml(path + "/" + files[i]), serverThread.getObjectOutStream());
        }
        }*/
    }

    private String encodeImage(Image image) {
        try {

            if (image == null) {
                return "";
            }

            BufferedImage bu = new BufferedImage(image.getWidth(null), image.getHeight(null), BufferedImage.TYPE_INT_RGB);
            Graphics g = bu.getGraphics();
            g.drawImage(image, 0, 0, null);
            g.dispose();
            ByteArrayOutputStream bas = new ByteArrayOutputStream();
            ImageIO.write(bu, "png", bas);
            byte[] data = bas.toByteArray();
            return Base64.encode(data);
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
        String imageData = "";
        Image image = null;
        ImageIcon imageIcon = new ImageIcon(p.getImagePath());
        if (imageIcon != null) {
            image = imageIcon.getImage();
            if (image != null) {
                imageData = encodeImage(image);
            }
        }

        content += "\t<image-data>" + imageData + "</image-data>\n";

        content += "\t<sender>" + p.getSender() + "</sender>\n";
        content += "\t<id>" + p.getId() + "</id>\n";

        content += "</question>\n";
        write(name, content);
        serverThread.getFileManagerProcessor().updateFileView(path);

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
