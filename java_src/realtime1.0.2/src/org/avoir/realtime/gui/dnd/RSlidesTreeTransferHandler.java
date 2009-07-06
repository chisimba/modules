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
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;

/**
 *
 * @author developer
 */
public class RSlidesTreeTransferHandler extends StringTransferHandler {

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
            return Constants.ADD_ROOM_RESOURCE_CMD + file.getFileName() + ";" + file.getFilePath();
        }
        return "not suported";
    }

    @Override
    protected void importString(JComponent c, String str) {
        String cmd = Constants.REMOVE_ROOM_RESOURCE_CMD;
        if (str.startsWith(cmd)) {
            str = str.substring(str.indexOf(cmd) + cmd.length());
            String localFilePath = str;
            //String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
            String serverFilePath = GeneralUtil.readTextFile(localFilePath + "/server_path.txt").trim();
            RealtimePacket p = new RealtimePacket();
            p.setMode(RealtimePacket.Mode.DELETE_ROOM_RESOURCE);
            StringBuilder sb = new StringBuilder();
            sb.append("<server-file-path>").append(serverFilePath).append("</server-file-path>");
            sb.append("<local-file-path>").append(localFilePath).append("</local-file-path>");
            p.setContent(sb.toString());
            ConnectionManager.sendPacket(p);

        }
    /*
    JTree tree = (JTree) c;
    TreePath parentPath = tree.getSelectionPath();
    DefaultMutableTreeNode selectedNode = (DefaultMutableTreeNode) (parentPath.getLastPathComponent());
    Object obj = selectedNode.getUserObject();
    JOptionPane.showMessageDialog(null, obj);
    if (obj instanceof RealtimeFile) {

    RealtimeFile file = (RealtimeFile) obj;
    RealtimePacket p = new RealtimePacket();
    p.setMode(RealtimePacket.Mode.DELETE_ROOM_RESOURCE);
    StringBuilder sb = new StringBuilder();
    sb.append("<file-path>").append(file.getFilePath()).append("</file-path>");
    p.setContent(sb.toString());
    ConnectionManager.sendPacket(p);


    }*/
    }
}
