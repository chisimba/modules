/*
 *
 *  Copyright (C) GNU/GPL AVOIR 2008
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if notfchat, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
package avoir.realtime.filetransfer;

import avoir.realtime.classroom.ClassroomMainFrame;
import avoir.realtime.classroom.whiteboard.item.Img;
import avoir.realtime.common.Constants;
import avoir.realtime.common.MessageCode;
import avoir.realtime.common.packet.FileUploadPacket;
import avoir.realtime.common.packet.SessionImgPacket;
import avoir.realtime.common.packet.UploadMsgPacket;
import java.io.File;
import java.io.FileOutputStream;
import java.io.OutputStream;
import java.text.DecimalFormat;
import javax.swing.ImageIcon;

/**
 * This class contains method for receiving/downloading a file that has been sent
 * by the presenter. If the file is large, then its send in chunks, and thus
 * downloaded in chunks here too. After the last chunk, they are all
 * concatenated to rebuild back the original file
 * @author David Wafula
 */
public class FileReceiverManager {

    String filename;
    ClassroomMainFrame base;
    int imageIndex;

    public FileReceiverManager(ClassroomMainFrame base) {
        this.base = base;
    }

    public String getFilename() {
        return filename;
    }

    public void setFilename(String filename) {
        this.filename = filename;
    }

    /**
     * Download/recieve the file packet. If is chunk..store it tempararily for later
     * concatenation when final chunk is received.
     * @param p
     */
    public void processFileDownload(FileUploadPacket p) {
        new File(base.getUser().getUserName()).mkdirs();
        new File(Constants.getRealtimeHome() + "/classroom/images/" + base.getUser().getSessionId()).mkdirs();
        new File(Constants.getRealtimeHome() + "/classroom/flash/" + base.getUser().getSessionId()).mkdirs();

        if (p.getFileType() == Constants.IMAGE) {
            filename = Constants.getRealtimeHome() + "/classroom/images/" + base.getUser().getSessionId() + "/" + p.getFilename();
        }


        if (p.getFileType() == Constants.FLASH) {
            filename = Constants.getRealtimeHome() + "/classroom/flash/" + base.getUser().getSessionId() + "/" + p.getFilename();
        }
        int nChunks = p.getTotalChunks();
        int chunk = p.getChunkNo();
        String clientID = p.getClientId();

        if (nChunks == -1 || chunk == -1) {
            System.out.println("Missing chunk information");
            base.getTcpConnector().sendPacket(
                    new UploadMsgPacket(base.getUser().getSessionId(), "Missing chunck information", p.getSender(), p.getRecepientIndex()));

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

                base.getTcpConnector().sendPacket(new UploadMsgPacket(base.getUser().getSessionId(), "Successful", p.getSender(), p.getRecepientIndex()));

                base.showInfoMessage("Download complete.");
                if (p.getFileType() == Constants.IMAGE) {

                    base.getWhiteBoardSurface().setPointer(Constants.WHITEBOARD);

                    base.getWhiteBoardSurface().addItem(new Img(100, 100, 150, 150, p.getFilename(), base.getWhiteBoardSurface().getImgs().size(), p.getId()));/// (int) image.getIconWidth(), (int) image.getIconHeight(), base.getSessionId(),imageIndex++));
                    ImageIcon image = new ImageIcon(filename);
                    base.getWhiteBoardSurface().setImage(image);
                }
                if (p.getFileType() == Constants.FLASH) {
                    processFlash(filename, p.getId(), p.getSessionId());
                }
                return;
            }

        } catch (Exception ex) {
            ex.printStackTrace();
            System.out.println("Error creating file");
            base.showErrorMessage("Error saving file.");
            base.getTcpConnector().sendPacket(new UploadMsgPacket(base.getUser().getSessionId(), "Error saving file", p.getSender(), p.getRecepientIndex()));
            return;
        }

        if (nChunks > 1 && chunk == nChunks - 1) {

            File tmpFile = new File(getTempFile(clientID));
            File destFile = new File(filename);
            if (destFile.exists()) {
                destFile.delete();
            }
            if (!tmpFile.renameTo(destFile)) {
                base.getTcpConnector().sendPacket(new UploadMsgPacket(base.getUser().getSessionId(), "Unable to create file", p.getSender(), p.getRecepientIndex()));
                base.showErrorMessage("Error saving file.");
            } else {
                base.getTcpConnector().sendPacket(new UploadMsgPacket(base.getUser().getSessionId(), "Successful.", p.getSender(), p.getRecepientIndex()));
                base.showInfoMessage("Download complete.");
                if (p.getFileType() == Constants.IMAGE) {
                    ImageIcon image = new ImageIcon(filename);
                    base.getWhiteBoardSurface().setPointer(Constants.WHITEBOARD);

                    base.getWhiteBoardSurface().addItem(new Img(100, 100, 150, 150, p.getFilename(), base.getWhiteBoardSurface().getImgs().size(), p.getId()));
                    base.getWhiteBoardSurface().setImage(image);
                    System.out.println("rendered image");
                }
                if (p.getFileType() == Constants.FLASH) {
                    processFlash(filename, p.getId(), p.getSessionId());
                }
            }

        } else {
            float val = chunk + 1;
            float total = p.getTotalChunks();
            String sval = new DecimalFormat("#.#").format((val / total) * 100) + "%";
            base.getTcpConnector().sendPacket(new UploadMsgPacket(base.getUser().getSessionId(), sval, p.getSender(), p.getRecepientIndex()));
            base.showInfoMessage("Downloading " + new File(filename).getName() + " " + sval + " ..");

        }

    }

    private void processFlash(final String filepath, final String id, final String sessionId) {
        Thread t = new Thread() {

            public void run() {
//                base.getClassroomManager().showFlashPlayer("file://" + filepath, id, sessionId);
            }
        };
        t.start();
    }

    public void processSessionImageFileDownload(SessionImgPacket p) {
        new File(base.getUser().getUserName()).mkdirs();
        new File(Constants.getRealtimeHome() + "/classroom/images/" + base.getUser().getSessionId()).mkdirs();
        filename = Constants.getRealtimeHome() + "/classroom/images/" + base.getUser().getSessionId() + "/" + p.getFilename();
        int nChunks = p.getTotalChunks();
        int chunk = p.getChunkNo();
        String clientID = p.getClientId();

        if (nChunks == -1 || chunk == -1) {
            System.out.println("Missing chunk information");
            base.showErrorMessage("Error donwloading session image");
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
                base.showErrorMessage("Download complete.");
                base.getWhiteBoardSurface().setPointer(Constants.WHITEBOARD);
                Img img = p.getImg();
                img.setImageIndex(base.getWhiteBoardSurface().getImgs().size());
                base.getWhiteBoardSurface().addItem(img);
                ImageIcon image = new ImageIcon(filename);
                base.getWhiteBoardSurface().setImage(image);

                return;
            }

        } catch (Exception ex) {
            ex.printStackTrace();
            System.out.println("Error creating file");
            base.showErrorMessage("Error saving file.");
            return;
        }

        if (nChunks > 1 && chunk == nChunks - 1) {

            File tmpFile = new File(getTempFile(clientID));
            File destFile = new File(filename);
            if (destFile.exists()) {
                destFile.delete();
            }
            if (!tmpFile.renameTo(destFile)) {
                base.showErrorMessage("Error saving file.");
            } else {
                base.showInfoMessage("Download complete.");
                ImageIcon image = new ImageIcon(filename);
                base.getWhiteBoardSurface().setPointer(Constants.WHITEBOARD);

                Img img = p.getImg();

                img.setImageIndex(base.getWhiteBoardSurface().getImgs().size());
                base.getWhiteBoardSurface().addItem(img);
                base.getWhiteBoardSurface().setImage(image);
            }
        } else {
            float val = chunk + 1;
            float total = p.getTotalChunks();
            String sval = new DecimalFormat("#.#").format((val / total) * 100) + "%";
            base.showInfoMessage("Downloading " + new File(filename).getName() + " " + sval + " ..");

        }

    }

    private String getTempFile(String clientID) {
        return base.getUser().getUserName() + "/" + clientID + ".tmp";
    }
}
