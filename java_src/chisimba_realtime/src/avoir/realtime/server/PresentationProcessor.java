/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.server;

import avoir.realtime.common.Constants;
import avoir.realtime.common.MessageCode;
import avoir.realtime.common.packet.ClassroomSlidePacket;
import avoir.realtime.common.packet.MsgPacket;

import avoir.realtime.common.packet.PresentationRequest;
import avoir.realtime.common.packet.SlideBuilderPacket;
import avoir.realtime.common.packet.SlideShowPopulateRequest;
import avoir.realtime.instructor.whiteboard.XmlBuilderSlide;
import avoir.realtime.survey.Value;
import com.artofsolving.jodconverter.DocumentConverter;
import com.artofsolving.jodconverter.openoffice.connection.OpenOfficeConnection;
import com.artofsolving.jodconverter.openoffice.connection.SocketOpenOfficeConnection;
import com.artofsolving.jodconverter.openoffice.converter.OpenOfficeDocumentConverter;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.net.ConnectException;
import java.util.ArrayList;
import java.util.Vector;

/**
 *
 * @author developer
 */
public class PresentationProcessor {

    private ServerThread serverThread;

    public PresentationProcessor(ServerThread serverThread) {
        this.serverThread = serverThread;
    }

    private String stripExtension(String s) {
        int i = s.lastIndexOf(".");
        if (i > -1) {
            return s.substring(0, i);
        }
        return s;
    }

    public void processSlideShowPopulateRequest(SlideShowPopulateRequest p) {
        String name = p.getPath();
        
        String path = serverThread.getSERVER_HOME() + "/userfiles/" +
                serverThread.getThisUser().getUserName() + "/slides/" + name;
        serverThread.getFileTransferEngine().populateBinaryFile(serverThread, path, "", Constants.SLIDE_SHOW_VIEW, false);

    }

    public void processPresentationRequest(PresentationRequest p) {
        String path = p.getPath();
        File f = new File(path);
        File parent = new File(f.getParent() + "/" + stripExtension(f.getName()));
        boolean presentationPossiblyProcessed = false;
        if (parent.exists()) {

            String[] contents = parent.list();
            if (contents != null) {
                for (int i = 0; i < contents.length; i++) {
                    if (contents[i].endsWith(".jpg")) {
                        presentationPossiblyProcessed = true;
                    }
                }
            }
        }
        if (presentationPossiblyProcessed) {
            populateConvertedDoc(parent.getAbsolutePath(), parent.getName());
        } else {
            if (jodConvert(path)) {
                serverThread.sendPacket(new MsgPacket("Complete",
                        Constants.LONGTERM_MESSAGE,
                        Constants.INFORMATION_MESSAGE,
                        MessageCode.CLASSROOM_SPECIFIC),
                        serverThread.getObjectOutStream());
                populateConvertedDoc(parent.getAbsolutePath(), parent.getName());
            } else {
                serverThread.sendPacket(new MsgPacket("Cannot process presentation",
                        Constants.LONGTERM_MESSAGE,
                        Constants.ERROR_MESSAGE,
                        MessageCode.CLASSROOM_SPECIFIC),
                        serverThread.getObjectOutStream());
            }
        }

    }

    /**
     * gets the jpg  file names generated from the presentation
     * @param contentPath
     * @param objectOut
     * @return
     */
    public int[] getImageFileNames(String contentPath) {
        File dir = new File(contentPath);
        String[] fileList = dir.list();

        if (fileList != null) {

            java.util.Arrays.sort(fileList);
            Vector newList = new Vector();
            int totalSlides = 0;
            int c = 0;
            for (int i = 0; i < fileList.length; i++) {
                if (fileList[i].endsWith(".jpg")) {
                    newList.addElement(fileList[i]);
                    totalSlides++;
                    c++;
                }
            }
            int[] imgNos = new int[newList.size()];
            for (int i = 0; i < newList.size(); i++) {
                String fn = (String) newList.elementAt(i);

                if (fn != null) {
                    for (int j = 0; j < fn.length(); j++) {
                        if (Character.isDigit(fn.charAt(j))) {
                            int imgNo = Integer.parseInt(fn.substring(fn.indexOf(fn.charAt(j)), fn.indexOf(".jpg")));
                            imgNos[i] = imgNo;
                            break;
                        }
                    }
                }
            }
            java.util.Arrays.sort(imgNos);
            return imgNos;

        }
        System.out.println(contentPath + " does not exist!!!");
        return null;
    }

    public void populateConvertedDoc(String slidesPath, String slideName) {
        int slides[] = getImageFileNames(slidesPath);
        // i hope to use threads here..dont know if it will help
        if (slides != null) {
            for (int i = 0; i <
                    slides.length; i++) {
                String filename = "img" + i + ".jpg";
                String filePath = slidesPath + "/" + filename;
                boolean lastFile = i == (slides.length - 1) ? true : false;
                //slidePath = filePath;
                byte[] byteArray = serverThread.readFile(filePath);
                if (i == 0) {
                    serverThread.getJ2meServer().populateGraphic(filePath);
                }
                ClassroomSlidePacket packet = new ClassroomSlidePacket(
                        serverThread.getThisUser().getSessionId(),
                        serverThread.getThisUser().getUserName(),
                        byteArray,
                        filename,
                        lastFile,
                        slides.length,
                        i,
                        slideName);
                serverThread.broadcastPacket(packet, true);
            }
        }
    }

    public boolean jodConvert(String filename) {
        try {
            serverThread.sendPacket(new MsgPacket("Converting ...",
                    Constants.LONGTERM_MESSAGE,
                    Constants.INFORMATION_MESSAGE,
                    MessageCode.CLASSROOM_SPECIFIC),
                    serverThread.getObjectOutStream());

            File inputFile = new File(filename);
            int dot = filename.lastIndexOf(".");
            String outname = filename.substring(0, dot);
            File f = new File(outname);
            f.mkdir();
            File outputFile = new File(outname + "/" + f.getName() + ".html");

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
            return true;
        } catch (Exception ex) {
            ex.printStackTrace();
            return false;
        }
    }

    public void processSlideBuilderPacket(SlideBuilderPacket p) {
        String path = serverThread.getSERVER_HOME() + "/userfiles/" + serverThread.getThisUser().getUserName() + "/slides/";
        new File(path).mkdirs();
        String name = "";
        if (p.getFilename().endsWith(".xml")) {
            name = path + "/" + p.getFilename();
        } else {
            name = path + "/" + p.getFilename() + ".xml";
        }

        String content = "<slide-show>\n";


        content += "\t<slides>\n";
        ArrayList<XmlBuilderSlide> slides = p.getSlides();
        for (int i = 0; i < slides.size(); i++) {
            content += "\t\t<slide>\n";
            content += "\t\t\t<title>" + slides.get(i).getTitle() + "</title>\n";
            content += "\t\t\t<text>" + slides.get(i).getText() + "</text>\n";
            content += "\t\t\t<text-size>" + slides.get(i).getTextSize() + "</text-size>\n";
            content += "\t\t\t<text-x-pos>" + slides.get(i).getTextXPos() + "</text-x-pos>\n";
            content += "\t\t\t<text-y-pos>" + slides.get(i).getTextYPos() + "</text-y-pos>\n";
            content += "\t\t\t<index>" + slides.get(i).getIndex() + "</index>\n";
            content += "\t\t\t<url>" + slides.get(i).getUrl() + "</url>\n";

            content += "\t\t\t<text-color>\n";
            content += "\t\t\t\t<red>" + slides.get(i).getTextColor().getRed() + "</red>\n";
            content += "\t\t\t\t<green>" + slides.get(i).getTextColor().getGreen() + "</green>\n";
            content += "\t\t\t\t<blue>" + slides.get(i).getTextColor().getBlue() + "</blue>\n";
            content += "\t\t\t</text-color>\n";
            content += "\t\t\t<question>\n";
            XmlBuilderSlide slide = slides.get(i);
            content += "\t\t\t\t<question-str>" + slide.getQuestion() + "</question-str>\n";

            content += "\t\t\t\t<answers>\n";
            ArrayList<Value> values = slide.getAnswerOptions();
            for (int k = 0; k < values.size(); k++) {
                content += "\t\t\t\t\t<answer>\n";
                content += "\t\t\t\t\t\t<option>" + values.get(k).getOption() + "</option>\n";
                content += "\t\t\t\t\t\t<value>" + values.get(k).getValue() + "</value>\n";
                content += "\t\t\t\t\t\t<correct-answer>" + values.get(k).isCorrectAnswer() + "</correct-answer>\n";
                content += "\t\t\t\t\t</answer>\n";
            }
            content += "\t\t\t\t</answers>\n";
            content += "\t\t\t\t<type>" + slide.getType() + "</type>\n";
            content += "\t\t\t\t<question-image-data>" + slide.getQuestionImageData() + "</question-image-data>\n";

            content += "\t\t\t\t<sender>" + slide.getSender() + "</sender>\n";
            content += "\t\t\t\t<id>" + slide.getId() + "</id>\n";

            content += "\t\t\t</question>\n";
            content += "\t\t\t<image-data>" + slides.get(i).getImageData() + "</image-data>\n";
            content += "\t\t</slide>\n";
        }
        content += "\t</slides>\n";
        content += "</slide-show>\n";
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
