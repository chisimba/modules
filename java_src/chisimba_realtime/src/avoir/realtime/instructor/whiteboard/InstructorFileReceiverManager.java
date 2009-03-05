/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.instructor.whiteboard;

import avoir.realtime.common.BuilderSlide;
import avoir.realtime.classroom.whiteboard.item.Img;
import avoir.realtime.common.Constants;
import avoir.realtime.common.XmlUtil;
import avoir.realtime.common.packet.FileUploadPacket;
import avoir.realtime.common.packet.UploadMsgPacket;
import avoir.realtime.common.packet.XmlQuestionPacket;
import avoir.realtime.filetransfer.FileReceiverManager;
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
                p.getFileType() == Constants.SLIDE_SHOW_NAV ||
                p.getFileType() == Constants.QUESTION_NAV ||
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
                if (p.getFileType() == Constants.SLIDE_SHOW_NAV) {
                    ArrayList<BuilderSlide> list = XmlUtil.readXmlSlideShowFile(filename);
                    mf.getSlideShowNavigator().addSlides(list);
                }

                if (p.getFileType() == Constants.SLIDE_SHOW) {
                    ArrayList<BuilderSlide> list = XmlUtil.readXmlSlideShowFile(filename);
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
                    mf.getSlideBuilderManager().setQuestion(XmlUtil.readXmlQuestionFile(filename));
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
                    ArrayList<BuilderSlide> list = XmlUtil.readXmlSlideShowFile(filename);
                    mf.getSlideBuilderManager().setSlides(list, stripExt(new File(filename).getName()));

                }

                if (p.getFileType() == Constants.SLIDE_SHOW_NAV) {
                    ArrayList<BuilderSlide> list = XmlUtil.readXmlSlideShowFile(filename);
                    mf.getSlideShowNavigator().addSlides(list);
                }
                if (p.getFileType() == Constants.QUESTION_NAV) {
                    XmlQuestionPacket pac = XmlUtil.readXmlQuestionFile(filename);
                    mf.initAnswerFrame(pac.getQuestionPacket(), pac.getName(),true);
                }
                if (p.getFileType() == Constants.NOTEPAD) {
                    javax.swing.text.Document doc = getNotepadContent(filename);
                    base.showNotepad(doc, new File(filename).getName(), filename);
                }
                if (p.getFileType() == Constants.SLIDE_BUILDER_TEXT) {
                    mf.getSlideBuilderManager().setSlideText(getContents(new File(filename)));
                }
                if (p.getFileType() == Constants.QUESTION_FILE) {
                    mf.getSlideBuilderManager().setQuestion(XmlUtil.readXmlQuestionFile(filename));
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
}
