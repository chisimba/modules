/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base.filetransfer;

import avoir.realtime.tcp.base.RealtimeBase;
import avoir.realtime.tcp.common.packet.FileUploadPacket;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import javax.swing.JOptionPane;
import javax.swing.ProgressMonitor;

/**
 *
 * @author developer
 */
public class FileUploader {

    public static final int BUFFER_SIZE = 4096;
    public static final int MAX_CHUNK_SIZE = 1000 * BUFFER_SIZE; //~4.1MB
    private RealtimeBase base;
    private ProgressMonitor progressMonitor;

    public FileUploader(RealtimeBase base) {
        this.base = base;
    }

    public void transferFile(String fileName) {
        try {

            File file = new File(fileName);
            long fileSize = file.length();

            FileInputStream in = new FileInputStream(file);

            int nChunks = (int) (fileSize / MAX_CHUNK_SIZE);
            if (fileSize % MAX_CHUNK_SIZE > 0) {
                nChunks++;
            }
            progressMonitor = new ProgressMonitor(base,
                    "Uploading File",
                    "", 0, nChunks);

          
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
                    
                    base.getTcpClient().sendPacket(new FileUploadPacket(base.getSessionId(), base.getSessionId(), buf, i, nChunks, file.getName(), clientID));
                }


            }
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public ProgressMonitor getProgressMonitor() {
        return progressMonitor;
    }

    public void setProgress(int value) {
        if (progressMonitor != null) {
            progressMonitor.setProgress(value);
        }
    }
}
