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

import java.awt.Color;
import java.awt.Graphics2D;
import java.awt.RenderingHints;
import java.awt.image.BufferedImage;
import java.io.ByteArrayInputStream;
import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import javax.imageio.ImageIO;
import javax.swing.ImageIcon;
import javax.swing.JOptionPane;
import javax.swing.SwingUtilities;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.common.util.XmlUtils;
import org.avoir.realtime.gui.SlidesNavigator;
import org.avoir.realtime.gui.WebpresentNavigator;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.WebPresentManager;
import org.avoir.realtime.net.packets.RealtimeQuestionPacket;
import org.avoir.realtime.net.packets.RealtimeSlideShowPacket;
import org.avoir.realtime.net.packets.WebpresentSlide;
import org.avoir.realtime.slidebuilder.Slide;
import org.jivesoftware.smack.util.Base64;
import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

/**
 *
 * @author developer
 */
public class SlideShowProcessor {

    static DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();
    static DocumentBuilder documentBuilder;
    // public static ProgressMonitor progressMonitor;
    public static int slideCount = 0;
    public static int totalSlideCount = 0;
private static boolean initSlideShown=false;

    static {
        try {
            documentBuilder = documentBuilderFactory.newDocumentBuilder();
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    public static RealtimeSlideShowPacket getRealtimeSlideShowPacket(String xmlContents) {
        try {
            xmlContents = "<slideshow>" + xmlContents + "</slideshow>";

            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));

            RealtimeSlideShowPacket p = new RealtimeSlideShowPacket();
            String filename = XmlUtils.readString(doc, "filename");
            String filePath = XmlUtils.readString(doc, "file-path");
            String access = XmlUtils.readString(doc, "access");

            p.setFilePath(filePath);
            p.setAccess(access);
            p.setFilename(filename);
            int version = XmlUtils.readInt(doc, "version");
            p.setVersion(version);
            NodeList slidesNodeList = doc.getElementsByTagName("slide");
            ArrayList<Slide> slides = new ArrayList<Slide>();
            ArrayList<Map> modifiedTitles = new ArrayList<Map>();
            for (int i = 0; i < slidesNodeList.getLength(); i++) {
                Node node = slidesNodeList.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    org.w3c.dom.Element element = (org.w3c.dom.Element) node;
                    String title = XmlUtils.readString(element, "title");
                    String text = XmlUtils.readString(element, "text");
                    //do color read
                    NodeList innerNodeList = node.getChildNodes();
                    int r = 0;
                    int g = 0;
                    int b = 0;
                    for (int j = 0; j < innerNodeList.getLength(); j++) {
                        Node innerNode = slidesNodeList.item(i);
                        if (innerNode.getNodeType() == Node.ELEMENT_NODE) {
                            org.w3c.dom.Element innerElement = (org.w3c.dom.Element) innerNode;
                            r = XmlUtils.readInt(innerElement, "red");
                            g = XmlUtils.readInt(innerElement, "green");
                            b = XmlUtils.readInt(innerElement, "blue");
                        }

                    }
                    Color textColor = new Color(r, g, b);
                    int textSize = XmlUtils.readInt(element, "text-size");
                    String questionContent = "";
                    int slideQnStart = xmlContents.indexOf("<slide" + i + "-question>");
                    int slideQnEnd = xmlContents.indexOf("</slide" + i + "-question>") + ("</slide" + i + "-question>").length();
                    if (slideQnStart > -1 && slideQnEnd > -1) {
                        questionContent = xmlContents.substring(slideQnStart, slideQnEnd);
                    }

                    RealtimeQuestionPacket qnP = QuestionProcessor.getRealtimeQuestionPacket(questionContent);

                    String questionPath = XmlUtils.readString(element, "question-path");
                    String url = XmlUtils.readString(element, "url");
                    String imageData = XmlUtils.readString(element, "image-data");
                    String isImageSlide = XmlUtils.readString(element, "is-image-slide");
                    String isQuestionSlide = XmlUtils.readString(element, "is-question-slide");
                    String isUrlSlide = XmlUtils.readString(element, "is-url-slide");
                    String contentModified = XmlUtils.readString(element, "content-modified");
                    String titleModified = XmlUtils.readString(element, "title-modified");
                    String oldTitle = XmlUtils.readString(element, "old-title");
                    String imagePath = XmlUtils.readString(element, "image-path");
                    ImageIcon image = new ImageIcon(Base64.decode(imageData));
                    Slide slide = new Slide(title, text, textColor, textSize,
                            questionPath, null, url, image, qnP, i);
                    slide.setContentModified(new Boolean(contentModified));
                    slide.setTitleModified(new Boolean(titleModified));
                    slide.setQuestionSlide(new Boolean(isQuestionSlide));
                    slide.setImageSlide(new Boolean(isImageSlide));
                    slide.setUrlSlide(new Boolean(isUrlSlide));
                    slide.setOldTitle(oldTitle);
                    slide.setImagePath(imagePath);
                    slides.add(slide);
                }
            }


            p.setSlides(slides);
            NodeList modifiedTitlesNodeList = doc.getElementsByTagName("modified-title");

            for (int i = 0; i < modifiedTitlesNodeList.getLength(); i++) {
                Node node = modifiedTitlesNodeList.item(i);
                Map<String, String> title = new HashMap<String, String>();
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    org.w3c.dom.Element element = (org.w3c.dom.Element) node;
                    String oldTitle = XmlUtils.readString(element, "old-title");
                    String newTitle = XmlUtils.readString(element, "new-title");
                    title.put("old-title", oldTitle);
                    title.put("new-title", newTitle);
                    modifiedTitles.add(title);

                }
            }
            p.setTitleChanges(modifiedTitles);
            return p;
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return null;
    }

    public static void initProgressMonitorIfNecessary(String xmlContents) {
        try {

            xmlContents = "<data>" + xmlContents + "</data>";

            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            totalSlideCount = XmlUtils.readInt(doc, "slide-count");
            String version = XmlUtils.readString(doc, "version");
            String filename = XmlUtils.readString(doc, "filename");
            filename = GeneralUtil.removeExt(filename);
            String serverPath = XmlUtils.readString(doc, "server-path");
            String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
            String targetDir = resourceDir + "/" + filename;
            new File(targetDir).mkdirs();
            GeneralUtil.writeTextFile(targetDir + "/version.txt", version + "", false);
            GeneralUtil.writeTextFile(targetDir + "/server_path.txt", serverPath, false);
            slideCount = 0;
            GUIAccessManager.mf.getWbProgressBar().setMinimum(0);
            GUIAccessManager.mf.getWbProgressBar().setMaximum(totalSlideCount-1);
        //  progressMonitor = new ProgressMonitor(GUIAccessManager.mf, "Initializing slides..please wait", "", 0, totalSlideCount);

        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    public static void deleteLocalSlide(String xmlContents) {
        try {

            xmlContents = "<data>" + xmlContents + "</data>";

            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            String path = XmlUtils.readString(doc, "file-path");
            File fileToDelete = new File(path);
            if (GeneralUtil.deleteDir(fileToDelete)) {
                GUIAccessManager.mf.getWebPresentNavigator().populateWithRoomResources();
            } else {
                JOptionPane.showMessageDialog(null, "Error: Could not delete " + fileToDelete.getName());

            }
        /*String filename = new File(path).getName();
        GUIAccessManager.mf.getRoomResourceNavigator().removeResource(filename);
        String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
        String fileToDeleteStr = resourceDir + "/" + filename;
        fileToDeleteStr=GeneralUtil.removeExt(fileToDeleteStr);
        File fileToDelete = new File(fileToDeleteStr);
        String[] contents = fileToDelete.list();
        for (String content : contents) {
        String p = fileToDeleteStr + "/" + content;
        new File(p).delete();
        }

        if (fileToDelete.delete()) {
        //JOptionPane.showMessageDialog(null, "Resource removed");
        } else {
        JOptionPane.showMessageDialog(null, "Error: Could not delete " + filename);
        }*/
        /*
        DefaultListModel model = GUIAccessManager.mf.getRoomResourceModel();
        for (int i = 0; i < model.getSize(); i++) {
        String fn = (String) model.getElementAt(i);
        if (fn.equals(filename)) {
        model.remove(i);
        String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
        new File(resourceDir + "/filename").delete();
        break;
        }
        }*/
        } catch (Exception ex) {
            ex.printStackTrace();
        }

    }

    public static WebpresentSlide getWebpresentSlideImage(String xmlContents) {
        try {

            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            String slideData = XmlUtils.readString(doc, "slide-data");
            String slideName = XmlUtils.readString(doc, "slide-name");
            String slideTitle = XmlUtils.readString(doc, "slide-title");

            String presentationName = XmlUtils.readString(doc, "presentation-name");
            String presentationId = XmlUtils.readString(doc, "presentation-id");
            ImageIcon icon = new ImageIcon(Base64.decode(slideData));
            WebpresentSlide s = new WebpresentSlide(slideName,
                    icon, presentationName, slideTitle, presentationId);
            return s;
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return null;
    }

    public static Slide getSlideFromFile(String path) {
        String xmlContents = GeneralUtil.readTextFile(path);
        try {



            if (xmlContents == null) {
                return null;
            }
            if (xmlContents.trim().equals("")) {
                return null;
            }
            xmlContents = "<ct>" + xmlContents + "</ct>";

            xmlContents = GeneralUtil.removeIllegalXmlChars(xmlContents);

            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));
            String text = XmlUtils.readString(doc, "slide-text");
            int textSize = XmlUtils.readInt(doc, "slide-text-size");
            String url = XmlUtils.readString(doc, "slide-url");
            NodeList colorNodeList = doc.getElementsByTagName("slide-text-color");
            int r = 0;
            int g = 0;
            int b = 0;
            for (int i = 0; i < colorNodeList.getLength(); i++) {
                Node node = colorNodeList.item(i);
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    org.w3c.dom.Element colorElement = (org.w3c.dom.Element) node;
                    r = XmlUtils.readInt(colorElement, "red");
                    g = XmlUtils.readInt(colorElement, "green");
                    b = XmlUtils.readInt(colorElement, "blue");
                }
            }
            Color textColor = new Color(r, g, b);
            Slide slide = new Slide();
            slide.setTextColor(textColor);
            slide.setText(text);
            slide.setUrl(url);
            slide.setTextSize(textSize);
            return slide;
        } catch (Exception ex) {

            ex.printStackTrace();
        }
        return null;
    }

    public static void updateSlideContent(String xmlContent) {

        String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
        try {
            xmlContent = "<slide-broadcast>" + xmlContent + "</slide-broadcast>";

            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContent.getBytes(Constants.PREFERRED_ENCODING)));
            String slideShowName = XmlUtils.readString(doc, "slide-show-name");
            String slideTitle = XmlUtils.readString(doc, "slide-title");
            String slideNoStr = slideTitle.substring(5);
            int slideNo = Integer.parseInt(slideNoStr.trim());
            String imgExt = "";//WebPresentManager.hasBeenLaunchedAsWebPresent ? "" : ".jpg";
            slideTitle = "Slide " + (slideNo - 1);
            String slideImagePath = resourceDir + "/" + slideShowName + "/" + slideTitle + imgExt;

            try {


                ImageIcon slideImage = null;
                try {
                    slideImage = new ImageIcon(GeneralUtil.readFile(slideImagePath));

                } catch (Exception ex) {
                    System.out.println(ex.getLocalizedMessage());
                }
                GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setSlideImage(slideImage);



            } catch (Exception ex) {
                ex.printStackTrace();

                GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setSlideImage(null);

            }



            String trExt = ".tr";
            String slideTranscriptPath = resourceDir + "/" + slideShowName + "/" + slideTitle + trExt;
            Slide slide = getSlideFromFile(slideTranscriptPath);
            final String url = slide == null ? "" : slide.getUrl();

            //ok, a slide with a url is given preference..of course this is overwitten when
            //the instructor clicks on a specific tab
            if (url != null) {
                if (!url.trim().equals("") && !url.trim().equals("null")) {
                    SwingUtilities.invokeLater(new Runnable() {

                        public void run() {

                            GUIAccessManager.mf.getGeneralWebBrowser().navigate(url);
                            GUIAccessManager.mf.getTabbedPane().setSelectedIndex(1);

                        }
                    });
                } else {

                    GUIAccessManager.mf.getTabbedPane().setSelectedIndex(0);

                }
            } else {

                GUIAccessManager.mf.getTabbedPane().setSelectedIndex(0);

            }
            String slideQnPath = resourceDir + "/" + slideShowName + "/" + slideTitle + ".qn";
            String qnContent = GeneralUtil.readTextFile(slideQnPath);
            RealtimeQuestionPacket qn = QuestionProcessor.getRealtimeQuestionPacket(qnContent);
            if (qn != null) {
                if (qn.getQuestion() != null) {
                    QuestionProcessor.displayQuestionForAnswering(
                            qn.getQuestion(),
                            qn.getType(),
                            qn.getFilename(),
                            qn.getAnswerOptions(),
                            qn.getImageData(),
                            qn.getUsername(),
                            qn.getInstructor(), false);
                }

            }

            GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setSlideText(slide == null ? "" : slide.getText());
            GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setSlideTextColor(slide == null ? Color.BLACK : slide.getTextColor());
            GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setSlideTextSize(slide == null ? 17 : slide.getTextSize());

        } catch (Exception ex) {
            ex.printStackTrace();

        }
    }

    public static void saveSlideShow(String xmlContent) {
        RealtimeSlideShowPacket ssp = SlideShowProcessor.getRealtimeSlideShowPacket(xmlContent);

        String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
        String fileName = ssp.getFilename();
        if (!fileName.endsWith(".ss")) {
            fileName += ".ss";
        }
        String slideShowDir = resourceDir + "/" + fileName;
        new File(slideShowDir).mkdirs();
        ArrayList<Slide> slides = ssp.getSlides();

        for (int i = 0; i < slides.size(); i++) {

            Slide slide = slides.get(i);

            ImageIcon image = slide.getImage();
            //create slide im, if any
            if (image != null) {
                try {
                    BufferedImage slideImg = new BufferedImage(image.getIconWidth(), image.getIconHeight(), BufferedImage.TYPE_INT_RGB);
                    Graphics2D g2 = slideImg.createGraphics();
                    g2.setRenderingHint(RenderingHints.KEY_INTERPOLATION, RenderingHints.VALUE_INTERPOLATION_BILINEAR);
                    g2.drawImage(image.getImage(), 0, 0, image.getIconWidth(), image.getIconHeight(), null);
                    g2.dispose();

                    ImageIO.write(slideImg, "jpg", new File(slideShowDir + "/" + slide.getTitle() + ".jpg"));

                } catch (Exception ex) {
                    System.out.println("SlideShowProcessor:saveSlideShow: " + ex.getMessage());
                }
            }
            //then the question
            RealtimeQuestionPacket qn = slide.getQuestion();
            if (qn != null) {

                int slideQnStart = xmlContent.indexOf("<slide" + i + "-question>");
                int slideQnEnd = xmlContent.indexOf("</slide" + i + "-question>") + ("</slide" + i + "-question>").length();
                if (slideQnStart > -1 && slideQnEnd > -1) {
                    String questionContent = xmlContent.substring(slideQnStart, slideQnEnd);
                    GeneralUtil.writeTextFile(slideShowDir + "/" + slide.getTitle() + ".qn", questionContent, false);
                }
            }

            //the text
            StringBuilder sb = new StringBuilder();
            sb.append("<transcript>");
            sb.append("<slide-text>").append(slide.getText()).append("</slide-text>");
            sb.append("<slide-text-size>").append(slide.getTextSize()).append("</slide-text-size>");
            sb.append("<slide-url>").append(slide.getUrl()).append("</slide-url>");
            sb.append("<slide-text-color>");
            Color c = slide.getTextColor();
            sb.append("<red>").append(c.getRed()).append("</red>");
            sb.append("<green>").append(c.getGreen()).append("</green>");
            sb.append("<blue>").append(c.getBlue()).append("</blue>");
            sb.append("</slide-text-color>");
            sb.append("<is-question-slide>").append(slide.isQuestionSlide()).append("</is-question-slide>");
            sb.append("<is-image-slide>").append(slide.isImageSlide()).append("</is-image-slide>");
            sb.append("<is-url-slide>").append(slide.isUrlSlide()).append("</is-url-slide>");

            sb.append("</transcript>");
            String trfilename = slideShowDir + "/" + slide.getTitle() + ".tr";

            GeneralUtil.writeTextFile(trfilename, sb.toString(), false);
        }
        //then the version

        GeneralUtil.writeTextFile(slideShowDir + "/version.txt", ssp.getVersion() + "", false);
        GeneralUtil.writeTextFile(slideShowDir + "/server_path.txt", ssp.getFilePath(), false);
        SlidesNavigator nav = GUIAccessManager.mf.getSlideNavigator();
        if (nav != null) {
            nav.populateNodes("slideshows");
        }

        //then rename the modified ones
        ArrayList<Map> modifiedTitles = ssp.getTitleChanges();
        for (int i = 0; i < modifiedTitles.size(); i++) {
            Map map = modifiedTitles.get(i);
            String oldTitle = (String) map.get("old-title");
            String newTitle = (String) map.get("new-title");
            String[] exts = {".qn", ".tr", ".jpg"};
            for (String ext : exts) {

                String oldFilename = slideShowDir + "/" + oldTitle + ext;
                String newFilename = slideShowDir + "/" + newTitle + ext;
                new File(oldFilename).renameTo(new File(newFilename));

            }

        }
    }

    public static void displayInitialSlide(String presentationId) {
        String slideName = "Slide 0";

        ImageIcon slideImage = null;
        try {
            String imgExt = "";
            String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
            String slideImagePath = resourceDir + "/" + presentationId + "/" + slideName + imgExt;
            slideImage = new ImageIcon(GeneralUtil.readFile(slideImagePath));
            GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setSlideImage(slideImage);

        } catch (Exception ex) {
            System.out.println(ex.getLocalizedMessage());
        }

    }

    public static void saveWebPresentSlide(String xmlContent) {
        WebpresentSlide slide = getWebpresentSlideImage(xmlContent);
        ImageIcon image = slide.getImage();
        String slideName = slide.getFileName();

        String presentationName = slide.getPresentationName();
        String presentationId = slide.getPresentationId();
        String targetDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName + "/" + presentationId;
       

        new File(targetDir).mkdirs();
        GeneralUtil.writeTextFile(targetDir + "/presentationname.txt", GeneralUtil.removeExt(presentationName), false);
        if (image != null) {
            try {
                BufferedImage slideImg = new BufferedImage(image.getIconWidth(), image.getIconHeight(), BufferedImage.TYPE_INT_RGB);
                Graphics2D g2 = slideImg.createGraphics();
                g2.setRenderingHint(RenderingHints.KEY_INTERPOLATION, RenderingHints.VALUE_INTERPOLATION_BILINEAR);
                g2.drawImage(image.getImage(), 0, 0, image.getIconWidth(), image.getIconHeight(), null);
                g2.dispose();
                slideName = GeneralUtil.removeExt(slideName);
                int index = 0;
                try {
                    index = Integer.parseInt(slideName.substring(3));
                } catch (NumberFormatException ex) {
                }

                String fn = targetDir + "/Slide " + index;

                ImageIO.write(slideImg, "jpg", new File(fn));
                String slideTitle = slide.getSlideTitle();
                String trContent = "<transcript><slide-text>" + slideTitle + "</slide-text><slide-text-size>18</slide-text-size><slide-url></slide-url><slide-text-color><red>0</red><green>0</green><blue>0</blue></slide-text-color><is-question-slide>false</is-question-slide><is-image-slide>false</is-image-slide><is-url-slide>false</is-url-slide></transcript>";
                GeneralUtil.writeTextFile(fn + ".tr", trContent, false);
                //if (progressMonitor != null) {
                //  progressMonitor.setProgress(slideCount++);
                GUIAccessManager.mf.getWbInfoField().setText("Downloaded " + (slideCount+1) + " of " + totalSlideCount);
                GUIAccessManager.mf.getWbProgressBar().setValue(slideCount + 1);

                slideCount++;
                //}
                if (slideCount == totalSlideCount && !initSlideShown) {
                   // GUIAccessManager.mf.getWebPresentNavigator().populateWithRoomResources();
                    WebPresentManager.presentationId = presentationId;

                    WebpresentNavigator.selectedPresentation = presentationName;
                    //GUIAccessManager.mf.getSlideScroller().refresh();
                    displayInitialSlide(presentationId);
                   
                   GUIAccessManager.mf.getRoomResourceNavigator().populateWithRoomResources();
                    initSlideShown=true;
                }

            } catch (Exception ex) {
                System.out.println("SlideShowProcessor:saveSlideShow: " + ex.getMessage());
            }
        }
    }
}
