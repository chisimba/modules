/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.dnd;

import javax.swing.BorderFactory;
import javax.swing.JComponent;
import javax.swing.JLabel;
import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;

/**
 *
 * @author developer
 */
public class RDelTransferHandler extends StringTransferHandler {

    @Override
    protected void cleanup(JComponent c, boolean remove) {
    }

    @Override
    protected String exportString(JComponent c) {
        JLabel l = (JLabel) c;
        return l.getText();
    }

    @Override
    protected void importString(JComponent c, String str) {
        JLabel l = (JLabel) c;

        l.setBorder(BorderFactory.createLoweredBevelBorder());
        String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
        String filePath =GeneralUtil.readTextFile(resourceDir + "/" + str + "/server_path.txt").trim();
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.DELETE_ROOM_RESOURCE);
        StringBuilder sb = new StringBuilder();
        sb.append("<file-path>").append(filePath).append("</file-path>");
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);
    }
}
