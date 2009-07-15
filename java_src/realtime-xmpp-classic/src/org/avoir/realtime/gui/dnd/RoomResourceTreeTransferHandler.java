/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.dnd;

import javax.swing.JComponent;
import javax.swing.JTree;
import javax.swing.tree.DefaultMutableTreeNode;
import javax.swing.tree.TreePath;
import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.RealtimeFile;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;

/**
 *
 * @author developer
 */
public class RoomResourceTreeTransferHandler extends StringTransferHandler {

    @Override
    protected void cleanup(JComponent c, boolean remove) {
    }

    @Override
    protected String exportString(JComponent c) {
        JTree tree = (JTree) c;
        TreePath parentPath = tree.getSelectionPath();
        DefaultMutableTreeNode selectedNode = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());
        Object obj = selectedNode.getUserObject();
        if (obj instanceof RealtimeFile) {

            RealtimeFile file = (RealtimeFile) obj;
            /*           RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.DELETE_ROOM_RESOURCE);
            StringBuilder sb = new StringBuilder();
            sb.append("<file-path>").append(file.getFilePath()).append("</file-path>");
            p.setContent(sb.toString());
            ConnectionManager.sendPacket(p);
             */
            return Constants.REMOVE_ROOM_RESOURCE_CMD + file.getFilePath();
        }
        return "No allowed";
    }

    @Override
    protected void importString(JComponent c, String str) {
        if (str.startsWith(Constants.ADD_ROOM_RESOURCE_CMD)) {
            str = str.substring(str.indexOf(Constants.ADD_ROOM_RESOURCE_CMD) + Constants.ADD_ROOM_RESOURCE_CMD.length());
            String[] cs = str.split(";");
            if (cs != null) {
                if (cs.length > 1) {
                    String filePath = cs[1];
                    RealtimePacket p = new RealtimePacket();
                    p.setMode(RealtimePacket.Mode.ADD_SLIDE_SHOW_CLASS_RESOURCE);
                    StringBuilder sb = new StringBuilder();
                    sb.append("<file-name>").append(str).append("</file-name>");
                    sb.append("<room-name>").append(ChatRoomManager.currentRoomName).append("</room-name>");
                    sb.append("<file-path>").append(filePath).append("</file-path>");
                    p.setContent(sb.toString());
                    ConnectionManager.sendPacket(p);
                }
            }
        }
    }
}
