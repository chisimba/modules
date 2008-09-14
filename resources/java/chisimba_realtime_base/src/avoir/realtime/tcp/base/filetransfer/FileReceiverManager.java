/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.filetransfer;

import avoir.realtime.tcp.base.*;
import avoir.realtime.tcp.common.Constants;
import avoir.realtime.tcp.common.MessageCode;
import avoir.realtime.tcp.common.packet.FileUploadPacket;
import avoir.realtime.tcp.common.packet.UploadMsgPacket;
import avoir.realtime.tcp.whiteboard.item.Img;
import java.io.File;
import java.io.FileOutputStream;
import java.io.OutputStream;
import java.text.DecimalFormat;
import javax.swing.ImageIcon;
import javax.swing.JOptionPane;

/**
 *
 * @author developer
 */
public class FileReceiverManager {

    String filename;
    RealtimeBase base;
    int imageIndex;

    public FileReceiverManager(RealtimeBase base) {
        this.base = base;
    }

    public String getFilename() {
        return filename;
    }

    public void setFilename(String filename) {
        this.filename = filename;
    }

    public void processFileDownload(FileUploadPacket p) {
        new File(base.getUserName()).mkdirs();
        new File(Constants.getRealtimeHome() + "/classroom/images/" + base.getSessionId()).mkdirs();
        if (p.getFileType() == Constants.IMAGE) {
            filename = Constants.getRealtimeHome() + "/classroom/images/" + base.getSessionId() + "/" + p.getFilename();
        }
        int nChunks = p.getTotalChunks();
        int chunk = p.getChunkNo();
        String clientID = p.getClientId();

        if (nChunks == -1 || chunk == -1) {
            System.out.println("Missing chunk information");
            base.getTcpClient().sendPacket(
                    new UploadMsgPacket(base.getSessionId(), "Missing chunck information", p.getSender(), p.getRecepientIndex()));

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
            //base.getTcpClient().sendPacket(new UploadMsgPacket(base.getSessionId(), chunk + 1));
            out.write(p.getBuff());
            out.close();
            if (nChunks == 1) {

                base.getTcpClient().sendPacket(new UploadMsgPacket(base.getSessionId(), "Successful", p.getSender(), p.getRecepientIndex()));

                base.showMessage("Download complete.", true, false, MessageCode.ALL);
                if (p.getFileType() == Constants.IMAGE) {

                    base.getWhiteboardSurface().setPointer(Constants.WHITEBOARD);

                    base.getWhiteboardSurface().addItem(new Img(100, 100, 150, 150, base.getSessionId(), base.getWhiteboardSurface().getImgs().size(), p.getId()));/// (int) image.getIconWidth(), (int) image.getIconHeight(), base.getSessionId(),imageIndex++));
                    ImageIcon image = new ImageIcon(filename);
                    base.getWhiteboardSurface().setImage(image);
                }
                return;
            }

        } catch (Exception ex) {
            ex.printStackTrace();
            System.out.println("Error creating file");
            base.showMessage("Error saving file.", true, true, MessageCode.ALL);
            base.getTcpClient().sendPacket(new UploadMsgPacket(base.getSessionId(), "Error saving file", p.getSender(), p.getRecepientIndex()));
            return;
        }

        if (nChunks > 1 && chunk == nChunks - 1) {

            File tmpFile = new File(getTempFile(clientID));
            File destFile = new File(filename);
            if (destFile.exists()) {
                destFile.delete();
            }
            if (!tmpFile.renameTo(destFile)) {
                base.getTcpClient().sendPacket(new UploadMsgPacket(base.getSessionId(), "Unable to create file", p.getSender(), p.getRecepientIndex()));
                base.showMessage("Error saving file.", true, true, MessageCode.ALL);
            } else {
                base.getTcpClient().sendPacket(new UploadMsgPacket(base.getSessionId(), "Successful.", p.getSender(), p.getRecepientIndex()));
                base.showMessage("Download complete.", true, false, MessageCode.ALL);
                if (p.getFileType() == Constants.IMAGE) {
                    ImageIcon image = new ImageIcon(filename);
                    base.getWhiteboardSurface().setPointer(Constants.WHITEBOARD);

                    base.getWhiteboardSurface().addItem(new Img(100, 100, 150, 150, base.getSessionId(), base.getWhiteboardSurface().getImgs().size(), p.getId()));
                    base.getWhiteboardSurface().setImage(image);
                }
            }
        } else {
            float val = chunk + 1;
            float total = p.getTotalChunks();
            String sval = new DecimalFormat("#.#").format((val / total) * 100) + "%";
            base.getTcpClient().sendPacket(new UploadMsgPacket(base.getSessionId(), sval, p.getSender(), p.getRecepientIndex()));
            base.showMessage("Downloading " + new File(filename).getName() + " " + sval + " ..", false, false, MessageCode.ALL);

        }

    }

    private String getTempFile(String clientID) {
        return base.getUserName() + "/" + clientID + ".tmp";
    }
}
