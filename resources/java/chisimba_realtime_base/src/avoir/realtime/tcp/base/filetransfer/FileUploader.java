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
package avoir.realtime.tcp.base.filetransfer;

import avoir.realtime.tcp.base.RealtimeBase;
import avoir.realtime.tcp.common.MessageCode;
import avoir.realtime.tcp.common.packet.FileUploadPacket;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.text.DecimalFormat;

/**
 * This file performs file upload operations
 * @author David Wafula
 */
public class FileUploader {

    public static final int BUFFER_SIZE = 4096;
    public static final int MAX_CHUNK_SIZE = 10 * BUFFER_SIZE;
    private RealtimeBase base;
    // private ProgressMonitor progressMonitor;
    public FileUploader(RealtimeBase base) {
        this.base = base;
    }

    public void transferFile(String fileName,int type) {
        try {

            File file = new File(fileName);
            long fileSize = file.length();

            FileInputStream in = new FileInputStream(file);

            int nChunks = (int) (fileSize / MAX_CHUNK_SIZE);
            if (fileSize % MAX_CHUNK_SIZE > 0) {
                nChunks++;
            }
            //progressMonitor = new ProgressMonitor(base,
            //      "Uploading File",
            //    "", 0, nChunks);


            long bytesRemaining = fileSize;

            String clientID = String.valueOf((long) (Long.MIN_VALUE *
                    Math.random()));

            for (int i = 0; i < nChunks; i++) {

                int chunkSize = (int) ((bytesRemaining > MAX_CHUNK_SIZE) ? MAX_CHUNK_SIZE : bytesRemaining);
                float val = (chunkSize / bytesRemaining) * 100;
                base.showMessage("Initializing " + new DecimalFormat("#.#").format(val) + "% ...", false, false, MessageCode.ALL);
                bytesRemaining -= chunkSize;
                byte[] buf = new byte[chunkSize];

                int read = in.read(buf);
                if (read == -1) {
                    break;
                } else if (read > 0) {

                    base.getTcpClient().sendPacket(new FileUploadPacket(
                            base.getSessionId(),
                            base.getSessionId(), buf, i, nChunks, file.getName(),
                            clientID, base.getSessionId(), true, base.getUserName(), -1,type));
                }


            }
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
