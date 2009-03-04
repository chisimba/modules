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

import avoir.realtime.common.packet.PresencePacket;
import avoir.realtime.common.ImageUtil;
import avoir.realtime.common.PresenceConstants;
import avoir.realtime.classroom.RButton;
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
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JPopupMenu;

/**
 *
 * @author developer
 */
public class RealtimeToolBar extends JPanel implements MouseListener, MouseMotionListener {

    private BlurShape blur = new BlurShape(5);
    protected ArrayList<RButton> buttons = new ArrayList<RButton>();
    private ArrayList<RButton> subButtons = new ArrayList<RButton>();
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
    private ImageIcon arrowSideIcon = ImageUtil.createImageIcon(this, "/icons/arrow_side.png");
    private ImageIcon arrowUpIcon = ImageUtil.createImageIcon(this, "/icons/arrow_up.png");
    private ImageIcon handLeftIcon = ImageUtil.createImageIcon(this, "/icons/hand_left.png");
    private ImageIcon handRightIcon = ImageUtil.createImageIcon(this, "/icons/hand_right.png");
    protected Object[][] arrows = {{arrowSideIcon, "arrowSide"}, {arrowUpIcon, "arrowUp"}, {handLeftIcon, "handLeft"},
        {handRightIcon, "handRight"}
    };
    protected boolean firsTimeArrowShow = true;
    private boolean talking = false;
    private Color ocolor = new Color(255, 204, 102);//new Color(255, 153, 51);
    protected JPopupMenu tooltip = new JPopupMenu();
    protected JLabel tooltipField = new JLabel();
    protected Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
    private boolean savednotes = false;
    protected boolean recording = false;
    protected ToolbarActionManager toolbarManager;

    public RealtimeToolBar(ClassroomMainFrame mf) {
        setPreferredSize(new Dimension(ss.width - 20, 80));
        setBackground(Color.WHITE);
        this.addMouseListener(this);
        this.addMouseMotionListener(this);
        this.mf = mf;
        tooltip.add(tooltipField);
        repaint();
        toolbarManager = new ToolbarActionManager(mf);
    }

    public void add(String imagePath, String actionCommand, String tooltipText, boolean bg, boolean isToggle) {
        ImageIcon image = ImageUtil.createImageIcon(this, imagePath);
        int w = image.getIconWidth();
        w = w > MAX_ICON_WIDTH ? MAX_ICON_WIDTH : w;
        int h = image.getIconHeight();
        h = h > MAX_ICON_HEIGHT ? MAX_ICON_HEIGHT : h;
        RoundRectangle2D roundRect = new RoundRectangle2D.Double(startx, 13, rectW, rectH, 10, 10);
        int xVal = startx + (((int) roundRect.getWidth() - w) / 2);
        int yVal = 10 + (RECT_HEIGHT - h) / 2;
        RButton rb = new RButton(image.getImage(), actionCommand, tooltipText,
                xVal, yVal, w, h, roundRect, "parent");
        rb.setAButtonGroup(bg);
        rb.setToggleButton(isToggle);
        buttons.add(rb);
        startx += SPACING;
        repaint();
    }

    public ToolbarActionManager getToolbarManager() {
        return toolbarManager;
    }

    public void showRecorderFrame() {
    }

    public void add(String imagePath, String actionCommand, String tooltipText) {
        add(imagePath, actionCommand, tooltipText, true, false);
    }

    public void addSubButton(String imagePath, String actionCommand, String tooltipText, String parent) {
        ImageIcon image = ImageUtil.createImageIcon(this, imagePath);

        for (int i = 0; i < buttons.size(); i++) {
            RButton b = buttons.get(i);
            if (b.getActionCommand().equals(parent)) {
                RoundRectangle2D rect = b.getRect();
                RoundRectangle2D srect = new RoundRectangle2D.Double(rect.getX() + 10, rect.getY() + rect.getHeight() - 8,
                        16, 16, 1, 1);
                buttons.add(new RButton(image.getImage(), actionCommand, tooltipText,
                        (int) srect.getX(), (int) srect.getY(), (int) srect.getWidth(), (int) srect.getHeight(), srect, "child"));
                savednotes = true;
            }
        }
        repaint();
    }

    public void showQuestionsManager() {
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
            Color color = ocolor;// Color.GRAY;

            if (selectedButton != null) {
                if (selectedIndex == i) {
                    paintSelected(g2, i, buttons.get(i).getType());
                } else {
                    if (clickedButton != null) {
                        if (clickedIndex == i && clickedButton.isClicked()) {
                            paintSelected(g2, i, buttons.get(i).getType());
                        } else {
                            if (talking && buttons.get(i).getActionCommand().equals("mic")) {
                                paintTalking(g2, i, buttons.get(i).getType());
                            } else if (recording && buttons.get(i).getActionCommand().equals("record")) {
                                paintRecording(g2, i, buttons.get(i).getType());
                            } else {

                                g2.setColor(color);
                            }
                            paintImage(g2, i, buttons.get(i).getType());
                        }
                    } else {

                        if (talking && buttons.get(i).getActionCommand().equals("mic")) {
                            paintTalking(g2, i, buttons.get(i).getType());
                        } else if (recording && buttons.get(i).getActionCommand().equals("record")) {
                            paintRecording(g2, i, buttons.get(i).getType());
                        } else {
                            g2.setColor(color);
                        }
                        paintImage(g2, i, buttons.get(i).getType());
                    }
                }
            } else if (clickedButton != null) {
                if (clickedIndex == i && clickedButton.isClicked()) {
                    paintSelected(g2, i, buttons.get(i).getType());
                } else {

                    if (talking && buttons.get(i).getActionCommand().equals("mic")) {
                        paintTalking(g2, i, buttons.get(i).getType());
                    } else if (recording && buttons.get(i).getActionCommand().equals("record")) {
                        paintRecording(g2, i, buttons.get(i).getType());
                    } else {
                        g2.setColor(color);
                    }
                    paintImage(g2, i, buttons.get(i).getType());
                }
            } else {
                if (talking && buttons.get(i).getActionCommand().equals("mic")) {
                    paintTalking(g2, i, buttons.get(i).getType());
                } else if (recording && buttons.get(i).getActionCommand().equals("record")) {
                    paintRecording(g2, i, buttons.get(i).getType());
                } else {
                    g2.setColor(color);
                }
                paintImage(g2, i, buttons.get(i).getType());
            }
            if (talking && buttons.get(i).getActionCommand().equals("mic")) {
                paintTalking(g2, i, buttons.get(i).getType());
            }
            if (recording && buttons.get(i).getActionCommand().equals("record")) {
                paintRecording(g2, i, buttons.get(i).getType());
            }

        }

    }

    private void showConfigFrame() {
        RealtimeOptionsFrame fr = new RealtimeOptionsFrame(mf, 0);
        fr.setSize(600, 400);
        fr.setLocationRelativeTo(null);
        fr.setVisible(true);
    }

    public void setTalking(boolean talking) {
        this.talking = talking;
        mf.getTcpConnector().sendPacket(new PresencePacket(
                mf.getUser().getSessionId(), PresenceConstants.SOUND_ICON, talking ? PresenceConstants.SPEAKING : PresenceConstants.NOT_SPEAKING, mf.getUser().getUserName()));

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

    private void paintSelected(Graphics2D g2, int i, String type) {
        g2.setStroke(new BasicStroke(4));
        g2.setColor(Color.LIGHT_GRAY);
        RoundRectangle2D r = buttons.get(i).getRect();
        g2.fill(r);
        Color color = new Color(255, 153, 51);
        g2.setColor(color);
        g2.draw(buttons.get(i).getRect());
        g2.drawImage(buttons.get(i).getImage(), buttons.get(i).getX() - 3, buttons.get(i).getY() - 3, buttons.get(i).getWidth() + 6, buttons.get(i).getHeight() + 6, this);
        paintImage(g2, i, type);
    }

    protected void paintImage(Graphics2D g2, int i, String type) {
        g2.setStroke(new BasicStroke(3));

        Color color = new Color(255, 153, 51);
        if (type.equals("child")) {
            color = new Color(0, 131, 0);
        }
        String actionCommand = buttons.get(i).getActionCommand();
        if (actionCommand.equals("mic") && talking) {

            color = Color.GREEN;

        }
        if (actionCommand.equals("record") && recording) {

            color = Color.GREEN;

        }
        g2.setColor(color);
        if (buttons.get(i).isClicked()) {
            g2.setStroke(new BasicStroke(4));
            g2.setColor(Color.LIGHT_GRAY);
            RoundRectangle2D r = buttons.get(i).getRect();
            g2.fill(r);

            g2.setColor(new Color(255, 153, 51));
            g2.draw(buttons.get(i).getRect());
        }
        //g2.draw(buttons.get(i).getRect());
        g2.drawImage(buttons.get(i).getImage(), buttons.get(i).getX(), buttons.get(i).getY(), buttons.get(i).getWidth(), buttons.get(i).getHeight(), this);

    }

    private void paintTalking(Graphics2D g2, int i, String type) {
        g2.setColor(Color.LIGHT_GRAY);
        RoundRectangle2D r = buttons.get(i).getRect();
        g2.fill(r);
        g2.setColor(Color.GREEN);
        g2.draw(buttons.get(i).getRect());

        g2.setFont(new Font("Dialog", 1, 10));
        g2.drawString("Talking ...", buttons.get(i).getX(), buttons.get(i).getY() + 40);
        paintImage(g2, i, type);
    }

    private void paintRecording(Graphics2D g2, int i, String type) {
        g2.setColor(Color.LIGHT_GRAY);
        RoundRectangle2D r = buttons.get(i).getRect();
        g2.fill(r);
        g2.setColor(Color.GREEN);
        g2.draw(buttons.get(i).getRect());
        g2.setFont(new Font("Dialog", 1, 10));
        g2.drawString("Recording ...", buttons.get(i).getX() - 10, buttons.get(i).getY() + 40);
        paintImage(g2, i, type);
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

    private void showPlayer() {
     
    }

    protected void showPointerOptions(MouseEvent evt) {
    }

    protected void startDesktopshare() {
    }

    protected void showWebpage() {
    }

    protected void setBold() {
    }

    protected void setUnder() {
    }

    protected void setItalic() {
    }

    public void mouseClicked(MouseEvent evt) {
        for (int i = 0; i < buttons.size(); i++) {

            if (buttons.get(i).getRect().contains(evt.getPoint())) {
                //unselect the prev button if any...and if is not a button group
                if (clickedIndex > -1 && clickedButton != null) {
                    if (clickedButton.isAButtonGroup() && clickedButton.isToggleButton()) {
                        clickedButton.setClicked(!clickedButton.isClicked());
                        buttons.set(clickedIndex, clickedButton);
                    }
                }
                clickedIndex = i;
                clickedButton = buttons.get(i);
                if (clickedButton.isToggleButton()) {
                    clickedButton.setClicked(!clickedButton.isClicked());
                } else {
                    clickedButton.setClicked(false);
                }
                if (buttons.get(i).getActionCommand().equals("pointer")) {
                    showPointerOptions(evt);
                }
                if (buttons.get(i).getActionCommand().equals("mic")) {
                    mf.getAudioChatClient().talk();
                }
                if (buttons.get(i).getActionCommand().equals("italic")) {
                    setItalic();
                }
                if (buttons.get(i).getActionCommand().equals("under")) {
                    setUnder();
                }
                if (buttons.get(i).getActionCommand().equals("bold")) {
                    setBold();
                }
                if (buttons.get(i).getActionCommand().equals("play")) {
                    showPlayer();
                }
                if (buttons.get(i).getActionCommand().equals("record")) {
                    showRecorderFrame();
                }
                if (buttons.get(i).getActionCommand().equals("config")) {
                    showConfigFrame();
                }
                if (buttons.get(i).getActionCommand().equals("desktopshare")) {

                    startDesktopshare();
                }
                if (buttons.get(i).getActionCommand().equals("webpage")) {
                    showWebpage();
                }
                if (buttons.get(i).getActionCommand().equals("notepad")) {
                    if (savednotes) {
                        toolbarManager.showNotepadList();
                    } else {
                        toolbarManager.showNotepad();
                    }
                }
                if (buttons.get(i).getActionCommand().equals("question")) {
                    showQuestionsManager();
                }
            }
        }
        repaint();
    }

    public void setSavednotes(boolean savednotes) {
        this.savednotes = savednotes;
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
}
