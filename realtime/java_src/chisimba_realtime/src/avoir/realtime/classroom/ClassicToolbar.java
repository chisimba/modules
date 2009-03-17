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
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.Graphics2D;
import java.awt.Image;
import java.awt.RenderingHints;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.awt.image.BufferedImage;
import javax.swing.ButtonGroup;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JPopupMenu;
import javax.swing.JToggleButton;
import javax.swing.JToolBar;

/**
 *
 * @author developer
 */
public class ClassicToolbar extends JToolBar implements ActionListener {

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
    protected ButtonGroup buttonGroup = new ButtonGroup();

    public ClassicToolbar(ClassroomMainFrame mf) {
        this.mf = mf;
        toolbarManager = new ToolbarActionManager(mf);
    }

    public Image getScaledImage(Image srcImg) {
        int w = 16;
        int h = 16;

        BufferedImage resizedImg = new BufferedImage(w, h, BufferedImage.TYPE_INT_RGB);
        Graphics2D g2 = resizedImg.createGraphics();
        g2.setRenderingHint(RenderingHints.KEY_INTERPOLATION, RenderingHints.VALUE_INTERPOLATION_BILINEAR);
        g2.drawImage(srcImg, 0, 0, w, h, null);
        g2.dispose();
        return resizedImg;
    }

    public void add(String text, String imagePath, String actionCommand, String tooltipText, boolean bg, boolean isToggle) {
        if (isToggle) {
            add(text, imagePath, actionCommand, tooltipText, bg);

        } else {
            add(text, imagePath, actionCommand, tooltipText);

        }
    }

    public void add(String text, String imagePath, String actionCommand, String tooltip) {
        ImageIcon image = ImageUtil.createImageIcon(this, imagePath);
        final JButton b = new JButton();
        b.setFont(new Font("dialog", 0, 11));
        b.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        b.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        b.setText(text);
        b.addActionListener(this);
        b.setBorderPainted(false);
        b.setContentAreaFilled(false);
        b.addMouseListener(new MouseAdapter() {

            public void mouseEntered(MouseEvent e) {
                b.setBorderPainted(true);

            }

            public void mouseExited(MouseEvent e) {
                b.setBorderPainted(false);
            }
        });
        b.setIcon(image);
        b.setActionCommand(actionCommand);
        b.setToolTipText(tooltip);
        add(b);
    }

    public void add(String text, String imagePath, String actionCommand, String tooltip, boolean bg) {
        ImageIcon image = ImageUtil.createImageIcon(this, imagePath);
        final JToggleButton b = new JToggleButton();
        b.addActionListener(this);
        b.setHorizontalTextPosition(javax.swing.SwingConstants.CENTER);
        b.setVerticalTextPosition(javax.swing.SwingConstants.BOTTOM);
        b.setText(text);
        b.setFont(new Font("dialog", 0, 11));
//        b.setMargin(new Insets(1, 1, 1, 1));

        b.setBorderPainted(false);
        b.setContentAreaFilled(false);
        b.addMouseListener(new MouseAdapter() {

            public void mouseEntered(MouseEvent e) {
                b.setBorderPainted(true);
                b.setContentAreaFilled(true);

            }

            public void mouseExited(MouseEvent e) {
                b.setBorderPainted(false);
                b.setContentAreaFilled(
                        b.isSelected());
            }
        });
        if (bg) {
            buttonGroup.add(b);
        }
        b.setIcon(image);
        b.setActionCommand(actionCommand);
        b.setToolTipText(tooltip);
        add(b);
    }

    protected void showPointerOptions(int x, int y) {
    }
/**
 * this is intentionally left empty here. Its implemented in the sublass <link>
 * ClassicInstructorToolbar</link>
 * @param b
 */
    protected void startDesktopshare(JToggleButton b) {
    }

    protected void showWebpage() {
    }

    protected void setBold() {
    }

    protected void setUnder() {
    }

    protected void setItalic() {
    }

    private void showPlayer() {
    }

    public void showRecorderFrame() {
    }

    private void showConfigFrame() {
        RealtimeOptionsFrame fr = new RealtimeOptionsFrame(mf, 0);
        fr.setSize(600, 400);
        fr.setLocationRelativeTo(
                null);
        fr.setVisible(true);
    }

    public void showQuestionsManager() {
    }

    public ToolbarActionManager getToolbarManager() {
        return toolbarManager;
    }

    public void setSavednotes(boolean savednotes) {
        this.savednotes = savednotes;
    }

    public void setTalking(boolean talking) {
        this.talking = talking;
        mf.getTcpConnector().sendPacket(new PresencePacket(
                mf.getUser().getSessionId(), PresenceConstants.SOUND_ICON, talking ? PresenceConstants.SPEAKING : PresenceConstants.NOT_SPEAKING, mf.getUser().getUserName()));
        for (int i = 0; i < this.getComponentCount(); i++) {
            if (this.getComponentAtIndex(i) instanceof JToggleButton) {
                JToggleButton button = (JToggleButton) this.getComponentAtIndex(i);

                if (button.getActionCommand().equals(
                        "mic")) {
                    if (talking) {
                        button.setIcon(new ImageIcon(micSelectedIcon));
                        button.setText("Talking");
                        button.setBorderPainted(true);
                    } else {
                        button.setIcon(new ImageIcon(micIcon));
                        button.setText("");
                        button.setBorderPainted(false);
                    }
                }
            }
        }
        repaint();
    }

    public void setMicNotAvailable() {


        for (int i = 0; i < this.getComponentCount(); i++) {
            if (this.getComponentAtIndex(i) instanceof JToggleButton) {
                JToggleButton button = (JToggleButton) this.getComponentAtIndex(i);
                if (button.getActionCommand().equals("mic")) {
                    button.setIcon(new ImageIcon(micFailIcon));
                }
            }
        }
        repaint();
    }

    public void setSpeakerNotAvailable() {


        for (int i = 0; i < this.getComponentCount(); i++) {
            if (this.getComponentAtIndex(i) instanceof JToggleButton) {

                JToggleButton button = (JToggleButton) this.getComponentAtIndex(i);
                if (button.getActionCommand().equals("speaker")) {
                    button.setIcon(new ImageIcon(spkrFailIcon));
                }
            }
        }
        repaint();
    }

    public void showFonts() {
    }

    protected void showDocumentViewer(){
        
    }

    protected void insertPresentation() {
    }

    protected void insertGraphic() {
    }

    protected void showSlideBuilder() {
    }

    public void actionPerformed(ActionEvent evt) {
         if (evt.getActionCommand().equals("documents")) {
             showDocumentViewer();
         }
        if (evt.getActionCommand().equals("slideBuilder")) {
            showSlideBuilder();
        }
        if (evt.getActionCommand().equals("graphic")) {
            insertGraphic();
        }
        if (evt.getActionCommand().equals("presentation")) {
            insertPresentation();
        }
        if (evt.getActionCommand().equals("pointer")) {
            JButton b = (JButton) evt.getSource();
            showPointerOptions(b.getX(), b.getY());
        }

        if (evt.getActionCommand().equals("fonts")) {
            showFonts();
        }
        if (evt.getActionCommand().equals("mic")) {

            mf.getAudioChatClient().talk();
        }
        if (evt.getActionCommand().equals("italic")) {
            setItalic();
        }
        if (evt.getActionCommand().equals("under")) {
            setUnder();
        }
        if (evt.getActionCommand().equals("bold")) {
            setBold();
        }
        if (evt.getActionCommand().equals("play")) {
            showPlayer();
        }
        if (evt.getActionCommand().equals("record")) {
            showRecorderFrame();
        }
        if (evt.getActionCommand().equals("config")) {
            showConfigFrame();
        }
        if (evt.getActionCommand().equals("desktopshare")) {
            JToggleButton b = (JToggleButton) evt.getSource();

            startDesktopshare(b);
        }
        if (evt.getActionCommand().equals("webpage")) {
            showWebpage();
        }
        if (evt.getActionCommand().equals(
                "notepad")) {
            /*if (savednotes) {
                toolbarManager.showNotepadList();
            } else {
                toolbarManager.showNotepad();
            }*/
            mf.showNotepad(null, "", "");
        }
        if (evt.getActionCommand().equals("question")) {
            showQuestionsManager();
        }
    }
}
