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

import com.artofsolving.jodconverter.DocumentConverter;
import com.artofsolving.jodconverter.openoffice.connection.OpenOfficeConnection;
import com.artofsolving.jodconverter.openoffice.connection.SocketOpenOfficeConnection;
import com.artofsolving.jodconverter.openoffice.converter.OpenOfficeDocumentConverter;

import java.awt.Color;
import java.awt.Graphics2D;
import java.awt.RenderingHints;
import java.awt.geom.AffineTransform;
import java.awt.image.BufferedImage;
import java.io.BufferedReader;
import java.io.ByteArrayInputStream;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;
import java.net.ConnectException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import javax.imageio.ImageIO;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;


import org.avoir.realtime.plugins.packets.RealtimeSlideShowPacket;
import org.avoir.realtime.plugins.packets.Slide;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;
import org.dom4j.QName;
import org.jivesoftware.openfire.PacketRouter;
import org.jivesoftware.util.Base64;
import org.jpedal.PdfDecoder;
import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xmpp.packet.IQ;
import org.xmpp.packet.JID;

/**
 *
 * @author developer
 */
public class SlideShowProcessor {

    private AvoirRealtimePlugin pl;
    // private ArrayList<JID> jids;
    private PacketRouter packetRouter;
    private DocumentBuilder documentBuilder;
    private DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();

    public SlideShowProcessor(AvoirRealtimePlugin pl) {
        this.pl = pl;
        //  jids = pl.getUsers(false);
        packetRouter = pl.getPacketRouter();
        try {
            documentBuilder = documentBuilderFactory.newDocumentBuilder();
        } catch (Exception ex) {
            ex.printStackTrace();
        }
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

    public IQ saveSlideShowFile(IQ packet, Document doc, String roomName) {
        String fileName = XmlUtils.readString(doc, "filename");
        String username = XmlUtils.readString(doc, "username");
        Boolean modified = new Boolean(XmlUtils.readString(doc, "modified"));
        String access = XmlUtils.readString(doc, "access");
        String contents = packet.toXML();
        RealtimeSlideShowPacket p = getRealtimeSlideShowPacket(contents);
        String presentationName = p.getFilename();
        int presentationId = pl.getRoomResourceManager().getPresentationId(presentationName);
        String mode = Mode.SLIDE_UPLOAD_SUCCESS;
        if (presentationId == -1) { //then this is new
            presentationId = pl.getRoomResourceManager().addPresentationName(username, p.getFilename());
        } else {
            Slide slide = p.getSlide();

            if (pl.getRoomResourceManager().addSlide(presentationId, slide.getTitle(), slide.getText(), slide.getSlideIndex(),
                    slide.getTextColor().getRed(), slide.getTextColor().getGreen(), slide.getTextColor().getBlue(),
                    slide.getTextSize(), slide.getQuestionPath(), slide.getUrl(), slide.getImagePath(), access, 0)) {
                mode = Mode.SLIDE_UPLOAD_SUCCESS;
            } else {
                mode = Mode.SLIDE_UPLOAD_FAILURE;
            }

        }
        IQ replyPacket = IQ.createResultIQ(packet);
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(mode);
        queryResult.addElement("content").addText("slide upload");
        replyPacket.setChildElement(queryResult);

        packetRouter.route(replyPacket);
        return null;
    /*

    String destDir = Constants.FILES_DIR + "/slideshows/" + fileName + ".ss";

    new File(Constants.FILES_DIR + "/slideshows/" + username).mkdirs();
    destDir = Constants.FILES_DIR + "/slideshows/" + fileName + ".ss";

    if (access.equals("private")) {
    destDir = Constants.FILES_DIR + "/slideshows/" + username + "/" + fileName + ".ss";

    }
    p.setFilePath(destDir);
    Writer output = null;
    try {
    output = new BufferedWriter(new FileWriter(destDir));
    output.write("<slide-show>" + p.getChildElementXML() + "</slide-show>");
    } catch (IOException ex) {
    ex.printStackTrace();
    } finally {
    try {
    output.close();
    } catch (Exception ex) {
    ex.printStackTrace();
    }
    }
    if (modified != null) {
    if (modified && pl.getRoomResourceManager().roomResourceExists(destDir)) {
    pl.getRoomResourceManager().increaseSlideShowRoomResourceVersion(packet, destDir);
    //detect, contruct and push changes to the users
    pushChangesToUsers(packet, p, roomName);
    }
    }
    return pl.getDefaultPacketProcessor().getFileView(packet, "slideshows", username);*/
    }

    private void pushChangesToUsers(IQ packet, RealtimeSlideShowPacket p, String roomName) {
        Slide slide = p.getSlide();
        ArrayList<Slide> modifiedSlides = new ArrayList<Slide>();
        ArrayList<Map> modifiedTitles = new ArrayList<Map>();


        if (slide.isContentModified()) {
            modifiedSlides.add(slide);
        }
        if (slide.isTitleModified()) {
            Map<String, String> m = new HashMap<String, String>();
            m.put("old-title", slide.getOldTitle());
            m.put("new-title", slide.getTitle());
            modifiedTitles.add(m);
        }

        //then construct xml to send over...

        StringBuilder buf = new StringBuilder();
        buf.append("<changes>");
        buf.append("<filename>").append(p.getFilename()).append("</filename>");
        buf.append("<modified-slides>");
        for (Slide xslide : modifiedSlides) {
            buf.append("<slide>");
            buf.append("<title>").append(xslide.getTitle() + "</title>");
            buf.append("<index>").append(xslide.getSlideIndex() + "</index>");
            buf.append("<text>").append(xslide.getText() + "</text>");
            buf.append("<text-color>");
            Color col = xslide.getTextColor();
            buf.append("<red>").append(col.getRed() + "</red>");
            buf.append("<green>").append(col.getGreen() + "</green>");
            buf.append("<blue>").append(col.getBlue() + "</blue>");
            buf.append("</text-color>");
            buf.append("<text-size>").append(xslide.getTextSize() + "</text-size>");

            buf.append("<question-path>").append(xslide.getQuestionPath() + "</question-path>");
            buf.append("<url>").append(xslide.getUrl() + "</url>");
            buf.append("<image-path>").append(xslide.getImagePath() + "</image-path>");
            buf.append("<title-modified>").append(xslide.isTitleModified()).append("</title-modified>");
            buf.append("<content-modified>").append(xslide.isContentModified()).append("</content-modified>");
            buf.append("<old-title>").append(xslide.getOldTitle()).append("</old-title>");
            buf.append("<is-question-slide>").append(xslide.isQuestionSlide()).append("</is-question-slide>");
            buf.append("<is-image-slide>").append(xslide.isImageSlide()).append("</is-image-slide>");
            buf.append("<is-url-slide>").append(xslide.isUrlSlide()).append("</is-url-slide>");

            buf.append("</slide>");
        }
        buf.append("</modified-slides>");
        buf.append("<modified-titles>");

        for (Map map : modifiedTitles) {
            buf.append("<modified-title>");
            buf.append("<old-title>").append(map.get("old-title")).append("</old-title>");
            buf.append("<new-title>").append(map.get("new-title")).append("</new-title>");
            buf.append("</modified-title>");
        }
        buf.append("</modified-titles>");
        buf.append("</changes>");
        IQ replyPacket = IQ.createResultIQ(packet);
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(Mode.SLIDE_SHOW_CHANGES);
        queryResult.addElement("content").addText(buf.toString());
        replyPacket.setChildElement(queryResult);
        ArrayList<JID> jids = RUserManager.users.get(roomName);
        for (int i = 0; i < jids.size(); i++) {
            JID jid = jids.get(i);
            replyPacket.setTo(jid);
            replyPacket.setFrom(packet.getFrom());

            packetRouter.route(replyPacket);
        }
    }

    private String extractContent(String xmlContents, IQ packet, int version, String access, String filePath) {
        StringBuilder sb = new StringBuilder();

        try {
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));

            String username = XmlUtils.readString(doc, "username");

            NodeList slidesNodeList = doc.getElementsByTagName("slide");
            String filename = XmlUtils.readString(doc, "filename");

            ArrayList<Slide> slides = new ArrayList<Slide>();
            for (int i = 0; i < slidesNodeList.getLength(); i++) {
                Node node = slidesNodeList.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    org.w3c.dom.Element element = (org.w3c.dom.Element) node;
                    String title = XmlUtils.readString(element, "title");
                    String text = XmlUtils.readString(element, "text");
                    int textSize = XmlUtils.readInt(element, "text-size");
                    NodeList colorNodeList = node.getChildNodes();
                    int r = 0;
                    int g = 0;
                    int b = 0;
                    for (int j = 0; j < colorNodeList.getLength(); j++) {
                        Node colorNode = slidesNodeList.item(i);
                        if (colorNode.getNodeType() == Node.ELEMENT_NODE) {
                            org.w3c.dom.Element colorElement = (org.w3c.dom.Element) colorNode;
                            r = XmlUtils.readInt(colorElement, "red");
                            g = XmlUtils.readInt(colorElement, "green");
                            b = XmlUtils.readInt(colorElement, "blue");
                        }
                    }
                    Color textColor = new Color(r, g, b);
                    String questionPath = XmlUtils.readString(element, "question-path");

                    String url = XmlUtils.readString(element, "url");
                    String imagePath = XmlUtils.readString(element, "image-path");
                    slides.add(new Slide(title, text, textColor, textSize, questionPath, imagePath,
                            url, null, null, i));
                }
            }
            if (!filePath.startsWith(Constants.FILES_DIR)) {
                filePath += Constants.FILES_DIR + "/" + filePath;
            }
            sb.append("<filename>").append(filename).append("</filename>");
            sb.append("<file-path>").append(filePath).append("</file-path>");
            sb.append("<username>").append(username).append("</username>");
            sb.append("<access>").append(access).append("</access>");
            sb.append("<version>").append(version).append("</version>");
            sb.append("<slides>");
            for (int i = 0; i < slides.size(); i++) {
                Slide slide = slides.get(i);
                sb.append("<slide>");
                sb.append("<title>").append(slide.getTitle()).append("</title>");
                sb.append("<text>").append(slide.getText()).append("</text>");
                sb.append("<url>").append(slide.getUrl()).append("</url>");
                sb.append("<text-color>");
                Color color = slide.getTextColor();
                sb.append("<red>").append(color.getRed()).append("</red>");
                sb.append("<blue>").append(color.getBlue()).append("</blue>");
                sb.append("<green>").append(color.getGreen()).append("</green>");
                sb.append("</text-color>");
                sb.append("<text-size>").append(slide.getTextSize()).append("</text-size>");
                String questionPath = slide.getQuestionPath();
                String questionContent = null;
                if (questionPath != null) {
                    if (!questionPath.trim().equals("null") || !questionPath.trim().equals("")) {
                        try {
                            String qnXmlContents = Util.getContents(new File(questionPath));
                            int startContentIndex = qnXmlContents.indexOf("<content>");
                            int endContentIndex = qnXmlContents.indexOf("</content>") + "</content>".length();
                            if (startContentIndex > -1 && endContentIndex > -1) {
                                String qnContent = qnXmlContents.substring(startContentIndex, endContentIndex);
                                questionContent = pl.getQuestionProcessor().extractQuestionContent(qnContent,
                                        "public", packet.getFrom(), questionPath);
                            }

                        } catch (Exception ex) {
                            ex.printStackTrace();
                        }
                    }
                }
                sb.append("<slide" + i + "-question>").append(questionContent).append("</slide" + i + "-question>");
                String imagePath = slide.getImagePath();
                String imageData = Base64.encodeFromFile(imagePath);
                sb.append("<image-path>").append(imagePath).append("</image-path>");
                sb.append("<image-data>").append(imageData).append("</image-data>");
                //we need image path for editing purpses

                sb.append("</slide>");
            }
            sb.append("</slides>");
        } catch (Exception ex) {
            ex.printStackTrace();
        }



        return sb.toString();
    }

    public static String getTagText(String xmlContent, String tag) {
        String content = null;
        int start = xmlContent.indexOf("<" + tag + ">") + ("<" + tag + ">").length();
        int end = xmlContent.indexOf("</" + tag + ">");
        if (start > -1 && end > -1) {
            content = xmlContent.substring(start, end);
        }
        //System.out.println(xmlContent+"; "+tag+": "+content);
        return content;
    }

    public static String readTextFile(String file) {
        StringBuilder builder = new StringBuilder();

        try {
            BufferedReader in = new BufferedReader(new FileReader(file));
            String line;
            while ((line = in.readLine()) != null) {
                builder.append(line + "\n");
            }

        } catch (Exception ex) {
            System.out.println(ex.getMessage());
        }
        return builder.toString();
    /*
     * outputStream = new BufferedWriter(new
     * FileWriter("characteroutput.txt"));
     */
    }

    public static String getExt(String str) {
        if (str == null) {
            return null;
        }
        int i = str.lastIndexOf(".");
        if (i > -1) {
            return str.substring(i);
        }
        return str;
    }

    public void downloadWebpresentSlides(IQ packet, String slidesDir, String presentationName,
            String presentationId) {

        String[] slides = new File(slidesDir).list();

        if (slides != null) {
            int count = 0;
            for (String slide : slides) {
                if (slide.endsWith(".jpg")) {
                    count++;
                }
            }
            //first..send the total number and other pre-check info:
            int version = pl.getRoomResourceManager().getRoomResourceRoomVersion(slidesDir);
            StringBuilder buf = new StringBuilder();
            buf.append("<slide-count>").append(count).append("</slide-count>");
            buf.append("<version>").append(version + "").append("</version>");
            buf.append("<filename>").append(presentationName).append("</filename>");
            buf.append("<server-path>").append(slidesDir + getExt(presentationName)).append("</server-path>");
            IQ slidesCountPacket = IQ.createResultIQ(packet);
            Element xqueryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
            xqueryResult.addElement("mode").addText(Mode.SLIDES_COUNT);
            xqueryResult.addElement("content").addText(buf.toString());
            slidesCountPacket.setChildElement(xqueryResult);
            slidesCountPacket.setTo(packet.getFrom());
            packetRouter.route(slidesCountPacket);
            int index = 0;
            for (String slide : slides) {
                if (slide.endsWith(".jpg")) {
                    String slideData = Base64.encodeFromFile(slidesDir + "/" + slide);
                    String slideTr = readTextFile(slidesDir + "/text" + index + ".html");

                    String slideTitle = getTagText(slideTr, "h1");
                    slideTitle = getTagText(slideTr, "b");
                    if (slideTitle == null) {
                        slideTitle = "";
                    }
                    StringBuilder sb = new StringBuilder();
                    sb.append("<webpresent>");
                    sb.append("<presentation-name>").append(presentationName).append("</presentation-name>");
                    sb.append("<presentation-id>").append(presentationId).append("</presentation-id>");

                    sb.append("<slide-title>").append(slideTitle).append("</slide-title>");
                    sb.append("<slide-name>").append(slide).append("</slide-name>");
                    sb.append("<slide-data>").append(slideData).append("</slide-data>");
                    sb.append("</webpresent>");
                    IQ replyPacket = IQ.createResultIQ(packet);
                    Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
                    queryResult.addElement("mode").addText(Mode.DOWNLOAD_WEBPRESENT_SLIDES);
                    queryResult.addElement("content").addText(sb.toString());
                    replyPacket.setChildElement(queryResult);
                    replyPacket.setTo(packet.getFrom());
                    packetRouter.route(replyPacket);
                    index++;
                }
            }
        }
    }

    public void broadcastSlide(IQ packet, Document xdoc) {

        String slideShowName = XmlUtils.readString(xdoc, "slide-show-name");
        String slideTitle = XmlUtils.readString(xdoc, "slide-title");
        String roomName = XmlUtils.readString(xdoc, "room-name");
        pl.getRoomResourceManager().storeLastSlide(slideShowName, slideTitle, roomName);
        IQ replyPacket = IQ.createResultIQ(packet);
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(Mode.BROADCAST_OUT_SLIDE);
        StringBuilder sb = new StringBuilder();
        sb.append("<slide-show-name>").append(slideShowName).append("</slide-show-name>");
        sb.append("<slide-title>").append(slideTitle).append("</slide-title>");
        queryResult.addElement("content").addText(sb.toString());
        replyPacket.setChildElement(queryResult);
        ArrayList<JID> jids = RUserManager.users.get(roomName.toUpperCase());
        if (jids == null) {
            jids = RUserManager.users.get(roomName.toLowerCase());
        }
        for (int i = 0; i < jids.size(); i++) {
            JID jid = jids.get(i);
            replyPacket.setTo(jid);
            replyPacket.setFrom(packet.getFrom());

            packetRouter.route(replyPacket);
        }
    }

    public IQ downloadSlideShowFile(IQ packet, Document xdoc) {
        IQ replyPacket = IQ.createResultIQ(packet);
        String path = XmlUtils.readString(xdoc, "file-path");
        String access = XmlUtils.readString(xdoc, "access");
        int version = pl.getRoomResourceManager().getRoomResourceRoomVersion(path);
        String xmlContents = getContents(new File(path));
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(Mode.DOWNLOAD_ROOM_SLIDE_SHOW);
        String replyContent = extractContent(xmlContents, packet, version, access, path);
        queryResult.addElement("content").addText(replyContent);
        replyPacket.setChildElement(queryResult);
        return replyPacket;
    }

    public IQ openSlideShowFile(IQ packet, Document xdoc) {
        IQ replyPacket = IQ.createResultIQ(packet);
        String fileName = XmlUtils.readString(xdoc, "filename");
        String username = XmlUtils.readString(xdoc, "username");
        String access = XmlUtils.readString(xdoc, "access");
        String destFile = Constants.FILES_DIR + "/slideshows/" + fileName;
        if (access.equals("private")) {
            destFile = Constants.FILES_DIR + "/slideshows/" + username + "/" + fileName;
        }
        String xmlContents = getContents(new File(destFile));
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(Mode.OPEN_SLIDE_SHOW);
        String replyContent = extractContent(xmlContents, packet,
                pl.getRoomResourceManager().getRoomResourceRoomVersion(destFile), access, destFile);
        queryResult.addElement("content").addText(replyContent);
        replyPacket.setChildElement(queryResult);
        return replyPacket;
    }

    public RealtimeSlideShowPacket getRealtimeSlideShowPacket(String xmlContents) {
        try {
            xmlContents = "<slideshow>" + xmlContents + "</slideshow>";

            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));

            RealtimeSlideShowPacket p = new RealtimeSlideShowPacket();
            String filename = XmlUtils.readString(doc, "filename");
            String access = XmlUtils.readString(doc, "access");

            p.setAccess(access);
            p.setFilename(filename);
            int version = XmlUtils.readInt(doc, "version");
            p.setVersion(version);


            String title = XmlUtils.readString(doc, "title");
            String text = XmlUtils.readString(doc, "text");
            String contentModified = XmlUtils.readString(doc, "content-modified");
            String titleModified = XmlUtils.readString(doc, "title-modified");
            String oldTitle = XmlUtils.readString(doc, "old-title");
            String isImageSlide = XmlUtils.readString(doc, "is-image-slide");
            String isQuestionSlide = XmlUtils.readString(doc, "is-question-slide");
            String isUrlSlide = XmlUtils.readString(doc, "is-url-slide");
            String imagePath = XmlUtils.readString(doc, "image-path");
            //do color read
            NodeList innerNodeList = doc.getElementsByTagName("text-color");
            int r = 0;
            int g = 0;
            int b = 0;
            for (int j = 0; j < innerNodeList.getLength(); j++) {
                Node innerNode = innerNodeList.item(j);
                if (innerNode.getNodeType() == Node.ELEMENT_NODE) {
                    org.w3c.dom.Element innerElement = (org.w3c.dom.Element) innerNode;
                    r = XmlUtils.readInt(innerElement, "red");
                    g = XmlUtils.readInt(innerElement, "green");
                    b = XmlUtils.readInt(innerElement, "blue");
                }

            }
            Color textColor = new Color(r, g, b);
            int textSize = XmlUtils.readInt(doc, "text-size");
            String questionPath = XmlUtils.readString(doc, "question-path");
            String url = XmlUtils.readString(doc, "url");
            int index = XmlUtils.readInt(doc, "index");
            Slide slide = new Slide(title, text, textColor, textSize,
                    questionPath, null, url, null, null, index);
            slide.setContentModified(new Boolean(contentModified));
            slide.setTitleModified(new Boolean(titleModified));
            slide.setQuestionSlide(new Boolean(isQuestionSlide));
            slide.setImageSlide(new Boolean(isImageSlide));
            slide.setUrlSlide(new Boolean(isUrlSlide));
            slide.setOldTitle(oldTitle);
            slide.setImagePath(imagePath);



            p.setSlide(slide);


            return p;
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return null;
    }

    public boolean jodConvert(String filename) {
        try {

            File inputFile = new File(filename);
            int dot = filename.lastIndexOf(".");
            String outname = filename.substring(0, dot);
            File f = new File(outname);
            f.mkdir();
            File outputFile = new File(outname + "/" + f.getName() + ".pdf");

            // connect to an OpenOffice.org instance running on port 8100
            OpenOfficeConnection connection = new SocketOpenOfficeConnection(8100);
            try {

                connection.connect();

            } catch (ConnectException ex) {
                ex.printStackTrace();


                return false;
            }
            // convert
            DocumentConverter converter = new OpenOfficeDocumentConverter(connection);
            converter.convert(inputFile, outputFile);

            // close the connection
            connection.disconnect();

            // generateImagesFromPdf(outputFile);
            return getPDFPagesasImages(outputFile.getAbsolutePath());

        } catch (Exception ex) {
            ex.printStackTrace();
            return false;
        }
    }

    private boolean getPDFPagesasImages(String file) {
        try {
            //open pdf file
            PdfDecoder decoder = openpdf(file.toString());

            //for each page
            int PageCount = decoder.getPageCount();
            int PageNumber = 1;
            while (PageNumber <= PageCount) {
                //generate png files
                double ImageWidth = 0;
                double ImageHeight = 0;
                generatePNGfromPDF(decoder, PageNumber, ImageWidth, ImageHeight, file);

                PageNumber++;
            }//end page code
            //close pdf
            decoder.closePdfFile();
            return true;
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return false;
    }

    private void generatePNGfromPDF(PdfDecoder decoder, int PageNumber, double width, double height, String formname) {
        //int size = 100;
        try {
            //decoder.decodePage(PageNumber);
            //decoder.setPageParameters(1,PageNumber); //values scaling (1=100%). page number
            decoder.useHiResScreenDisplay(true);

            int dpi = 300;
            decoder.setExtractionMode(32);
            //decoder.setExtractionMode(32, dpi, 2);
            BufferedImage PDF = decoder.getPageAsImage(PageNumber);
            /*width = PDF.getWidth();
            height = PDF.getHeight();
            BufferedImage buffer =
            new BufferedImage((int)width,
            (int)height,
            BufferedImage.TYPE_INT_RGB);
            Graphics g = buffer.createGraphics();

            // GraphicsConfiguration gc = getDefaultConfiguration();
            final BufferedImage PNG = Util. //getScaledInstance(PDF, (int) Math.round(width), (int) Math.round(height), gc);
            //String fileName = formname + "-" + PageNumber + ".jpg";
            //System.out.println(fileName);*/
            File f = new File(formname);
            String filePath = new File(f.getParent()).getAbsolutePath();
            ImageIO.write(PDF, "jpg", new File(filePath + "/img" + (PageNumber - 1) + ".jpg"));
            decoder.flushObjectValues(true);
        } catch (Exception ex) {
            ex.printStackTrace();
        }

    }

    /*  private static GraphicsConfiguration getDefaultConfiguration() {
    GraphicsEnvironment ge =
    GraphicsEnvironment.getLocalGraphicsEnvironment();
    GraphicsDevice gd = ge.getDefaultScreenDevice();
    return gd.getDefaultConfiguration();
    }

    private static BufferedImage toCompatibleImage(BufferedImage image, GraphicsConfiguration gc) {
    if (gc == null) {
    gc = getDefaultConfiguration();
    }
    int w = image.getWidth();
    int h = image.getHeight();
    int transparency = image.getColorModel().getTransparency();
    BufferedImage result = gc.createCompatibleImage(w, h, transparency);
    Graphics2D g2 = result.createGraphics();
    g2.drawRenderedImage(image, null);
    g2.dispose();
    return result;
    }*/
    private static BufferedImage copy(BufferedImage source, BufferedImage target) {
        Graphics2D g2 = target.createGraphics();
        g2.setRenderingHint(RenderingHints.KEY_INTERPOLATION,
                RenderingHints.VALUE_INTERPOLATION_BICUBIC);
        g2.setRenderingHint(RenderingHints.KEY_DITHERING,
                RenderingHints.VALUE_DITHER_DISABLE);
        g2.setRenderingHint(RenderingHints.KEY_RENDERING,
                RenderingHints.VALUE_RENDER_QUALITY);
        g2.setRenderingHint(RenderingHints.KEY_ANTIALIASING,
                RenderingHints.VALUE_ANTIALIAS_OFF);

        g2.setRenderingHint(RenderingHints.KEY_FRACTIONALMETRICS,
                RenderingHints.VALUE_FRACTIONALMETRICS_ON);
        double scalex = (double) target.getWidth() / source.getWidth();
        double scaley = (double) target.getHeight() / source.getHeight();
        AffineTransform xform = AffineTransform.getScaleInstance(scalex,
                scaley);
        g2.drawRenderedImage(source, xform);
        g2.dispose();
        return target;
    }
    /*
    private static BufferedImage getScaledInstance(BufferedImage image, int width, int height, GraphicsConfiguration gc) {
    if (gc == null) {
    gc = getDefaultConfiguration();
    }
    int transparency = image.getColorModel().getTransparency();
    return copy(image, gc.createCompatibleImage(width, height, transparency));
    }
     */

    private PdfDecoder openpdf(Object file) {
        //open pdf using jpedal
        PdfDecoder decoder = new PdfDecoder();
        try {
            decoder.openPdfFile(file.toString());
        } catch (org.jpedal.exception.PdfException ex) {
            ex.printStackTrace();
        }
        return decoder;
    }
}
