/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * WhiteboardPanel.java
 *
 * Created on 2009/03/26, 11:07:05
 */
package org.avoir.realtime.gui.whiteboard;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Component;
import java.awt.Dimension;
import java.util.ArrayList;
import javax.swing.DefaultListModel;
import javax.swing.ImageIcon;
import javax.swing.JLabel;
import javax.swing.JList;
import javax.swing.JScrollPane;
import javax.swing.JTabbedPane;
import javax.swing.JToggleButton;
import javax.swing.JToolBar;
import javax.swing.ListCellRenderer;
import org.avoir.realtime.common.RealtimeFile;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.gui.PointerListPanel;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;
import static org.avoir.realtime.common.Constants.Whiteboard.*;

/**
 *
 * @author developer
 */
public class WhiteboardPanel extends javax.swing.JPanel {

    private Whiteboard whiteboard;
    private DefaultListModel model = new DefaultListModel();
    private JList roomResourceSlides = new JList(model);
    private JTabbedPane slidesPanel = new JTabbedPane();

    /** Creates new form WhiteboardPanel */
    public WhiteboardPanel() {
        initComponents();
        whiteboard = new Whiteboard(this);
        add(whiteboard, BorderLayout.CENTER);

    }

    public void addSlideViewerNavigator() {
        roomResourceSlides.setLayoutOrientation(JList.HORIZONTAL_WRAP);
        roomResourceSlides.setCellRenderer(new RoomResourceRenderer());
        roomResourceSlides.setFixedCellHeight(21);
        roomResourceSlides.setPreferredSize(new Dimension(1000, 100));
        Color bg = new Color(244, 247, 203);
        roomResourceSlides.setBackground(bg);
        slidesPanel.addTab("Current Slides", new JScrollPane(roomResourceSlides));
    // slidesPanel.setBorder(BorderFactory.createTitledBorder("Slides"));
    // add(slidesPanel, BorderLayout.SOUTH);
    }

    public void setSlides(ArrayList<RealtimeFile> slides, String title) {
        model.clear();
        slidesPanel.setTitleAt(0, title);
        for (RealtimeFile slide : slides) {
            model.addElement(slide);
        }

    }

    public void setMoveButtonSelected(boolean select) {
        moveButton.setSelected(select);
    }

    public JToggleButton getMoveButton() {
        return moveButton;
    }

    public JToolBar getWbToolbar() {
        return wbToolbar;
    }

    public void setWhiteboardToolbarEnabled(boolean state) {
        /*for (int i = 0; i < wbToolbar.getComponentCount(); i++) {
        wbToolbar.getComponentAtIndex(i).setEnabled(state);
        }*/
    }

    public Whiteboard getWhiteboard() {
        return whiteboard;
    }

    class RoomResourceRenderer extends JLabel
            implements ListCellRenderer {

        private ImageIcon icoPlus = ImageUtil.createImageIcon(this, "/images/file.png");

        public RoomResourceRenderer() {

            setOpaque(true);
            setHorizontalAlignment(LEADING);
            setVerticalAlignment(CENTER);

        }

        /*
         * This method finds the image and text corresponding
         * to the selected value and returns the label, set up
         * to display the text and image.
         */
        public Component getListCellRendererComponent(
                JList list,
                Object value,
                int index,
                boolean isSelected,
                boolean cellHasFocus) {
            //Get the selected index. (The index param isn't
            //always valid, so just use the value.)
            //int selectedIndex = ((Integer) value).intValue();
            RealtimeFile file = (RealtimeFile) value;
            if (isSelected) {
                setBackground(list.getSelectionBackground());
                setForeground(list.getSelectionForeground());

                RealtimePacket p = new RealtimePacket();
                p.setMode(RealtimePacket.Mode.BROADCAST_SLIDE);
                StringBuilder sb = new StringBuilder();
                sb.append("<slide-show-name>").append(slidesPanel.getTitleAt(0)).append("</slide-show-name>");
                sb.append("<slide-title>").append(GeneralUtil.removeExt(file.getFileName())).append("</slide-title>");
                p.setContent(sb.toString());
                ConnectionManager.sendPacket(p);

            } else {
                setBackground(list.getBackground());
                setForeground(list.getForeground());
            }

            setIcon(icoPlus);

            setText(file.getFileName());
            setFont(list.getFont());

            return this;
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

        wbButtonGroup = new javax.swing.ButtonGroup();
        wbToolbar = new javax.swing.JToolBar();
        moveButton = new javax.swing.JToggleButton();
        lineButton = new javax.swing.JToggleButton();
        drawOvalButton = new javax.swing.JToggleButton();
        ovalFillButton = new javax.swing.JToggleButton();
        drawRectButton = new javax.swing.JToggleButton();
        rectFillButton = new javax.swing.JToggleButton();
        scribbleButton = new javax.swing.JToggleButton();
        eraseButton = new javax.swing.JToggleButton();
        textButton = new javax.swing.JToggleButton();
        undoButton = new javax.swing.JButton();

        wbToolbar.setRollover(true);

        wbButtonGroup.add(moveButton);
        moveButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/whiteboard/movearrow.gif"))); // NOI18N
        moveButton.setSelected(true);
        moveButton.setToolTipText("Move");
        moveButton.setFocusable(false);
        moveButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        moveButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        moveButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                moveButtonActionPerformed(evt);
            }
        });
        wbToolbar.add(moveButton);

        wbButtonGroup.add(lineButton);
        lineButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/whiteboard/draw_Line.gif"))); // NOI18N
        lineButton.setToolTipText("Line");
        lineButton.setFocusable(false);
        lineButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        lineButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        lineButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                lineButtonActionPerformed(evt);
            }
        });
        wbToolbar.add(lineButton);

        wbButtonGroup.add(drawOvalButton);
        drawOvalButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/whiteboard/stock_draw-circle-unfilled.png"))); // NOI18N
        drawOvalButton.setToolTipText("Draw Oval");
        drawOvalButton.setFocusable(false);
        drawOvalButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        drawOvalButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        drawOvalButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                drawOvalButtonActionPerformed(evt);
            }
        });
        wbToolbar.add(drawOvalButton);

        wbButtonGroup.add(ovalFillButton);
        ovalFillButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/whiteboard/draw_Oval.gif"))); // NOI18N
        ovalFillButton.setToolTipText("Fill Oval");
        ovalFillButton.setFocusable(false);
        ovalFillButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        ovalFillButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        ovalFillButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                ovalFillButtonActionPerformed(evt);
            }
        });
        wbToolbar.add(ovalFillButton);

        wbButtonGroup.add(drawRectButton);
        drawRectButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/whiteboard/stock_draw-rectangle-unfilled.png"))); // NOI18N
        drawRectButton.setToolTipText("Draw Rectangle");
        drawRectButton.setFocusable(false);
        drawRectButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        drawRectButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        drawRectButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                drawRectButtonActionPerformed(evt);
            }
        });
        wbToolbar.add(drawRectButton);

        wbButtonGroup.add(rectFillButton);
        rectFillButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/whiteboard/draw_Rectangle.gif"))); // NOI18N
        rectFillButton.setToolTipText("Fill Rectangle");
        rectFillButton.setFocusable(false);
        rectFillButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        rectFillButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        rectFillButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                rectFillButtonActionPerformed(evt);
            }
        });
        wbToolbar.add(rectFillButton);

        wbButtonGroup.add(scribbleButton);
        scribbleButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/whiteboard/draw_Scribble.gif"))); // NOI18N
        scribbleButton.setToolTipText("Scribble");
        scribbleButton.setFocusable(false);
        scribbleButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        scribbleButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        scribbleButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                scribbleButtonActionPerformed(evt);
            }
        });
        wbToolbar.add(scribbleButton);

        wbButtonGroup.add(eraseButton);
        eraseButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/whiteboard/erase_all_annotations.gif"))); // NOI18N
        eraseButton.setToolTipText("Erase selected Item");
        eraseButton.setFocusable(false);
        eraseButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        eraseButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        eraseButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                eraseButtonActionPerformed(evt);
            }
        });
        wbToolbar.add(eraseButton);

        wbButtonGroup.add(textButton);
        textButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/whiteboard/text.png"))); // NOI18N
        textButton.setToolTipText("Text");
        textButton.setFocusable(false);
        textButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        textButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        textButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                textButtonActionPerformed(evt);
            }
        });
        wbToolbar.add(textButton);

        undoButton.setIcon(new javax.swing.ImageIcon(getClass().getResource("/images/whiteboard/undo.png"))); // NOI18N
        undoButton.setToolTipText("Undo");
        undoButton.setFocusable(false);
        undoButton.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        undoButton.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        undoButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                undoButtonActionPerformed(evt);
            }
        });
        wbToolbar.add(undoButton);

        setLayout(new java.awt.BorderLayout());
    }// </editor-fold>//GEN-END:initComponents

    private void moveButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_moveButtonActionPerformed
        whiteboard.setCurrentPointer(PointerListPanel.NO_POINTER);
        whiteboard.setItemType(MOVE);
    }//GEN-LAST:event_moveButtonActionPerformed

    private void lineButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_lineButtonActionPerformed
        whiteboard.setCurrentPointer(PointerListPanel.NO_POINTER);
        whiteboard.setItemType(LINE);
    }//GEN-LAST:event_lineButtonActionPerformed

    private void ovalFillButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_ovalFillButtonActionPerformed
        whiteboard.setCurrentPointer(PointerListPanel.NO_POINTER);
        whiteboard.setItemType(FILL_OVAL);
}//GEN-LAST:event_ovalFillButtonActionPerformed

    private void rectFillButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_rectFillButtonActionPerformed
        whiteboard.setCurrentPointer(PointerListPanel.NO_POINTER);
        whiteboard.setItemType(FILL_RECT);
}//GEN-LAST:event_rectFillButtonActionPerformed

    private void scribbleButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_scribbleButtonActionPerformed
        whiteboard.setCurrentPointer(PointerListPanel.NO_POINTER);
        whiteboard.setItemType(PEN);
    }//GEN-LAST:event_scribbleButtonActionPerformed

    private void eraseButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_eraseButtonActionPerformed
        whiteboard.setCurrentPointer(PointerListPanel.NO_POINTER);
        whiteboard.setItemType(ERASE);
    }//GEN-LAST:event_eraseButtonActionPerformed

    private void textButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_textButtonActionPerformed
        whiteboard.setCurrentPointer(PointerListPanel.NO_POINTER);
        whiteboard.setItemType(TEXT);
    }//GEN-LAST:event_textButtonActionPerformed

    private void drawOvalButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_drawOvalButtonActionPerformed
        whiteboard.setCurrentPointer(PointerListPanel.NO_POINTER);
        whiteboard.setItemType(DRAW_OVAL);
    }//GEN-LAST:event_drawOvalButtonActionPerformed

    private void drawRectButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_drawRectButtonActionPerformed
        whiteboard.setCurrentPointer(PointerListPanel.NO_POINTER);
        whiteboard.setItemType(DRAW_RECT);
    }//GEN-LAST:event_drawRectButtonActionPerformed

    private void undoButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_undoButtonActionPerformed
       whiteboard.undo();
    }//GEN-LAST:event_undoButtonActionPerformed

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JToggleButton drawOvalButton;
    private javax.swing.JToggleButton drawRectButton;
    private javax.swing.JToggleButton eraseButton;
    private javax.swing.JToggleButton lineButton;
    private javax.swing.JToggleButton moveButton;
    private javax.swing.JToggleButton ovalFillButton;
    private javax.swing.JToggleButton rectFillButton;
    private javax.swing.JToggleButton scribbleButton;
    private javax.swing.JToggleButton textButton;
    private javax.swing.JButton undoButton;
    private javax.swing.ButtonGroup wbButtonGroup;
    private javax.swing.JToolBar wbToolbar;
    // End of variables declaration//GEN-END:variables
}
