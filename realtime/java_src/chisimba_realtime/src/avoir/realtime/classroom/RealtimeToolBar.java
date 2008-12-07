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
package avoir.realtime.classroom;

import avoir.realtime.classroom.notepad.JNotepad;
import avoir.realtime.classroom.notepad.Stylepad;
import avoir.realtime.common.ImageUtil;
import java.awt.BasicStroke;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Image;
import java.awt.Toolkit;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;
import java.awt.geom.RoundRectangle2D;
import java.util.ArrayList;
import javax.swing.ImageIcon;
import javax.swing.JFrame;
import javax.swing.JPanel;

/**
 *
 * @author developer
 */
public class RealtimeToolBar extends JPanel implements MouseListener, MouseMotionListener {

    private Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private BlurShape blur = new BlurShape(5);
    private ArrayList<RButton> buttons = new ArrayList<RButton>();
    private int MAX_ICON_WIDTH = 50;
    private int MAX_ICON_HEIGHT = 50;
    private int SPACING = 64;
    private int RECT_HEIGHT = 60;
    private RButton selectedButton = null;
    private RButton clickedButton = null;
    private int selectedIndex = -1;
    private int clickedIndex = -1;
    private int startx = 30;
    private int rectW = MAX_ICON_WIDTH + 6;
    private int rectH = MAX_ICON_HEIGHT + 6;
    private ClassroomMainFrame mf;
    private Image spkrFailIcon = ImageUtil.createImageIcon(this, "/icons/speaker_fail.png").getImage();
    private Image spkrIcon = ImageUtil.createImageIcon(this, "/icons/speaker.png").getImage();
    private Image micIcon = ImageUtil.createImageIcon(this, "/icons/micro.png").getImage();
    private Image micFailIcon = ImageUtil.createImageIcon(this, "/icons/mic_fail.png").getImage();
    private Image micSelectedIcon = ImageUtil.createImageIcon(this, "/icons/micro_selected.png").getImage();
    private Image speakerSelectedIcon = ImageUtil.createImageIcon(this, "/icons/speaker_selected.png").getImage();
    private boolean talking = false;

    public RealtimeToolBar(ClassroomMainFrame mf) {
        setPreferredSize(new Dimension(ss.width - 20, 80));
        setBackground(Color.WHITE);
        this.addMouseListener(this);
        this.addMouseMotionListener(this);
        this.mf = mf;
        repaint();
    }

    public void add(String imagePath, String actionCommand, String tooltipText) {
        ImageIcon image = ImageUtil.createImageIcon(this, imagePath);
        int w = image.getIconWidth();
        w = w > MAX_ICON_WIDTH ? MAX_ICON_WIDTH : w;
        int h = image.getIconHeight();
        h = h > MAX_ICON_HEIGHT ? MAX_ICON_HEIGHT : h;
        RoundRectangle2D roundRect = new RoundRectangle2D.Double(startx, 13, rectW, rectH, 10, 10);
        int xVal = startx + (((int) roundRect.getWidth() - w) / 2);
        int yVal = 10 + (RECT_HEIGHT - h) / 2;
        buttons.add(new RButton(image.getImage(), actionCommand, tooltipText,
                xVal, yVal, w, h, roundRect));
        startx += SPACING;
        repaint();
    }

    private void showNotepad() {
        JNotepad fr = new JNotepad();
        //fr.setAlwaysOnTop(true);
        fr.setSize(400, 300);
        fr.setLocationRelativeTo(null);
        fr.setVisible(true);
    }

    @Override
    protected void paintComponent(Graphics g) {
        super.paintComponent(g);
        Graphics2D g2 = (Graphics2D) g;
        RoundRectangle2D rect = new RoundRectangle2D.Double(10, 10, getWidth() - 20, 60, 20, 20);
        g2.setStroke(new BasicStroke(3f));
        g2.setColor(Color.GRAY);
        blur.draw(g2, rect);
        g2.setStroke(new BasicStroke(4f));
        g2.setColor(Color.GRAY);
        g2.drawRoundRect(6, 6, getWidth() - 12, 70, 20, 20);

        for (int i = 0; i < buttons.size(); i++) {
            Color color = Color.GRAY;

            if (selectedButton != null) {
                if (selectedIndex == i) {
                    paintSelected(g2, i);
                } else {
                    if (clickedButton != null) {
                        if (clickedIndex == i && clickedButton.isClicked()) {
                            paintSelected(g2, i);
                        } else {
                            if (talking && buttons.get(i).getActionCommand().equals("mic")) {
                                paintTalking(g2, i);
                            } else {

                                g2.setColor(color);
                            }
                            paintImage(g2, i);
                        }
                    } else {

                        if (talking && buttons.get(i).getActionCommand().equals("mic")) {
                            paintTalking(g2, i);
                        } else {
                            g2.setColor(color);
                        }
                        paintImage(g2, i);
                    }
                }
            } else if (clickedButton != null) {
                if (clickedIndex == i && clickedButton.isClicked()) {
                    paintSelected(g2, i);
                } else {

                    if (talking && buttons.get(i).getActionCommand().equals("mic")) {
                        paintTalking(g2, i);
                    } else {
                        g2.setColor(color);
                    }
                    paintImage(g2, i);
                }
            } else {
                if (talking && buttons.get(i).getActionCommand().equals("mic")) {
                    paintTalking(g2, i);
                } else {
                    g2.setColor(color);
                }
                paintImage(g2, i);
            }
            if (talking && buttons.get(i).getActionCommand().equals("mic")) {
                paintTalking(g2, i);
            }
        }

    }

    public void setTalking(boolean talking) {
        this.talking = talking;
        for (int i = 0; i < buttons.size(); i++) {
            if (buttons.get(i).getActionCommand().equals("mic")) {
                RButton button = buttons.get(i);
                if (talking) {
                    button.setImage(micSelectedIcon);
                    buttons.set(i, button);
                } else {
                    button.setImage(micIcon);
                    buttons.set(i, button);
                }
            }
        }
        repaint();
    }

    private void paintSelected(Graphics2D g2, int i) {
        g2.setColor(Color.LIGHT_GRAY);
        RoundRectangle2D r = buttons.get(i).getRect();
        g2.fill(r);
        Color color = new Color(255, 153, 51);
        g2.setColor(color);
        g2.draw(buttons.get(i).getRect());
        g2.drawImage(buttons.get(i).getImage(), buttons.get(i).getX() - 3, buttons.get(i).getY() - 3, buttons.get(i).getWidth() + 6, buttons.get(i).getHeight() + 6, this);
        paintImage(g2, i);
    }

    private void paintImage(Graphics2D g2, int i) {
        g2.draw(buttons.get(i).getRect());
        g2.drawImage(buttons.get(i).getImage(), buttons.get(i).getX(), buttons.get(i).getY(), buttons.get(i).getWidth(), buttons.get(i).getHeight(), this);

    }

    private void paintTalking(Graphics2D g2, int i) {
        g2.setColor(Color.LIGHT_GRAY);
        RoundRectangle2D r = buttons.get(i).getRect();
        g2.fill(r);
        g2.setColor(Color.GREEN);
        g2.setFont(new Font("Dialog", 1, 10));
        g2.drawString("Talking ...", buttons.get(i).getX(), buttons.get(i).getY() + 40);
        paintImage(g2, i);
    }

    public void setMicNotAvailable() {
        for (int i = 0; i < buttons.size(); i++) {
            if (buttons.get(i).getActionCommand().equals("mic")) {
                RButton button = buttons.get(i);
                button.setImage(micFailIcon);
                buttons.set(i, button);

            }
        }
        repaint();
    }

    public void setSpeakerNotAvailable() {
        for (int i = 0; i < buttons.size(); i++) {
            if (buttons.get(i).getActionCommand().equals("speaker")) {
                RButton button = buttons.get(i);
                button.setImage(spkrFailIcon);
                buttons.set(i, button);
            }
        }
        repaint();
    }

    public void mouseClicked(MouseEvent evt) {
        for (int i = 0; i < buttons.size(); i++) {

            if (buttons.get(i).getRect().contains(evt.getPoint())) {
                //unselect the prev button if any
                if (clickedIndex > -1 && clickedButton != null) {
                    clickedButton.setClicked(!clickedButton.isClicked());
                    buttons.set(clickedIndex, clickedButton);
                }
                clickedIndex = i;
                clickedButton = buttons.get(i);
                clickedButton.setClicked(!clickedButton.isClicked());
                if (buttons.get(i).getActionCommand().equals("mic")) {
                    mf.getAudioChatClient().connect();
                    mf.getAudioChatClient().signIn(mf.getUser().getUserName());
                    mf.getAudioChatClient().talk();

                }
                if (buttons.get(i).getActionCommand().equals("notepad")) {
                    showNotepad();
                }
            }
        }
        repaint();
    }

    public void mouseEntered(MouseEvent evt) {
    }

    public void mouseExited(MouseEvent evt) {
        selectedButton = null;
        if (clickedButton == null) {
            selectedIndex = -1;
        }
        repaint();

    }

    public void mousePressed(MouseEvent evt) {
    }

    public void mouseReleased(MouseEvent evt) {
    }

    public void mouseDragged(MouseEvent evt) {
    }

    public void mouseMoved(MouseEvent evt) {
        for (int i = 0; i < buttons.size(); i++) {
            if (buttons.get(i).getRect().contains(evt.getPoint())) {
                selectedIndex = i;
                selectedButton = buttons.get(i);
            }
        }
        repaint();
    }

    private class RButton {

        private Image image;
        private String actionCommand;
        private String tooltiptext;
        private int width,  height,  x,  y;
        private RoundRectangle2D rect;
        private boolean clicked = false;

        public RButton(
                Image image,
                String actionCommand,
                String tooltiptext,
                int x,
                int y,
                int width,
                int height,
                RoundRectangle2D rect) {
            this.image = image;
            this.actionCommand = actionCommand;
            this.tooltiptext = tooltiptext;
            this.width = width;
            this.height = height;
            this.rect = rect;
            this.x = x;
            this.y = y;
        }

        public boolean isClicked() {
            return clicked;
        }

        public void setClicked(boolean clicked) {
            this.clicked = clicked;
        }

        public RoundRectangle2D getRect() {
            return rect;
        }

        public void setRect(RoundRectangle2D rect) {
            this.rect = rect;
        }

        public int getX() {
            return x;
        }

        public void setX(int x) {
            this.x = x;
        }

        public int getY() {
            return y;
        }

        public void setY(int y) {
            this.y = y;
        }

        public String getActionCommand() {
            return actionCommand;
        }

        public void setActionCommand(String actionCommand) {
            this.actionCommand = actionCommand;
        }

        public String getTooltiptext() {
            return tooltiptext;
        }

        public void setTooltiptext(String tooltiptext) {
            this.tooltiptext = tooltiptext;
        }

        public int getHeight() {
            return height;
        }

        public void setHeight(int height) {
            this.height = height;
        }

        public int getWidth() {
            return width;
        }

        public void setWidth(int width) {
            this.width = width;
        }

        public Image getImage() {
            return image;
        }

        public void setImage(Image image) {
            this.image = image;
        }
    }
}
