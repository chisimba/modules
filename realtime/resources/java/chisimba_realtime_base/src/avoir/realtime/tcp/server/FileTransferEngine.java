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
package avoir.realtime.tcp.server;

import avoir.realtime.tcp.common.Constants;
import avoir.realtime.tcp.common.GenerateUUID;
import avoir.realtime.tcp.common.packet.FileUploadPacket;
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
public class FileTransferEngine {

    int index;
    String recepient = "";
    int BUFFER_SIZE = 4096;
    int MAX_CHUNK_SIZE = 1 * BUFFER_SIZE;
    int nChunks;

    /**
     * Read the file in small chunks
     * @param filepath
     * @param index
     * @param recepient
     */
    public synchronized void populateBinaryFile(ServerThread server, String filepath) {

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

                    FileUploadPacket packet = new FileUploadPacket(server.getThisUser().getSessionId(),
                            server.getThisUser().getSessionId(), buf, i, nChunks, file.getName(),
                            clientID, recepient, false, server.getThisUser().getUserName(), index, Constants.IMAGE);
                    packet.setId(GenerateUUID.getId());
                    server.broadcastPacket(packet, true);

                }


            }
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
