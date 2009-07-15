/*

 *
 * Copyright (C) GNU/GPL AVOIR 2008
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

 */
package org.avoir.realtime.net.providers;

import java.io.ByteArrayInputStream;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.util.XmlUtils;
import org.avoir.realtime.net.packets.RealtimeNotepadPacket;
import org.w3c.dom.Document;

/**
 *
 * @author developer
 */
public class NotepadProcessor {

    private RealtimePacketListener realtimePacketListener;

    public void addRealtimePacketListener(RealtimePacketListener realtimePacketListener) {
        this.realtimePacketListener = realtimePacketListener;
    }

    public RealtimeNotepadPacket getRealtimeNotepadPacket(String xmlContents) {
        try {
            DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();

            DocumentBuilder documentBuilder = documentBuilderFactory.newDocumentBuilder();
            Document doc = documentBuilder.parse(
                    new ByteArrayInputStream(xmlContents.getBytes(Constants.PREFERRED_ENCODING)));

            RealtimeNotepadPacket p = new RealtimeNotepadPacket();

            String fileType = XmlUtils.readString(doc, "file-type");
            p.setPacketType(fileType);
            String filename=XmlUtils.readString(doc, "filename");
            p.setFilename(filename);
            String content = XmlUtils.readString(doc, "file-content");
            String filePath = XmlUtils.readString(doc, "file-path");
            p.setFilePath(filePath);
            p.setContent(content);
            if (realtimePacketListener != null) {
                realtimePacketListener.processPacket(p);
            }
            return p;
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return null;
    }
}
