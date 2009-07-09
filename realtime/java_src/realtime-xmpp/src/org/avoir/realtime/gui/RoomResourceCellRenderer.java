/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui;

import java.awt.Component;
import java.io.File;
import java.util.ArrayList;
import javax.swing.ImageIcon;
import javax.swing.JLabel;
import javax.swing.JList;
import javax.swing.ListCellRenderer;
import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.RealtimeFile;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.gui.main.GUIAccessManager;

public class RoomResourceCellRenderer extends JLabel
        implements ListCellRenderer {

    private Navigator nav;
    private ImageIcon icoPlus = ImageUtil.createImageIcon(this, "/images/ico_plus.png");
    private String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;

    public RoomResourceCellRenderer(Navigator nav) {
        this.nav = nav;
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
        RealtimeFile file = null;
        try {
            file = nav.getRoomResources().get(index);
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        if (isSelected) {
            setBackground(list.getSelectionBackground());
            setForeground(list.getSelectionForeground());
            String path = resourceDir + "/" + value;
            String[] slides = new File(path).list();

            ArrayList<RealtimeFile> lts = new ArrayList<RealtimeFile>();
            for (int j = 0; j < slides.length; j++) {

                if (slides[j].endsWith(".tr")) {
                    try {
                        RealtimeFile f = new RealtimeFile(slides[j], null, false, false);
                        f.setSlide(true);
                        lts.add(f);

                    } catch (Exception ex) {
                        ex.printStackTrace();
                    }
                }
            }
            GUIAccessManager.mf.getWhiteboardPanel().setSlides(lts, value + "");
            GUIAccessManager.mf.getWhiteboardPanel().repaint();
        } else {
            setBackground(list.getBackground());
            setForeground(list.getForeground());
        }

        setIcon(icoPlus);
        if (file != null) {
            setText(file.getFileName());
        }
        setFont(list.getFont());

        return this;
    }
}
