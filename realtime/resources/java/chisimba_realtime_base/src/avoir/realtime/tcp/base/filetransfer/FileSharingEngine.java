/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.filetransfer;

import avoir.realtime.tcp.base.*;
import avoir.realtime.tcp.common.packet.BinaryFileChunkPacket;
import avoir.realtime.tcp.common.packet.FileUploadPacket;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;
import javax.swing.JOptionPane;

/**
 *
 * @author developer
 */
public class FileSharingEngine {

    RealtimeBase base;

    public FileSharingEngine(RealtimeBase base) {
        this.base = base;
    }

    private void delay(long time) {
        try {
            Thread.sleep(time);
        } catch (Exception ex) {
        }
    }

    private byte[] readFile(String filePath) {
        File f = new File(filePath);
        try {
            if (f.exists()) {
                FileChannel fc = new FileInputStream(f.getAbsolutePath()).getChannel();

                ByteBuffer buff = ByteBuffer.allocate((int) fc.size());
                fc.read(buff);
                if (buff.hasArray()) {
                    byte[] byteArray = buff.array();
                    fc.close();
                    return byteArray;
                } else {
                    JOptionPane.showMessageDialog(null, "Error reading the file");
                    return null;
                }
            } else {
                JOptionPane.showMessageDialog(null, filePath + " doesn't exist.");
                return null;
            }
        } catch (Exception ex) {
            ex.printStackTrace();
            return null;
        }

    }

    public void readBinaryFile(String filepath, int index, String recepient) {
        int BUFFER_SIZE = 4096;
        int MAX_CHUNK_SIZE = 1 * BUFFER_SIZE; //~4.1MB
        String filename = "unknown";
        /*
        byte[] byteArray = readFile(filepath);
        base.getTcpClient().sendPacket(new BinaryFileChunkPacket(base.getSessionId(), filename, byteArray, rec, false));
        //notify that this is the last chunk
        BinaryFileChunkPacket lastChunk = new BinaryFileChunkPacket(base.getSessionId(), filename, new byte[0], rec, true);
        base.getTcpClient().sendPacket(lastChunk);
        base.getFileTransferPanel().setProgress(index, "Complete");
         */
        try {

            File file = new File(filepath);
            long fileSize = file.length();

            FileInputStream in = new FileInputStream(file);

            int nChunks = (int) (fileSize / MAX_CHUNK_SIZE);
            if (fileSize % MAX_CHUNK_SIZE > 0) {
                nChunks++;
            }


            long bytesRemaining = fileSize;

            String clientID = String.valueOf((long) (Long.MIN_VALUE *
                    Math.random()));

            for (int i = 0; i < nChunks; i++) {

                int chunkSize = (int) ((bytesRemaining > MAX_CHUNK_SIZE) ? MAX_CHUNK_SIZE : bytesRemaining);
                bytesRemaining -= chunkSize;
                byte[] buf = new byte[chunkSize];

                int read = in.read(buf);
                if (read == -1) {
                    break;
                } else if (read > 0) {

                    base.getTcpClient().sendPacket(new FileUploadPacket(base.getSessionId(),
                            base.getSessionId(), buf, i, nChunks, file.getName(),
                            clientID, recepient, false, base.getUserName(), index));
                    //simulate
                    //delay(1000);
                }


            }
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
