/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.plugins;

import java.io.File;
import java.io.FileOutputStream;
import java.io.OutputStream;
import java.text.DecimalFormat;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;
import org.dom4j.QName;
import org.jivesoftware.openfire.PacketRouter;
import org.jivesoftware.util.Base64;
import org.w3c.dom.Document;
import org.xmpp.packet.IQ;

/**
 *
 * @author developer
 */
public class FileManager {

    private AvoirRealtimePlugin pl;
    private PacketRouter packetRouter;

    public FileManager(AvoirRealtimePlugin pl) {
        this.pl = pl;
        packetRouter = pl.getPacketRouter();
    }

    public static String removeExt(String str) {
        if (str == null) {
            return null;
        }
        int i = str.lastIndexOf(".");
        if (i > -1) {
            return str.substring(0, i);
        }
        return str;
    }

    public IQ downloadFile(IQ packet, Document doc) {

        IQ replyPacket = IQ.createResultIQ(packet);
        IQ replyPacket2 = replyPacket.createCopy();
        Element queryResult = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
        queryResult.addElement("mode").addText(Mode.FILE_UPLOAD_RESULT);
        StringBuilder sb = new StringBuilder();
        int nChunks = XmlUtils.readInt(doc, "total-chunks");
        int chunk = XmlUtils.readInt(doc, "chunk-no");
        String clientID = XmlUtils.readString(doc, "client-id");
        String username = XmlUtils.readString(doc, "username");
        String uploadedFileName = XmlUtils.readString(doc, "file-name");
        String chunkData = XmlUtils.readString(doc, "chunk-data");
        String roomName = XmlUtils.readString(doc, "room-name");
        String action = XmlUtils.readString(doc, "action");
        String fileType = XmlUtils.readString(doc, "file-type");
        byte[] buff = Base64.decode(chunkData);
        if (nChunks == -1 || chunk == -1) {

            sb.append("<message>").append("Missing Chunk Information").append("</message>");
            queryResult.addElement("content").addText(sb.toString());
            replyPacket.setChildElement(queryResult);
            return replyPacket;
        }

        if (chunk == 0) {
            // need check permission to create file here
        }
        OutputStream out = null;
        File ff = new File(Constants.FILES_DIR + "/" + fileType + "/" + username);
        ff.mkdirs();
        String path = ff.getAbsolutePath();
        new File(Constants.FILES_DIR + "/" + fileType + "/" + username + "/tmp/").mkdirs();

        String tempPath = Constants.FILES_DIR + "/" + fileType + "/" + username + "/tmp/" + clientID + ".tmp";
        try {
            if (nChunks == 1) {
                out = new FileOutputStream(path + "/" + uploadedFileName);
            } else {
                out = new FileOutputStream(tempPath, (chunk > 0));
            }

            out.write(buff);
            out.close();
            if (nChunks == 1) {

                if (action.equals("jodconvert")) {
                    sb.append("<message>").append("Converting ...").append("</message>");
                    sb.append("<progress>").append(chunk - 1).append("</progress>");
                    queryResult.addElement("content").addText(sb.toString());
                    replyPacket.setChildElement(queryResult);
                    replyPacket.setTo(packet.getFrom());
                    packetRouter.route(replyPacket);
                    if (pl.getSlideshowProcessor().jodConvert(path + "/" + uploadedFileName)) {
                        StringBuilder sb2 = new StringBuilder();
                        Element queryResult2 = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
                        queryResult2.addElement("mode").addText(Mode.FILE_UPLOAD_RESULT);
                        sb2.append("<message>").append("success").append("</message>");
                        sb2.append("<progress>").append(chunk + 1).append("</progress>");
                        sb2.append("<upload-path>").append(path + "/" + uploadedFileName).append("</upload-path>");
                        queryResult2.addElement("content").addText(sb2.toString());
                        replyPacket2.setTo(packet.getFrom());
                        replyPacket2.setChildElement(queryResult2);
                        pl.getRoomResourceManager().addPresentationName(roomName, path + "/" + uploadedFileName);
                        packetRouter.route(replyPacket2);
                    } else {
                        StringBuilder sb2 = new StringBuilder();
                        Element queryResult2 = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
                        queryResult2.addElement("mode").addText(Mode.FILE_UPLOAD_RESULT);
                        sb2.append("<message>").append("Error converting file").append("</message>");
                        sb2.append("<progress>").append(chunk + 1).append("</progress>");
                        queryResult2.addElement("content").addText(sb2.toString());
                        replyPacket2.setTo(packet.getFrom());
                        replyPacket2.setChildElement(queryResult2);
                        packetRouter.route(replyPacket2);
                    }
                } else {
                    return replyPacket;
                }
            } else if (nChunks > 1 && chunk == nChunks - 1) {
                File tmpFile = new File(tempPath);
                File destFile = new File(path + "/" + uploadedFileName);
                if (destFile.exists()) {
                    destFile.delete();
                }
                if (!tmpFile.renameTo(destFile)) {
                    sb.append("<message>").append("Error saving file").append("</message>");
                    queryResult.addElement("content").addText(sb.toString());
                    replyPacket.setChildElement(queryResult);
                    return replyPacket;
                } else {

                    if (action.equals("jodconvert")) {
                        sb.append("<message>").append("Converting ...").append("</message>");
                        sb.append("<progress>").append(chunk - 1).append("</progress>");
                        queryResult.addElement("content").addText(sb.toString());
                        replyPacket.setChildElement(queryResult);
                        replyPacket.setTo(packet.getFrom());
                        packetRouter.route(replyPacket);
                        if (pl.getSlideshowProcessor().jodConvert(path + "/" + uploadedFileName)) {
                            StringBuilder sb2 = new StringBuilder();
                            Element queryResult2 = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
                            queryResult2.addElement("mode").addText(Mode.FILE_UPLOAD_RESULT);
                            sb2.append("<message>").append("success").append("</message>");
                            sb2.append("<progress>").append(chunk + 1).append("</progress>");
                            sb2.append("<upload-path>").append(path + "/" + uploadedFileName).append("</upload-path>");
                            queryResult2.addElement("content").addText(sb2.toString());
                            replyPacket2.setTo(packet.getFrom());
                            replyPacket2.setChildElement(queryResult2);
                            pl.getRoomResourceManager().addPresentationName(roomName, path + "/" + uploadedFileName);
                            packetRouter.route(replyPacket2);
                        } else {
                            StringBuilder sb2 = new StringBuilder();
                            Element queryResult2 = DocumentHelper.createElement(QName.get("query", Constants.NAME_SPACE));
                            queryResult2.addElement("mode").addText(Mode.FILE_UPLOAD_RESULT);
                            sb2.append("<message>").append("Error converting file").append("</message>");
                            sb2.append("<progress>").append(chunk + 1).append("</progress>");
                            queryResult2.addElement("content").addText(sb2.toString());
                            replyPacket2.setTo(packet.getFrom());
                            replyPacket2.setChildElement(queryResult2);
                            packetRouter.route(replyPacket2);
                        }
                    } else {
                        return replyPacket;
                    }
                }
            } else {
                float val = chunk + 1;
                float total = nChunks;
                String sval = new DecimalFormat("#.#").format((val / total) * 100) + "%";
                sb.append("<message>").append(sval).append("</message>");
                sb.append("<progress>").append(chunk).append("</progress>");
                queryResult.addElement("content").addText(sb.toString());
                replyPacket.setChildElement(queryResult);
                return replyPacket;
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }

        return null;
    }
}
