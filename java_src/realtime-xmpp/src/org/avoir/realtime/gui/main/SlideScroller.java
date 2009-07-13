/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui.main;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Image;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;
import java.awt.geom.RoundRectangle2D;
import java.io.File;
import javax.swing.BorderFactory;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.common.Constants;
import org.avoir.realtime.common.util.GeneralUtil;
import org.avoir.realtime.common.util.ImageUtil;
import org.avoir.realtime.gui.WebpresentNavigator;
import org.avoir.realtime.gui.whiteboard.items.Img;
import org.avoir.realtime.net.ConnectionManager;
import org.avoir.realtime.net.packets.RealtimePacket;

/**
 *
 * @author david
 */
public class SlideScroller extends JPanel implements MouseListener, MouseMotionListener {

    private RoundRectangle2D[] thumbNails;
    private int startIndex;
    private int endIndex;
    private Image thumbs[];
    private int selectedIndex = 0;
    private JButton nextButton = new JButton();
    private JButton backButton = new JButton();
    int slideCount = 0;

    public int getEndIndex() {
        return endIndex;
    }

    public void setEndIndex(int endIndex) {
        this.endIndex = endIndex;
    }

    public int getStartIndex() {
        return startIndex;
    }

    public void setStartIndex(int startIndex) {
        this.startIndex = startIndex;
    }

    public void mouseClicked(MouseEvent evt) {
    }

    public void mouseEntered(MouseEvent evt) {
    }

    public void mouseExited(MouseEvent evt) {
    }

    public void mousePressed(MouseEvent evt) {
        String presentationName = WebpresentNavigator.selectedPresentation.replaceAll("\n", "");
        sendPacket(presentationName, "Slide " + (selectedIndex + 1));
    }

    public void mouseReleased(MouseEvent evt) {
    }

    public void mouseDragged(MouseEvent evt) {
    }

    private void sendPacket(String presentationName, String slideName) {
        RealtimePacket p = new RealtimePacket();
        p.setMode(RealtimePacket.Mode.BROADCAST_SLIDE);
        StringBuilder sb = new StringBuilder();
        sb.append("<slide-show-name>").append(presentationName).append("</slide-show-name>");
        sb.append("<slide-title>").append(slideName).append("</slide-title>");
        sb.append("<room-name>").append(ConnectionManager.getRoomName()).append("</room-name>");
        p.setContent(sb.toString());
        ConnectionManager.sendPacket(p);
    }

    public void mouseMoved(MouseEvent evt) {
        if (thumbNails != null) {
            for (int i = 0; i < thumbNails.length; i++) {
                if (thumbNails[i] != null) {
                    if (thumbNails[i].contains(evt.getPoint())) {
                        selectedIndex = i;
                    }
                }
            }
        }
        repaint();
    }

    public void resetSlideCount() {
        slideCount = 0;
        String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
        String files[] = new File(resourceDir + "/" + WebpresentNavigator.selectedPresentation.replaceAll("\n", "")).list();
        if (files != null) {
            slideCount = 0;
            for (int i = 0; i < files.length; i++) {
                
                if (!files[i].endsWith(".tr") && !files[i].endsWith(".txt")) {
                    slideCount++;
                  
                }
            }
        }
    }

    public void refresh() {

        String resourceDir = Constants.HOME + "/rooms/" + ChatRoomManager.currentRoomName;
        resetSlideCount();
        thumbs = null;
        thumbs = new Image[endIndex + 1];
        thumbNails = new RoundRectangle2D[endIndex + 1];
        int yValue = 50;
        for (int i = startIndex; i < endIndex; i++) {
            String path = resourceDir + "/" + WebpresentNavigator.selectedPresentation + "/Slide " + i;
            path = path.replaceAll("\n", "");
            thumbs[i] = GeneralUtil.getScaledImage(new ImageIcon(path).getImage(), 32, 32);
            RoundRectangle2D rect = new RoundRectangle2D.Double(8, yValue - 2, 34, 34, 5, 5);
            thumbNails[i] = rect;
            yValue += 80;
        }
        repaint();
    }

    public SlideScroller() {
        setBackground(Color.WHITE);
        setBorder(BorderFactory.createEtchedBorder());
        setPreferredSize(new Dimension(60, 60));
        addMouseListener(this);
        addMouseMotionListener(this);
        setLayout(new BorderLayout());
        nextButton.setIcon(ImageUtil.createImageIcon(this, "/images/1downarrow.png"));
        nextButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {
                
                if (endIndex < slideCount) {
                    startIndex++;
                    endIndex++;
                    refresh();
                }

            }
        });
        add(nextButton, BorderLayout.EAST);



        backButton.setIcon(ImageUtil.createImageIcon(this, "/images/1uparrow.png"));
        backButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {

                if (startIndex > 0) {
                    startIndex--;
                    endIndex--;
                    refresh();
                }

            }
        });
        add(backButton, BorderLayout.WEST);
    }

    @Override
    public void paintComponent(Graphics g) {
        Graphics2D g2 = (Graphics2D) g;
        if (thumbs != null) {
            int yValue = 50;
            g2.setColor(Color.WHITE);
            g2.fillRect(0, 0, 100, getHeight());
            for (int i = startIndex; i < endIndex; i++) {
                g2.drawImage(thumbs[i], 10, yValue, this);
                g2.setColor(Color.GREEN);
                if (thumbNails[i] != null) {
                    g2.draw(thumbNails[i]);
                    g2.setColor(Color.BLACK);
                    g2.drawString("Slide " + (i + 1), 10, yValue + 60);
                }
                yValue += 80;
            }
            g2.setColor(Color.RED);
            if (thumbNails != null) {
                g2.draw(thumbNails[selectedIndex]);
            }

        }
    }
}
