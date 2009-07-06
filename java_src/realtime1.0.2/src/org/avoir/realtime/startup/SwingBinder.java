/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.startup;

import java.awt.BorderLayout;
import java.awt.Dimension;
import java.awt.Toolkit;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.io.File;
import javax.swing.JFileChooser;
import javax.swing.JFrame;

import org.avoir.realtime.common.RealtimeFxInt;
import org.avoir.realtime.common.filetransfer.FileManager;
import org.avoir.realtime.gui.PointerListPanel;
import org.avoir.realtime.gui.room.InviteParticipants;
import org.avoir.realtime.gui.room.RoomListFrame;
import org.avoir.realtime.gui.room.RoomMemberListFrame;
import org.avoir.realtime.gui.whiteboard.Whiteboard;

/**
 * This class is used to invke the GUI compoents of the application that just
 * best in swing or havent yet been implemented in javafx
 * @author david
 */
public class SwingBinder {

    private static RoomListFrame roomListFrame;
    private static RoomMemberListFrame roomMemberListFrame;
    private JFileChooser presentationFC = new JFileChooser();
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private RealtimeFxInt realtimeFxInt;

    public SwingBinder(RealtimeFxInt realtimeFxInt) {
        this.realtimeFxInt = realtimeFxInt;
    }

    /**
     * gets the roommemberlistframe object
     * @return RoomMemberListFrame
     */
    public static RoomMemberListFrame getRoomMemberListFrame() {
        return roomMemberListFrame;
    }

    /**
     * Get rhe roonlist object
     * @return
     */
    public static RoomListFrame getRoomListFrame() {
        return roomListFrame;
    }

    /**
     * creates room list from, if null, else it sets it visible
     * @return
     */
    public void showRoomList(InviteParticipants inviteParticipants) {
        if (roomListFrame == null) {
            roomListFrame = new RoomListFrame();

        }
        roomListFrame.setInviteParticipants(inviteParticipants);
        roomListFrame.setSize((ss.width / 4) * 3, (ss.height / 4) * 3);
        roomListFrame.setLocationRelativeTo(null);
        roomListFrame.requestRoomList();
        roomListFrame.setVisible(true);

    }

    /**
     * Create new room list for first time, else sets it visible
     */
    public void showRoomMemberList() {
        if (roomMemberListFrame == null) {
            roomMemberListFrame = new RoomMemberListFrame();

        }

        roomMemberListFrame.setSize((ss.width / 4) * 3, (ss.height / 4) * 3);
        roomMemberListFrame.setLocationRelativeTo(null);
        roomMemberListFrame.requestRoomMemberList();
        roomMemberListFrame.setVisible(true);

    }

    /**
     * Displays a file chooser for the user to insert a presentation
     */
    public void insertPresentation() {

        if (presentationFC.showOpenDialog(null) == JFileChooser.APPROVE_OPTION) {
            final File selectedFile = presentationFC.getSelectedFile();
            FileManager.transferFile(selectedFile.getAbsolutePath(), "slideshows", "jodconvert");

        }
    }

    public void showEditableWhiteboard(final Whiteboard whiteboard) {
        SwingBinderPanel swingBinderPanel=new SwingBinderPanel(whiteboard);
        final JFrame fr = new JFrame("Whiteboard");
        
        whiteboard.setDrawEnabled(true);
        whiteboard.setItemType(org.avoir.realtime.common.Constants.Whiteboard.PEN);
        fr.setLayout(new BorderLayout());
        fr.add(swingBinderPanel.getWbToolbar(),BorderLayout.NORTH);
        fr.add(whiteboard,BorderLayout.CENTER);
        fr.addWindowListener(new WindowAdapter() {

            @Override
            public void windowClosing(WindowEvent e) {
                fr.removeAll();
                whiteboard.setCurrentPointer(PointerListPanel.NO_POINTER);
                realtimeFxInt.addWhiteboardToScreenGraph();
            }
        });
        
        fr.setSize((ss.width/8)*5,(ss.height/8)*5);
        fr.setLocationRelativeTo(null);
        fr.setVisible(true);
    }
}
