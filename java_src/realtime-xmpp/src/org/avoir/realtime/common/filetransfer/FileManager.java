/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.common.filetransfer;

import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import javax.swing.JOptionPane;
import javax.swing.ProgressMonitor;
import javax.swing.SwingUtilities;
import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.main.WebPresentManager;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;
import org.jivesoftware.smack.util.Base64;

/**
 *
 * @author developer
 */
public class FileManager {

    public static final int BUFFER_SIZE = 4096;
    public static final int MAX_CHUNK_SIZE = 10 * BUFFER_SIZE;
    private static ProgressMonitor monitor;
    private static boolean upload = true;
    private static String filename = "";

    public static void setUploadNote(final String note, final int progress, final String uploadPath) {


        SwingUtilities.invokeLater(new Runnable() {

            public void run() {

                monitor.setNote(note);
                GUIAccessManager.mf.getWbInfoField().setText(note);
                monitor.setProgress(progress);
                upload = true;

                if (note != null) {
                    if (note.equals("success")) {
                        int n = JOptionPane.showConfirmDialog(null, "Would you like" +
                                " to add this file as a room resource?",
                                "Room Resource",
                                JOptionPane.YES_NO_OPTION);
                        if (n == JOptionPane.YES_OPTION) {
                            filename = new File(filename).getName();
                            WebPresentManager.slidesDir = GeneralUtil.removeExt(uploadPath);
                            WebPresentManager.presentationName = GeneralUtil.removeExt(filename);
                            WebPresentManager.presentationId = GeneralUtil.removeExt(filename);
                            //GUIAccessManager.mf.getChatRoomManager().requestNewSlides("");


                            RealtimePacket p = new RealtimePacket();
                            p.setMode(RealtimePacket.Mode.ADD_SLIDE_SHOW_CLASS_RESOURCE);
                            StringBuilder sb = new StringBuilder();
                            sb.append("<file-name>").append(filename).append("</file-name>");
                            sb.append("<room-name>").append(ChatRoomManager.currentRoomName).append("</room-name>");
                            sb.append("<file-path>").append(uploadPath).append("</file-path>");
                            p.setContent(sb.toString());
                            ConnectionManager.sendPacket(p);
                        }
                    }
                    if (note.toLowerCase().indexOf("error") > -1) {
                        monitor.close();
                        JOptionPane.showMessageDialog(null, note, "Error", JOptionPane.ERROR_MESSAGE);
                    }
                }
            }
        });

    }

    public static void transferFile(final String fileName, final String type, final String action) {
        Thread t = new Thread() {

            public void run() {
                try {

                    File file = new File(fileName);
                    long fileSize = file.length();
                    filename = fileName;
                    FileInputStream in = new FileInputStream(file);

                    int nChunks = (int) (fileSize / MAX_CHUNK_SIZE);
                    if (fileSize % MAX_CHUNK_SIZE > 0) {
                        nChunks++;
                    }


                    long bytesRemaining = fileSize;

                    String clientID = String.valueOf((long) (Long.MIN_VALUE *
                            Math.random()));
                    monitor = new ProgressMonitor(GUIAccessManager.mf, "Uploading ..", "", 0, nChunks);

                    for (int i = 0; i < nChunks; i++) {
                        int chunkSize = (int) ((bytesRemaining > MAX_CHUNK_SIZE) ? MAX_CHUNK_SIZE : bytesRemaining);
                        float val = (chunkSize / bytesRemaining) * 100;

                        bytesRemaining -= chunkSize;
                        byte[] buf = new byte[chunkSize];

                        int read = in.read(buf);
                        if (read == -1) {
                            break;
                        } else if (read > 0) {
                            RealtimePacket packet = new RealtimePacket();
                            packet.setMode(RealtimePacket.Mode.FILE_UPLOAD);
                            StringBuilder sb = new StringBuilder();
                            String buffAsString = Base64.encodeBytes(buf);
                            sb.append("<username>").append(ConnectionManager.getUsername()).append("</username>");
                            sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
                            sb.append("<file-type>").append(type).append("</file-type>");
                            sb.append("<action>").append(action).append("</action>");
                            sb.append("<file-name>").append(file.getName()).append("</file-name>");
                            sb.append("<client-id>").append(clientID).append("</client-id>");
                            sb.append("<chunk-no>").append(i).append("</chunk-no>");
                            sb.append("<total-chunks>").append(nChunks).append("</total-chunks>");
                            sb.append("<chunk-data>").append(buffAsString).append("</chunk-data>");

                            packet.setContent(sb.toString());
                            ConnectionManager.sendPacket(packet);
                            upload = false;
                            //block till we get confirmation of chunk received
                            while (!upload) {
                                try {
                                    sleep(100);
                                } catch (Exception ex) {
                                }
                            }


                        }
                    }
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        };
        t.start();
    }
}
