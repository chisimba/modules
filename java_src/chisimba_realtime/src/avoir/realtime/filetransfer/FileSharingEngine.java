/*
 * Copyright (C) GNU/GPL AVOIR 2008
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
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
package avoir.realtime.filetransfer;

import avoir.realtime.classroom.ClassroomMainFrame;
import avoir.realtime.common.Constants;
import avoir.realtime.common.packet.FileUploadPacket;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.OutputStream;
import java.util.Vector;

/**
 * Use for reading the selected file in small chunks onto the server
 * @author David Wafula
 */
public class FileSharingEngine extends Thread{

    ClassroomMainFrame base;
    String filepath;
    int index;
    String recepient;
    int BUFFER_SIZE = 4096;
    int MAX_CHUNK_SIZE = 1 * BUFFER_SIZE;
    int nChunks;
    final Vector<FileUploadPacket> chunks = new Vector<FileUploadPacket>();

    public FileSharingEngine(ClassroomMainFrame base, String filepath, int index, String recepient) {
        this.base = base;
        this.filepath = filepath;
        this.index = index;
        this.recepient = recepient;
    }

    public void run(){
        readBinaryFile();
    }
    /**
     * Read the file in small chunks
     * @param filepath
     * @param index
     * @param recepient
     */
    public synchronized void readBinaryFile() {

        try {

            File file = new File(filepath);
            long fileSize = file.length();

            FileInputStream in = new FileInputStream(file);

            nChunks = (int) (fileSize / MAX_CHUNK_SIZE);
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
                    //System.out.println("Uploading " + i + " to " + recepient);

                    /*synchronized (chunks) {
                        chunks.addElement(new FileUploadPacket(base.getSessionId(),
                                base.getSessionId(), buf, i, nChunks, file.getName(),
                                clientID, recepient, false, base.getUserName(), index));
                    }*/
/*                    base.getTcpClient().sendPacket(new FileUploadPacket(base.getSessionId(),
                                base.getSessionId(), buf, i, nChunks, file.getName(),
                                clientID, recepient, false, base.getUserName(), index,Constants.ANY));
  */              }


            }
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void saveChunks(String filename) {
        try {
            OutputStream out = new FileOutputStream(filename);
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
