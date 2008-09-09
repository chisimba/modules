/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.filetransfer;

import avoir.realtime.tcp.base.*;
import avoir.realtime.tcp.common.packet.FileUploadPacket;
import avoir.realtime.tcp.common.packet.UploadMsgPacket;
import java.io.File;
import java.io.FileOutputStream;
import java.io.OutputStream;
import java.text.DecimalFormat;

/**
 *
 * @author developer
 */
public class FileReceiverManager {

    String filename;
    RealtimeBase base;

    public FileReceiverManager(RealtimeBase base) {
        this.base = base;
    }

    public String getFilename() {
        return filename;
    }

    public void setFilename(String filename) {
        this.filename = filename;
    }

    public void processFileUpload(FileUploadPacket p) {
        new File(base.getUserName()).mkdirs();
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

                return;
            }

        } catch (Exception ex) {
            ex.printStackTrace();
            System.out.println("Error creating file");
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
            } else {
                base.getTcpClient().sendPacket(new UploadMsgPacket(base.getSessionId(), "Successful.", p.getSender(), p.getRecepientIndex()));

            }
        } else {
            float val = chunk + 1;
            float total = p.getTotalChunks();
            String sval = new DecimalFormat("#.#").format((val / total) * 100) + "%";
            base.getTcpClient().sendPacket(new UploadMsgPacket(base.getSessionId(), sval, p.getSender(), p.getRecepientIndex()));
        }

    }

    private String getTempFile(String clientID) {
        return base.getUserName() + "/" + clientID + ".tmp";
    }
}
