/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * RoomToolsPanel.java
 *
 * Created on 2009/07/11, 5:09:05 PM
 */
package org.avoir.realtime.gui;

import java.awt.Component;
import java.awt.Dimension;
import java.awt.Toolkit;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;
import javax.swing.JButton;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.gui.main.GUIAccessManager;
import org.avoir.realtime.gui.room.InviteParticipants;
import org.avoir.realtime.gui.room.RoomListFrame;
import org.avoir.realtime.gui.room.RoomMemberListFrame;
import sun.java2d.loops.DrawGlyphListAA.General;

/**
 *
 * @author david
 */
public class RoomToolsPanel extends javax.swing.JPanel {

    private RoomListFrame roomListFrame;
    private RoomMemberListFrame roomMemberListFrame;
    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private PointerListPanel pointerListPanel;

    /** Creates new form RoomToolsPanel */
    public RoomToolsPanel() {
        initComponents();
    }

    public void setRoomListFrame(RoomListFrame roomListFrame) {
        this.roomListFrame = roomListFrame;
    }

    public RoomListFrame getRoomListFrame() {

        return roomListFrame;
    }

    public void setRoomMemberListFrame(RoomMemberListFrame roomMemberListFrame) {
        this.roomMemberListFrame = roomMemberListFrame;
    }

    public void showRoomMemberList() {
        if (roomMemberListFrame == null) {
            roomMemberListFrame = new RoomMemberListFrame(GUIAccessManager.mf, false);

        }

        roomMemberListFrame.setSize((ss.width / 4) * 3, (ss.height / 4) * 3);
        roomMemberListFrame.setLocationRelativeTo(null);
        roomMemberListFrame.requestRoomMemberList();
        roomMemberListFrame.setVisible(true);

    }

    public RoomMemberListFrame getRoomMemberListFrame() {
        return roomMemberListFrame;
    }

    public void showRoomList(InviteParticipants inviteParticipants) {
        if (roomListFrame == null) {
            roomListFrame = new RoomListFrame(GUIAccessManager.mf, false);
        }
        roomListFrame.setInviteParticipants(inviteParticipants);
        roomListFrame.setSize((ss.width / 4) * 3, (ss.height / 4) * 3);
        roomListFrame.setLocationRelativeTo(null);

        roomListFrame.requestRoomList();
        roomListFrame.setVisible(true);

    }

    public void setGUIAccess() {
        pointerButton.setEnabled(GeneralUtil.isMyRoom());
        desktopButton.setEnabled(GeneralUtil.isMyRoom());
        imagesButton.setEnabled(GeneralUtil.isMyRoom());
        slidesButton.setEnabled(GeneralUtil.isMyRoom());
        resourcesButton.setEnabled(GeneralUtil.isMyRoom());
        roomMembersButton.setEnabled(GeneralUtil.isMyRoom());
    }

    class RButton extends JButton implements MouseListener, MouseMotionListener {

        public void mouseClicked(MouseEvent e) {
        }

        public void mouseEntered(MouseEvent e) {
        }

        public void mouseExited(MouseEvent e) {
        }

        public void mousePressed(MouseEvent e) {
            for (Component c : RoomToolsPanel.this.getComponents()) {
                if (c instanceof JButton) {
                    JButton b = (JButton) c;
                    b.setBorderPainted(false);
                    b.setContentAreaFilled(false);
                }

            }
            setBorderPainted(true);
            setContentAreaFilled(true);
        }

        public void mouseReleased(MouseEvent e) {
        }

        public void mouseDragged(MouseEvent e) {
        }

        public void mouseMoved(MouseEvent e) {
        }

        public RButton() {
            addMouseListener(this);
            addMouseMotionListener(this);
            setBorderPainted(false);
            setContentAreaFilled(false);

        }
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        buttonGroup1 = new javax.swing.ButtonGroup();
        roomListButton = new RButton();
        pointerButton = new RButton();
        desktopButton = new RButton();
        imagesButton = new RButton();
        jButton5 = new RButton();
        slidesButton = new RButton();
        resourcesButton = new RButton();
        roomMembersButton = new RButton();

        setName("roomToolsPanel"); // NOI18N
        setLayout(new java.awt.GridLayout(4, 2, 0, 5));

        roomListButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/roomlist.png"))); // NOI18N
        roomListButton.setText("Room List");
        buttonGroup1.add(roomListButton);
        roomListButton.setHorizontalAlignment(javax.swing.SwingConstants.LEFT);
        roomListButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                roomListButtonActionPerformed(evt);
            }
        });
        add(roomListButton);

        pointerButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/arrow_side32.png"))); // NOI18N
        pointerButton.setText("Pointer");
        buttonGroup1.add(pointerButton);
        pointerButton.setHorizontalAlignment(javax.swing.SwingConstants.LEFT);
        pointerButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                pointerButtonActionPerformed(evt);
            }
        });
        add(pointerButton);

        desktopButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/desktopsharing.png"))); // NOI18N
        desktopButton.setText("Deskshare");
        buttonGroup1.add(desktopButton);
        desktopButton.setHorizontalAlignment(javax.swing.SwingConstants.LEFT);
        desktopButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                desktopButtonActionPerformed(evt);
            }
        });
        add(desktopButton);

        imagesButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/image.png"))); // NOI18N
        imagesButton.setText("Images");
        buttonGroup1.add(imagesButton);
        imagesButton.setHorizontalAlignment(javax.swing.SwingConstants.LEFT);
        imagesButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                imagesButtonActionPerformed(evt);
            }
        });
        add(imagesButton);

        jButton5.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/kedit32.png"))); // NOI18N
        jButton5.setText("Notepad");
        buttonGroup1.add(jButton5);
        jButton5.setHorizontalAlignment(javax.swing.SwingConstants.LEFT);
        add(jButton5);

        slidesButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/slidebuilder.png"))); // NOI18N
        slidesButton.setText("Slides");
        slidesButton.setHorizontalAlignment(javax.swing.SwingConstants.LEFT);
        slidesButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                slidesButtonActionPerformed(evt);
            }
        });
        add(slidesButton);

        resourcesButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/join_room.png"))); // NOI18N
        resourcesButton.setText("Resources");
        buttonGroup1.add(resourcesButton);
        resourcesButton.setHorizontalAlignment(javax.swing.SwingConstants.LEFT);
        add(resourcesButton);

        roomMembersButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/virtualroom.png"))); // NOI18N
        roomMembersButton.setText("Members");
        roomMembersButton.setHorizontalAlignment(javax.swing.SwingConstants.LEFT);
        roomMembersButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                roomMembersButtonActionPerformed(evt);
            }
        });
        add(roomMembersButton);
    }// </editor-fold>//GEN-END:initComponents

    private void roomListButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_roomListButtonActionPerformed
        showRoomList(null);
    }//GEN-LAST:event_roomListButtonActionPerformed

    private void roomMembersButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_roomMembersButtonActionPerformed
        showRoomMemberList();
}//GEN-LAST:event_roomMembersButtonActionPerformed

    private void pointerButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_pointerButtonActionPerformed
        if (pointerListPanel == null) {
            pointerListPanel = new PointerListPanel();
        }

        pointerListPanel.setSize(300, 150);
        pointerListPanel.setLocationRelativeTo(this);
        pointerListPanel.setVisible(true);
        GUIAccessManager.mf.getWhiteboardPanel().getWhiteboard().setCurrentPointer(pointerListPanel.getSelectedPointer());
}//GEN-LAST:event_pointerButtonActionPerformed

    private void desktopButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_desktopButtonActionPerformed
        GUIAccessManager.mf.initScreenShare();
    }//GEN-LAST:event_desktopButtonActionPerformed

    private void imagesButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_imagesButtonActionPerformed
        GUIAccessManager.mf.showImageFileChooser();
    }//GEN-LAST:event_imagesButtonActionPerformed

    private void slidesButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_slidesButtonActionPerformed
        GUIAccessManager.mf.insertPresentation();
    }//GEN-LAST:event_slidesButtonActionPerformed

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.ButtonGroup buttonGroup1;
    private javax.swing.JButton desktopButton;
    private javax.swing.JButton imagesButton;
    private javax.swing.JButton jButton5;
    private javax.swing.JButton pointerButton;
    private javax.swing.JButton resourcesButton;
    private javax.swing.JButton roomListButton;
    private javax.swing.JButton roomMembersButton;
    private javax.swing.JButton slidesButton;
    // End of variables declaration//GEN-END:variables
}
